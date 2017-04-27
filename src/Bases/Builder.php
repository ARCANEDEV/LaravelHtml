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
     * @return \Illuminate\Contracts\View\View|mixed
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

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Transform the string to an Html serializable object
     *
     * @param  string  $html
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function toHtmlString($html)
    {
        return new HtmlString($html);
    }
}
