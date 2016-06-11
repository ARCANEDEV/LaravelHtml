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
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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
            'html', HtmlBuilder::class, Contracts\HtmlBuilderInterface::class,
            'form', FormBuilder::class, Contracts\FormBuilderInterface::class,
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Services Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Register the HTML builder.
     */
    protected function registerHtmlBuilder()
    {
        $this->app->singleton('html', function($app) {
            return new HtmlBuilder($app['url']);
        });

        $this->app->alias('html', HtmlBuilder::class);
        $this->app->bind(Contracts\HtmlBuilderInterface::class, 'html');
    }

    /**
     * Register the form builder.
     */
    protected function registerFormBuilder()
    {
        $this->app->singleton('form', function($app) {
            /**
             * @var HtmlBuilder                       $html
             * @var \Illuminate\Routing\UrlGenerator  $url
             * @var \Illuminate\Session\Store         $session
             */
            $html    = $app['html'];
            $url     = $app['url'];
            $session = $app['session.store'];

            $form = new FormBuilder($html, $url, $session->getToken());

            $form->setSessionStore($session);

            return $form;
        });

        $this->app->alias('form', FormBuilder::class);
        $this->app->bind(Contracts\FormBuilderInterface::class, 'form');
    }
}
