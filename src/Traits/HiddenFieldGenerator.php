<?php

namespace Keogblem\FormTools\Traits;

trait HiddenFieldGenerator
{
  protected function generateHiddenField(array $field)
  {
    if (empty($field['name'])) {
      return null;
    }
    // check and set value from model if exist
    $field = $this->setValueFromModel($field);

    // prepare the ID and CLASS values -------------------------------------
    $field_id    = Generator::buildFieldID($field);
    $field_class = Generator::buildFieldClass($field);
    // prepare input ATTRIBUTES -------------------------------------
    $input_attributes = self::processCommonAttributes($field, $field_id, $field_class);
    if (!empty($field['value'])) {
      $input_attributes .= 'value="' . $field['value'] . '" ';
    }
    // start Outputing -------------------------------------
    $output = '<input ' . $input_attributes . ' >';

    return $output;
  }

  protected function generateHiddenIDField($id)
  {
    return $this->generateHiddenField([
      'type' => 'hidden',
      'name' => 'id',
      'value' => $id,
      'required' => true
    ]);
  }
}
