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
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /** @var  HtmlServiceProvider */
    private $provider;

    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
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

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_must_be_a_laravel_provider()
    {
        $this->assertInstanceOf(\Illuminate\Support\ServiceProvider::class, $this->provider);
        $this->assertInstanceOf(\Arcanedev\Support\ServiceProvider::class,  $this->provider);
    }

    /** @test */
    public function it_can_get_provides()
    {
        $this->assertEquals([
            'html', \Arcanedev\LaravelHtml\HtmlBuilder::class,
            'form', \Arcanedev\LaravelHtml\FormBuilder::class
        ], $this->provider->provides());
    }
}
