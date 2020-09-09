<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Contracts;

use Illuminate\Support\HtmlString;

/**
 * Interface  HtmlBuilder
 *
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface HtmlBuilder
{
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
    public function entities(string $value, bool $doubleEncode = false): string;

    /**
     * Convert all applicable characters to HTML entities.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function escape(string $value): string;

    /**
     * Convert entities to HTML characters.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function decode(string $value): string;

    /**
     * Generate a link to a JavaScript file.
     *
     * @param  string     $url
     * @param  array      $attributes
     * @param  bool|null  $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script(string $url, array $attributes = [], ?bool $secure = null): HtmlString;

    /**
     * Generate a link to a CSS file.
     *
     * @param  string     $url
     * @param  array      $attributes
     * @param  bool|null  $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function style(string $url, array $attributes = [], ?bool $secure = null): HtmlString;

    /**
     * Generate an HTML image element.
     *
     * @param  string     $url
     * @param  string     $alt
     * @param  array      $attributes
     * @param  bool|null  $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function image(string $url, ?string $alt = null, array $attributes = [], ?bool $secure = null): HtmlString;

    /**
     * Generate a link to a Favicon file.
     *
     * @param  string     $url
     * @param  array      $attributes
     * @param  bool|null  $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function favicon(string $url, array $attributes = [], ?bool $secure = null): HtmlString;

    /**
     * Generate a HTML link.
     *
     * @param  string     $url
     * @param  string     $title
     * @param  array      $attributes
     * @param  bool|null  $secure
     * @param  bool       $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function link(string $url, ?string $title = null, array $attributes = [], ?bool $secure = null, bool $escaped = true): HtmlString;

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
    public function secureLink(string $url, ?string $title = null, array $attributes = [], bool $escaped = true): HtmlString;

    /**
     * Generate a HTML link to an asset.
     *
     * @param  string       $url
     * @param  string|null  $title
     * @param  array        $attributes
     * @param  bool         $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkAsset(string $url, ?string $title = null, array $attributes = [], ?bool $secure = null): HtmlString;

    /**
     * Generate a HTTPS HTML link to an asset.
     *
     * @param  string       $url
     * @param  string|null  $title
     * @param  array        $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkSecureAsset(string $url, ?string $title = null, array $attributes = []): HtmlString;

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
    public function linkRoute(string $name, ?string $title = null, array $parameters = [], array $attributes = [], bool $escaped = true): HtmlString;

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
    public function linkAction(string $action, ?string $title = null, array $parameters = [], array $attributes = [], bool $escaped = true): HtmlString;

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
    public function mailto(string $email, ?string $title = null, array $attributes = [], bool $escaped = true): HtmlString;

    /**
     * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
     *
     * @param  string  $email
     *
     * @return string
     */
    public function email(string $email): string;

    /**
     * Generate an ordered list of items.
     *
     * @param  iterable|array  $items
     * @param  iterable|array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ol($items, $attributes = []): HtmlString;

    /**
     * Generate an un-ordered list of items.
     *
     * @param  iterable|array  $items
     * @param  iterable|array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ul($items, $attributes = []): HtmlString;

    /**
     * Generate a description list of items.
     *
     * @param  iterable|array  $items
     * @param  iterable|array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function dl($items, $attributes = []): HtmlString;

    /**
     * Generates non-breaking space entities based on a supplied multiplier.
     *
     * @param  int  $multiplier
     *
     * @return string
     */
    public function nbsp(int $multiplier = 1): string;

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public function attributes(array $attributes): string;

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function obfuscate(string $value): string;

    /**
     * Generate a meta tag.
     *
     * @param  string  $name
     * @param  string  $content
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function meta(string $name, string $content, array $attributes = []): HtmlString;

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
    public function tel(string $phone, ?string $title = null, array $attributes = [], bool $escaped = true): HtmlString;
}
