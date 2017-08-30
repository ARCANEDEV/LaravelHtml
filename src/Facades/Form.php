<?php namespace Arcanedev\LaravelHtml\Facades;

use Arcanedev\LaravelHtml\Contracts\FormBuilder;
use Illuminate\Support\Facades\Facade;

/**
 * Class     Form
 *
 * @package  Arcanedev\LaravelHtml\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Form extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor() { return FormBuilder::class; }
}
