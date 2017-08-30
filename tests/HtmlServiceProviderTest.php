<?php namespace Arcanedev\LaravelHtml\Tests;

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

    public function setUp()
    {
        parent::setUp();

        $this->app->loadDeferredProviders();
        $this->provider = $this->app->getProvider(HtmlServiceProvider::class);
    }

    public function tearDown()
    {
        unset($this->provider);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $expectations = [
            \Illuminate\Support\ServiceProvider::class,
            \Arcanedev\Support\ServiceProvider::class,
            HtmlServiceProvider::class,
        ];

        foreach ($expectations as $expected) {
            $this->assertInstanceOf($expected, $this->provider);
        }
    }

    /** @test */
    public function it_can_get_provides()
    {
        $expected = [
            \Arcanedev\LaravelHtml\HtmlBuilder::class,
            \Arcanedev\LaravelHtml\Contracts\HtmlBuilder::class,
            \Arcanedev\LaravelHtml\FormBuilder::class,
            \Arcanedev\LaravelHtml\Contracts\FormBuilder::class,
        ];

        $this->assertEquals($expected, $this->provider->provides());
    }
}
