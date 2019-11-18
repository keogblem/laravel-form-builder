<?php

namespace KeoGblem\FormTools\Traits;

trait SelectFieldGenerator
{
  /**
   * @param array $field
   * @return string|null
   */
  protected function generateSelectField(array $field)
  {
    if (empty($field['name'])) {
      return null;
    }
    // check and set value from model if exist
    $field = $this->setValueFromModel($field);

    // prepare the ID and CLASS values -------------------------------------
    $field_id    = Generator::buildFieldID($field);
    $field_class = Generator::buildFieldClass($field, 'custom-select');

    // prepare input ATTRIBUTES -------------------------------------
    $input_attributes = self::processCommonAttributes($field, $field_id, $field_class);

    // start Outputing -------------------------------------
    $output = '<div class="form-group">';
    // label
    if (!empty($field['label'])) {
      $output .= '<label for="' . $field_id . '">' . $field['label'] . '</label>';
    }
    // input
    $output .= '<select ' . $input_attributes . ' >';
    if (!empty($field['options_groups'])) {
      foreach ($field['options_groups'] as $options_group) {
        $output .= '<optgroup label="' . $options_group['label'] . '" ' . !empty($options_group['attributes']) ? $options_group['attributes'] : '' . '>';
        $output .= $this->processSelectOptions($field, $options_group['options']);
        $output .= '</optgroup>' . "\n";
      }
    } else {
      $output .= $this->processSelectOptions($field, $field['options']);
    }
    $output .= '</select>';
    // helper text
    if (!empty($field['help-text'])) {
      $output .= '<small id="" class="form-text text-muted">' . $field['help-text'] . '</small>';
    }
    $output .= '</div>';

    return $output;
  }

  /**
   * @param       $field
   * @param array $options
   * @return string
   */
  protected function processSelectOptions($field, array $options): string
  {
    $output = '';
    foreach ($options as $option) {
      $option_attributes = '';
      if (!empty($field['value'])) {
        if ($option['value'] == $field['value']) {
          $option_attributes .= 'selected ';
        }
      } elseif (!empty($option['default-selected'])) {
        $option_attributes .= 'selected ';
      }
      $option_attributes .= !empty($option['attributes']) ? $option['attributes'] : '';
      $output            .= '<option value="' . $option['value'] . '" ' . $option_attributes . '>' . $option['text'] . '</option>' . "\n";
    }
    return $output;
  }
}
