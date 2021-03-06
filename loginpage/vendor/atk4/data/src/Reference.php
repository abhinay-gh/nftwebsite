<?php

declare(strict_types=1);

namespace atk4\data;

/**
 * Reference implements a link between one model and another. The basic components for
 * a reference is ability to generate the destination model, which is returned through
 * getModel() and that's pretty much it.
 *
 * It's possible to extend the basic reference with more meaningful references.
 *
 * @property Model $owner definition of our model
 */
class Reference
{
    use \atk4\core\InitializerTrait {
        init as _init;
    }
    use \atk4\core\TrackableTrait;
    use \atk4\core\DiContainerTrait;
    use \atk4\core\FactoryTrait;

    /**
     * Use this alias for related entity by default. This can help you
     * if you create sub-queries or joins to separate this from main
     * table. The table_alias will be uniquely generated.
     *
     * @var string
     */
    protected $table_alias;

    /**
     * What should we pass into owner->ref() to get through to this reference.
     * Each reference has a unique identifier, although it's stored
     * in Model's elements as '#ref-xx'.
     *
     * @var string
     */
    public $link;

    /**
     * Definition of the destination their model, that can be either an object, a
     * callback or a string. This can be defined during initialization and
     * then used inside getModel() to fully populate and associate with
     * persistence.
     *
     * @var Model|string|array
     */
    public $model;

    /**
     * This is an optional property which can be used by your implementation
     * to store field-level relationship based on a common field matching.
     *
     * @var string
     */
    protected $our_field;

    /**
     * This is an optional property which can be used by your implementation
     * to store field-level relationship based on a common field matching.
     *
     * @var string
     */
    protected $their_field;

    /**
     * Caption of the reeferenced model. Can be used in UI components, for example.
     * Should be in plain English and ready for proper localization.
     *
     * @var string
     */
    public $caption;

    /**
     * Default constructor. Will copy argument into properties.
     *
     * @param string $link a short_name component
     */
    public function __construct($link)
    {
        $this->link = $link;
    }

    protected function onHookToOurModel(Model $model, string $spot, \Closure $fx, array $args = [], int $priority = 5): int
    {
        $name = $this->short_name; // use static function to allow this object to be GCed

        return $model->onHookDynamic(
            $spot,
            static function (Model $model) use ($name) {
                return $model->getElement($name);
            },
            $fx,
            $args,
            $priority
        );
    }

    protected function onHookToTheirModel(Model $model, string $spot, \Closure $fx, array $args = [], int $priority = 5): int
    {
        if ($model->ownerReference !== null && $model->ownerReference !== $this) {
            throw new Exception('Model owner reference unexpectedly already set');
        }
        $model->ownerReference = $this;
        $getThisFx = static function (Model $model) {
            return $model->ownerReference;
        };

        return $model->onHookDynamic(
            $spot,
            $getThisFx,
            $fx,
            $args,
            $priority
        );
    }

    /**
     * Initialization.
     */
    protected function init(): void
    {
        $this->_init();

        $this->initTableAlias();
    }

    /**
     * Will use #ref_<link>.
     */
    public function getDesiredName(): string
    {
        return '#ref_' . $this->link;
    }

    public function getOurModel(): Model
    {
        return $this->owner;
    }

    /**
     * @deprecated use getTheirModel instead - will be removed in dec-2020
     */
    public function getModel(array $defaults = []): Model
    {
        'trigger_error'('Method Reference::getModel is deprecated. Use Reference::getTheirModel instead', E_USER_DEPRECATED);

        return $this->getTheirModel($defaults);
    }

    /**
     * Returns destination model that is linked through this reference. Will apply
     * necessary conditions.
     *
     * IMPORTANT: the returned model must be a fresh clone or freshly built from a seed
     */
    public function getTheirModel(array $defaults = []): Model
    {
        // set table_alias
        $defaults['table_alias'] = $defaults['table_alias'] ?? $this->table_alias;

        if (is_object($this->model)) {
            if ($this->model instanceof \Closure) {
                // if model is Closure, then call the closure and whci should return a model
                $theirModel = ($this->model)($this->getOurModel(), $this, $defaults);
            } else {
                // if model is set, then use clone of this model
                $theirModel = clone $this->model;
            }
        } else {
            // add model from seed
            if (is_array($this->model)) {
                $modelDefaults = $this->model;
                $theirModelSeed = [$modelDefaults[0]];

                unset($modelDefaults[0]);

                $defaults = array_merge($modelDefaults, $defaults);
            } else {
                $theirModelSeed = [$this->model];
            }

            $theirModel = $this->factory($theirModelSeed, $defaults);
        }

        return $this->addToPersistence($theirModel, $defaults);
    }

    protected function getOurField(): Field
    {
        return $this->getOurModel()->getField($this->getOurFieldName());
    }

    protected function getOurFieldName(): string
    {
        return $this->our_field ?: $this->getOurModel()->id_field;
    }

    protected function getOurFieldValue()
    {
        return $this->getOurField()->get();
    }

    protected function initTableAlias(): void
    {
        if (!$this->table_alias) {
            $ourModel = $this->getOurModel();

            $this->table_alias = $this->link;
            $this->table_alias = preg_replace('/_' . ($ourModel->id_field ?: 'id') . '/', '', $this->table_alias);
            $this->table_alias = preg_replace('/([a-zA-Z])[a-zA-Z]*[^a-zA-Z]*/', '\1', $this->table_alias);
            if (isset($ourModel->table_alias)) {
                $this->table_alias = $ourModel->table_alias . '_' . $this->table_alias;
            }
        }
    }

    /**
     * Adds model to persistence.
     */
    protected function addToPersistence(Model $theirModel, array $defaults = []): Model
    {
        if (!$theirModel->persistence && $persistence = $this->getDefaultPersistence($theirModel)) {
            $persistence->add($theirModel, $defaults);
        }

        // set model caption
        if ($this->caption !== null) {
            $theirModel->caption = $this->caption;
        }

        return $theirModel;
    }

    /**
     * Returns default persistence for theirModel.
     *
     * @return Persistence|false
     */
    protected function getDefaultPersistence(Model $theirModel)
    {
        $ourModel = $this->getOurModel();

        // this will be useful for containsOne/Many implementation in case when you have
        // SQL_Model->containsOne()->hasOne() structure to get back to SQL persistence
        // from Array persistence used in containsOne model
        if ($ourModel->contained_in_root_model && $ourModel->contained_in_root_model->persistence) {
            return $ourModel->contained_in_root_model->persistence;
        }

        return $ourModel->persistence ?: false;
    }

    /**
     * Returns referenced model without any extra conditions. However other
     * relationship types may override this to imply conditions.
     */
    public function ref(array $defaults = []): Model
    {
        return $this->getTheirModel($defaults);
    }

    /**
     * Returns referenced model without any extra conditions. Ever when extended
     * must always respond with Model that does not look into current record
     * or scope.
     */
    public function refModel(array $defaults = []): Model
    {
        return $this->getTheirModel($defaults);
    }

    // {{{ Debug Methods

    /**
     * List of properties to show in var_dump.
     */
    protected $__debug_fields = ['link', 'model', 'our_field', 'their_field'];

    /**
     * Returns array with useful debug info for var_dump.
     */
    public function __debugInfo(): array
    {
        $arr = [];
        foreach ($this->__debug_fields as $k => $v) {
            $k = is_numeric($k) ? $v : $k;
            if (isset($this->{$v})) {
                $arr[$k] = $this->{$v};
            }
        }

        return $arr;
    }

    // }}}
}
