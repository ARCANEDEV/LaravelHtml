<?php namespace Arcanedev\LaravelHtml\Tests\Stubs;

/**
 * Class     FormBuilderModelStub
 *
 * @package  Arcanedev\LaravelHtml\Tests\Stubs
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormBuilderModelStub
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    protected $data;

    /* ------------------------------------------------------------------------------------------------
     |  Constructor
     | ------------------------------------------------------------------------------------------------
     */
    public function __construct(array $data = [])
    {
        foreach ($data as $key => $val) {
            if (is_array($val)) {
                $val = new self($val);
            }

            $this->data[$key] = $val;
        }
    }

    /* ------------------------------------------------------------------------------------------------
     |  Special Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @param  string $key
     *
     * @return mixed
     */
    public function __get($key)
    {
        return $this->data[$key];
    }

    /**
     * @param  string $key
     *
     * @return bool
     */
    public function __isset($key)
    {
        return isset($this->data[$key]);
    }
}
