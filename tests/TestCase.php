<?php namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\HtmlBuilder;
use Arcanedev\LaravelHtml\Tests\Concerns\AssertsHtmlStrings;
use Illuminate\Http\Request;
use Illuminate\Routing\Router;
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
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use AssertsHtmlStrings;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Illuminate\Routing\UrlGenerator */
    protected $urlGenerator;

    /** @var \Arcanedev\LaravelHtml\HtmlBuilder */
    protected $html;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->app->loadDeferredProviders();
        $router             = $this->registerRoutes();
        $this->urlGenerator = new UrlGenerator($router->getRoutes(), Request::create('/foo', 'GET'));
        $this->html         = new HtmlBuilder($this->urlGenerator);
    }

    public function tearDown(): void
    {
        unset($this->urlGenerator);
        unset($this->html);

        parent::tearDown();
    }

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
            \Arcanedev\LaravelHtml\HtmlServiceProvider::class,
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
            'Html'  => \Arcanedev\LaravelHtml\Facades\Html::class,
        ];
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application   $app
     */
    protected function getEnvironmentSetUp($app)
    {
        /** @var  \Illuminate\Contracts\Config\Repository  $config */
        $config = $app['config'];

        $viewPaths   = $config->get('view.paths');
        $viewPaths[] = __DIR__.'/fixtures/views';

        $config->set('view.paths', array_map('realpath', $viewPaths));
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Migrate the database.
     */
    protected function migrate()
    {
        $this->loadMigrationsFrom(__DIR__.'/fixtures/migrations');
    }

    /**
     * Register routes for tests.
     *
     * @return \Illuminate\Routing\Router
     */
    protected function registerRoutes()
    {
        /** @var Router $router */
        $router = $this->app['router'];

        $router->group([
            'namespace' => 'Arcanedev\LaravelHtml\Tests\Stubs',
        ], function (Router $router) {
            $router->get('/', [
                'as'    => 'home',
                'uses'  => 'DummyController@index'
            ]);
        });

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
                '<input type="'.$type.'" name="foo">',
                'foo',
                null,
                []
            ],[
                '<input type="'.$type.'" name="foo" value="'.$value.'">',
                'foo',
                $value,
                []
            ],[
                '<input type="'.$type.'" name="foo" class="form-control">',
                'foo',
                null,
                ['class' => 'form-control']
            ]
        ], $merge);
    }
}
