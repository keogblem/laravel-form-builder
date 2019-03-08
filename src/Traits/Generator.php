<?php

namespace Keogblem\FormTools\Traits;

trait Generator
{
  use TextFieldGenerator;
  use SelectFieldGenerator;
  use HiddenFieldGenerator;
  use ButtonFieldGenerator;
  use TextareaFieldGenerator;

  protected function createFromData()
  {
    $output = "<form " . $this->getFormAttributes() . ">" . "\n";
    $output .= $this->getFormHeader();
    $output .= $this->getFormBody();
    $output .= "\n" . "</form>";
    return $output;
  }

  protected function getFormAttributes()
  {
    $output = 'id="form-' . ($this->form_data['id'] ?? $this->id_stamp) . '" ';
    if (!empty($this->form_data['url'])) {
      $output .= 'action="' . $this->form_data['type'] . '" ';
    }
    if (!empty($this->form_data['method'])) {
      $output .= 'method="' . strtoupper($this->form_data['method']) . '" ';
    }
    if (!empty($this->form_data['class'])) {
      $output .= 'class="' . $this->form_data['class'] . '" ';
    }
    return $output;
  }

  protected function getFormHeader()
  {
    $output = "";
    if (in_array($this->form_data['type'], ['edit', 'delete']) and !empty($this->model)) {
      $output .= $this->generateHiddenIDField($this->model['id']) . "\n";
    }

    if (strtoupper($this->form_data['method']) == 'POST') {
      $output .= csrf_field() . "\n";
    }
    return $output;
  }

  protected function getFormBody()
  {
    $column_class  = 'col-md-' . (12 / $this->form_data['columns']);
    $fields_groups = array_chunk($this->form_data['fields'], ceil(count($this->form_data['fields']) / $this->form_data['columns']));

    $output = "";
    $output .= '<div class="row">';
    foreach ($fields_groups as $fields) {
      $output .= '<div class="' . $column_class . '">';
      foreach ($fields as $field) {
        $output .= $this->generateField($field) . "\n";
      }
      $output .= '</div>';
    }
    $output .= '</div>';

    return $output;
  }

  protected function generateField(array $field)
  {
    if (empty(trim($field['type']))) {
      return null;
    }
    $field['name'] = $field['name'] ?? '';

    switch ($field['type']) {
      case 'text':
        return $this->generateTextField($field);
      case 'email':
        return $this->generateTextField($field);
      case 'select':
        return $this->generateSelectField($field);
      case 'textarea':
        return $this->generateTextareaField($field);
      case 'file':
        return $this->generateFileField($field);
      case 'checkbox':
        return $this->generateCheckboxField($field);
      case 'radio':
        return $this->generateRadioField($field);
      case 'hidden':
        return $this->generateHiddenField($field);
      case 'submit':
        return $this->generateButtonField($field);
      default:
        return null;
    }
  }

  /**
   * @param array  $field
   * @param        $field_id
   * @param string $field_class
   * @return string
   */
  protected function processCommonAttributes(array $field, $field_id, string $field_class): string
  {
    $input_attributes = 'type="' . $field['type'] . '" ';
    $input_attributes .= 'name="' . $field['name'] . '" ';
    $input_attributes .= 'id="' . $field_id . '" ';
    $input_attributes .= 'class="' . $field_class . '" ';

    if (!empty($field['placeholder'])) {
      $input_attributes .= 'placeholder="' . $field['placeholder'] . '" ';
    }
    if (!empty($field['required'])) {
      $input_attributes .= 'required ';
    }
    if (!empty($field['disabled'])) {
      $input_attributes .= 'disabled="disabled" ';
    }
    if (!empty($field['attributes'])) {
      $input_attributes .= ' ' . $field['attributes'] . ' ';
    }
    return $input_attributes;
  }

  /**
   * @param array  $field
   * @param string $field_class
   * @return string
   */
  public static function buildFieldClass(array $field, string $field_class = '')
  {
    if (!empty($field['class'])) {
      if (!empty($field['class-override'])) {
        $field_class = $field['class'];
      } else {
        $field_class .= ' ' . $field['class'];
      }
    }
    if (!empty($field['size']) and in_array($field['size'], ['lg', 'sm'])) {
      $field_class .= ' form-control-' . $field['size'];
    }

    return $field_class;
  }

  public static function buildFieldID(array $field)
  {
    return $field['id'] ?? $field['name'] ?? null;
  }

  /**
   * @param array $field
   * @return array
   */
  protected function setValueFromModel(array $field): array
  {
    if ($this->model) {
      $_key           = !empty($field['model-key']) ? $field['model-key'] : $field['name'];
      $field['value'] = $this->model[$_key];
      logger($_key . ' ' . $field['value']);
    }
    return $field;
  }
}
