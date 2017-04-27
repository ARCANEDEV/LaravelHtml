<?php namespace Arcanedev\LaravelHtml;

use Arcanedev\Support\ServiceProvider;

/**
 * Class     HtmlServiceProvider
 *
 * @package  Arcanedev\LaravelHtml
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlServiceProvider extends ServiceProvider
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register()
    {
        $this->registerHtmlBuilder();
        $this->registerFormBuilder();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [
            'html', HtmlBuilder::class, Contracts\HtmlBuilder::class,
            'form', FormBuilder::class, Contracts\FormBuilder::class,
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the HTML builder.
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton(Contracts\HtmlBuilder::class, HtmlBuilder::class);
        $this->app->singleton('html', Contracts\HtmlBuilder::class);
    }

    /**
     * Register the form builder.
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton(Contracts\FormBuilder::class, FormBuilder::class);
        $this->app->singleton('form', Contracts\FormBuilder::class);
    }
}
