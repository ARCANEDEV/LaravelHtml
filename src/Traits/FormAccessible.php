<?php namespace Arcanedev\LaravelHtml\Traits;

use Illuminate\Support\Str;
use ReflectionClass;
use ReflectionMethod;

/**
 * Class     FormAccessible
 *
 * @package  Arcanedev\LaravelHtml\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
trait FormAccessible
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * A cached ReflectionClass instance for $this.
     *
     * @var \ReflectionClass
     */
    protected $reflection;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get form value from the eloquent model.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    public function getFormValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        if (in_array($key, $this->getDates()) && ! is_null($value))
            $value = $this->asDateTime($value);

        return $this->hasFormMutator($key)
            ? $this->mutateFormAttribute($key, $value)
            : data_get($this, $key); // No form mutator, let the model resolve this
    }

    /* -----------------------------------------------------------------
     |  Eloquent Methods
     | -----------------------------------------------------------------
     */

    /**
     * Get an attribute from the $attributes array.
     *
     * @param  string  $key
     *
     * @return mixed
     */
    abstract protected function getAttributeFromArray($key);

    /**
     * Get the attributes that should be converted to dates.
     *
     * @return array
     */
    abstract public function getDates();

    /**
     * Return a timestamp as DateTime object.
     *
     * @param  mixed  $value
     *
     * @return \Carbon\Carbon
     */
    abstract protected function asDateTime($value);

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Check if has a form mutator.
     *
     * @param  string  $key
     *
     * @return bool
     */
    protected function hasFormMutator($key)
    {
        $methods  = $this->getReflection()->getMethods(ReflectionMethod::IS_PUBLIC);

        return collect($methods)->filter(function (ReflectionMethod $method) use ($key) {
            return $method->name === $this->getMutateFromMethodName($key);
        })->isNotEmpty();
    }

    /**
     * Mutate the form attribute.
     *
     * @param  string  $key
     * @param  mixed   $value
     *
     * @return mixed
     */
    private function mutateFormAttribute($key, $value)
    {
        return $this->{$this->getMutateFromMethodName($key)}($value);
    }

    /**
     * Get the mutate form method name.
     *
     * @param  string  $key
     *
     * @return string
     */
    private function getMutateFromMethodName($key)
    {
        return 'form'.Str::studly($key).'Attribute';
    }

    /**
     * Get a ReflectionClass Instance.
     *
     * @return \ReflectionClass
     *
     * @throws \ReflectionException
     */
    protected function getReflection()
    {
        if (is_null($this->reflection)) {
            $this->reflection = new ReflectionClass($this);
        }

        return $this->reflection;
    }
}
