<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\Contracts\{FormBuilder, HtmlBuilder};
use Arcanedev\LaravelHtml\HtmlServiceProvider;

/**
 * Class     HtmlServiceProviderTest
 *
 * @package  Arcanedev\LaravelHtml\Tests
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlServiceProviderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var  \Arcanedev\LaravelHtml\HtmlServiceProvider */
    private $provider;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp(): void
    {
        parent::setUp();

        $this->app->loadDeferredProviders();

        $this->provider = $this->app->getProvider(HtmlServiceProvider::class);
    }

    public function tearDown(): void
    {
        unset($this->provider);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        $expectations = [
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\Providers\ServiceProvider::class,
            HtmlServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            static::assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_get_provides(): void
    {
        $expected = [
            HtmlBuilder::class,
            FormBuilder::class,
        ];

        static::assertEquals($expected, $this->provider->provides());
    }
}
