<?php

use KeoGblem\FormTools\FormTools;

if (! function_exists('form_builder')) {
    function form_builder(...$args): FormTools
    {
        return new FormTools(...$args);
    }
}
