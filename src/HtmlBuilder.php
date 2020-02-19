<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml;

use Arcanedev\Html\Elements\{A, Dl, Element, Img, Meta, Ol, Ul};
use Arcanedev\Html\Entities\Attributes;
use Arcanedev\LaravelHtml\Contracts\HtmlBuilder as HtmlBuilderContract;
use Arcanedev\LaravelHtml\Helpers\Obfuscater;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Support\HtmlString;

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
    public function entities(string $value, bool $doubleEncode = false): string
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
    public function escape(string $value): string
    {
        return $this->entities($value, false);
    }

    /**
     * Convert entities to HTML characters.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function decode(string $value): string
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
    public function script(string $url, array $attributes = [], ?bool $secure = null): HtmlString
    {
        return Element::withTag('script')
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
    public function style(string $url, array $attributes = [], ?bool $secure = null): HtmlString
    {
        $attributes = array_merge($attributes, [
            'rel'  => 'stylesheet',
            'href' => $this->url->asset($url, $secure),
        ]);

        return Element::withTag('link')
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
    public function image(string $url, ?string $alt = null, array $attributes = [], ?bool $secure = null): HtmlString
    {
        return Img::make()
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
    public function favicon(string $url, array $attributes = [], ?bool $secure = null): HtmlString
    {
        $attributes = array_merge([
            'rel'  => 'shortcut icon',
            'type' => 'image/x-icon',
        ], $attributes);

        return Element::withTag('link')
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
    public function link(string $url, ?string $title = null, array $attributes = [], ?bool $secure = null, bool $escaped = true): HtmlString
    {
        $url = $this->url->to($url, [], $secure);

        if (is_null($title) || $title === false)
            $title = $url;

        return A::make()
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
    public function secureLink(string $url, ?string $title = null, array $attributes = [], bool $escaped = true): HtmlString
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
    public function linkAsset(string $url, ?string $title = null, array $attributes = [], ?bool $secure = null): HtmlString
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
    public function linkSecureAsset(string $url, ?string $title = null, array $attributes = []): HtmlString
    {
        return $this->linkAsset($url, $title, $attributes, true);
    }

    /**
     * Generate a HTML link to a named route.
     *
     * @param  string       $name
     * @param  string|null  $title
     * @param  array        $parameters
     * @param  array        $attributes
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkRoute(
        string $name, ?string $title = null, array $parameters = [], array $attributes = [], bool $escaped = true
    ): HtmlString {
        $url = $this->url->route($name, $parameters);

        return $this->link($url, $title, $attributes, null, $escaped);
    }

    /**
     * Generate a HTML link to a controller action.
     *
     * @param  string       $action
     * @param  string|null  $title
     * @param  array        $parameters
     * @param  array        $attributes
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkAction(
        string $action, ?string $title = null, array $parameters = [], array $attributes = [], bool $escaped = true
    ): HtmlString {
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
    public function mailto(string $email, ?string $title = null, array $attributes = [], bool $escaped = true): HtmlString
    {
        $email = $this->email($email);
        $title = $title ?: $email;

        return A::make()
            ->href($this->obfuscate('mailto:').$email)
            ->attributes($attributes)
            ->html($escaped ? $this->entities($title) : $title)
            ->render();
    }

    /**
     * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
     *
     * @param  string  $email
     *
     * @return string
     */
    public function email(string $email): string
    {
        return str_replace('@', '&#64;', $this->obfuscate($email));
    }

    /**
     * Generate an ordered list of items.
     *
     * @param  iterable|array  $items
     * @param  iterable|array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ol($items, $attributes = []): HtmlString
    {
        return Ol::make()->items($items)->attributes($attributes)->render();
    }

    /**
     * Generate an un-ordered list of items.
     *
     * @param  iterable|array  $items
     * @param  iterable|array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ul($items, $attributes = []): HtmlString
    {
        return Ul::make()->items($items)->attributes($attributes)->render();
    }

    /**
     * Generate a description list of items.
     *
     * @param  iterable|array  $items
     * @param  iterable|array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function dl($items, $attributes = []): HtmlString
    {
        return Dl::make()->items($items)->attributes($attributes)->render();
    }

    /**
     * Generates non-breaking space entities based on a supplied multiplier.
     *
     * @param  int  $multiplier
     *
     * @return string
     */
    public function nbsp(int $multiplier = 1): string
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
    public function attributes(array $attributes): string
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
    public function obfuscate(string $value): string
    {
        return Obfuscater::make($value);
    }

    /**
     * Generate a meta tag.
     *
     * @param  string|null  $name
     * @param  string       $content
     * @param  array        $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function meta(?string $name, string $content, array $attributes = []): HtmlString
    {
        return Meta::make()
            ->attributeIfNotNull($name, 'name', $name)
            ->attributeIfNotNull($content, 'content', $content)
            ->attributes($attributes)
            ->render();
    }

    /**
     * Generate a HTML link to an phone number (call).
     *
     * @param  string       $phone
     * @param  string|null  $title
     * @param  array        $attributes
     * @param  bool         $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function tel(string $phone, ?string $title = null, array $attributes = [], $escaped = true): HtmlString
    {
        $title = $title ?: $phone;

        return A::make()
            ->href("tel:{$phone}")
            ->attributes($attributes)
            ->html($escaped ? $this->entities($title) : $title)
            ->render();
    }
}
