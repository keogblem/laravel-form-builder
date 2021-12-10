<?php

namespace KeoGblem\FormBuilder\Concerns\Sections;

use Arr;

trait SectionGenerator
{
    protected function buildFormSectionAsRow(array $section): string
    {
        $output = '<div class="row ' . Arr::get($section, 'class') . '">' . PHP_EOL;

        foreach (Arr::get($section, 'columns') as $col) {
            $output .= $this->generateSectionFields(Arr::get($col, 'fields', []), Arr::get($col, 'class', 'col-md')) . PHP_EOL;
        }

        return $output . '</div>' . PHP_EOL;
    }

    protected function buildFormSectionAsBlock(array $section): string
    {
        return $this->generateSectionFields(Arr::get($section, 'fields', []), Arr::get($section, 'class')) . PHP_EOL;
    }

    protected function buildFormSectionAsRowBlock(array $section): string
    {
        $output = '<div class="row ' . Arr::get($section, 'class') . '">' . PHP_EOL;

        $output .= "\t" . $this->generateSectionFields(Arr::get($section, 'fields', []), 'col') . PHP_EOL;

        return $output . '</div>' . PHP_EOL;
    }

    protected function generateSectionFields(array $fields, $container_class = ''): string
    {
        $output = '<div class="' . $container_class . '">' . PHP_EOL;

        foreach ($fields as $field) {
            $output .= "\t" . $this->generateField($field) . PHP_EOL;
        }

        return $output . '</div>' . PHP_EOL;
    }
}
