<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Traits;

use BadMethodCallException;
use Illuminate\Support\{Arr, HtmlString};

/**
 * Trait     Componentable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait Componentable
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The registered components.
     *
     * @var array
     */
    protected static $components = [];

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register a custom component.
     *
     * @param  string  $name
     * @param  string  $view
     * @param  array   $signature
     */
    public static function component(string $name, string $view, array $signature)
    {
        static::$components[$name] = compact('view', 'signature');
    }

    /**
     * Check if a component is registered.
     *
     * @param  string  $name
     *
     * @return bool
     */
    public static function hasComponent(string $name): bool
    {
        return isset(static::$components[$name]);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Render a custom component.
     *
     * @param  string  $name
     * @param  array   $arguments
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function renderComponent(string $name, array $arguments): HtmlString
    {
        $component = static::$components[$name];
        $data      = $this->getComponentData($component['signature'], $arguments);

        return new HtmlString(
            view()->make($component['view'], $data)->render()
        );
    }

    /**
     * Prepare the component data, while respecting provided defaults.
     *
     * @param  array  $signature
     * @param  array  $arguments
     *
     * @return array
     */
    protected function getComponentData(array $signature, array $arguments): array
    {
        $data = [];
        $i    = 0;

        foreach ($signature as $variable => $default) {
            if (is_numeric($variable)) {
                $variable = $default;
                $default  = null;
            }

            $data[$variable] = Arr::get($arguments, $i, $default);
            $i++;
        }

        return $data;
    }

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return \Illuminate\Support\HtmlString|mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if ( ! static::hasComponent($method))
            throw new BadMethodCallException("Method {$method} does not exist.");

        return $this->renderComponent($method, $parameters);
    }
}
