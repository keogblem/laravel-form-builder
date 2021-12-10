<?php

use KeoGblem\FormBuilder\FormBuilder;

if (! function_exists('form_builder')) {
    function form_builder(...$args): FormBuilder
    {
        return new FormBuilder(...$args);
    }
}
