<?php namespace Arcanedev\LaravelHtml\Bases;

use Arcanedev\LaravelHtml\Traits\Componentable;
use BadMethodCallException;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Traits\Macroable;

/**
 * Class     Builder
 *
 * @package  Arcanedev\LaravelHtml\Bases
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class Builder
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use Macroable, Componentable {
        Macroable::__call     as macroCall;
        Componentable::__call as componentCall;
    }

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Dynamically handle calls to the class.
     *
     * @param  string  $method
     * @param  array   $parameters
     *
     * @return mixed
     *
     * @throws \BadMethodCallException
     */
    public function __call($method, $parameters)
    {
        try {
            return $this->componentCall($method, $parameters);
        }
        catch (BadMethodCallException $e) {
            // Continue
        }

        return $this->macroCall($method, $parameters);
    }
}
