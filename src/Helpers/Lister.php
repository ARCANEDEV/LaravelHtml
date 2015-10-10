<?php namespace Arcanedev\LaravelHtml\Helpers;

class Lister
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a listing HTML element.
     *
     * @param  string  $type
     * @param  array   $list
     * @param  array   $attributes
     *
     * @return string
     */
    public static function make($type, array $list, array $attributes = [])
    {
        if (count($list) == 0) {
            return '';
        }

        $html = self::makeElements($type, $list);

        $attributes = Attributes::make($attributes);

        return "<{$type}{$attributes}>{$html}</{$type}>";
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Make listing elements.
     *
     * @param  string  $type
     * @param  array   $list
     *
     * @return string
     */
    private static function makeElements($type, array $list)
    {
        $html = '';

        // Essentially we will just spin through the list and build the list of the HTML
        // elements from the array. We will also handled nested lists in case that is
        // present in the array. Then we will build out the final listing elements.
        foreach ($list as $key => $value) {
            $html .= self::makeElement($key, $type, $value);
        }

        return $html;
    }

    /**
     * Create list element.
     *
     * @param  mixed   $key
     * @param  string  $type
     * @param  mixed   $value
     *
     * @return string
     */
    private static function makeElement($key, $type, $value)
    {
        return is_array($value)
            ? self::makeNestedElements($key, $type, $value)
            : '<li>' . e($value) . '</li>';
    }

    /**
     * Create a nested list attribute.
     *
     * @param  mixed   $key
     * @param  string  $type
     * @param  mixed   $value
     *
     * @return string
     */
    private static function makeNestedElements($key, $type, $value)
    {
        return is_int($key)
            ? self::make($type, $value)
            : '<li>' . $key . self::make($type, $value) . '</li>';
    }
}
