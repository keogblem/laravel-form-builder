<?php

namespace KeoGblem\FormTools\Concerns\Fields;

trait ButtonFieldGenerator
{
    protected function generateButtonField(array $field): string
    {
        // prepare the ID and CLASS values -------------------------------------
        $field_id = \KeoGblem\FormTools\Concerns\Generator::buildFieldID($field);

        $field_class = 'btn';

        if (! isset($field['color'])) {
            $field_class .= ' btn-primary';
        } elseif (! empty($field['color'])) {
            $field_class .= ' btn-' . $field['color'];
        }

        $field_class = \KeoGblem\FormTools\Concerns\Generator::buildFieldClass($field, $field_class);

        // prepare input ATTRIBUTES -------------------------------------
        $input_attributes = self::processCommonAttributes($field, $field_id, $field_class);
        if (! empty($field['value'])) {
            $input_attributes .= 'value="' . $field['value'] . '" ';
        }
        // start Outputing -------------------------------------

        return '<button ' . $input_attributes . ' >' . $field['text'] . '</button>';
    }
}
