<?php namespace Arcanedev\LaravelHtml\Traits;

use Illuminate\Support\HtmlString;
use BadMethodCallException;

/**
 * Class     Componentable
 *
 * @package  Arcanedev\LaravelHtml\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait Componentable
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The registered components.
     *
     * @var array
     */
    protected static $components = [];

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register a custom component.
     *
     * @param  string  $name
     * @param  string  $view
     * @param  array   $signature
     */
    public static function component($name, $view, array $signature)
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
    public static function hasComponent($name)
    {
        return isset(static::$components[$name]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Render a custom component.
     *
     * @param  string  $name
     * @param  array   $arguments
     *
     * @return \Illuminate\Contracts\View\View
     */
    protected function renderComponent($name, array $arguments)
    {
        $component = static::$components[$name];
        $data      = $this->getComponentData($component['signature'], $arguments);

        return new HtmlString(
            view($component['view'], $data)->render()
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
    protected function getComponentData(array $signature, array $arguments)
    {
        $data = [];
        $i    = 0;

        foreach ($signature as $variable => $default) {
            if (is_numeric($variable)) {
                $variable = $default;
                $default  = null;
            }

            $data[$variable] = array_get($arguments, $i, $default);
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
     * @return \Illuminate\Contracts\View\View|mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        if (static::hasComponent($method)) {
            return $this->renderComponent($method, $parameters);
        }

        throw new BadMethodCallException("Method {$method} does not exist.");
    }
}
