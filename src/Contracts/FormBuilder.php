<?php namespace Arcanedev\LaravelHtml\Contracts;

use Illuminate\Contracts\Session\Session;

/**
 * Interface  FormBuilder
 *
 * @package   Arcanedev\LaravelHtml\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface FormBuilder
{
    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the session store implementation.
     *
     * @return \Illuminate\Contracts\Session\Session
     */
    public function getSessionStore();

    /**
     * Set the session store implementation.
     *
     * @param  \Illuminate\Contracts\Session\Session  $session
     *
     * @return self
     */
    public function setSessionStore(Session $session);

    /**
     * Set the model instance on the form builder.
     *
     * @param  mixed  $model
     *
     * @return self
     */
    public function setModel($model);

    /**
     * Get the ID attribute for a field name.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return string
     */
    public function getIdAttribute($name, array $attributes);

    /**
     * Get the value that should be assigned to the field.
     *
     * @param  string  $name
     * @param  string  $value
     *
     * @return mixed
     */
    public function getValueAttribute($name, $value = null);

    /**
     * Determine if the old input is empty.
     *
     * @return bool
     */
    public function oldInputIsEmpty();

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Open up a new HTML form.
     *
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function open(array $options = []);

    /**
     * Create a new model based form builder.
     *
     * @param  mixed  $model
     * @param  array  $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function model($model, array $options = []);

    /**
     * Close the current form.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function close();

    /**
     * Generate a hidden field with the current CSRF token.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function token();

    /**
     * Create a form label element.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     * @param  bool    $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function label($name, $value = null, array $options = [], $escaped = true);

    /**
     * Create a form input field.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function input($type, $name, $value = null, array $options = []);

    /**
     * Create a text input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function text($name, $value = null, array $options = []);

    /**
     * Create a password input field.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function password($name, array $options = []);

    /**
     * Create a hidden input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function hidden($name, $value = null, array $options = []);

    /**
     * Create an e-mail input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function email($name, $value = null, array $options = []);

    /**
     * Create a tel input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function tel($name, $value = null, array $options = []);

    /**
     * Create a number input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function number($name, $value = null, array $options = []);

    /**
     * Create a date input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function date($name, $value = null, array $options = []);

    /**
     * Create a time input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function time($name, $value = null, array $options = []);

    /**
     * Create a url input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function url($name, $value = null, array $options = []);

    /**
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function file($name, array $options = []);

    /**
     * Create a textarea input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function textarea($name, $value = null, array $options = []);

    /**
     * Create a select box field.
     *
     * @param  string  $name
     * @param  array   $list
     * @param  string  $selected
     * @param  array   $attributes
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function select($name, $list = [], $selected = null, array $attributes = [], array $options = []);

    /**
     * Create a select range field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectRange($name, $begin, $end, $selected = null, array $options = []);

    /**
     * Create a select year field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectYear($name, $begin, $end, $selected = null, array $options = []);

    /**
     * Create a select month field.
     *
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $options
     * @param  string  $format
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectMonth($name, $selected = null, array $options = [], $format = '%B');

    /**
     * Create a checkbox input field.
     *
     * @param  string     $name
     * @param  mixed      $value
     * @param  bool|null  $checked
     * @param  array      $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function checkbox($name, $value = 1, $checked = null, array $options = []);

    /**
     * Create a radio button input field.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function radio($name, $value = null, $checked = null, array $options = []);

    /**
     * Create a HTML reset input element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function reset($value, array $attributes = []);

    /**
     * Create a HTML image input element.
     *
     * @param  string  $url
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function image($url, $name = null, array $attributes = []);

    /**
     * Create a submit button element.
     *
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function submit($value = null, array $options = []);

    /**
     * Create a button element.
     *
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function button($value = null, array $options = []);

    /**
     * Create a color input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function color($name, $value = null, array $options = []);

    /**
     * Get a value from the session's old input.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function old($name);
}
