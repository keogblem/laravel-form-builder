<?php

namespace KeoGblem\FormBuilder\Concerns\Fields;

trait TextareaFieldGenerator
{
    protected function generateTextareaField(array $field): ?string
    {
        if (empty($field['name'])) {
            return null;
        }
        // check and set value from model if exist
        $field = $this->setValueFromModel($field);

        // prepare the ID and CLASS values -------------------------------------
        $field_id    = \KeoGblem\FormBuilder\Concerns\Generator::buildFieldID($field);
        $field_class = \KeoGblem\FormBuilder\Concerns\Generator::buildFieldClass($field, 'form-control ');

        // prepare input ATTRIBUTES -------------------------------------
        $input_attributes = self::processCommonAttributes($field, $field_id, $field_class);

        // start Outputing -------------------------------------
        $output = '<div class="form-group">';

        // label
        if (! empty($field['label'])) {
            $output .= '<label for="' . $field_id . '">' . $field['label'] . '</label>';
        }

        // input
        $output .= '<textarea ' . $input_attributes . ' >' . (! empty($field['value']) ? trim($field['value']) : \old($field['name'])) . '</textarea>';

        // helper text
        if (! empty($field['help-text'])) {
            $output .= '<small id="" class="form-text text-muted">' . $field['help-text'] . '</small>';
        }

        return $output . '</div>';
    }
}
