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
        parent::register();

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
            HtmlBuilder::class,
            Contracts\HtmlBuilder::class,
            FormBuilder::class,
            Contracts\FormBuilder::class,
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
        $this->singleton(Contracts\HtmlBuilder::class, HtmlBuilder::class);
    }

    /**
     * Register the form builder.
     */
    protected function registerFormBuilder()
    {
        $this->singleton(Contracts\FormBuilder::class, FormBuilder::class);
    }
}
