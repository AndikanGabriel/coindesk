<?php

namespace GabrielAndy\Coindesk;

use Illuminate\Support\Facades\Facade;

class CoindeskFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'coindesk';
    }
}
