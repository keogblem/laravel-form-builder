<?php

namespace KeoGblem\FormBuilder\Concerns;

use Arr;
use KeoGblem\FormBuilder\Concerns\Fields\ButtonFieldGenerator;
use KeoGblem\FormBuilder\Concerns\Fields\HiddenFieldGenerator;
use KeoGblem\FormBuilder\Concerns\Fields\SelectFieldGenerator;
use KeoGblem\FormBuilder\Concerns\Fields\TextareaFieldGenerator;
use KeoGblem\FormBuilder\Concerns\Fields\TextFieldGenerator;
use KeoGblem\FormBuilder\Concerns\Sections\SectionGenerator;

trait Generator
{
    use SectionGenerator;
    use TextFieldGenerator;
    use SelectFieldGenerator;
    use HiddenFieldGenerator;
    use ButtonFieldGenerator;
    use TextareaFieldGenerator;

    protected $form_output = '';

    protected function createFromData(): string
    {
        $this->form_output = "<form " . $this->getFormAttributes() . ">" . "\n";

        $this->buildFormHeader();

        $this->buildFormBody();

        $this->form_output .= "\n" . "</form>";

        return $this->form_output;
    }

    protected function getFormAttributes(): string
    {
        $output = 'id="form-' . ($this->form_data['id'] ?? $this->id_stamp) . '" ';

        if (! empty($this->form_data['url'])) {
            $output .= 'action="' . $this->form_data['type'] . '" ';
        }

        if (! empty($this->form_data['method'])) {
            $output .= 'method="' . strtoupper($this->form_data['method']) . '" ';
        }

        if (! empty($this->form_data['class'])) {
            $output .= 'class="' . $this->form_data['class'] . '" ';
        }

        return $output;
    }

    protected function buildFormHeader()
    {
        if (! empty($this->model) && in_array($this->form_data['type'], ['edit', 'delete'])) {
            $this->form_output .= $this->generateHiddenIDField($this->model['id']) . "\n";
        }

        /** @noinspection TypeUnsafeComparisonInspection */
        if (strtoupper($this->form_data['method']) == 'POST') {
            $this->form_output .= csrf_field() . "\n";
        }
    }

    protected function buildFormBody()
    {
        foreach (Arr::get($this->form_data, 'sections', []) as $section) {
            if (! is_array($section)) {
                continue;
            }

            $this->form_output .= match (Arr::get($section, 'layout')) {
                'row'       => $this->buildFormSectionAsRow($section),
                'row-block' => $this->buildFormSectionAsRowBlock($section),
                default     => $this->buildFormSectionAsBlock($section),
            };
        }
    }

    protected function generateField(array $field): ?string
    {
        if (empty(trim($field['type']))) {
            return null;
        }

        $field['name'] = $field['name'] ?? '';

        return match ($field['type']) {
            'text', 'email' => $this->generateTextField($field),
            'select'        => $this->generateSelectField($field),
            'textarea'      => $this->generateTextareaField($field),
            'file'          => $this->generateFileField($field),
            'checkbox'      => $this->generateCheckboxField($field),
            'radio'         => $this->generateRadioField($field),
            'hidden'        => $this->generateHiddenField($field),
            'submit'        => $this->generateButtonField($field),
            default         => null,
        };
    }

    /**
     * @param  array  $field
     * @param        $field_id
     * @param  string  $field_class
     * @return string
     */
    protected function processCommonAttributes(array $field, $field_id, string $field_class): string
    {
        $input_attributes = 'type="' . $field['type'] . '" ';
        $input_attributes .= 'name="' . $field['name'] . '" ';
        $input_attributes .= 'id="' . $field_id . '" ';
        $input_attributes .= 'class="' . $field_class . '" ';

        if (! empty($field['placeholder'])) {
            $input_attributes .= 'placeholder="' . $field['placeholder'] . '" ';
        }
        if (! empty($field['required'])) {
            $input_attributes .= 'required ';
        }
        if (! empty($field['disabled'])) {
            $input_attributes .= 'disabled="disabled" ';
        }
        if (! empty($field['attributes'])) {
            $input_attributes .= ' ' . $field['attributes'] . ' ';
        }
        return $input_attributes;
    }

    /**
     * @param  array  $field
     * @param  string  $field_class
     * @return string
     */
    public static function buildFieldClass(array $field, string $field_class = ''): string
    {
        if (! empty($field['class'])) {
            if (! empty($field['class-override'])) {
                $field_class = $field['class'];
            } else {
                $field_class .= ' ' . $field['class'];
            }
        }
        if (! empty($field['size']) && in_array($field['size'], ['lg', 'sm'])) {
            $field_class .= ' form-control-' . $field['size'];
        }

        return $field_class;
    }

    public static function buildFieldID(array $field)
    {
        return $field['id'] ?? $field['name'] ?? null;
    }

    /**
     * @param  array  $field
     * @return array
     */
    protected function setValueFromModel(array $field): array
    {
        if ($this->model) {
            $key = $field['model-key'] ?? $field['name'];

            $field['value'] = $this->model[$key] ?? null;
            logger($key . ' ' . $field['value']);
        }

        return $field;
    }
}
