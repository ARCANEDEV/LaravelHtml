<?php namespace Arcanedev\LaravelHtml\Helpers;

/**
 * Class     Attributes
 *
 * @package  Arcanedev\LaravelHtml\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Attributes
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public static function make(array $attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = static::makeAttributeElement($key, $value);

            if ( ! is_null($element)) {
                $html[] = $element;
            }
        }

        return (count($html) > 0) ? ' ' . implode(' ', $html) : '';
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make attribute element.
     *
     * @param  mixed  $key
     * @param  mixed  $value
     *
     * @return null|string
     */
    private static function makeAttributeElement($key, $value)
    {
        if (is_null($value)) return null;

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        if (is_numeric($key)) {
            $key = $value;
        }

        return $key.'="'.Str::escape($value).'"';
    }
}
