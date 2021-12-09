<?php

namespace KeoGblem\FormTools\Concerns;

trait Validator
{
    protected function validateInputs($inputs): \Illuminate\Validation\Validator
    {
        $all_rules = $this->getAllValidationRules();

        $rules    = $all_rules['rules'];
        $messages = $all_rules['messages'];

        // logger($all_rules);

        return \Validator::make($inputs, $rules, $messages);
    }

    protected function getAllValidationRules()
    {
        $all_rules = [
            'rules'    => [],
            'messages' => [],
        ];

        foreach ($this->form_data['fields'] as $field) {
            $field_rules = $this->getFieldValidationRules($field);
            logger($field_rules);
            $all_rules['rules']    = array_merge($all_rules['rules'], $field_rules['rules']);
            $all_rules['messages'] = array_merge($all_rules['messages'], $field_rules['messages']);
        }

        return $all_rules;
    }

    private function getFieldValidationRules($field)
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
