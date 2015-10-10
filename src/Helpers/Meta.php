<?php namespace Arcanedev\LaravelHtml\Helpers;

class Meta
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Function
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate a meta tag.
     *
     * @param  string  $name
     * @param  string  $content
     * @param  array   $attributes
     *
     * @return string
     */
    public static function make($name, $content, array $attributes = [])
    {
        $attributes = array_merge(
            compact('name', 'content'), $attributes
        );

        return '<meta' . Attributes::make($attributes) . '>' . PHP_EOL;
    }
}
