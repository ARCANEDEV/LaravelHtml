<?php namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\HtmlBuilder;
use Illuminate\Http\Request;
use Illuminate\Routing\RouteCollection;
use Illuminate\Routing\UrlGenerator;
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
 * @package  Arcanedev\LaravelHtml\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
abstract class TestCase extends BaseTestCase
{
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
    public function setUp()
    {
        parent::setUp();

        $router             = $this->registerRoutes();
        $this->urlGenerator = new UrlGenerator($router->getRoutes(), Request::create('/foo', 'GET'));
        $this->htmlBuilder  = new HtmlBuilder($this->urlGenerator);
    }

    public function tearDown()
    {
        parent::tearDown();

        unset($this->urlGenerator);
        unset($this->htmlBuilder);
    }

    /* ------------------------------------------------------------------------------------------------
     |  Laravel Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Get package providers.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageProviders($app)
    {
        return [
            \Arcanedev\LaravelHtml\HtmlServiceProvider::class
        ];
    }

    /**
     * Get package aliases.
     *
     * @param  \Illuminate\Foundation\Application  $app
     *
     * @return array
     */
    protected function getPackageAliases($app)
    {
        return [
            'Form'  => \Arcanedev\LaravelHtml\Facades\Form::class,
            'Html'  => \Arcanedev\LaravelHtml\Facades\Html::class
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register routes for tests.
     *
     * @return \Illuminate\Routing\Router
     */
    protected function registerRoutes()
    {
        /** @var \Illuminate\Routing\Router $router */
        $router = $this->app['router'];

        $router->get('/', [
            'as'    => 'home',
            'uses'  => 'Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index'
        ]);

        return $router;
    }
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
     * Get input data.
     *
     * @param  string  $type
     * @param  mixed   $value
     * @param  array   $merge
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
