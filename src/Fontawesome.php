<?php

namespace Mdixon18\Fontawesome;

use Laravel\Nova\Fields\Field;

class Fontawesome extends Field
{
    /**
     * The field's component.
     *
     * @var string
     */
    public $component = 'fontawesome';

    public function addButtonText($text)
    {
        return $this->withMeta([
            'add_button_text' => $text
        ]);
    }
}
