<?php

namespace KeoGblem\FormBuilder\Concerns\Fields;

use KeoGblem\FormBuilder\Concerns\Generator;

trait TextFieldGenerator
{
    protected function generateTextField(array $field): ?string
    {
        if (empty($field['name'])) {
            return null;
        }
        // check and set value from model if exist
        $field = $this->setValueFromModel($field);

        // prepare the ID and CLASS values -------------------------------------
        $field_id    = Generator::buildFieldID($field);
        $field_class = Generator::buildFieldClass($field, 'form-control ');

        // prepare input ATTRIBUTES -------------------------------------
        $input_attributes = self::processCommonAttributes($field, $field_id, $field_class);
        if (! empty($field['value'])) {
            $input_attributes .= ' value="' . $field['value'] . '" ';
        } else {
            $input_attributes .= ' value="' . \old($field['name']) . '" ';
        }

        // start Outputing -------------------------------------
        $output = '<div class="form-group">' . PHP_EOL;

        // label
        if (! empty($field['label'])) {
            $output .= "\t" . '<label for="' . $field_id . '">' . $field['label'] . '</label>' . PHP_EOL;
        }

        // input
        $output .= "\t" . '<input ' . $input_attributes . ' >' . PHP_EOL;

        // helper text
        if (! empty($field['help-text'])) {
            $output .= "\t" . '<small id="" class="form-text text-muted">' . $field['help-text'] . '</small>' . PHP_EOL;
        }

        return $output . '</div>' . PHP_EOL;
    }
}
