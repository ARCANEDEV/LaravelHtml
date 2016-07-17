<?php namespace Arcanedev\LaravelHtml;

use Arcanedev\LaravelHtml\Bases\Builder;
use Arcanedev\LaravelHtml\Contracts\HtmlBuilderInterface;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * Class     HtmlBuilder
 *
 * @package  Arcanedev\LaravelHtml
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlBuilder extends Builder implements HtmlBuilderInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Create a new HTML builder instance.
     *
     * @param  \Illuminate\Contracts\Routing\UrlGenerator  $url
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
     *
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
     * @return \Illuminate\Support\HtmlString
     */
    public function script($url, $attributes = [], $secure = null)
    {
        $attributes['src'] = $this->url->asset($url, $secure);

        return $this->toHtmlString(
            '<script' . $this->attributes($attributes) . '></script>' . PHP_EOL
        );
    }

    /**
     * Generate a link to a CSS file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function style($url, $attributes = [], $secure = null)
    {
        $attributes         = $attributes + [
            'media' => 'all',
            'type'  => 'text/css',
            'rel'   => 'stylesheet'
        ];
        $attributes['href'] = $this->url->asset($url, $secure);

        return $this->toHtmlString(
            '<link' . $this->attributes($attributes) . '>' . PHP_EOL
        );
    }

    /**
     * Generate an HTML image element.
     *
     * @param  string  $url
     * @param  string  $alt
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function image($url, $alt = null, $attributes = [], $secure = null)
    {
        $attributes['alt'] = $alt;

        return $this->toHtmlString(
            '<img src="' . $this->url->asset($url, $secure) . '"' . $this->attributes($attributes) . '>'
        );
    }

    /**
     * Generate a link to a Favicon file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function favicon($url, $attributes = [], $secure = null)
    {
        $attributes = array_merge($attributes, [
            'rel'  => 'shortcut icon',
            'type' => 'image/x-icon',
            'href' => $this->url->asset($url, $secure)
        ]);

        return $this->toHtmlString(
            '<link' . $this->attributes($attributes) . '>' . PHP_EOL
        );
    }

    /**
     * Generate a HTML link.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $secure
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function link($url, $title = null, $attributes = [], $secure = null, $escaped = true)
    {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title) || $title === false)
            $title = $url;

        return $this->toHtmlString(
            '<a href="' . $url . '"' . $this->attributes($attributes) . '>' .
                ($escaped ? $this->entities($title) : $title).
            '</a>'
        );
    }

    /**
     * Generate a HTTPS HTML link.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function secureLink($url, $title = null, $attributes = [], $escaped = true)
    {
        return $this->link($url, $title, $attributes, true, $escaped);
    }

    /**
     * Generate a HTML link to an asset.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return \Illuminate\Support\HtmlString
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
     * @return \Illuminate\Support\HtmlString
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
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkRoute($name, $title = null, $parameters = [], $attributes = [], $escaped = true)
    {
        $url = $this->url->route($name, $parameters);

        return $this->link($url, $title, $attributes, null, $escaped);
    }

    /**
     * Generate a HTML link to a controller action.
     *
     * @param  string  $action
     * @param  string  $title
     * @param  array   $parameters
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkAction($action, $title = null, $parameters = [], $attributes = [], $escaped = true)
    {
        $url = $this->url->action($action, $parameters);

        return $this->link($url, $title, $attributes, null, $escaped);
    }

    /**
     * Generate a HTML link to an email address.
     *
     * @param  string  $email
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function mailto($email, $title = null, $attributes = [], $escaped = true)
    {
        $email = $this->email($email);
        $title = $title ?: $email;
        $email = $this->obfuscate('mailto:') . $email;

        return $this->toHtmlString(
            '<a href="' . $email . '"' . $this->attributes($attributes) . '>' .
                ($escaped ? $this->entities($title) : $title) .
            '</a>'
        );
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
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ol(array $list, array $attributes = [])
    {
        return $this->toHtmlString(
            Helpers\Lister::ol($list, $attributes)
        );
    }

    /**
     * Generate an un-ordered list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ul(array $list, array $attributes = [])
    {
        return $this->toHtmlString(
            Helpers\Lister::ul($list, $attributes)
        );
    }

    /**
     * Generate a description list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function dl(array $list, array $attributes = [])
    {
        return $this->toHtmlString(
            Helpers\Lister::dl($list, $attributes)
        );
    }

    /**
     * Generates non-breaking space entities based on a supplied multiplier.
     *
     * @param  int  $multiplier
     *
     * @return string
     */
    public function nbsp($multiplier = 1)
    {
        return str_repeat('&nbsp;', $multiplier);
    }

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public function attributes(array $attributes)
    {
        return Helpers\Attributes::make($attributes);
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
        return Helpers\Obfuscater::make($value);
    }

    /**
     * Generate a meta tag.
     *
     * @param  string  $name
     * @param  string  $content
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function meta($name, $content, array $attributes = [])
    {
        return $this->toHtmlString(
            Helpers\Meta::make($name, $content, $attributes)
        );
    }
}
