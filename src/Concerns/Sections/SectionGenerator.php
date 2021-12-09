<?php

namespace KeoGblem\FormTools\Concerns\Sections;

use Arr;

trait SectionGenerator
{
    protected function buildFormSectionAsRow(array $section): string
    {
        $output = '<div class="row ' . Arr::get($section, 'class') . '">';

        foreach (Arr::get($section, 'columns') as $col) {
            $output .= $this->generateSectionFields(Arr::get($col, 'fields', []), Arr::get($col, 'class', 'col-md'));
        }

        return $output . '</div>';
    }

    protected function buildFormSectionAsBlock(array $section): string
    {
        return $this->generateSectionFields(Arr::get($section, 'fields', []), Arr::get($section, 'class'));
    }

    protected function buildFormSectionAsRowBlock(array $section): string
    {
        $output = '<div class="row ' . Arr::get($section, 'class') . '">';

        $output .= $this->generateSectionFields(Arr::get($section, 'fields', []), 'col');

        return $output . '</div>';
    }

    protected function generateSectionFields(array $fields, $container_class = ''): string
    {
        $output = '<div class="' . $container_class . '">';

        foreach ($fields as $field) {
            $output .= $this->generateField($field) . "\n";
        }

        return $output . '</div>';
    }
}
