<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml;

use Arcanedev\LaravelHtml\Contracts\{FormBuilder as FormBuilderContract, HtmlBuilder as HtmlBuilderContract};
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

        $this->singleton(HtmlBuilderContract::class, HtmlBuilder::class);
        $this->singleton(FormBuilderContract::class, FormBuilder::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides(): array
    {
        return [
            HtmlBuilderContract::class,
            FormBuilderContract::class,
        ];
    }
}
