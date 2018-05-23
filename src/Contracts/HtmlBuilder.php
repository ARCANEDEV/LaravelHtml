<?php namespace Arcanedev\LaravelHtml\Contracts;

/**
 * Interface  HtmlBuilder
 *
 * @package   Arcanedev\LaravelHtml\Contracts
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
    public function entities($value, $doubleEncode = false);

    /**
     * Convert all applicable characters to HTML entities.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function escape($value);

    /**
     * Convert entities to HTML characters.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function decode($value);

    /**
     * Generate a link to a JavaScript file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function script($url, $attributes = [], $secure = null);

    /**
     * Generate a link to a CSS file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function style($url, $attributes = [], $secure = null);

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
    public function image($url, $alt = null, $attributes = [], $secure = null);

    /**
     * Generate a link to a Favicon file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function favicon($url, $attributes = [], $secure = null);

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
    public function link($url, $title = null, $attributes = [], $secure = null, $escaped = true);

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
    public function secureLink($url, $title = null, $attributes = [], $escaped = true);

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
    public function linkAsset($url, $title = null, $attributes = [], $secure = null);

    /**
     * Generate a HTTPS HTML link to an asset.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function linkSecureAsset($url, $title = null, $attributes = []);

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
    public function linkRoute($name, $title = null, $parameters = [], $attributes = [], $escaped = true);

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
    public function linkAction($action, $title = null, $parameters = [], $attributes = [], $escaped = true);

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
    public function mailto($email, $title = null, $attributes = [], $escaped = true);

    /**
     * Obfuscate an e-mail address to prevent spam-bots from sniffing it.
     *
     * @param  string  $email
     *
     * @return string
     */
    public function email($email);

    /**
     * Generate an ordered list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ol(array $list, array $attributes = []);

    /**
     * Generate an un-ordered list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function ul(array $list, array $attributes = []);

    /**
     * Generate a description list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function dl(array $list, array $attributes = []);

    /**
     * Generates non-breaking space entities based on a supplied multiplier.
     *
     * @param  int  $multiplier
     *
     * @return string
     */
    public function nbsp($multiplier = 1);

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public function attributes(array $attributes);

    /**
     * Obfuscate a string to prevent spam-bots from sniffing it.
     *
     * @param  string  $value
     *
     * @return string
     */
    public function obfuscate($value);

    /**
     * Generate a meta tag.
     *
     * @param  string  $name
     * @param  string  $content
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function meta($name, $content, array $attributes = []);

    /**
     * Generate a HTML link to an phone number (call).
     *
     * @param  string  $phone
     * @param  string  $title
     * @param  array   $attributes
     * @param  bool    $escaped
     */
    public function tel($phone, $title = null, $attributes = [], $escaped = true);
}
