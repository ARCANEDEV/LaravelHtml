<?php namespace Arcanedev\LaravelHtml\Builders;

use Arcanedev\LaravelHtml\Contracts\HtmlBuilderInterface;
use Illuminate\Routing\UrlGenerator;
use Illuminate\Support\Traits\Macroable;

/**
 * Class HtmlBuilder
 * @package Arcanedev\LaravelHtml
 */
class HtmlBuilder implements HtmlBuilderInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Traits
     | ------------------------------------------------------------------------------------------------
     */
    use Macroable;

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The URL generator instance.
     *
     * @var UrlGenerator
     */
    protected $url;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new HTML builder instance.
     *
     * @param  UrlGenerator $url
     */
    public function __construct(UrlGenerator $url = null)
    {
        $this->url = $url;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Convert an HTML string to entities.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function entities($value)
    {
        return htmlentities($value, ENT_QUOTES, 'UTF-8', false);
    }

    /**
     * Convert entities to HTML characters.
     *
     * @param  string  $value
     * @return string
     */
    public function decode($value)
    {
        return html_entity_decode($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * Generate a link to a JavaScript file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
     */
    public function script($url, $attributes = [], $secure = null)
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return '<script' . $this->attributes($attributes) . '></script>' . PHP_EOL;
    }

    /**
     * Generate a link to a CSS file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
     */
    public function style($url, $attributes = [], $secure = null)
    {
        $attributes         = $attributes + [
            'media' => 'all',
            'type'  => 'text/css',
            'rel'   => 'stylesheet'
        ];
        $attributes['href'] = $this->url->asset($url, $secure);

        return '<link' . $this->attributes($attributes) . '>' . PHP_EOL;
    }

    /**
     * Generate an HTML image element.
     *
     * @param  string  $url
     * @param  string  $alt
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
     */
    public function image($url, $alt = null, $attributes = [], $secure = null)
    {
        $attributes['alt'] = $alt;

        return '<img src="' . $this->url->asset($url, $secure) . '"' . $this->attributes($attributes) . '>';
    }

    /**
     * Generate a link to a Favicon file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
     */
    public function favicon($url, $attributes = [], $secure = null)
    {
        $attributes         = $attributes + [
            'rel'  => 'shortcut icon',
            'type' => 'image/x-icon'
        ];
        $attributes['href'] = $this->url->asset($url, $secure);

        return '<link' . $this->attributes($attributes) . '>' . PHP_EOL;
    }

    /**
     * Generate a HTML link.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
     */
    public function link($url, $title = null, $attributes = [], $secure = null)
    {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title) || $title === false) {
            $title = $url;
        }

        return '<a href="' . $url . '"' . $this->attributes($attributes) . '>' . $this->entities($title) . '</a>';
    }

    /**
     * Generate a HTTPS HTML link.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     *
     * @return string
     */
    public function secureLink($url, $title = null, $attributes = [])
    {
        return $this->link($url, $title, $attributes, true);
    }

    /**
     * Generate a HTML link to an asset.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
     */
    public function linkAsset($url, $title = null, $attributes = [], $secure = null)
    {
        $url = $this->url->asset($url, $secure);

        return $this->link($url, $title ?: $url, $attributes, $secure);
    }

    /**
     * Generate a HTTPS HTML link to an asset.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     *
     * @return string
     */
    public function linkSecureAsset($url, $title = null, $attributes = [])
    {
        return $this->linkAsset($url, $title, $attributes, true);
    }

    /**
     * Generate a HTML link to a named route.
     *
     * @param  string  $name
     * @param  string  $title
     * @param  array   $parameters
     * @param  array   $attributes
     *
     * @return string
     */
    public function linkRoute($name, $title = null, $parameters = [], $attributes = [])
    {
        return $this->link(
            $this->url->route($name, $parameters),
            $title,
            $attributes
        );
    }

    /**
     * Generate a HTML link to a controller action.
     *
     * @param  string  $action
     * @param  string  $title
     * @param  array   $parameters
     * @param  array   $attributes
     *
     * @return string
     */
    public function linkAction($action, $title = null, $parameters = [], $attributes = [])
    {
        return $this->link(
            $this->url->action($action, $parameters),
            $title,
            $attributes
        );
    }

    /**
     * Generate a HTML link to an email address.
     *
     * @param  string  $email
     * @param  string  $title
     * @param  array   $attributes
     * @return string
     */
    public function mailto($email, $title = null, $attributes = [])
    {
        $email = $this->email($email);
        $title = $title ?: $email;
        $email = $this->obfuscate('mailto:') . $email;

        return '<a href="' . $email . '"' . $this->attributes($attributes) . '>' . $this->entities($title) . '</a>';
    }

    /**
     * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
     *
     * @param  string  $email
     *
     * @return string
     */
    public function email($email)
    {
        return str_replace('@', '&#64;', $this->obfuscate($email));
    }

    /**
     * Generate an ordered list of items.
     *
     * @param  array   $list
     * @param  array   $attributes
     *
     * @return string
     */
    public function ol($list, $attributes = [])
    {
        return $this->listing('ol', $list, $attributes);
    }

    /**
     * Generate an un-ordered list of items.
     *
     * @param  array   $list
     * @param  array   $attributes
     *
     * @return string
     */
    public function ul($list, $attributes = [])
    {
        return $this->listing('ul', $list, $attributes);
    }

    /**
     * Generate a description list of items.
     *
     * @param  array   $list
     * @param  array   $attributes
     *
     * @return string
     */
    public function dl(array $list, array $attributes = [])
    {
        $attributes = $this->attributes($attributes);

        $html = "<dl{$attributes}>";

        foreach ($list as $key => $value) {
            $html .= "<dt>$key</dt><dd>$value</dd>";
        }

        $html .= '</dl>';

        return $html;
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public function attributes($attributes)
    {
        $html = [];

        foreach ((array) $attributes as $key => $value) {
            $element = $this->attributeElement($key, $value);

            if ( ! is_null($element)) {
                $html[] = $element;
            }
        }

        return (count($html) > 0) ? ' ' . implode(' ', $html) : '';
    }

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function obfuscate($value)
    {
        $safe = '';

        foreach (str_split($value) as $letter) {
            if (ord($letter) > 128) {
                return $letter;
            }

            // To properly obfuscate the value, we will randomly convert each letter to
            // its entity or hexadecimal representation, keeping a bot from sniffing
            // the randomly obfuscated letters out of the string on the responses.
            switch (rand(1, 3)) {
                case 1:
                    $safe .= '&#'.ord($letter).';';
                    break;

                case 2:
                    $safe .= '&#x'.dechex(ord($letter)).';';
                    break;

                case 3:
                    $safe .= $letter;
                    // no break
            }
        }

        return $safe;
    }

    /**
     * Generate a meta tag.
     *
     * @param  string  $name
     * @param  string  $content
     * @param  array   $attributes
     *
     * @return string
     */
    public function meta($name, $content, array $attributes = [])
    {
        $defaults   = compact('name', 'content');
        $attributes = array_merge($defaults, $attributes);

        return '<meta' . $this->attributes($attributes) . '>' . PHP_EOL;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
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
    protected function listing($type, $list, $attributes = [])
    {
        if (count($list) == 0) {
            return '';
        }

        $html = '';

        // Essentially we will just spin through the list and build the list of the HTML
        // elements from the array. We will also handled nested lists in case that is
        // present in the array. Then we will build out the final listing elements.
        foreach ($list as $key => $value) {
            $html .= $this->listingElement($key, $type, $value);
        }

        $attributes = $this->attributes($attributes);

        return "<{$type}{$attributes}>{$html}</{$type}>";
    }

    /**
     * Create the HTML for a listing element.
     *
     * @param  mixed    $key
     * @param  string  $type
     * @param  string  $value
     *
     * @return string
     */
    protected function listingElement($key, $type, $value)
    {
        return is_array($value)
            ? $this->nestedListing($key, $type, $value)
            : '<li>' . e($value) . '</li>';
    }

    /**
     * Create the HTML for a nested listing attribute.
     *
     * @param  mixed   $key
     * @param  string  $type
     * @param  string  $value
     *
     * @return string
     */
    protected function nestedListing($key, $type, $value)
    {
        return is_int($key)
            ? $this->listing($type, $value)
            : '<li>' . $key . $this->listing($type, $value) . '</li>';
    }

    /**
     * Build a single attribute element.
     *
     * @param  string  $key
     * @param  string  $value
     *
     * @return string
     */
    protected function attributeElement($key, $value)
    {
        if (is_null($value)) {
            return null;
        }

        // For numeric keys we will assume that the key and the value are the same
        // as this will convert HTML attributes such as "required" to a correct
        // form like required="required" instead of using incorrect numerics.
        if (is_numeric($key)) {
            $key = $value;
        }

        return $key . '="' . e($value) . '"';
    }
}
