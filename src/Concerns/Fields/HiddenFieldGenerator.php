<?php

namespace KeoGblem\FormTools\Concerns\Fields;

trait HiddenFieldGenerator
{
    protected function generateHiddenField(array $field): ?string
    {
        if (empty($field['name'])) {
            return null;
        }

        // check and set value from model if exist
        $field = $this->setValueFromModel($field);

        // prepare the ID and CLASS values -------------------------------------
        $field_id    = \KeoGblem\FormTools\Concerns\Generator::buildFieldID($field);
        $field_class = \KeoGblem\FormTools\Concerns\Generator::buildFieldClass($field);

        // prepare input ATTRIBUTES -------------------------------------
        $input_attributes = self::processCommonAttributes($field, $field_id, $field_class);
        if (! empty($field['value'])) {
            $input_attributes .= 'value="' . $field['value'] . '" ';
        }

        // start Outputing -------------------------------------
        return '<input ' . $input_attributes . ' >';
    }

    protected function generateHiddenIDField($id): ?string
    {
        return $this->generateHiddenField([
            'type'     => 'hidden',
            'name'     => 'id',
            'value'    => $id,
            'required' => true,
        ]);
    }
}
