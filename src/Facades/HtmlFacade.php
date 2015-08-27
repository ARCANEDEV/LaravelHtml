<?php namespace Arcanedev\LaravelHtml\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class HtmlFacade
 * @package Arcanedev\LaravelHtml\Facades
 */
class HtmlFacade extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'html'; }
}
