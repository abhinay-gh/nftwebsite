<?php

declare(strict_types=1);

namespace Atk4\Ui\UserAction;

use atk4\data\Model;
use atk4\ui\Exception;
use atk4\ui\Form;

/**
 * BasicExecutor executor will typically fail if supplied arguments are not sufficient.
 *
 * ArgumentFormExecutor will ask user to fill in the blanks
 */
class ArgumentFormExecutor extends BasicExecutor
{
    /**
     * @var Form
     */
    public $form;

    /**
     * Initialization.
     */
    public function initPreview()
    {
        /*
         * We might want console later!
         *
        $this->console = \atk4\ui\Console::addTo($this, ['event'=>false]);//->addStyle('display', 'none');
        $this->console->addStyle('max-height', '50em')->addStyle('overflow', 'scroll');

        */

        \atk4\ui\Header::addTo($this, [$this->action->caption, 'subHeader' => $this->action->getDescription()]);
        $this->form = Form::addTo($this);

        foreach ($this->action->args as $key => $val) {
            if (is_numeric($key)) {
                throw (new Exception('Action arguments must be named'))
                    ->addMoreInfo('args', $this->action->args);
            }

            if ($val instanceof Model) {
                $val = ['model' => $val];
            }

            if (isset($val['model'])) {
                $val['model'] = $this->factory($val['model']);
                $this->form->addControl($key, [Form\Control\Lookup::class])->setModel($val['model']);
            } else {
                $this->form->addControl($key, null, $val);
            }
        }

        $this->form->buttonSave->set('Run');

        $this->form->onSubmit(function (Form $form) {
            // set arguments from the model
            $this->setArguments($form->model->get());

            return $this->jsExecute();
            //return [$this->console->js()->show(), $this->console->sse];
        });

        /*
        $this->console->set(function($c) {
            $data = $this->recall('data');
            $args = [];

            foreach($this->defs as $key=>$val) {
                if (is_numeric($key)) {
                    $key = 'Argument'.$key;
                }

                if ($val instanceof \Closure) {
                    $val = $val($this->model, $this->method, $data);
                } elseif ($val instanceof Model) {
                    $val->load($data[$key]);
                } else {
                    $val = $data[$key];
                }

                $args[] = $val;
            }

            $c->setModel($this->model, $this->method, $args);
        });
        */
    }
}
