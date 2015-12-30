<?php namespace Arcanedev\LaravelHtml\Traits;

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
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * A cached ReflectionClass instance for $this.
     *
     * @var ReflectionClass
     */
    protected $reflection;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get form value from the eloquent model.
     *
     * @param string $key
     *
     * @return mixed
     */
    public function getFormValue($key)
    {
        $value = $this->getAttributeFromArray($key);

        if (in_array($key, $this->getDates()) && ! is_null($value)) {
            $value = $this->asDateTime($value);
        }

        if ($this->hasFormMutator($key)) {
            $value = $this->mutateFormAttribute($key, $value);
        }

        return $value;
    }

    /* ------------------------------------------------------------------------------------------------
     |  Eloquent Functions
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
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
        $methods = $this->getReflection()->getMethods(
            ReflectionMethod::IS_PUBLIC
        );

        $mutator = collect($methods)
            ->first(function ($index, ReflectionMethod $method) use ($key) {
                return $method->name === $this->getMutateFromMethodName($key);
            });

        return (bool) $mutator;
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
        return 'form' . str_studly($key) . 'Attribute';
    }

    /**
     * Get a ReflectionClass Instance.
     *
     * @return ReflectionClass
     */
    protected function getReflection()
    {
        if ( ! $this->reflection) {
            $this->reflection = new ReflectionClass($this);
        }

        return $this->reflection;
    }
}
