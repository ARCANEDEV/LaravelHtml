<?php namespace Arcanedev\LaravelHtml\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * Class     Html
 *
 * @package  Arcanedev\LaravelHtml\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Html extends Facade {
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return 'html'; }
}
