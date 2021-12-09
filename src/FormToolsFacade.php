<?php

namespace KeoGblem\FormTools;

use Illuminate\Support\Facades\Facade;

class FormToolsFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'formtools';
    }
}
