<?php

namespace Keogblem\FormTools;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class FormToolsServiceProvider extends ServiceProvider
{
  public function boot()
  {
    // logger("Keo Package");
  }

  public function register()
  {
    App::bind('formtools', 'Keogblem\FormTools\FormTools');
  }

}
