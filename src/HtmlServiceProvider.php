<?php namespace Arcanedev\LaravelHtml;

use Arcanedev\Support\Providers\ServiceProvider;
use Illuminate\Contracts\Support\DeferrableProvider;

/**
 * Class     HtmlServiceProvider
 *
 * @package  Arcanedev\LaravelHtml
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlServiceProvider extends ServiceProvider implements DeferrableProvider
{
    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        parent::register();

        $this->singleton(Contracts\HtmlBuilder::class, HtmlBuilder::class);
        $this->singleton(Contracts\FormBuilder::class, FormBuilder::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            Contracts\HtmlBuilder::class,
            Contracts\FormBuilder::class,
        ];
    }
}
