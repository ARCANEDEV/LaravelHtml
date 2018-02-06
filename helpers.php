<?php

use Arcanedev\LaravelHtml\Contracts\FormBuilder;
use Arcanedev\LaravelHtml\Contracts\HtmlBuilder;

if ( ! function_exists('form')) {
    /**
     * Get the Form Builder instance.
     *
     * @return \Arcanedev\LaravelHtml\Contracts\FormBuilder
     */
    function form()
    {
        return app(FormBuilder::class);
    }
}

if ( ! function_exists('html')) {
    /**
     * Get the HTML Builder instance.
     *
     * @return \Arcanedev\LaravelHtml\Contracts\HtmlBuilder
     */
    function html()
    {
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
    function link_to($url, $title = null, $attributes = [], $secure = null, $escaped = true)
    {
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
    function link_to_asset($url, $title = null, $attributes = [], $secure = null)
    {
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
    function link_to_route($name, $title = null, $params = [], $attributes = [], $escaped = true)
    {
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
    function link_to_action($action, $title = null, $params = [], $attributes = [], $escaped = true)
    {
        return html()->linkAction($action, $title, $params, $attributes, $escaped);
    }
}
