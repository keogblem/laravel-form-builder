<?php

namespace KeoGblem\FormBuilder\Concerns;

trait Validator
{
    protected function validateInputs($inputs): \Illuminate\Validation\Validator
    {
        $all_rules = $this->getAllValidationRules();

        $rules    = $all_rules['rules'];
        $messages = $all_rules['messages'];

        return \Validator::make($inputs, $rules, $messages);
    }

    protected function getAllValidationRules(): array
    {
        $all_rules = [
            'rules'    => [],
            'messages' => [],
        ];

        foreach ($this->form_data['fields'] as $field) {
            $field_rules = $this->getFieldValidationRules($field);

            $all_rules['rules']    = array_merge($all_rules['rules'], $field_rules['rules']);
            $all_rules['messages'] = array_merge($all_rules['messages'], $field_rules['messages']);
        }

        return $all_rules;
    }

    private function getFieldValidationRules($field): array
    {
        $rules = [
            'rules'    => [],
            'messages' => [],
        ];

        if (! empty($field['rules'])) {
            $rules['rules'] = [$field['name'] => $field['rules']];
        }

        if (! empty($field['rules-messages'])) {
            $rules['messages'] = $field['rules-messages'];
        }

        return $rules;
    }
}
