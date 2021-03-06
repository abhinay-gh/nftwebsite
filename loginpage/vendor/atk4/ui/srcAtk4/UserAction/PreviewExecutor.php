<?php

declare(strict_types=1);

namespace Atk4\Ui\UserAction;

use atk4\ui\View;

class PreviewExecutor extends BasicExecutor
{
    /** @var View */
    public $preview;

    /**
     * @var string can be "console", "text", or "html"
     */
    public $previewType = 'console';

    public function initPreview()
    {
        if (!$this->hasAllArguments()) {
            \atk4\ui\Message::addTo($this, ['type' => 'error', 'Insufficient arguments']);

            return;
        }

        $text = $this->executePreview();

        switch ($this->previewType) {
            case 'console':
                $this->preview = View::addTo($this, ['ui' => 'inverted black segment', 'element' => 'pre']);
                $this->preview->set($text);

                break;
            case 'text':
                $this->preview = View::addTo($this, ['ui' => 'segment']);
                $this->preview->set($text);

                break;
            case 'html':
                $this->preview = View::addTo($this, ['ui' => 'segment']);
                $this->preview->template->setHtml('Content', $text);

                break;
        }

        \atk4\ui\Button::addToWithCl($this, $this->executorButton)->on('click', function () {
            return $this->jsExecute();
        });
    }

    public function executePreview()
    {
        $args = [];

        foreach ($this->action->args as $key => $val) {
            $args[] = $this->arguments[$key];
        }

        return $this->action->preview(...$args);
    }
}
