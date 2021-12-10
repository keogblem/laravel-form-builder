<?php

namespace KeoGblem\FormBuilder;

use App;
use Illuminate\Support\ServiceProvider;
use KeoGblem\FormBuilder\FormBuilder;

class FormBuilderServiceProvider extends ServiceProvider
{
    public function boot()
    {
    }

    public function register()
    {
        require 'helpers.php';

        App::bind('formbuilder', FormBuilder::class);
    }
}
