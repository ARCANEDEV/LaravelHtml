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
        static::assertInstanceOf(HtmlBuilder::class, $this->html);
        static::assertInstanceOf(HtmlBuilder::class, html());
    }

    /** @test */
    public function it_can_convert_html_string_to_entities()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        static::assertEquals($result, $this->html->entities($value));
    }

    /** @test */
    public function it_can_convert_html_entities_to_string()
    {
        $value  = 'Un \'apostrophe\' en <strong>gras</strong>';
        $result = 'Un &#039;apostrophe&#039; en &lt;strong&gt;gras&lt;/strong&gt;';

        static::assertEquals($value, $this->html->decode($result));
    }

    /** @test */
    public function it_can_make_script_tags()
    {
        $url = $this->urlTo($file = 'bootstrap.min.js');

        static::assertEquals(
            '<script src="'.$url.'"></script>',
            $this->html->script($file)
        );
    }

    /** @test */
    public function it_can_make_style_tags()
    {
        $url = $this->urlTo($file = 'bootstrap.min.css');

        static::assertEquals(
            '<link rel="stylesheet" href="'.$url.'">',
            $this->html->style($file)
        );
    }

    /** @test */
    public function it_can_make_image_tags()
    {
        $url = $this->urlTo($file = 'avatar.png');

        static::assertEquals(
            '<img src="'.$url.'">',
            $this->html->image($file)
        );
    }

    /** @test */
    public function it_can_make_favicon()
    {
        $url = $this->urlTo('bar.ico');

        static::assertHtmlStringEqualsHtmlString(
            '<link href="'.$url.'" rel="shortcut icon" type="image/x-icon">',
            $this->html->favicon($url)->toHtml()
        );
    }

    /** @test */
    public function it_can_make_link_tags()
    {
        $url = $this->urlTo('hello');

        static::assertEquals(
            '<a href="'.$url.'">'.$url.'</a>',
            $this->html->link($url)
        );

        static::assertEquals(
            '<a href="'.$url.'">Website</a>',
            $this->html->link($url, 'Website')
        );

        static::assertEquals(
            '<a href="'.$url.'" class="btn btn-primary">Website</a>',
            $this->html->link($url, 'Website', ['class' => 'btn btn-primary'])
        );

        $title = '<i class="fa fa-globe"></i> '.$url;
        static::assertEquals(
            '<a href="'.$url.'">'.$title.'</a>',
            $this->html->link($url, $title, [], null, false)
        );
    }

    /** @test */
    public function it_can_make_secure_link_tags()
    {
        $title = null;
        $url   = $this->urlTo('hello', [], true);

        static::assertEquals(
            '<a href="'.$url.'">'.$url.'</a>',
            $this->html->secureLink($url, $title)
        );

        $title = '<i class="fa fa-globe"></i> '.$url;

        static::assertEquals(
            '<a href="'.$url.'">'.e($title).'</a>',
            $this->html->secureLink($url, $title, [])
        );

        static::assertEquals(
            '<a href="'.$url.'">'.$title.'</a>',
            $this->html->secureLink($url, $title, [], false)
        );
    }

    /** @test */
    public function it_must_escape_link_urls()
    {
        static::assertEquals(
            '<a href="https://localhost/b?id=4&amp;not_id=5">Escaped url</a>',
            $this->html->link('https://localhost/b?id=4&not_id=5', 'Escaped url')
        );
    }

    /** @test */
    public function it_can_make_link_tags_for_assets()
    {
        $file = 'assets/img/logo.png';
        $url  = "{$this->baseUrl}/{$file}";

        static::assertEquals(
            '<a href="'.$url.'">'.$url.'</a>',
            $this->html->linkAsset($file)
        );
    }

    /** @test */
    public function it_can_make_secure_links_tags_for_assets()
    {
        $file = 'assets/img/logo.png';
        $url  = "https://localhost/{$file}";

        static::assertEquals(
            '<a href="'.$url.'">'.$url.'</a>',
            $this->html->linkSecureAsset($file)
        );
    }

    /** @test */
    public function it_can_make_link_from_route()
    {
        $title = 'Home';
        $route = 'home';
        static::assertEquals(
            '<a href="'.route($route).'">'.$title.'</a>',
            $this->html->linkRoute($route, $title)
        );

        static::assertEquals(
            '<a href="'.route($route).'" class="btn btn-primary">'.$title.'</a>',
            $this->html->linkRoute($route, $title, [], ['class' => 'btn btn-primary'])
        );

        $title = '<i class="fa fa-home"></i>'.$title;

        static::assertEquals(
            '<a href="'.route($route).'" class="btn btn-primary">'.e($title).'</a>',
            $this->html->linkRoute($route, $title, [], ['class' => 'btn btn-primary'])
        );

        static::assertEquals(
            '<a href="'.route($route).'" class="btn btn-primary">'.$title.'</a>',
            $this->html->linkRoute($route, $title, [], ['class' => 'btn btn-primary'], false)
        );
    }

    /** @test */
    public function it_can_make_link_from_action()
    {
        $title  = 'Home';
        $action = 'Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index';

        static::assertEquals(
            '<a href="'.action($action).'">'.$title.'</a>',
            $this->html->linkAction($action, $title)
        );

        static::assertEquals(
            '<a href="'.action($action).'" class="btn btn-primary">'.$title.'</a>',
            $this->html->linkAction($action, $title, [], ['class' => 'btn btn-primary'])
        );

        $title = '<i class="fa fa-home"></i>'.$title;

        static::assertEquals(
            '<a href="'.action($action).'" class="btn btn-primary">'.e($title).'</a>',
            $this->html->linkAction($action, $title, [], ['class' => 'btn btn-primary'])
        );

        static::assertEquals(
            '<a href="'.action($action).'" class="btn btn-primary">'.$title.'</a>',
            $this->html->linkAction($action, $title, [], ['class' => 'btn btn-primary'], false)
        );
    }

    /** @test */
    public function it_can_make_mailto_link()
    {
        $email  = 'j.doe@gmail.com';
        $mailto = $this->html->mailto($email)->toHtml();

        static::assertStringStartsWith('<a href="', $mailto);
        static::assertStringContainsString('&#', $mailto);
        static::assertStringEndsWith('</a>', $mailto);

        $name   = 'John DOE';
        $mailto = $this->html->mailto($email, $name)->toHtml();

        static::assertStringStartsWith('<a href="', $mailto);
        static::assertStringContainsString('&#', $mailto);
        static::assertStringEndsWith($name . '</a>', $mailto);

        $name   = '<span>John DOE</span>';
        $mailto = $this->html->mailto($email, $name, ['class' => 'mailto-link'], false)->toHtml();

        static::assertStringStartsWith('<a href="', $mailto);
        static::assertStringContainsString('&#', $mailto);
        static::assertStringEndsWith($name . '</a>', $mailto);
    }

    /** @test */
    public function it_can_make_ol_tags()
    {
        $list = [
            'foo'   => 'bar',
            'bing'  => 'baz',
        ];

        $attributes = [
            'class' => 'example',
        ];

        static::assertHtmlStringEqualsHtmlString(
            '<ol class="example">' .
                '<li>bar</li>' .
                '<li>baz</li>' .
            '</ol>',
            $this->html->ol($list, $attributes)
        );

        // Empty list
        static::assertHtmlStringEqualsHtmlString(
            '<ol></ol>',
            $this->html->ol([])->toHtml()
        );
    }

    /** @test */
    public function it_can_make_ul_tags()
    {
        $list = [
            'foo'   => 'bar',
            'bing'  => 'baz',
        ];

        $attributes = [
            'class' => 'example',
        ];

        static::assertHtmlStringEqualsHtmlString(
            '<ul class="example">' .
                '<li>bar</li>' .
                '<li>baz</li>' .
            '</ul>',
            $this->html->ul($list, $attributes)
        );

        // Empty list
        static::assertHtmlStringEqualsHtmlString(
            '<ul></ul>',
            $this->html->ul([])->toHtml()
        );
    }

    /** @test */
    public function it_can_make_dl_tags()
    {
        $list = [
            'foo'   => 'bar',
            'bing'  => 'baz',
        ];

        $attributes = [
            'class' => 'example',
        ];

        static::assertEquals(
            '<dl class="example">' .
                '<dt>foo</dt><dd>bar</dd>' .
                '<dt>bing</dt><dd>baz</dd>' .
            '</dl>',
            $this->html->dl($list, $attributes)
        );

        $list = [
            'foo'   => 'bar',
            'bing'  => 'baz',
            'qux'   => [
                'qux-foo', 'qux-bar', 'qux-baz',
            ]
        ];

        static::assertHtmlStringEqualsHtmlString(
            '<dl class="example">' .
                '<dt>foo</dt><dd>bar</dd>' .
                '<dt>bing</dt><dd>baz</dd>' .
                '<dt>qux</dt><dd>qux-foo</dd><dd>qux-bar</dd><dd>qux-baz</dd>' .
            '</dl>',
            $this->html->dl($list, $attributes)
        );

        // Empty list
        static::assertEquals(
            '<dl></dl>',
            $this->html->dl([])
        );
    }

    /** @test */
    public function it_can_make_nested_listing()
    {
        $list = [
            'bar',
            'baz',
            [
                'child-1',
                'child-2',
                'child-3',
            ],
        ];

        static::assertHtmlStringEqualsHtmlString(
            '<ul>'.
                '<li>bar</li>' .
                '<li>baz</li>' .
                '<li>' .
                    '<ul>' .
                        '<li>child-1</li>' .
                        '<li>child-2</li>' .
                        '<li>child-3</li>' .
                    '</ul>' .
                '</li>' .
            '</ul>',
            $this->html->ul($list)
        );

        static::assertHtmlStringEqualsHtmlString(
            '<ol>'.
                '<li>bar</li>' .
                '<li>baz</li>' .
                '<li>' .
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

    /** @test */
    public function it_can_obfuscate()
    {
        static::assertNotEmpty($this->html->obfuscate('à'));
    }

    /** @test */
    public function it_can_make_attributes()
    {
        static::assertEquals(
            'class="strong" required',
            $this->html->attributes(['class' => 'strong', 'required'])
        );
    }

    /** @test */
    public function it_can_make_meta_tags()
    {
        static::assertEquals(
            '<meta name="description" content="Lorem ipsum dolor sit amet.">',
            $this->html->meta('description', 'Lorem ipsum dolor sit amet.')
        );
    }

    /** @test */
    public function it_can_make_meta_open_graph_tags()
    {
        static::assertEquals(
            '<meta content="website" property="og:type">',
            $this->html->meta(null, 'website', ['property' => 'og:type'])
        );
    }

    /** @test */
    public function it_can_register_a_component()
    {
        $this->html->component('tweet', 'components.tweet', [
            'handle', 'body', 'date'
        ]);

        static::assertTrue($this->html->hasComponent('tweet'));
    }

    /** @test */
    public function it_must_throw_bad_method_call_exception_on_component()
    {
        $this->expectException(\BadMethodCallException::class);
        $this->expectExceptionMessage('Method Arcanedev\LaravelHtml\HtmlBuilder::btnSuccess does not exist.');

        $this->html->btnSuccess('Hello');
    }

    /** @test */
    public function it_can_generate_nbsp()
    {
        $nbsp = '&nbsp;';

        static::assertEquals($nbsp, $this->html->nbsp());
        static::assertEquals(str_repeat($nbsp, 5), $this->html->nbsp(5));
    }

    /** @test */
    public function it_can_make_tel_link()
    {
        $phone = '+123-456-789';

        static::assertSame(
            '<a href="tel:'.$phone.'">'.$phone.'</a>',
            $this->html->tel($phone)->toHtml()
        );

        static::assertSame(
            '<a href="tel:'.$phone.'">Call us!</a>',
            $this->html->tel($phone, 'Call us!')->toHtml()
        );

        static::assertSame(
            '<a href="tel:'.$phone.'">Call us!</a>',
            $this->html->tel($phone, 'Call us!')->toHtml()
        );
    }
}
