<?php namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\Builders\HtmlBuilder;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use PHPUnit_Framework_TestCase;

/**
 * Class TestCase
 * @package Arcanedev\LaravelHtml\Tests
 */
abstract class TestCase extends PHPUnit_Framework_TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Constants
     | ------------------------------------------------------------------------------------------------
     */
    const BASE_URL = 'http://localhost.com';

    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var UrlGenerator
     */
    protected $urlGenerator;

    /**
     * @var HtmlBuilder
     */
    protected $htmlBuilder;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    protected function setUp()
    {
        parent::setUp();

        $this->urlGenerator = new UrlGenerator(new RouteCollection, Request::create('/foo', 'GET'));
        $this->htmlBuilder  = new HtmlBuilder($this->urlGenerator);

        $this->urlGenerator->forceRootUrl(self::BASE_URL);
    }

    protected function tearDown()
    {
        parent::tearDown();

        unset($this->urlGenerator);
        unset($this->htmlBuilder);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Generate a absolute URL to the given path.
     *
     * @param  string     $path
     * @param  mixed      $extra
     * @param  bool|null  $secure
     *
     * @return string
     */
    protected function urlTo($path, $extra = [], $secure = null)
    {
        return $this->urlGenerator->to($path, $extra, $secure);
    }

    /**
     * @param  string $type
     * @param  mixed $value
     * @param  array $merge
     *
     * @return array
     */
    protected function getInputData($type, $value = 'bar', array $merge = [])
    {
        return array_merge([
            [
                '<input name="foo" type="' . $type . '">',
                'foo',
                null,
                []
            ],[
                '<input name="foo" type="' . $type . '" value="' . $value . '">',
                'foo',
                $value,
                []
            ],[
                '<input class="form-control" name="foo" type="' . $type . '">',
                'foo',
                null,
                ['class' => 'form-control']
            ]
        ], $merge);
    }
}
