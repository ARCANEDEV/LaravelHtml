<?php namespace Arcanedev\LaravelHtml\Helpers;

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
     * @param  array   $options
     *
     * @return string
     */
    public static function make($name, $value = null, array $options = [])
    {
        return implode('', [
            '<label for="' . $name . '"' . Attributes::make($options) . '>',
                e(self::format($name, $value)),
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
