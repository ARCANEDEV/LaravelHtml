<?php namespace Arcanedev\LaravelHtml;

use Arcanedev\Html\Elements;
use Arcanedev\Html\Entities\Attributes;
use Arcanedev\LaravelHtml\Contracts\HtmlBuilder as HtmlBuilderContract;
use Illuminate\Contracts\Routing\UrlGenerator;

/**
 * Class     HtmlBuilder
 *
 * @package  Arcanedev\LaravelHtml
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlBuilder extends AbstractBuilder implements HtmlBuilderContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The URL generator instance.
     *
     * @var \Illuminate\Contracts\Routing\UrlGenerator
     */
    protected $url;

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
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

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Convert an HTML string to entities.
     *
     * @param  string  $value
     * @param  bool    $doubleEncode
     *
     * @return string
     */
    public function entities($value, $doubleEncode = false)
    {
        return e($value, $doubleEncode);
    }

    /**
     * Convert all applicable characters to HTML entities.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function escape($value)
    {
        return e($value, false);
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
    public function script($url, array $attributes = [], $secure = null)
    {
        return Elements\Element::withTag('script')
            ->attribute('src', $this->url->asset($url, $secure))
            ->attributes($attributes)
            ->render();
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
    public function style($url, array $attributes = [], $secure = null)
    {
        $attributes = array_merge($attributes, [
            'rel'  => 'stylesheet',
            'href' => $this->url->asset($url, $secure),
        ]);

        return Elements\Element::withTag('link')
            ->attributes($attributes)
            ->render();
    }

    /**
     * Generate an HTML image element.
     *
     * @param  string       $url
     * @param  string|null  $alt
     * @param  array        $attributes
     * @param  bool         $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function image($url, $alt = null, $attributes = [], $secure = null)
    {
        return Elements\Img::make()
            ->src($this->url->asset($url, $secure))
            ->attributeUnless(is_null($alt), 'alt', $alt)
            ->attributes($attributes)
            ->render();
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
    public function favicon($url, array $attributes = [], $secure = null)
    {
        $attributes = array_merge([
            'rel'  => 'shortcut icon',
            'type' => 'image/x-icon',
        ], $attributes);

        return Elements\Element::withTag('link')
            ->attribute('href', $this->url->asset($url, $secure))
            ->attributes($attributes)
            ->render();
    }

    /**
     * Generate a HTML link.
     *
     * @param  string       $url
     * @param  string|null  $title
     * @param  array        $attributes
     * @param  bool         $secure
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function link($url, $title = null, array $attributes = [], $secure = null, $escaped = true)
    {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title) || $title === false)
            $title = $url;

        return Elements\A::make()
            ->href($this->entities($url))
            ->attributes($attributes)
            ->html($escaped ? $this->entities($title) : $title)
            ->render();
    }

    /**
     * Generate a HTTPS HTML link.
     *
     * @param  string       $url
     * @param  string|null  $title
     * @param  array        $attributes
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function secureLink($url, $title = null, array $attributes = [], $escaped = true)
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
    public function linkAsset($url, $title = null, array $attributes = [], $secure = null)
    {
        $url = $this->url->asset($url, $secure);

        return $this->link($url, $title ?: $url, $attributes, $secure);
    }

    /**
     * Generate a HTTPS HTML link to an asset.
     *
     * @param  string       $url
     * @param  string|null  $title
     * @param  array        $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkSecureAsset($url, $title = null, array $attributes = [])
    {
        return $this->linkAsset($url, $title, $attributes, true);
    }

    /**
     * Generate a HTML link to a named route.
     *
     * @param  string       $name
     * @param  string|null  $title
     * @param  array|mixed  $parameters
     * @param  array        $attributes
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkRoute($name, $title = null, $parameters = [], array $attributes = [], $escaped = true)
    {
        $url = $this->url->route($name, $parameters);

        return $this->link($url, $title, $attributes, null, $escaped);
    }

    /**
     * Generate a HTML link to a controller action.
     *
     * @param  string       $action
     * @param  string       $title
     * @param  array|mixed  $parameters
     * @param  array        $attributes
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkAction($action, $title = null, $parameters = [], array $attributes = [], $escaped = true)
    {
        $url = $this->url->action($action, $parameters);

        return $this->link($url, $title, $attributes, null, $escaped);
    }

    /**
     * Generate a HTML link to an email address.
     *
     * @param  string       $email
     * @param  string|null  $title
     * @param  array        $attributes
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function mailto($email, $title = null, array $attributes = [], $escaped = true)
    {
        $email = $this->email($email);
        $title = $title ?: $email;

        return Elements\A::make()
            ->href($this->obfuscate('mailto:').$email)
            ->attributes($attributes)
            ->html(($escaped ? $this->entities($title) : $title))
            ->render();
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
     * @param  array  $items
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ol(array $items, array $attributes = [])
    {
        return Elements\Ol::make()
            ->items($items)
            ->attributes($attributes)
            ->render();
    }

    /**
     * Generate an un-ordered list of items.
     *
     * @param  array  $items
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ul(array $items, array $attributes = [])
    {
        return Elements\Ul::make()
            ->items($items)
            ->attributes($attributes)
            ->render();
    }

    /**
     * Generate a description list of items.
     *
     * @param  array  $items
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function dl(array $items, array $attributes = [])
    {
        return Elements\Dl::make()
            ->items($items)
            ->attributes($attributes)
            ->render();
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
        return Attributes::make($attributes)->render();
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
        return Elements\Meta::make()
            ->attributeUnless(is_null($name), 'name', $name)
            ->attributeUnless(is_null($content), 'content', $content)
            ->attributes($attributes)
            ->render();
    }

    /**
     * Generate a HTML link to an phone number (call).
     *
     * @param  string  $phone
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function tel($phone, $title = null, $attributes = [], $escaped = true)
    {
        $title = $title ?: $phone;

        return Elements\A::make()
            ->href("tel:{$phone}")
            ->attributes($attributes)
            ->html($escaped ? $this->entities($title) : $title)
            ->render();
    }
}
