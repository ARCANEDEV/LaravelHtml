<?php namespace Arcanedev\LaravelHtml\Tests\Facades;

use Arcanedev\LaravelHtml\Facades\Form;
use Arcanedev\LaravelHtml\Tests\TestCase;

/**
 * Class     FormTest
 *
 * @package  Arcanedev\LaravelHtml\Tests\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /**
     * @test
     *
     * @dataProvider provideOpeningForms
     *
     * @param  string  $expected
     * @param  array   $options
     */
    public function it_can_open_form($expected, $options)
    {
        $this->assertEquals($expected, Form::open($options));
    }

    /** @test */
    public function it_can_close_form()
    {
        $this->assertEquals('</form>', Form::close());
    }

    /* -----------------------------------------------------------------
     |  Providers
     | -----------------------------------------------------------------
     */

    /**
     * Provide opening form data
     *
     * @return array
     */
    public function provideOpeningForms()
    {
        $url = $this->baseUrl . '/foo';

        return [
            [
                '<form method="GET" action="' . $url . '" accept-charset="UTF-8">',
                ['url' => $url, 'method' => 'GET']
            ],[
                '<form method="POST" action="' . $url . '" accept-charset="UTF-8" class="form" id="id-form">'.
                '<input name="_token" type="hidden">',
                ['url' => $url, 'method' => 'POST', 'class' => 'form', 'id' => 'id-form']
            ],[
                '<form method="GET" action="' . $url . '" accept-charset="UTF-16">',
                ['url' => $url, 'method' => 'GET', 'accept-charset' => 'UTF-16']
            ],[
                '<form method="GET" action="' . $url . '" accept-charset="UTF-16" enctype="multipart/form-data">',
                ['url' => $url, 'method' => 'GET', 'accept-charset' => 'UTF-16', 'files' => true]
            ],[
                '<form method="POST" action="' . $url . '" accept-charset="UTF-8"><input name="_method" type="hidden" value="PUT">'.
                '<input name="_token" type="hidden">',
                ['url' => $url, 'method' => 'PUT']
            ]
        ];
    }
}
