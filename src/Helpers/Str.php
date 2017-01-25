<?php namespace Arcanedev\LaravelHtml\Helpers;

/**
 * Class     Str
 *
 * @package  Arcanedev\LaravelHtml\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Str
{
    /**
     * Convert all applicable characters to HTML entities
     *
     * @param  string  $value
     * @param  bool    $doubleEncode
     *
     * @return string
     */
    public static function escape($value, $doubleEncode = true)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', $doubleEncode);
    }
}
