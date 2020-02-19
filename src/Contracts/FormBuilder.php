<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Contracts;

use Arcanedev\Html\Elements\{Button, File, Input, Label, Select, Textarea};
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\HtmlString;

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
     * @return \Illuminate\Contracts\Session\Session|null
     */
    public function getSessionStore(): ?Session;

    /**
     * Set the session store implementation.
     *
     * @param  \Illuminate\Contracts\Session\Session  $session
     *
     * @return $this
     */
    public function setSessionStore(Session $session);

    /**
     * Set the model instance on the form builder.
     *
     * @param  \Illuminate\Database\Eloquent\Model|mixed  $model
     *
     * @return $this
     */
    public function setModel($model);

    /**
     * Get the model instance on the form builder.
     *
     * @return \Illuminate\Database\Eloquent\Model|mixed|null
     */
    public function getModel();

    /**
     * Get the ID attribute for a field name.
     *
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return string|null
     */
    public function getIdAttribute($name, array $attributes): ?string;

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
    public function open(array $attributes = []): HtmlString;

    /**
     * Create a new model based form builder.
     *
     * @param  mixed  $model
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function model($model, array $attributes = []): HtmlString;

    /**
     * Close the current form.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function close(): HtmlString;

    /**
     * Generate a hidden field with the current CSRF token.
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function token(): Input;

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
    public function label(string $name, $value = null, array $attributes = [], $escaped = true): Label;

    /**
     * Create a form input field.
     *
     * @param  string        $type
     * @param  string|null   $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function input(string $type, string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a text input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function text(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a password input field.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function password(string $name, array $attributes = []): Input;

    /**
     * Create a hidden input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function hidden(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create an e-mail input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function email(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a tel input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function tel(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a number input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function number(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a date input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function date(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a time input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function time(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a url input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function url(string $name, $value = null, array $attributes = []): Input;

    /**
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\File
     */
    public function file(string $name, array $attributes = []): File;

    /**
     * Create a textarea input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Textarea
     */
    public function textarea(string $name, $value = null, array $attributes = []): Textarea;

    /**
     * Create a select box field.
     *
     * @param  string                                         $name
     * @param  array|\Illuminate\Support\Collection|iterable  $list
     * @param  string|bool                                    $selected
     * @param  array                                          $attributes
     * @param  array                                          $optionsAttributes
     * @param  array                                          $optgroupsAttributes
     *
     * @return \Arcanedev\Html\Elements\Select
     */
    public function select(
        string $name, iterable $list = [], $selected = null,
        array $attributes = [], array $optionsAttributes = [], array $optgroupsAttributes = []
    ): Select;

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
    public function selectRange(string $name, $begin, $end, $selected = null, array $attributes = []): Select;

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
    public function selectYear(string $name, $begin, $end, $selected = null, array $attributes = []): Select;

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
    public function selectMonth(string $name, $selected = null, array $attributes = [], $format = '%B'): Select;

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
    public function checkbox(string $name, $value = 1, $checked = null, array $attributes = []): Input;

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
    public function radio(string $name, $value = null, $checked = null, array $attributes = []): Input;

    /**
     * Create a HTML reset input element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Button
     */
    public function reset($value, array $attributes = []): Button;

    /**
     * Create a HTML image input element.
     *
     * @param  string       $url
     * @param  string|null  $name
     * @param  array        $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function image(string $url, $name = null, array $attributes = []): Input;

    /**
     * Create a submit button element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Button
     */
    public function submit($value = null, array $attributes = []): Button;

    /**
     * Create a button element.
     *
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Button
     */
    public function button($value = null, array $attributes = []): Button;

    /**
     * Create a color input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Arcanedev\Html\Elements\Input
     */
    public function color(string $name, $value = null, array $attributes = []): Input;

    /**
     * Get a value from the session's old input.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function old(string $name);
}
