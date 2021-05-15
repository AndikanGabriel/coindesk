<?php

namespace GabrielAndy\Coindesk\Tests;

use Coindesk;
use GabrielAndy\Coindesk\CoindeskFacade;
use GabrielAndy\Coindesk\CoindeskServiceProvider;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class TestCase extends OrchestraTestCase
{
    /**
     * Load package service provider.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            CoindeskServiceProvider::class,
        ];
    }

    /**
     * Load package alias/facade.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Coindesk' => CoindeskFacade::class,
        ];
    }
}
