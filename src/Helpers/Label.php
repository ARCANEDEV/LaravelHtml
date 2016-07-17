<?php namespace Arcanedev\LaravelHtml\Helpers;

/**
 * Class     Label
 *
 * @package  Arcanedev\LaravelHtml\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Label
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a form label element.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return string
     */
    public static function make($name, $value = null, array $attributes = [], $escaped = true)
    {
        $value = static::format($name, $value);

        return implode('', [
            '<label for="' . $name . '"' . Attributes::make($attributes) . '>',
                $escaped ? e($value) : $value,
            '</label>'
        ]);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Format the label value.
     *
     * @param  string       $name
     * @param  string|null  $value
     *
     * @return string
     */
    private static function format($name, $value)
    {
        return $value ?: ucwords(str_replace('_', ' ', $name));
    }
}
