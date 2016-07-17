<?php namespace Arcanedev\LaravelHtml\Helpers;

/**
 * Class     Lister
 *
 * @package  Arcanedev\LaravelHtml\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Lister
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate an ordered list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return string
     */
    public static function ol(array $list, array $attributes = [])
    {
        return static::make('ol', $list, $attributes);
    }

    /**
     * Generate an un-ordered list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return string
     */
    public static function ul(array $list, array $attributes = [])
    {
        return static::make('ul', $list, $attributes);
    }

    /**
     * Generate a description list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return string
     */
    public static function dl(array $list, array $attributes = [])
    {
        $html       = '';
        $attributes = Attributes::make($attributes);

        foreach ($list as $key => $value) {
            $html .= "<dt>$key</dt>";
            $value = (array) $value;

            foreach ($value as $vKey => $vValue) {
                $html .= "<dd>$vValue</dd>";
            }
        }

        return "<dl{$attributes}>{$html}</dl>";
    }

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
        if (count($list) == 0) return '';

        $html       = static::makeElements($type, $list);
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
            $html .= static::makeElement($key, $type, $value);
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
            ? static::makeNestedElements($key, $type, $value)
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
            ? static::make($type, $value)
            : '<li>' . $key . static::make($type, $value) . '</li>';
    }
}
