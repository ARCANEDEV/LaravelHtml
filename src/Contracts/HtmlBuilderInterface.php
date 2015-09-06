<?php namespace Arcanedev\LaravelHtml\Contracts;

/**
 * Interface  HtmlBuilderInterface
 *
 * @package   Arcanedev\LaravelHtml\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface HtmlBuilderInterface
{
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
    public function entities($value);

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
     * @return string
     */
    public function script($url, $attributes = [], $secure = null);

    /**
     * Generate a link to a CSS file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
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
     * @return string
     */
    public function image($url, $alt = null, $attributes = [], $secure = null);

    /**
     * Generate a link to a Favicon file.
     *
     * @param  string  $url
     * @param  array   $attributes
     * @param  bool    $secure
     *
     * @return string
     */
    public function favicon($url, $attributes = [], $secure = null);

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
    public function link($url, $title = null, $attributes = [], $secure = null);

    /**
     * Generate a HTTPS HTML link.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     *
     * @return string
     */
    public function secureLink($url, $title = null, $attributes = []);

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
    public function linkAsset($url, $title = null, $attributes = [], $secure = null);

    /**
     * Generate a HTTPS HTML link to an asset.
     *
     * @param  string  $url
     * @param  string  $title
     * @param  array   $attributes
     *
     * @return string
     */
    public function linkSecureAsset($url, $title = null, $attributes = []);

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
    public function linkRoute($name, $title = null, $parameters = [], $attributes = []);

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
    public function linkAction($action, $title = null, $parameters = [], $attributes = []);

    /**
     * Generate a HTML link to an email address.
     *
     * @param  string  $email
     * @param  string  $title
     * @param  array   $attributes
     *
     * @return string
     */
    public function mailto($email, $title = null, $attributes = []);

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
     * @return string
     */
    public function ol($list, $attributes = []);

    /**
     * Generate an un-ordered list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return string
     */
    public function ul($list, $attributes = []);

    /**
     * Generate a description list of items.
     *
     * @param  array  $list
     * @param  array  $attributes
     *
     * @return string
     */
    public function dl(array $list, array $attributes = []);

    /**
     * Build an HTML attribute string from an array.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    public function attributes($attributes);

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
     * @return string
     */
    public function meta($name, $content, array $attributes = []);
}
