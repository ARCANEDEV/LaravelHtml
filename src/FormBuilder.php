<?php namespace Arcanedev\LaravelHtml;

use Arcanedev\Html\Elements;
use Arcanedev\LaravelHtml\Bases\Builder;
use Arcanedev\LaravelHtml\Contracts\FormBuilder as FormBuilderContract;
use DateTime;
use Illuminate\Contracts\Routing\UrlGenerator;
use Illuminate\Contracts\Session\Session;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

/**
 * Class     FormBuilder
 *
 * @package  Arcanedev\LaravelHtml
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormBuilder extends Builder implements FormBuilderContract
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
    * The HTML builder instance.
    *
    * @var \Arcanedev\LaravelHtml\Contracts\HtmlBuilder
    */
    protected $html;

    /**
    * The URL generator instance.
    *
    * @var \Illuminate\Contracts\Routing\UrlGenerator
    */
    protected $url;

    /**
    * The CSRF token used by the form builder.
    *
    * @var string
    */
    protected $csrfToken;

    /**
    * The session store implementation.
    *
    * @var \Illuminate\Contracts\Session\Session|\Illuminate\Session\Store
    */
    protected $session;

    /**
    * The current model instance for the form.
    *
    * @var \Illuminate\Database\Eloquent\Model|null
    */
    protected $model;

    /**
    * An array of label names we've created.
    *
    * @var array
    */
    protected $labels = [];

    /**
    * The reserved form open attributes.
    *
    * @var array
    */
    protected $reserved = ['method', 'url', 'route', 'action', 'files'];

    /**
    * The form methods that should be spoofed, in uppercase.
    *
    * @var array
    */
    protected $spoofedMethods = ['DELETE', 'PATCH', 'PUT'];

    /**
    * The types of inputs to not fill values on by default.
    *
    * @var array
    */
    protected $skipValueTypes = ['file', 'password', 'checkbox', 'radio'];

    /* -----------------------------------------------------------------
     |  Constructor
     | -----------------------------------------------------------------
     */

    /**
    * Create a new form builder instance.
    *
    * @param  \Arcanedev\LaravelHtml\Contracts\HtmlBuilder  $html
    * @param  \Illuminate\Contracts\Routing\UrlGenerator    $url
    * @param  \Illuminate\Contracts\Session\Session         $session
    */
    public function __construct(Contracts\HtmlBuilder $html, UrlGenerator $url, Session $session)
    {
        $this->url       = $url;
        $this->html      = $html;
        $this->csrfToken = $session->token();

        $this->setSessionStore($session);
    }

    /* -----------------------------------------------------------------
     |  Getters & Setters
     | -----------------------------------------------------------------
     */

    /**
     * Get the session store implementation.
     *
     * @return  \Illuminate\Contracts\Session\Session
     */
    public function getSessionStore()
    {
        return $this->session;
    }

    /**
     * Set the session store implementation.
     *
     * @param  \Illuminate\Contracts\Session\Session  $session
     *
     * @return self
     */
    public function setSessionStore(Session $session)
    {
        $this->session = $session;

        return $this;
    }

    /**
     * Set the model instance on the form builder.
     *
     * @param  \Illuminate\Database\Eloquent\Model|null  $model
     *
     * @return self
     */
    public function setModel($model)
    {
        $this->model = $model;

        return $this;
    }

    /**
     * Get the model instance on the form builder.
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Get the ID attribute for a field name.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return string
     */
    public function getIdAttribute($name, array $attributes)
    {
        if (array_key_exists('id', $attributes))
            return $attributes['id'];

        if (in_array($name, $this->labels))
            return $name;

        return null;
    }

    /**
     * Get the value that should be assigned to the field.
     *
     * @param  string  $name
     * @param  mixed   $value
     *
     * @return mixed
     */
    public function getValueAttribute($name, $value = null)
    {
        if (is_null($name))
            return $value;

        if ( ! is_null($this->old($name)) && $name !== '_method')
            return $this->old($name);

        if ( ! is_null($value))
            return $value;

        return $this->getModelValueAttribute($name);
    }

    /**
     * Get the model value that should be assigned to the field.
     *
     * @param  string                               $name
     * @param  \Illuminate\Database\Eloquent\Model  $model
     *
     * @return mixed
     */
    private function getModelValueAttribute($name, $model = null)
    {
        $model = $model ?: $this->getModel();

        $key = $this->transformKey($name);

        if (strpos($key, '.') !== false) {
            $keys = explode('.', $key, 2);

            return $this->getModelValueAttribute(
                $keys[1],
                $this->getModelValueAttribute($keys[0], $model)
            );
        }

        return method_exists($model, 'getFormValue')
            ? $model->getFormValue($key)
            : data_get($model, $key);
    }

    /**
     * Get a value from the session's old input.
     *
     * @param  string  $name
     *
     * @return mixed
     */
    public function old($name)
    {
        return ! is_null($this->session)
            ? $this->session->getOldInput($this->transformKey($name))
            : null;
    }

    /**
     * Transform key from array to dot syntax.
     *
     * @param  string  $key
     *
     * @return string
     */
    private function transformKey($key)
    {
        return str_replace(
            ['.', '[]', '[', ']'],
            ['_', '', '.', ''],
            $key
        );
    }

    /**
     * Determine if the old input is empty.
     *
     * @return bool
     */
    public function oldInputIsEmpty()
    {
        return ! is_null($this->session)
            && (count($this->session->getOldInput()) === 0);
    }

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
    public function open(array $attributes = [])
    {
        $method = strtoupper(Arr::pull($attributes, 'method', 'POST'));

        $form = Elements\Form::make()
            ->method($method !== 'GET' ? 'POST' : $method)
            ->action($this->getAction($attributes))
            ->attributes(array_merge(
                ['accept-charset' => 'UTF-8'],
                Arr::except($attributes, $this->reserved)
            ));

        if (Arr::pull($attributes, 'files', false))
            $form = $form->acceptsFiles();

        if (in_array($method, $this->spoofedMethods))
            $form = $form->addChild($this->hidden('_method', $method));

        if ($method !== 'GET')
            $form = $form->addChild($this->token());

        return $form->open();
    }

    /**
     * Create a new model based form builder.
     *
     * @param  mixed  $model
     * @param  array  $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function model($model, array $attributes = [])
    {
        return $this->setModel($model)
                    ->open($attributes);
    }

    /**
     * Close the current form.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function close()
    {
        $this->labels = [];
        $this->setModel(null);

        return Elements\Form::make()->close();
    }

    /**
     * Generate a hidden field with the current CSRF token.
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function token()
    {
        return $this->hidden(
            '_token',
            empty($this->csrfToken) ? $this->session->token() : $this->csrfToken
        );
    }

    /**
     * Create a form label element.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     * @param  bool          $escaped
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function label($name, $value = null, array $attributes = [], $escaped = true)
    {
        $this->labels[] = $name;

        $value = $value ?: Str::title(str_replace(['_', '-'], ' ', $name));

        return Elements\Label::make()
            ->for($name)
            ->attributes($attributes)
            ->html($escaped ? e($value) : $value)
            ->render();
    }

    /**
     * Create a form input field.
     *
     * @param  string        $type
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function input($type, $name, $value = null, array $attributes = [])
    {
        $id = $this->getIdAttribute($name, $attributes);

        if ( ! in_array($type, $this->skipValueTypes))
            $value = $this->getValueAttribute($name, $value);

        return Elements\Input::make()
            ->type($type)
            ->attributeUnless(is_null($name), 'name', $name)
            ->attributeUnless(is_null($id), 'id', $id)
            ->attributeUnless(is_null($value) || empty($value), 'value', $value)
            ->attributes($attributes)
            ->render();
    }

    /**
     * Create a text input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function text($name, $value = null, array $attributes = [])
    {
        return $this->input('text', $name, $value, $attributes);
    }

    /**
     * Create a password input field.
     *
     * @param  string  $name
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function password($name, array $attributes = [])
    {
        return $this->input('password', $name, null, $attributes);
    }

    /**
     * Create a hidden input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function hidden($name, $value = null, array $attributes = [])
    {
        return $this->input('hidden', $name, $value, $attributes);
    }

    /**
     * Create an e-mail input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function email($name, $value = null, array $attributes = [])
    {
        return $this->input('email', $name, $value, $attributes);
    }

    /**
     * Create a tel input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function tel($name, $value = null, array $attributes = [])
    {
        return $this->input('tel', $name, $value, $attributes);
    }

    /**
     * Create a number input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function number($name, $value = null, array $attributes = [])
    {
        return $this->input('number', $name, $value, $attributes);
    }

    /**
     * Create a date input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function date($name, $value = null, array $attributes = [])
    {
        if ($value instanceof DateTime)
            $value = $value->format('Y-m-d');

        return $this->input('date', $name, $value, $attributes);
    }

    /**
     * Create a datetime input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function datetime($name, $value = null, array $attributes = [])
    {
        if ($value instanceof DateTime)
            $value = $value->format(DateTime::RFC3339);

        return $this->input('datetime', $name, $value, $attributes);
    }

    /**
     * Create a datetime-local input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function datetimeLocal($name, $value = null, array $attributes = [])
    {
        if ($value instanceof DateTime)
            $value = $value->format('Y-m-d\TH:i');

        return $this->input('datetime-local', $name, $value, $attributes);
    }

    /**
     * Create a time input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function time($name, $value = null, array $options = [])
    {
        return $this->input('time', $name, $value, $options);
    }

    /**
     * Create a url input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function url($name, $value = null, array $options = [])
    {
        return $this->input('url', $name, $value, $options);
    }

    /**
     * Create a file input field.
     *
     * @param  string  $name
     * @param  array   $options
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function file($name, array $options = [])
    {
        return $this->input('file', $name, null, $options);
    }

    /**
     * Create a textarea input field.
     *
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function textarea($name, $value = null, array $attributes = [])
    {
        // Next we will look for the rows and cols attributes, as each of these are put
        // on the textarea element definition. If they are not present, we will just
        // assume some sane default values for these attributes for the developer.
        $attributes = $this->setTextAreaSize($attributes);
        $id      = $this->getIdAttribute($name, $attributes);
        $value   = (string) $this->getValueAttribute($name, $value);

        unset($attributes['size']);

        return Elements\Textarea::make()
            ->name($name)
            ->attributeUnless(is_null($id), 'id', $id)
            ->attributes($attributes)
            ->html($this->html->escape($value))
            ->render();
    }

    /**
     * Set the text area size on the attributes.
     *
     * @param  array  $attributes
     *
     * @return array
     */
    private function setTextAreaSize(array $attributes)
    {
        if (isset($attributes['size']))
            return $this->setQuickTextAreaSize($attributes);

        // If the "size" attribute was not specified, we will just look for the regular
        // columns and rows attributes, using sane defaults if these do not exist on
        // the attributes array. We'll then return this entire options array back.
        $cols = Arr::get($attributes, 'cols', 50);
        $rows = Arr::get($attributes, 'rows', 10);

        return array_merge($attributes, compact('cols', 'rows'));
    }

    /**
     * Set the text area size using the quick "size" attribute.
     *
     * @param  array  $attributes
     *
     * @return array
     */
    protected function setQuickTextAreaSize(array $attributes)
    {
        list($cols, $rows) = explode('x', $attributes['size']);

        return array_merge($attributes, compact('cols', 'rows'));
    }

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
     * @return \Illuminate\Support\HtmlString
     */
    public function select(
        $name,
        $list = [],
        $selected = null,
        array $attributes = [],
        array $optionsAttributes = [],
        array $optgroupsAttributes = []
    ) {

        $select = Elements\Select::make()->name($name);

        // When building a select box the "value" attribute is really the selected one
        // so we will use that when checking the model or session for a value which
        // should provide a convenient method of re-populating the forms on post.
        $selected = $this->getValueAttribute($name, $selected);
        $id       = $this->getIdAttribute($name, $attributes);

        return $select
            ->options($list, $optionsAttributes, $optgroupsAttributes)
            ->attributes($attributes)
            ->attributeUnless(is_null($id), 'id', $id)
            ->value($selected)
            ->render();
    }

    /**
     * Create a select range field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectRange($name, $begin, $end, $selected = null, array $attributes = [])
    {
        $range = array_combine($range = range($begin, $end), $range);

        return $this->select($name, $range, $selected, $attributes);
    }

    /**
     * Create a select year field.
     *
     * @param  string  $name
     * @param  string  $begin
     * @param  string  $end
     * @param  string  $selected
     * @param  array   $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectYear($name, $begin, $end, $selected = null, array $attributes = [])
    {
        return $this->selectRange($name, $begin, $end, $selected, $attributes);
    }

    /**
     * Create a select month field.
     *
     * @param  string  $name
     * @param  string  $selected
     * @param  array   $attributes
     * @param  string  $format
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function selectMonth($name, $selected = null, array $attributes = [], $format = '%B')
    {
        $months = [];

        foreach(range(1, 12) as $month) {
            $months[$month] = strftime($format, mktime(0, 0, 0, $month, 1));
        }

        return $this->select($name, $months, $selected, $attributes);
    }

    /**
     * Create a checkbox input field.
     *
     * @param  string     $name
     * @param  mixed      $value
     * @param  bool|null  $checked
     * @param  array      $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function checkbox($name, $value = 1, $checked = null, array $attributes = [])
    {
        return $this->checkable('checkbox', $name, $value, $checked, $attributes);
    }

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
    public function radio($name, $value = null, $checked = null, array $options = [])
    {
        return $this->checkable('radio', $name, $value ?: $name, $checked, $options);
    }

    /**
     * Create a HTML reset input element.
     *
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function reset($value, array $attributes = [])
    {
        return $this->input('reset', null, $value, $attributes);
    }

    /**
    * Create a HTML image input element.
    *
    * @param  string       $url
    * @param  string|null  $name
    * @param  array        $attributes
    *
     * @return \Illuminate\Support\HtmlString
    */
    public function image($url, $name = null, array $attributes = [])
    {
        return $this->input('image', $name, null, array_merge($attributes, [
            'src' => $this->url->asset($url),
        ]));
    }

    /**
     * Create a submit button element.
     *
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function submit($value = null, array $attributes = [])
    {
        return $this->input('submit', null, $value, $attributes);
    }

    /**
     * Create a button element.
     *
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function button($value = null, array $attributes = [])
    {
        return Elements\Button::make()
            ->type(Arr::pull($attributes, 'type', 'button'))
            ->attributes($attributes)
            ->html($value)
            ->render();
    }

    /**
     * Create a color input field.
     *
     * @param  string        $name
     * @param  string|mixed  $value
     * @param  array         $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    public function color($name, $value = null, array $attributes = [])
    {
        return $this->input('color', $name, $value, $attributes);
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Create a checkable input field.
     *
     * @param  string     $type
     * @param  string     $name
     * @param  mixed      $value
     * @param  bool|null  $checked
     * @param  array      $attributes
     *
     * @return \Illuminate\Support\HtmlString
     */
    protected function checkable($type, $name, $value, $checked, array $attributes)
    {
        $checked = $this->getCheckedState($type, $name, $value, $checked);

        if ( ! is_null($checked) && $checked)
            $attributes['checked'] = 'checked';

        return $this->input($type, $name, $value, $attributes);
    }

    /**
     * Get the check state for a checkable input.
     *
     * @param  string     $type
     * @param  string     $name
     * @param  mixed      $value
     * @param  bool|null  $checked
     *
     * @return bool
     */
    private function getCheckedState($type, $name, $value, $checked)
    {
        switch($type) {
            case 'checkbox':
                return $this->getCheckboxCheckedState($name, $value, $checked);

            case 'radio':
                return $this->getRadioCheckedState($name, $value, $checked);

            default:
                return $this->getValueAttribute($name) === $value;
        }
    }

    /**
     * Get the check state for a checkbox input.
     *
     * @param  string     $name
     * @param  mixed      $value
     * @param  bool|null  $checked
     *
     * @return bool
     */
    private function getCheckboxCheckedState($name, $value, $checked)
    {
        if (
            isset($this->session) &&
            ! $this->oldInputIsEmpty() &&
            is_null($this->old($name))
        )
            return false;

        if ($this->missingOldAndModel($name))
            return $checked;

        $posted = $this->getValueAttribute($name, $checked);

        if (is_array($posted))
            return in_array($value, $posted);

        if ($posted instanceof Collection)
            return $posted->contains('id', $value);

        return (bool) $posted;
    }

    /**
     * Get the check state for a radio input.
     *
     * @param  string     $name
     * @param  mixed      $value
     * @param  bool|null  $checked
     *
     * @return bool
     */
    private function getRadioCheckedState($name, $value, $checked)
    {
        return $this->missingOldAndModel($name)
            ? $checked
            : $this->getValueAttribute($name) === $value;
    }

    /**
     * Determine if old input or model input exists for a key.
     *
     * @param  string  $name
     *
     * @return bool
     */
    private function missingOldAndModel($name)
    {
        return is_null($this->old($name))
            && is_null($this->getModelValueAttribute($name));
    }

    /**
     * Get the form action from the options.
     *
     * @param  array  $attributes
     *
     * @return string
     */
    private function getAction(array $attributes)
    {
        if (isset($attributes['url']))
            return $this->getUrlAction($attributes['url']);

        if (isset($attributes['route']))
            return $this->getRouteAction($attributes['route']);

        if (isset($attributes['action']))
            return $this->getControllerAction($attributes['action']);

        return $this->url->current();
    }

    /**
     * Get the action for a "url" option.
     *
     * @param  array|string  $attribute
     *
     * @return string
     */
    private function getUrlAction($attribute)
    {
        return is_array($attribute)
            ? $this->url->to($attribute[0], array_slice($attribute, 1))
            : $this->url->to($attribute);
    }

    /**
     * Get the action for a "route" option.
     *
     * @param  array|string  $attribute
     *
     * @return string
     */
    private function getRouteAction($attribute)
    {
        return is_array($attribute)
            ? $this->url->route($attribute[0], array_slice($attribute, 1))
            : $this->url->route($attribute);
    }

    /**
     * Get the action for an "action" option.
     *
     * @param  array|string  $attribute
     *
     * @return string
     */
    private function getControllerAction($attribute)
    {
        return is_array($attribute)
            ? $this->url->action($attribute[0], array_slice($attribute, 1))
            : $this->url->action($attribute);
    }
}
