<?php namespace Arcanedev\LaravelHtml\Helpers;

/**
 * Class     Meta
 *
 * @package  Arcanedev\LaravelHtml\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Meta
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
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
        $attributes = Attributes::make(array_merge(
            compact('name', 'content'), $attributes
        ));

        return "<meta{$attributes}>" . PHP_EOL;
    }
}
