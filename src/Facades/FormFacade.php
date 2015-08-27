<?php namespace Arcanedev\LaravelHtml\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class FormFacade
 * @package Arcanedev\LaravelHtml\Facades
 */
class FormFacade extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'form'; }
}
