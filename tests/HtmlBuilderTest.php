<?php namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\HtmlBuilder;
use Mockery as m;

/**
 * Class     HtmlBuilderTest
 *
 * @package  Arcanedev\LaravelHtml\Tests\Builders
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlBuilderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Main Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * Setup the test environment.
     */
    public function setUp()
    {
        parent::setUp();
    }

    /**
     * Destroy the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();

        m::close();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(HtmlBuilder::class, $this->htmlBuilder);
    }

    /** @test */
    public function it_can_convert_html_string_to_entities()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        $this->assertEquals($result, $this->htmlBuilder->entities($value));

    }

    /** @test */
    public function it_can_convert_html_entities_to_string()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        $this->assertEquals($value,  $this->htmlBuilder->decode($result));
    }

    /** @test */
    public function it_can_make_script_tags()
    {
        $file = 'bootstrap.min.js';
        $url  = $this->urlTo($file);

        $this->assertEquals(
            '<script src="' . $url . '"></script>' . PHP_EOL,
            $this->htmlBuilder->script($file)
        );
    }

    /** @test */
    public function it_can_make_style_tags()
    {
        $file = 'bootstrap.min.css';
        $url  = $this->urlTo($file);

        $this->assertEquals(
            '<link media="all" type="text/css" rel="stylesheet" href="' . $url . '">' . PHP_EOL,
            $this->htmlBuilder->style($file)
        );
    }

    /** @test */
    public function it_can_make_image_tags()
    {
        $file = 'avatar.png';
        $url   = $this->urlTo($file);

        $this->assertEquals(
            '<img src="' . $url . '">',
            $this->htmlBuilder->image($file)
        );
    }

    /** @test */
    public function it_can_make_favicon()
    {
        $url = $this->urlTo('bar.ico');

        $this->assertEquals(
            '<link rel="shortcut icon" type="image/x-icon" href="' . $url . '">' . PHP_EOL,
            $this->htmlBuilder->favicon($url)
        );
    }

    /** @test */
    public function it_can_make_link_tags()
    {
        $title = null;
        $url   = $this->urlTo('hello');

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->htmlBuilder->link($url, $title)
        );
    }

    /** @test */
    public function it_can_make_secure_link_tags()
    {
        $title = null;
        $url   = $this->urlTo('hello', [], true);

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->htmlBuilder->secureLink($url, $title)
        );
    }

    /** @test */
    public function it_can_make_link_tags_for_assets()
    {
        $file = 'style.min.css';
        $url  = $this->baseUrl . '/style.min.css';

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->htmlBuilder->linkAsset($file)
        );
    }

    /** @test */
    public function it_can_make_secure_links_tags_for_assets()
    {
        $file = 'style.min.css';
        $url  = 'https://localhost/' . $file;

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->htmlBuilder->linkSecureAsset($file)
        );
    }

    /** @test */
    public function it_can_make_link_from_route()
    {
        $this->assertEquals(
            '<a href="http://localhost">Home</a>',
            $this->htmlBuilder->linkRoute('home', 'Home')
        );
    }

    /** @test */
    public function it_can_make_link_from_action()
    {
        $this->assertEquals(
            '<a href="http://localhost">Home</a>',
            $this->htmlBuilder->linkAction(
                'Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index', 'Home'
            )
        );
    }

    /** @test */
    public function it_can_make_mailto_link()
    {
        $email  = 'j.doe@gmail.com';
        $mailto = $this->htmlBuilder->mailto($email);

        $this->assertStringStartsWith('<a href="', $mailto);
        $this->assertContains('&#', $mailto);
        $this->assertStringEndsWith('</a>', $mailto);

        $name   = 'John DOE';
        $mailto = $this->htmlBuilder->mailto($email, $name);

        $this->assertStringStartsWith('<a href="', $mailto);
        $this->assertContains('&#', $mailto);
        $this->assertStringEndsWith($name . '</a>', $mailto);
    }

    /** @test */
    public function it_can_make_ol_tags()
    {
        $list       = [
            'foo'   => 'bar',
            'bing'  => 'baz',
        ];

        $attributes = [
            'class' => 'example',
        ];

        $this->assertEquals(
            '<ol class="example">' .
                '<li>bar</li>' .
                '<li>baz</li>' .
            '</ol>',
            $this->htmlBuilder->ol($list, $attributes)
        );

        // Empty list
        $this->assertEmpty($this->htmlBuilder->ol([]));
    }

    /** @test */
    public function it_can_make_ul_tags()
    {
        $list       = [
            'foo'   => 'bar',
            'bing'  => 'baz',
        ];

        $attributes = [
            'class' => 'example',
        ];

        $this->assertEquals(
            '<ul class="example">' .
                '<li>bar</li>' .
                '<li>baz</li>' .
            '</ul>',
            $this->htmlBuilder->ul($list, $attributes)
        );

        // Empty list
        $this->assertEmpty($this->htmlBuilder->ul([]));
    }

    /** @test */
    public function it_can_make_dl_tags()
    {
        $list       = [
            'foo'   => 'bar',
            'bing'  => 'baz',
        ];

        $attributes = [
            'class' => 'example',
        ];

        $this->assertEquals(
            '<dl class="example">' .
                '<dt>foo</dt><dd>bar</dd>' .
                '<dt>bing</dt><dd>baz</dd>' .
            '</dl>',
            $this->htmlBuilder->dl($list, $attributes)
        );

        // Empty list
        $this->assertEquals('<dl></dl>', $this->htmlBuilder->dl([]));
    }

    /** @test */
    public function it_can_make_nested_listing()
    {
        $list = [
            'foo'    => 'bar',
            'bing'   => 'baz',
            'nested' => [
                'child-1',
                'child-2',
                'child-3',
            ],
        ];

        $this->assertEquals(
            '<ul>'.
                '<li>bar</li>' .
                '<li>baz</li>' .
                '<li>nested' .
                    '<ul>' .
                        '<li>child-1</li>' .
                        '<li>child-2</li>' .
                        '<li>child-3</li>' .
                    '</ul>' .
                '</li>' .
            '</ul>',
            $this->htmlBuilder->ul($list)
        );

        $this->assertEquals(
            '<ol>'.
                '<li>bar</li>' .
                '<li>baz</li>' .
                '<li>nested' .
                    '<ol>' .
                        '<li>child-1</li>' .
                        '<li>child-2</li>' .
                        '<li>child-3</li>' .
                    '</ol>' .
                '</li>' .
            '</ol>',
            $this->htmlBuilder->ol($list)
        );
    }

    /**
     * @test
     *
     * @expectedException         \Exception
     * @expectedExceptionMessage  Array to string conversion
     */
    public function it_must_throw_an_error_on_dl_nest_list()
    {
        $list = [
            'foo'    => 'bar',
            'bing'   => 'baz',
            'nested' => [
                'child-1',
                'child-2',
                'child-3',
            ],
        ];

        $this->htmlBuilder->dl($list);
    }

    /** @test */
    public function it_can_obfuscate()
    {
        $this->assertNotEmpty($this->htmlBuilder->obfuscate('à'));
    }

    /** @test */
    public function it_can_make_attributes()
    {
        $this->assertEquals(
            ' class="strong" required="required"',
            $this->htmlBuilder->attributes(['class' => 'strong', 'required'])
        );
    }

    /** @test */
    public function it_can_make_meta_tags()
    {
        $this->assertEquals(
            '<meta name="description" content="Lorem ipsum dolor sit amet.">' . PHP_EOL,
            $this->htmlBuilder->meta('description', 'Lorem ipsum dolor sit amet.')
        );
    }

    /** @test */
    public function it_can_make_meta_open_graph_tags()
    {
        $this->assertEquals(
            '<meta content="website" property="og:type">' . PHP_EOL,
            $this->htmlBuilder->meta(null, 'website', [
                'property' => 'og:type'
            ])
        );
    }
}
