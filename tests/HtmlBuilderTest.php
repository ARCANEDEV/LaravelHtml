<?php namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\HtmlBuilder;

/**
 * Class     HtmlBuilderTest
 *
 * @package  Arcanedev\LaravelHtml\Tests\Builders
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class HtmlBuilderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(HtmlBuilder::class, $this->html);
        $this->assertInstanceOf(HtmlBuilder::class, html());
    }

    /** @test */
    public function it_can_convert_html_string_to_entities()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        $this->assertEquals($result, $this->html->entities($value));

    }

    /** @test */
    public function it_can_convert_html_entities_to_string()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        $this->assertEquals($value, $this->html->decode($result));
    }

    /** @test */
    public function it_can_make_script_tags()
    {
        $file = 'bootstrap.min.js';
        $url  = $this->urlTo($file);

        $this->assertEquals(
            '<script src="' . $url . '"></script>' . PHP_EOL,
            $this->html->script($file)
        );
    }

    /** @test */
    public function it_can_make_style_tags()
    {
        $file = 'bootstrap.min.css';
        $url  = $this->urlTo($file);

        $this->assertEquals(
            '<link media="all" type="text/css" rel="stylesheet" href="' . $url . '">' . PHP_EOL,
            $this->html->style($file)
        );
    }

    /** @test */
    public function it_can_make_image_tags()
    {
        $file = 'avatar.png';
        $url   = $this->urlTo($file);

        $this->assertEquals(
            '<img src="' . $url . '">',
            $this->html->image($file)
        );
    }

    /** @test */
    public function it_can_make_favicon()
    {
        $url = $this->urlTo('bar.ico');

        $this->assertEquals(
            '<link rel="shortcut icon" type="image/x-icon" href="' . $url . '">' . PHP_EOL,
            $this->html->favicon($url)
        );
    }

    /** @test */
    public function it_can_make_link_tags()
    {
        $url   = $this->urlTo('hello');

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->html->link($url, null)
        );

        $this->assertEquals(
            '<a href="' . $url . '">Website</a>',
            $this->html->link($url, 'Website')
        );

        $this->assertEquals(
            '<a href="' . $url . '" class="btn btn-primary">Website</a>',
            $this->html->link($url, 'Website', ['class' => 'btn btn-primary'])
        );

        $title = '<i class="fa fa-globe"></i> ' . $url;
        $this->assertEquals(
            '<a href="' . $url . '">' . $title . '</a>',
            $this->html->link($url, $title, [], null, false)
        );
    }

    /** @test */
    public function it_can_make_secure_link_tags()
    {
        $title = null;
        $url   = $this->urlTo('hello', [], true);

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->html->secureLink($url, $title)
        );

        $title = '<i class="fa fa-globe"></i> ' . $url;

        $this->assertEquals(
            '<a href="' . $url . '">' . e($title) . '</a>',
            $this->html->secureLink($url, $title, [])
        );

        $this->assertEquals(
            '<a href="' . $url . '">' . $title . '</a>',
            $this->html->secureLink($url, $title, [], false)
        );
    }

    /** @test */
    public function it_can_make_link_tags_for_assets()
    {
        $file = 'assets/img/logo.png';
        $url  = $this->baseUrl . '/' . $file;

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->html->linkAsset($file)
        );
    }

    /** @test */
    public function it_can_make_secure_links_tags_for_assets()
    {
        $file = 'assets/img/logo.png';
        $url  = 'https://localhost/' . $file;

        $this->assertEquals(
            '<a href="' . $url . '">' . $url . '</a>',
            $this->html->linkSecureAsset($file)
        );
    }

    /** @test */
    public function it_can_make_link_from_route()
    {
        $title = 'Home';
        $route = 'home';
        $this->assertEquals(
            '<a href="' . route($route) . '">' . $title . '</a>',
            $this->html->linkRoute($route, $title)
        );

        $this->assertEquals(
            '<a href="' . route($route) . '" class="btn btn-primary">' . $title . '</a>',
            $this->html->linkRoute($route, $title, [], ['class' => 'btn btn-primary'])
        );

        $title = '<i class="fa fa-home"></i>' . $title;

        $this->assertEquals(
            '<a href="' . route($route) . '" class="btn btn-primary">' . e($title) . '</a>',
            $this->html->linkRoute($route, $title, [], ['class' => 'btn btn-primary'])
        );

        $this->assertEquals(
            '<a href="' . route($route) . '" class="btn btn-primary">' . $title . '</a>',
            $this->html->linkRoute($route, $title, [], ['class' => 'btn btn-primary'], false)
        );
    }

    /** @test */
    public function it_can_make_link_from_action()
    {
        $title  = 'Home';
        $action = 'Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index';

        $this->assertEquals(
            '<a href="' . action($action) . '">' . $title . '</a>',
            $this->html->linkAction($action, $title)
        );

        $this->assertEquals(
            '<a href="' . action($action) . '" class="btn btn-primary">' . $title . '</a>',
            $this->html->linkAction($action, $title, [], ['class' => 'btn btn-primary'])
        );

        $title = '<i class="fa fa-home"></i>' . $title;

        $this->assertEquals(
            '<a href="' . action($action) . '" class="btn btn-primary">' . e($title) . '</a>',
            $this->html->linkAction($action, $title, [], ['class' => 'btn btn-primary'])
        );

        $this->assertEquals(
            '<a href="' . action($action) . '" class="btn btn-primary">' . $title . '</a>',
            $this->html->linkAction($action, $title, [], ['class' => 'btn btn-primary'], false)
        );
    }

    /** @test */
    public function it_can_make_mailto_link()
    {
        $email  = 'j.doe@gmail.com';
        $mailto = $this->html->mailto($email)->toHtml();

        $this->assertStringStartsWith('<a href="', $mailto);
        $this->assertContains('&#', $mailto);
        $this->assertStringEndsWith('</a>', $mailto);

        $name   = 'John DOE';
        $mailto = $this->html->mailto($email, $name)->toHtml();

        $this->assertStringStartsWith('<a href="', $mailto);
        $this->assertContains('&#', $mailto);
        $this->assertStringEndsWith($name . '</a>', $mailto);

        $name   = '<span>John DOE</span>';
        $mailto = $this->html->mailto($email, $name, ['class' => 'mailto-link'], false)->toHtml();

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
            $this->html->ol($list, $attributes)
        );

        // Empty list
        $this->assertEmpty(
            $this->html->ol([])->toHtml()
        );
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
            $this->html->ul($list, $attributes)
        );

        // Empty list
        $this->assertEmpty(
            $this->html->ul([])->toHtml()
        );
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
            $this->html->dl($list, $attributes)
        );

        $list       = [
            'foo'   => 'bar',
            'bing'  => 'baz',
            'qux'   => [
                'qux-foo', 'qux-bar', 'qux-baz',
            ]
        ];

        $this->assertEquals(
            '<dl class="example">' .
                '<dt>foo</dt><dd>bar</dd>' .
                '<dt>bing</dt><dd>baz</dd>' .
                '<dt>qux</dt><dd>qux-foo</dd><dd>qux-bar</dd><dd>qux-baz</dd>' .
            '</dl>',
            $this->html->dl($list, $attributes)
        );

        // Empty list
        $this->assertEquals(
            '<dl></dl>',
            $this->html->dl([])
        );
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
            $this->html->ul($list)
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
            $this->html->ol($list)
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
                'child-3' => [
                    'hello', 'world'
                ],
            ],
        ];

        $this->html->dl($list);
    }

    /** @test */
    public function it_can_obfuscate()
    {
        $this->assertNotEmpty($this->html->obfuscate('à'));
    }

    /** @test */
    public function it_can_make_attributes()
    {
        $this->assertEquals(
            ' class="strong" required="required"',
            $this->html->attributes(['class' => 'strong', 'required'])
        );
    }

    /** @test */
    public function it_can_make_meta_tags()
    {
        $this->assertEquals(
            '<meta name="description" content="Lorem ipsum dolor sit amet.">' . PHP_EOL,
            $this->html->meta('description', 'Lorem ipsum dolor sit amet.')
        );
    }

    /** @test */
    public function it_can_make_meta_open_graph_tags()
    {
        $this->assertEquals(
            '<meta content="website" property="og:type">' . PHP_EOL,
            $this->html->meta(null, 'website', [
                'property' => 'og:type'
            ])
        );
    }

    /** @test */
    public function it_can_register_a_component()
    {
        $this->html->component('tweet', 'components.tweet', [
            'handle', 'body', 'date'
        ]);

        $this->assertTrue($this->html->hasComponent('tweet'));
    }

    /**
     * @test
     *
     * @expectedException         \BadMethodCallException
     * @expectedExceptionMessage  Method btnSuccess does not exist.
     */
    public function it_must_throw_bad_method_call_exception_on_component()
    {
        $this->html->btnSuccess('Hello');
    }

    /** @test */
    public function it_can_generate_nbsp()
    {
        $nbsp = '&nbsp;';

        $this->assertEquals($nbsp, $this->html->nbsp());
        $this->assertEquals(str_repeat($nbsp, 5), $this->html->nbsp(5));
    }

    /** @test */
    public function it_can_make_tel_link()
    {
        $phone = '+123-456-789';

        $this->assertSame(
            '<a href="tel:'.$phone.'">'.$phone.'</a>',
            $this->html->tel($phone)->toHtml()
        );

        $this->assertSame(
            '<a href="tel:'.$phone.'">Call us!</a>',
            $this->html->tel($phone, 'Call us!')->toHtml()
        );

        $this->assertSame(
            '<a href="tel:'.$phone.'">Call us!</a>',
            $this->html->tel($phone, 'Call us!')->toHtml()
        );
    }
}
