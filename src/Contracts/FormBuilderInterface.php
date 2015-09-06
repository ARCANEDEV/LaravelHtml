<?php namespace Arcanedev\LaravelHtml\Contracts;

use Illuminate\Session\Store as Session;

/**
 * Interface  FormBuilderInterface
 *
 * @package   Arcanedev\LaravelHtml\Contracts
 * @author    ARCANEDEV <arcanedev.maroc@gmail.com>
 */
interface FormBuilderInterface
{
    /* ------------------------------------------------------------------------------------------------
     |  Getters & Setters
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get the session store implementation.
     *
     * @return  Session  $session
     */
    public function getSessionStore();

    /**
     * Set the session store implementation.
     *
     * @param  Session  $session
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

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Open up a new HTML form.
     *
     * @param  array  $options
     *
     * @return string
     */
    public function open(array $options = []);

    /**
     * Create a new model based form builder.
     *
     * @param  mixed  $model
     * @param  array  $options
     *
     * @return string
     */
    public function model($model, array $options = []);

    /**
     * Close the current form.
     *
     * @return string
     */
    public function close();

    /**
     * Generate a hidden field with the current CSRF token.
     *
     * @return string
     */
    public function token();

    /**
     * Create a form label element.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function label($name, $value = null, $options = []);

    /**
     * Create a form input field.
     *
     * @param  string  $type
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function input($type, $name, $value = null, $options = []);

    /**
     * Create a text input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function text($name, $value = null, $options = []);

    /**
     * Create a password input field.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return string
     */
    public function password($name, $options = []);

    /**
     * Create a hidden input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function hidden($name, $value = null, $options = []);

    /**
     * Create an e-mail input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function email($name, $value = null, $options = []);

    /**
     * Create a tel input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function tel($name, $value = null, $options = []);

    /**
     * Create a number input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function number($name, $value = null, $options = []);

    /**
     * Create a date input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function date($name, $value = null, $options = []);

    /**
     * Create a time input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function time($name, $value = null, $options = []);

    /**
     * Create a url input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function url($name, $value = null, $options = []);

    /**
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return string
     */
    public function file($name, $options = []);

    /**
     * Create a textarea input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function textarea($name, $value = null, $options = []);

    /**
     * Create a select box field.
     *
     * @param  string  $name
     * @param  array   $list
     * @param  string  $selected
     * @param  array   $options
     *
     * @return string
     */
    public function select($name, $list = [], $selected = null, $options = []);

    /**
     * Create a select range field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $options
     *
     * @return string
     */
    public function selectRange($name, $begin, $end, $selected = null, $options = []);

    /**
     * Create a select year field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $options
     *
     * @return string
     */
    public function selectYear($name, $begin, $end, $selected = null, $options = []);

    /**
     * Create a select month field.
     *
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $options
     * @param  string  $format
     *
     * @return string
     */
    public function selectMonth($name, $selected = null, $options = [], $format = '%B');

    /**
     * Get the select option for the given value.
     *
     * @param  string  $display
     * @param  string  $value
     * @param  string  $selected
     *
     * @return string
     */
    public function getSelectOption($display, $value, $selected);

    /**
     * Create a checkbox input field.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $options
     *
     * @return string
     */
    public function checkbox($name, $value = 1, $checked = null, $options = []);

    /**
     * Create a radio button input field.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $options
     *
     * @return string
     */
    public function radio($name, $value = null, $checked = null, $options = []);

    /**
     * Create a HTML reset input element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return string
     */
    public function reset($value, $attributes = []);

    /**
     * Create a HTML image input element.
     *
     * @param  string  $url
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return string
     */
    public function image($url, $name = null, $attributes = []);

    /**
     * Create a submit button element.
     *
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function submit($value = null, $options = []);

    /**
     * Create a button element.
     *
     * @param  string  $value
     * @param  array   $options
     *
     * @return string
     */
    public function button($value = null, $options = []);

    /**
     * Get the ID attribute for a field name.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return string
     */
    public function getIdAttribute($name, $attributes);

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
     * Get a value from the session's old input.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function old($name);

    /**
     * Determine if the old input is empty.
     *
     * @return bool
     */
    public function oldInputIsEmpty();
}
