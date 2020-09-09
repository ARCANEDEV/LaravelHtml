<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\Contracts\FormBuilder as FormBuilderContract;
use Arcanedev\LaravelHtml\FormBuilder;
use Arcanedev\LaravelHtml\HtmlBuilder;
use Arcanedev\LaravelHtml\Tests\Concerns\AssertsHtmlStrings;
use Illuminate\Http\Request;
use Illuminate\Routing\{Router, UrlGenerator};
use Orchestra\Testbench\TestCase as BaseTestCase;

/**
 * Class     TestCase
 *
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
    protected function getPackageProviders($app): array
    {
        return [
            \Arcanedev\LaravelHtml\HtmlServiceProvider::class,
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
    protected function migrate(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/fixtures/migrations');
    }

    /**
     * Get the form builder.
     *
     * @return \Arcanedev\LaravelHtml\Contracts\FormBuilder
     */
    protected function getFormBuilder(): FormBuilderContract
    {
        return new FormBuilder(
            $this->html,
            $this->urlGenerator,
            tap($this->app['session.store'], function ($session) {
                /** @var  \Illuminate\Contracts\Session\Session   $session */
                $session->put('_token', 'abc');
            })
        );
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
    protected function getInputData(string $type, $value = 'bar', array $merge = [])
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
