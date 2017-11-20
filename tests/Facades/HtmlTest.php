<?php namespace Arcanedev\LaravelHtml\Tests\Facades;

use Arcanedev\LaravelHtml\Facades\Html;
use Arcanedev\LaravelHtml\Tests\TestCase;

/**
 * Class     HtmlTest
 *
 * @package  Arcanedev\LaravelHtml\Tests\Facades
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_convert_html_string_to_entities()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        $this->assertEquals($result, Html::entities($value));

    }

    /** @test */
    public function it_can_convert_html_entities_to_string()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        $this->assertEquals($value,  Html::decode($result));
    }

    /** @test */
    public function it_can_make_script_tags()
    {
        $file = 'bootstrap.min.js';
        $url  = $this->urlTo($file);

        $this->assertEquals(
            '<script src="' . $url . '"></script>' . PHP_EOL,
            Html::script($file)
        );
    }

    /** @test */
    public function it_can_make_style_tags()
    {
        $file = 'bootstrap.min.css';
        $url  = $this->urlTo($file);

        $this->assertEquals(
            '<link media="all" type="text/css" rel="stylesheet" href="' . $url . '">' . PHP_EOL,
            Html::style($file)
        );
    }

    /** @test */
    public function it_can_make_image_tags()
    {
        $file = 'avatar.png';
        $url   = $this->urlTo($file);

        $this->assertEquals(
            '<img src="' . $url . '">',
            Html::image($file)
        );
    }

    /** @test */
    public function it_can_make_favicon()
    {
        $url = $this->urlTo('bar.ico');

        $this->assertEquals(
            '<link rel="shortcut icon" type="image/x-icon" href="' . $url . '">' . PHP_EOL,
            Html::favicon($url)
        );
    }

    /** @test */
    public function it_can_make_link_tags()
    {
        $title = null;
        $url   = $this->urlTo('hello');

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            Html::link($url, $title)
        );
    }

    /** @test */
    public function it_can_make_secure_link_tags()
    {
        $title = null;
        $url   = $this->urlTo('hello', [], true);

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            Html::secureLink($url, $title)
        );
    }

    /** @test */
    public function it_can_make_link_tags_for_assets()
    {
        $file = 'style.min.css';
        $url  = $this->baseUrl . '/style.min.css';

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            Html::linkAsset($file)
        );
    }

    /** @test */
    public function it_can_make_dl_tags()
    {
        $list       = [
            'foo'   => 'bar',
            'bing'  => 'baz'
        ];

        $attributes = [
            'class' => 'example'
        ];

        $this->assertEquals(
            '<dl class="example">' .
            '<dt>foo</dt><dd>bar</dd>' .
            '<dt>bing</dt><dd>baz</dd>' .
            '</dl>',
            Html::dl($list, $attributes)
        );
    }

    /** @test */
    public function it_can_make_meta_tags()
    {
        $this->assertEquals(
            '<meta name="description" content="Lorem ipsum dolor sit amet.">' . PHP_EOL,
            Html::meta('description', 'Lorem ipsum dolor sit amet.')
        );
    }

    /** @test */
    public function it_can_make_meta_open_graph_tags()
    {
        $this->assertEquals(
            '<meta content="website" property="og:type">' . PHP_EOL,
            Html::meta(null, 'website', [
                'property' => 'og:type'
            ])
        );
    }
}
