<?php

declare(strict_types=1);

use Arcanedev\LaravelHtml\Contracts\{FormBuilder, HtmlBuilder};
use Illuminate\Support\HtmlString;

if ( ! function_exists('form')) {
    /**
     * Get the Form Builder instance.
     *
     * @return \Arcanedev\LaravelHtml\Contracts\FormBuilder
     */
    function form(): FormBuilder {
        return app(FormBuilder::class);
    }
}

if ( ! function_exists('html')) {
    /**
     * Get the HTML Builder instance.
     *
     * @return \Arcanedev\LaravelHtml\Contracts\HtmlBuilder
     */
    function html(): HtmlBuilder {
        return app(HtmlBuilder::class);
    }
}

/* ------------------------------------------------------------------------------------------------
 |  Link Helpers
 | ------------------------------------------------------------------------------------------------
 */
if ( ! function_exists('link_to')) {
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
    function link_to(string $url, $title = null, array $attributes = [], $secure = null, $escaped = true): HtmlString {
        return html()->link($url, $title, $attributes, $secure, $escaped);
    }
}

if ( ! function_exists('link_to_asset')) {
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
    function link_to_asset(string $url, $title = null, array $attributes = [], $secure = null): HtmlString {
        return html()->linkAsset($url, $title, $attributes, $secure);
    }
}

if ( ! function_exists('link_to_route')) {
    /**
     * Generate a HTML link to a named route.
     *
     * @param  string  $name
     * @param  string  $title
     * @param  array   $params
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    function link_to_route(string $name, $title = null, array $params = [], array $attributes = [], $escaped = true): HtmlString {
        return html()->linkRoute($name, $title, $params, $attributes, $escaped);
    }
}

if ( ! function_exists('link_to_action')) {
    /**
     * Generate a HTML link to a controller action.
     *
     * @param  string  $action
     * @param  string  $title
     * @param  array   $params
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    function link_to_action(string $action, $title = null, array $params = [], array $attributes = [], $escaped = true): HtmlString {
        return html()->linkAction($action, $title, $params, $attributes, $escaped);
    }
}
