<?php namespace Arcanedev\LaravelHtml\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class Form
 * @package Arcanedev\LaravelHtml\Facades
 */
class Form extends Facade {

    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'form'; }
}
