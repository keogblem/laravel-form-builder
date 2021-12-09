<?php

namespace KeoGblem\FormTools;

use App;
use Illuminate\Support\ServiceProvider;

class FormToolsServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // logger("Keo Package");
    }

    public function register()
    {
        require 'helpers.php';

        App::bind('formtools', 'KeoGblem\FormTools\FormTools');
    }
}
