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
     * Get the model instance on the form builder.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel();

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
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function open(array $attributes = []);

    /**
     * Create a new model based form builder.
     *
     * @param  mixed  $model
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function model($model, array $attributes = []);

    /**
     * Close the current form.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function close();

    /**
     * Generate a hidden field with the current CSRF token.
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function token();

    /**
     * Create a form label element.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     * @param  bool    $escaped
     *
     * @return \Arcanedev\Html\Elements\Label
     */
    public function label($name, $value = null, array $attributes = [], $escaped = true);

    /**
     * Create a form input field.
     *
     * @param  string        $type
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function input($type, $name, $value = null, array $attributes = []);

    /**
     * Create a text input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function text($name, $value = null, array $attributes = []);

    /**
     * Create a password input field.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function password($name, array $attributes = []);

    /**
     * Create a hidden input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function hidden($name, $value = null, array $attributes = []);

    /**
     * Create an e-mail input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function email($name, $value = null, array $attributes = []);

    /**
     * Create a tel input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function tel($name, $value = null, array $attributes = []);

    /**
     * Create a number input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function number($name, $value = null, array $attributes = []);

    /**
     * Create a date input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function date($name, $value = null, array $attributes = []);

    /**
     * Create a time input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function time($name, $value = null, array $attributes = []);

    /**
     * Create a url input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function url($name, $value = null, array $attributes = []);

    /**
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\File
     */
    public function file($name, array $attributes = []);

    /**
     * Create a textarea input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Textarea
     */
    public function textarea($name, $value = null, array $attributes = []);

    /**
     * Create a select box field.
     *
     * @param  string                                $name
     * @param  array|\Illuminate\Support\Collection  $list
     * @param  string|bool                           $selected
     * @param  array                                 $attributes
     * @param  array                                 $optionsAttributes
     * @param  array                                 $optgroupsAttributes
     *
     * @return \Arcanedev\Html\Elements\Select
     */
    public function select(
        $name, $list = [], $selected = null,
        array $attributes = [], array $optionsAttributes = [], array $optgroupsAttributes = []
    );

    /**
     * Create a select range field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Select
     */
    public function selectRange($name, $begin, $end, $selected = null, array $attributes = []);

    /**
     * Create a select year field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Select
     */
    public function selectYear($name, $begin, $end, $selected = null, array $attributes = []);

    /**
     * Create a select month field.
     *
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $attributes
     * @param  string  $format
     *
     * @return \Arcanedev\Html\Elements\Select
     */
    public function selectMonth($name, $selected = null, array $attributes = [], $format = '%B');

    /**
     * Create a checkbox input field.
     *
     * @param  string     $name
     * @param  mixed      $value
     * @param  bool|null  $checked
     * @param  array      $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function checkbox($name, $value = 1, $checked = null, array $attributes = []);

    /**
     * Create a radio button input field.
     *
     * @param  string  $name
     * @param  mixed   $value
     * @param  bool    $checked
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function radio($name, $value = null, $checked = null, array $attributes = []);

    /**
     * Create a HTML reset input element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Button
     */
    public function reset($value, array $attributes = []);

    /**
     * Create a HTML image input element.
     *
     * @param  string       $url
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function image($url, $name = null, array $attributes = []);

    /**
     * Create a submit button element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Button
     */
    public function submit($value = null, array $attributes = []);

    /**
     * Create a button element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Button
     */
    public function button($value = null, array $attributes = []);

    /**
     * Create a color input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function color($name, $value = null, array $attributes = []);

    /**
     * Get a value from the session's old input.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function old($name);
}
