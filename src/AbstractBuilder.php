<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml;

use Arcanedev\LaravelHtml\Traits\Componentable;
use BadMethodCallException;
use Illuminate\Support\Traits\Macroable;

/**
 * Class     AbstractBuilder
 *
 * @package  Arcanedev\LaravelHtml
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class AbstractBuilder
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
            return $this->macroCall($method, $parameters);
        }
    }
}
