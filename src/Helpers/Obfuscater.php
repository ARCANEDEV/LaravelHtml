<?php namespace Arcanedev\LaravelHtml\Helpers;

/**
 * Class     Obfuscater
 *
 * @package  Arcanedev\LaravelHtml\Helpers
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class Obfuscater
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @param  string  $value
     *
     * @return string
     */
    public static function make($value)
    {
        $safe = '';

        foreach (str_split($value) as $letter) {
            if (ord($letter) > 128)
                return $letter;

            self::makeSafer($safe, $letter);
        }

        return $safe;
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Make safer.
     *
     * @param  string  $letter
     * @param  string  $safe
     */
    private static function makeSafer(&$safe, $letter)
    {
        // To properly obfuscate the value, we will randomly convert each letter to
        // its entity or hexadecimal representation, keeping a bot from sniffing
        // the randomly obfuscated letters out of the string on the responses.
        switch (rand(1, 3)) {
            case 1:
                $safe .= '&#' . ord($letter).';';
                break;

            case 2:
                $safe .= '&#x' . dechex(ord($letter)).';';
                break;

            case 3:
                $safe .= $letter;
                // no break
        }
    }
}
