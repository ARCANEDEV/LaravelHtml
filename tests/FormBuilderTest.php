<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\FormBuilder;
use Arcanedev\LaravelHtml\Tests\Stubs\FormBuilderModelStub;
use Closure;
use Illuminate\Contracts\Session\Session;
use Illuminate\Session\Store;
use Illuminate\Support\Collection;
use Mockery\MockInterface;
use SessionHandler;
use StdClass;

/**
 * Class     FormBuilderTest
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormBuilderTest extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /** @var \Arcanedev\LaravelHtml\FormBuilder */
    private $form;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    /**
     * Setup the test environment.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->form = $this->getFormBuilder();
    }

    /**
     * Destroy the test environment.
     */
    public function tearDown(): void
    {
        unset($this->form);

        parent::tearDown();
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_be_instantiated(): void
    {
        static::assertInstanceOf(FormBuilder::class, $this->form);
        static::assertNull($this->form->getModel());
    }

    /** @test */
    public function it_can_be_instantiated_via_helper(): void
    {
        static::assertInstanceOf(FormBuilder::class, form());
        static::assertNull(form()->getModel());
    }

    /** @test */
    public function it_can_set_and_get_session(): void
    {
        static::assertEquals(
            $this->mockSession(),
            $this->form->getSessionStore()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideOpeningForms
     *
     * @param  string  $expected
     * @param  array   $attributes
     */
    public function it_can_open_form($expected, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->open($attributes)->toHtml()
        );
    }

    /** @test */
    public function it_can_open_form_with_different_actions(): void
    {
        $expected = implode('', [
            '<form method="POST" action="http://localhost" accept-charset="UTF-8">',
            '<input type="hidden" name="_token" value="abc">',
        ]);

        $attributes = [
            ['route'  => 'home'],
            ['action' => 'Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index'],
        ];

        foreach ($attributes as $option) {
            static::assertEquals($expected, $this->form->open($option));
        }
    }

    /** @test */
    public function it_can_open_form_with_route_name_and_parameters(): void
    {
        static::assertEquals(
            '<form method="POST" action="http://localhost?hello" accept-charset="UTF-8"><input type="hidden" name="_token" value="abc">',
            $this->form->open(['route'  => ['home', 'hello']])->toHtml()
        );

        static::assertEquals(
            '<form method="POST" action="http://localhost?hello&amp;world" accept-charset="UTF-8"><input type="hidden" name="_token" value="abc">',
            $this->form->open(['route'  => ['home', 'hello', 'world']])->toHtml()
        );
    }

    /** @test */
    public function it_can_open_form_with_action_name_and_parameters(): void
    {
        static::assertEquals(
            '<form method="POST" action="http://localhost?hello" accept-charset="UTF-8"><input type="hidden" name="_token" value="abc">',
            $this->form->open(['action' => ['Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index', 'hello']])->toHtml()
        );

        static::assertEquals(
            '<form method="POST" action="http://localhost?hello&amp;world" accept-charset="UTF-8"><input type="hidden" name="_token" value="abc">',
            $this->form->open(['action' => ['Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index', 'hello', 'world']])->toHtml()
        );
    }

    /** @test */
    public function it_can_open_form_with_url_and_parameters(): void
    {
        static::assertEquals(
            '<form method="POST" action="http://localhost/hello" accept-charset="UTF-8"><input type="hidden" name="_token" value="abc">',
            $this->form->open(['url' => ['/', 'hello']])->toHtml()
        );

        static::assertEquals(
            '<form method="POST" action="http://localhost/hello/world" accept-charset="UTF-8"><input type="hidden" name="_token" value="abc">',
            $this->form->open(['url' => ['/', 'hello', 'world']])->toHtml()
        );
    }

    /** @test */
    public function it_can_close_form(): void
    {
        static::assertEquals('</form>', $this->form->close());
    }

    /**
     * @test
     *
     * @dataProvider provideLabels
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     * @param  bool    $escaped
     */
    public function it_can_make_label($expected, $name, $value, $attributes, $escaped = true): void
    {
        static::assertEquals(
            $expected,
            $this->form->label($name, $value, $attributes, $escaped)
        );
    }

    /**
     * @test
     *
     * @dataProvider provideInputs
     *
     * @param  string  $expected
     * @param  string  $type
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_form_inputs($expected, $type, $name, $value, array $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->input($type, $name, $value, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider providePassword
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  array   $attributes
     */
    public function it_can_make_password_inputs($expected, $name, $attributes): void
    {
        static::assertEquals($expected, $this->form->password($name, $attributes)->toHtml());
    }

    /** @test */
    public function it_can_make_not_filled_passwords(): void
    {
        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldNotReceive('getOldInput');
        });

        static::assertEquals(
            '<input type="password" name="password">',
            $this->form->password('password')->toHtml()
        );
    }

    /** @test */
    public function it_can_make_not_filled_files(): void
    {
        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldNotReceive('getOldInput');
        });

        static::assertEquals(
            '<input type="file" name="img">',
            $this->form->file('img')->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideTextInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_text_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->text($name, $value, $attributes)->toHtml()
        );
    }

    /** @test */
    public function it_can_make_populated_text_inputs(): void
    {
        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')
                 ->with('name_with_dots')
                 ->andReturn('some value')
                 ->times(2);
        });

        $this->setModel($model = [
            'relation'  => [
                'key'   => 'attribute'
            ],
            'other'     => 'val'
        ]);

        static::assertEquals(
            '<input type="text" name="name.with.dots" value="some value">',
            $this->form->text('name.with.dots', 'default value')->toHtml()
        );

        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')
                 ->with('text.key.sub')
                 ->andReturn(null)
                 ->once();
        });

        static::assertEquals(
            '<input type="text" name="text[key][sub]" value="default value">',
            $this->form->text('text[key][sub]', 'default value')->toHtml()
        );

        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')
                 ->with('relation.key')
                 ->andReturn(null);
        });

        $textInput = $this->form->text('relation[key]')->toHtml();

        static::assertEquals(
            '<input type="text" name="relation[key]" value="attribute">',
            $textInput
        );

        $this->setModel($model, false);

        static::assertEquals(
            $textInput,
            $this->form->text('relation[key]')->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideTextArrayAndObjectModel
     *
     * @param  string  $expected
     * @param  string  $model
     * @param  string  $name
     */
    public function it_can_make_populated_text_inputs_with_mix_of_arrays_and_objects($expected, $model, $name): void
    {
        $this->form->model($model);

        static::assertEquals(
            $expected,
            $this->form->text($name)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideHiddenData
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_hidden_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->hidden($name, $value, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideEmailData
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_email_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals($expected, $this->form->email($name, $value, $attributes)->toHtml());

        static::assertEquals(
            '<input type="email" name="foo" class="email-input">',
            $this->form->email('foo', null, ['class' => 'email-input'])->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideTelInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_tel_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals($expected, $this->form->tel($name, $value, $attributes));
    }

    /**
     * @test
     *
     * @dataProvider provideNumberInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_number_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals($expected, $this->form->number($name, $value, $attributes));
    }

    /**
     * @test
     *
     * @dataProvider provideDateInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_date_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->date($name, $value, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideDateTimeInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     */
    public function it_can_make_datetime_input($expected, $name, $value, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->datetime($name, $value, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideDateTimeLocaleInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     */
    public function it_can_make_datetime_locale_input($expected, $name, $value, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->datetimeLocal($name, $value, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideColorInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  string  $value
     * @param  array   $attributes
     */
    public function it_can_make_color_input($expected, $name, $value, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->color($name, $value, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideTimeInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_time_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals($expected, $this->form->time($name, $value, $attributes));
    }

    /**
     * @test
     *
     * @dataProvider provideUrlInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  array   $attributes
     */
    public function it_can_make_url_inputs($expected, $name, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->url($name, null, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideFileInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  array   $attributes
     */
    public function it_can_make_files_inputs($expected, $name, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->file($name, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideTextAreaInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $attributes
     */
    public function it_can_make_textarea_inputs($expected, $name, $value, $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->textarea($name, $value, $attributes)->toHtml()
        );
    }

    /**
     * @test
     *
     * @dataProvider provideSelectData
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  array   $list
     * @param  mixed   $selected
     * @param  array   $attributes
     */
    public function it_can_make_select_inputs($expected, $name, $list, $selected, array $attributes): void
    {
        static::assertEquals(
            $expected,
            $this->form->select($name, $list, $selected, $attributes)->toHtml()
        );
    }

    /** @test */
    public function it_can_make_select_input_with_label(): void
    {
        static::assertHtmlStringEqualsHtmlString(
            '<label for="select-name-id">Select Name Id</label>',
            $this->form->label('select-name-id')->toHtml()
        );

        static::assertHtmlStringEqualsHtmlString(
            '<select name="select-name" id="select-name-id"></select>',
            $this->form->select(
                'select-name-id',
                [],
                null,
                ['name' => 'select-name']
            )->toHtml()
        );
    }

    /** @test */
    public function it_can_make_populated_select_inputs(): void
    {
        $list  = [
            'L' => 'Large',
            'M' => 'Medium',
            'S' => 'Small',
        ];
        $model = [
            'size'  => ['key' => 'S'],
            'other' => 'val'
        ];

        $this->setModel($model);

        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')
                 ->with('size')
                 ->andReturn('M')
                 ->times(2);
        });

        static::assertEquals(
            implode('', [
                '<select name="size">',
                    '<option value="L">Large</option>',
                    '<option value="M" selected>Medium</option>',
                    '<option value="S">Small</option>',
                '</select>',
            ]),
            $this->form->select('size', $list, 'S')
        );

        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')
                ->withArgs(['size.multi'])
                ->times(2)
                ->andReturn(['L', 'S']);
        });

        static::assertEquals(
            implode('', [
                '<select name="size[multi][]" multiple="multiple">',
                    '<option value="L" selected>Large</option>',
                    '<option value="M">Medium</option>',
                    '<option value="S" selected>Small</option>',
                '</select>'
            ]),
            $this->form->select('size[multi][]', $list, 'M', ['multiple' => 'multiple'])->toHtml()
        );

        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')
                ->withArgs(['size.key'])
                ->andReturn(null);
        });

        static::assertEquals(
            implode('', [
                '<select name="size[key]">',
                    '<option value="L">Large</option>',
                    '<option value="M">Medium</option>',
                    '<option value="S" selected>Small</option>',
                '</select>',
            ]),
            $this->form->select('size[key]', $list)
        );
    }

    /** @test */
    public function it_can_make_select_options_with_attributes(): void
    {
        static::assertEquals(
            '<select name="size">'.
                '<option value="L" data-foo="bar" disabled>Large</option>'.
                '<option value="S">Small</option>'.
            '</select>',
            $this->form->select(
                'size',
                ['L' => 'Large', 'S' => 'Small'],
                null,
                [],
                ['L' => ['data-foo' => 'bar', 'disabled']]
            )->toHtml()
        );
    }

    /** @test */
    public function it_can_make_select_inputs_with_optional_placeholder(): void
    {
        $list = ['null' => 'Select One...', 'L' => 'Large', 'S' => 'Small'];

        static::assertEquals(
            implode('', [
                '<select name="size">',
                    '<option value="null">Select One...</option>',
                    '<option value="L" selected>Large</option>',
                    '<option value="S">Small</option>',
                '</select>'
            ]),
            $this->form->select('size', $list, 'L')->toHtml()
        );
    }

    /** @test */
    public function it_can_make_select_year_inputs(): void
    {
        static::assertStringStartsWith(
            implode('', [
                '<select name="year">',
                    '<option value="2000">2000</option>',
                    '<option value="2001">2001</option>'
                    // To be continued...
            ]),
            $this->form->selectYear('year', 2000, 2020)->toHtml()
        );

        static::assertStringStartsWith(
            implode('', [
                '<select name="year" id="foo">',
                    '<option value="2000">2000</option>',
                    '<option value="2001">2001</option>'
                    // To be continued...
            ]),
            $this->form->selectYear('year', 2000, 2020, null, ['id' => 'foo'])->toHtml()
        );

        static::assertStringStartsWith(
            implode('', [
                '<select name="year">',
                    '<option value="2000" selected>2000</option>',
                    '<option value="2001">2001</option>'
                    // To be continued...
            ]),
            $this->form->selectYear('year', 2000, 2020, '2000')->toHtml()
        );
    }

    /** @test */
    public function it_can_make_select_range_inputs(): void
    {
        $range = $this->form->selectRange('dob', 1900, 2013, 2000)->toHtml();

        static::assertStringStartsWith('<select name="dob"><option value="1900">1900</option>', $range);
        static::assertStringContainsString('<option value="2000" selected>2000</option>', $range);
        static::assertStringContainsString('<option value="2013">2013</option>', $range);
    }

    /** @test */
    public function it_can_make_select_month_inputs(): void
    {
        static::assertStringStartsWith(
            implode('', [
                '<select name="month">',
                    '<option value="1">January</option>',
                    '<option value="2">February</option>'
                    // To be continued ...
            ]),
            $this->form->selectMonth('month')->toHtml()
        );

        static::assertStringStartsWith(
            implode('', [
                '<select name="month">',
                    '<option value="1" selected>January</option>',
                    '<option value="2">February</option>'
                    // To be continued ...
            ]),
            $this->form->selectMonth('month', '1')->toHtml()
        );

        static::assertStringStartsWith(
            implode('', [
                '<select name="month" id="foo">',
                    '<option value="1">January</option>',
                    '<option value="2">February</option>'
                    // To be continued ...
            ]),
            $this->form->selectMonth('month', null, ['id' => 'foo'])->toHtml()
        );
    }

    /** @test */
    public function it_can_make_select_options_inputs(): void
    {
        $list     = [
            'country-1' => [
                'city-1' => 'City 1',
                'city-2' => 'City 2',
            ],
            'country-2' => [
                'city-3' => 'City 3',
                'city-4' => 'City 4',
            ],
        ];

        static::assertEquals(
            '<select name="countries">'.
                '<optgroup label="country-1">'.
                    '<option value="city-1">City 1</option>'.
                    '<option value="city-2">City 2</option>'.
                '</optgroup>'.
                '<optgroup label="country-2">'.
                    '<option value="city-3">City 3</option>'.
                    '<option value="city-4">City 4</option>'.
                '</optgroup>'.
            '</select>',
            $this->form->select('countries', $list, null)
        );
    }

    /** @test */
    public function it_can_make_select_input_with_nested_options(): void
    {
        $list = [
            'Large sizes' => [
                'L'  => 'Large',
                'XL' => 'Extra Large',
            ],
            'S' => 'Small',
        ];

        static::assertEquals(
            '<select name="size" id="select-id" class="class-name">'.
                '<optgroup label="Large sizes">'.
                    '<option value="L">Large</option>'.
                    '<option value="XL">Extra Large</option>'.
                '</optgroup>'.
                '<option value="S">Small</option>'.
            '</select>',
            $this->form->select('size', $list, null, ['id' => 'select-id', 'class' => 'class-name'])->toHtml()
        );

        $list = [
            'Large sizes' => [
                'L'  => 'Large',
                'XL' => 'Extra Large',
            ],
            'M'           => 'Medium',
            'Small sizes' => [
                'S'  => 'Small',
                'XS' => 'Extra Small',
            ],
        ];
        $attributesAttributes = [
            'L' => ['disabled'],
            'M' => ['disabled'],
        ];
        $optgroupAttributes = [
            'Small sizes' => ['disabled'],
        ];

        static::assertEquals(
            '<select name="size">'.
                '<optgroup label="Large sizes">'.
                    '<option value="L" disabled>Large</option>'.
                    '<option value="XL">Extra Large</option>'.
                '</optgroup>'.
                '<option value="M" disabled>Medium</option>'.
                '<optgroup label="Small sizes" disabled>'.
                    '<option value="S">Small</option>'.
                    '<option value="XS">Extra Small</option>'.
                '</optgroup>'.
            '</select>',
            $this->form->select('size', $list, null, [], $attributesAttributes, $optgroupAttributes)->toHtml()
        );

        static::assertHtmlStringEqualsHtmlString(
            '<select name="encoded_html">'.
                '<option value="no_break_space">&nbsp;</option>'.
                '<option value="ampersand">&amp;</option>'.
                '<option value="lower_than">&lt;</option>'.
            '</select>',
            $this->form->select('encoded_html', ['no_break_space' => '&nbsp;', 'ampersand' => '&amp;', 'lower_than' => '&lt;'])->toHtml()
        );

        $list              = ['L' => 'Large', 'S' => 'Small'];
        $attributesAttributes = ['L' => ['data-foo' => 'bar', 'disabled']];

        static::assertEquals(
            '<select name="size">'.
                '<option value="L" data-foo="bar" disabled>Large</option>'.
                '<option value="S">Small</option>'.
            '</select>',
            $this->form->select('size', $list, null, [], $attributesAttributes)
        );

        $this->form->setSessionStore(
            tap(new Store('name', new SessionHandler), function (Store $store) {
                $store->put('_old_input', ['countries' => ['1']]);
            })
        );

        static::assertEquals(
            '<select name="countries"><option value="1" selected>L</option><option value="2">M</option></select>',
            $this->form->select('countries', [1 => 'L', 2 => 'M'])->toHtml()
        );

        static::assertEquals(
            '<select name="avc">'.
                '<option value="null">Select an option</option>'.
                '<option value="1" selected>Yes</option>'.
                '<option value="0">No</option>'.
            '</select>',
            $this->form->select('avc', ['null' => 'Select an option', 1 => 'Yes', 0 => 'No'], 1)->toHtml()
        );
    }

    /** @test */
    public function it_can_make_checkbox_inputs(): void
    {
        $session = $this->mockSession();

        $session->shouldReceive('getOldInput')->with('foo')->andReturn(null);
        $session->shouldReceive('getOldInput')->andReturn([]);

        static::assertEquals(
            '<input type="checkbox" name="foo">',
            $this->form->input('checkbox', 'foo')->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="foo" value="1">',
            $this->form->checkbox('foo')->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="foo" value="foobar" checked="checked">',
            $this->form->checkbox('foo', 'foobar', true)->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="foo" value="foobar" class="form-control">',
            $this->form->checkbox('foo', 'foobar', false, ['class' => 'form-control'])->toHtml()
        );
    }

    /** @test */
    public function it_can_make_populated_checkbox_inputs(): void
    {
        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')->with('check')->andReturn(null);
            $mock->shouldReceive('getOldInput')->with('check.key')->andReturn('yes');
            $mock->shouldReceive('getOldInput')->with('multicheck')->andReturn([1, 3]);
            $mock->shouldReceive('getOldInput')->andReturn([1]);
        });

        static::assertEquals(
            '<input type="checkbox" name="check" value="1">',
            $this->form->checkbox('check', 1, true)->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="check[key]" value="yes" checked="checked">',
            $this->form->checkbox('check[key]', 'yes')->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="multicheck[]" value="1" checked="checked">',
            $this->form->checkbox('multicheck[]', 1)->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="multicheck[]" value="2">',
            $this->form->checkbox('multicheck[]', 2, true)->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="multicheck[]" value="3" checked="checked">',
            $this->form->checkbox('multicheck[]', 3)->toHtml()
        );
    }

    /** @test */
    public function it_can_make_checkbox_inputs_without_session(): void
    {
        static::assertEquals(
            '<input type="checkbox" name="foo" value="1">',
            $this->form->checkbox('foo')->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="foo" value="foobar" checked="checked">',
            $this->form->checkbox('foo', 'foobar', true)->toHtml()
        );
    }

    /** @test */
    public function it_can_make_checkbox_with_model_relation(): void
    {
        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')->with('items')->andReturn(null);
            $mock->shouldReceive('getOldInput')->andReturn([]);
        });

        $models = [];

        foreach(range(2, 3) as $id) {
            $model     = new StdClass;
            $model->id = $id;
            $models[]  = $model;
        }

        $this->setModel(['items' => collect($models)]);

        static::assertEquals(
            '<input type="checkbox" name="items[]" value="1">',
            $this->form->checkbox('items[]', 1)->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="items[]" value="2" checked="checked">',
            $this->form->checkbox('items[]', 2)->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="items[]" value="3">',
            $this->form->checkbox('items[]', 3, false)->toHtml()
        );

        static::assertEquals(
            '<input type="checkbox" name="items[]" value="4" checked="checked">',
            $this->form->checkbox('items[]', 4, true)->toHtml()
        );
    }

    /** @test */
    public function it_can_test_the_checkable_method(): void
    {
        $reflector = new \ReflectionMethod(FormBuilder::class, 'checkable');
        $reflector->setAccessible(true);

        $result = $reflector->invokeArgs($this->form, ['checkable', 'checkable', 1, true, []]);

        static::assertEquals(
            '<input type="checkable" name="checkable" value="1">',
            $result->toHtml()
        );
    }

    /** @test */
    public function it_can_make_radio_inputs(): void
    {
        static::assertEquals(
            '<input type="radio" name="foo">',
            $this->form->input('radio', 'foo')->toHtml()
        );

        static::assertEquals(
            '<input type="radio" name="foo" value="foo">',
            $this->form->radio('foo')->toHtml()
        );

        static::assertEquals(
            '<input type="radio" name="foo" value="foobar" checked="checked">',
            $this->form->radio('foo', 'foobar', true)->toHtml()
        );

        static::assertEquals(
            '<input type="radio" name="foo" value="foobar" class="form-control">',
            $this->form->radio('foo', 'foobar', false, ['class' => 'form-control'])->toHtml()
        );
    }

    /** @test */
    public function it_can_make_populated_radio_inputs(): void
    {
        $this->mockSession(function (MockInterface $mock) {
            $mock->shouldReceive('getOldInput')
                ->with('radio')
                ->andReturn(1);
        });

        static::assertEquals(
            '<input type="radio" name="radio" value="1" checked="checked">',
            $this->form->radio('radio', 1)->toHtml()
        );

        static::assertEquals(
            '<input type="radio" name="radio" value="2">',
            $this->form->radio('radio', 2, true)->toHtml()
        );
    }

    /** @test */
    public function it_can_make_submit_inputs(): void
    {
        static::assertEquals(
            '<button type="submit">foo</button>',
            $this->form->submit('foo')->toHtml()
        );

        static::assertEquals(
            '<button type="submit" class="form-control">foo</button>',
            $this->form->submit('foo', ['class' => 'form-control'])->toHtml()
        );
    }

    /** @test */
    public function it_can_make_buttons(): void
    {
        static::assertEquals(
            '<button type="button">foo</button>',
            $this->form->button('foo')->toHtml()
        );

        static::assertEquals(
            '<button type="button" class="btn">foo</button>',
            $this->form->button('foo', ['class' => 'btn'])->toHtml()
        );

        static::assertEquals(
            '<button type="submit" class="btn">foo</button>',
            $this->form->button('foo', ['type' => 'submit', 'class' => 'btn'])->toHtml()
        );
    }

    /** @test */
    public function it_can_make_reset_inputs(): void
    {
        static::assertHtmlStringEqualsHtmlString(
            '<button type="reset">foo</button>',
            $this->form->reset('foo')->toHtml()
        );
    }

    /** @test */
    public function it_can_make_image_inputs(): void
    {
        $url = 'http://laravel.com/';

        static::assertHtmlStringEqualsHtmlString(
            '<input type="image" src="'. $url .'">',
            $this->form->image($url)->toHtml()
        );
    }

    /** @test */
    public function it_can_register_and_use_a_component(): void
    {
        $view       = '_components.text';
        $name       = 'first_name';
        $value      = null;
        $attributes = [];

        $this->form->component('bsText', $view, ['name', 'value', 'attributes']);

        static::assertEquals(
            view($view, compact('name', 'value', 'attributes'))->render(),
            $this->form->bsText($name, $value, $attributes)->toHtml()
        );
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * @return array
     */
    public function provideInputs(): array
    {
        return [
            [
                '<input type="text" name="foo">',
                'text',
                'foo',
                null,
                [],
            ],
            [
                '<input type="text" name="foo" value="foobar">',
                'text',
                'foo',
                'foobar',
                [],
            ],
            [
                '<input type="date" name="dob" class="form-control">',
                'date',
                'dob',
                null,
                ['class' => 'form-control'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function providePassword(): array
    {
        return [
            [
                '<input type="password" name="foo">',
                'foo',
                []
            ],[
                '<input type="password" name="foo" class="form-control">',
                'foo',
                ['class' => 'form-control']
            ]
        ];
    }

    /**
     * Provider text inputs data
     *
     * @return array
     */
    public function provideTextInputs(): array
    {
        return [
            [
                '<input type="text" name="foo">',
                'foo',
                null,
                []
            ],[
                '<input type="text" name="foo" value="foobar">',
                'foo',
                'foobar',
                []
            ],[
                '<input type="text" name="foo" class="form-control">',
                'foo',
                null,
                ['class' => 'form-control']
            ]
        ];
    }

    /**
     * @return array
     */
    public function provideTextArrayAndObjectModel(): array
    {
        return [
            [
                '<input type="text" name="user[password]" value="apple">',
                [
                    'user' => (object) [
                        'password' => 'apple'
                    ]
                ],
                'user[password]'
            ],[
                '<input type="text" name="letters[1]" value="b">',
                (object) [
                    'letters' => ['a', 'b', 'c']
                ],
                'letters[1]'
            ]
        ];
    }

    /**
     * @return array
     */
    public function provideHiddenData(): array
    {
        return $this->getInputData('hidden');
    }

    /**
     * @return array
     */
    public function provideEmailData(): array
    {
        return $this->getInputData('email');
    }

    /**
     * @return array
     */
    public function provideTelInputs(): array
    {
        return $this->getInputData('tel');
    }

    /**
     * @return array
     */
    public function provideNumberInputs(): array
    {
        return $this->getInputData('number', 1);
    }

    /**
     * @return array
     */
    public function provideDateInputs(): array
    {
        return $this->getInputData('date', '2015-02-20', [
            [
                '<input type="date" name="foo" value="'.now()->format('Y-m-d').'">',
                'foo',
                now(),
                []
            ]
        ]);
    }

    /**
     * Provide datetime inputs.
     *
     * @return array
     */
    public function provideDateTimeInputs(): array
    {
        date_default_timezone_set('UTC');

        return [
            [
                '<input type="datetime" name="datetime" value="2015-01-01T00:00:00+00:00">',
                'datetime', new \DateTime('2015-01-01'), [],
            ],[
                '<input type="datetime" name="datetime" value="2015-01-01T15:01:01+00:00">',
                'datetime', new \DateTime('2015-01-01 15:01:01'), [],
            ],[
                '<input type="datetime" name="datetime" value="2015-01-01 15:01:01">',
                'datetime', '2015-01-01 15:01:01', [],
            ],[
                '<input type="datetime" name="datetime" value="2015-01-01 12:00:00" class="datetime-picker">',
                'datetime', '2015-01-01 12:00:00', ['class' => 'datetime-picker'],
            ]
        ];
    }

    /**
     * Provide datetime locale inputs.
     *
     * @return array
     */
    public function provideDateTimeLocaleInputs(): array
    {
        return [
            [
                '<input type="datetime-local" name="datetime-locale" value="2015-01-01T00:00">',
                'datetime-locale', new \DateTime('2015-01-01'), [],
            ],
            [
                '<input type="datetime-local" name="datetime-local" value="2015-01-01T15:01">',
                'datetime-local', new \DateTime('2015-01-01 15:01:01'), [],
            ],
            [
                '<input type="datetime-local" name="datetime-local" value="2015-01-01 15:01:01">',
                'datetime-local', '2015-01-01 15:01:01', [],
            ],
            [
                '<input type="datetime-local" name="datetime-local" value="2015-01-01 12:00:00" class="datetime-local-picker">',
                'datetime-local', '2015-01-01 12:00:00', ['class' => 'datetime-local-picker'],
            ],
        ];
    }

    /**
     * Provide color inputs.
     *
     * @return array
     */
    public function provideColorInputs(): array
    {
        return [
            [
                '<input type="color" name="palette">',
                'palette', null, [],
            ],
            [
                '<input type="color" name="palette" value="#BADA55">',
                'palette', '#BADA55', [],
            ],
            [
                '<input type="color" name="palette" value="#BADA55" class="palette-class">',
                'palette', '#BADA55', ['class' => 'palette-class'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideTimeInputs(): array
    {
        return $this->getInputData('time', now()->format('H:i'));
    }

    /**
     * @return array
     */
    public function provideUrlInputs(): array
    {
        return [
            [
                '<input type="url" name="website">',
                'website',
                [],
            ],
            [
                '<input type="url" name="website" class="form-control">',
                'website',
                ['class' => 'form-control'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideFileInputs(): array
    {
        return [
            [
                '<input type="file" name="foo">',
                'foo',
                [],
            ],
            [
                '<input type="file" name="foo" class="form-control">',
                'foo',
                ['class' => 'form-control'],
            ],
        ];
    }

    /**
     * @return array
     */
    public function provideTextAreaInputs(): array
    {
        return [
            [
                '<textarea name="foo"></textarea>',
                'foo', null, []
            ],[
                '<textarea name="foo" cols="50" rows="10">foobar</textarea>',
                'foo', 'foobar', ['cols' => 50, 'rows' => 10]
            ],[
                '<textarea name="foo" class="form-control"></textarea>',
                'foo', null, ['class' => 'form-control']
            ],[
                '<textarea name="foo" cols="60" rows="15"></textarea>',
                'foo', null, ['size' => '60x15']
            ],[
                '<textarea name="encoded_html" cols="60" rows="60">Eggs &amp; Sausage</textarea>',
                'encoded_html', 'Eggs & Sausage', ['size' => '60x60']
            ],[
                '<textarea name="encoded_html" cols="60" rows="60">Eggs &amp;&amp; Sausage</textarea>',
                'encoded_html', 'Eggs &amp;&amp; Sausage', ['size' => '60x60']
            ],
        ];
    }

    /**
     * Provide form select data
     *
     * @return array
     */
    public function provideSelectData(): array
    {
        $list     = [
            'L' => 'Large',
            'S' => 'Small',
        ];
        $selected    = ['L', 'S'];
        $attributes  = [
            'name'     => 'sizes[]',
            'id'       => 'select-id',
            'multiple' => 'multiple',
            'class'    => 'class-name',
        ];

        return [
            [
                '<select name="size">'.
                '<option value="L">Large</option>'.
                '<option value="S">Small</option>'.
                '</select>',
                'size', $list, null, [],
            ],
            [
                '<select name="size">'.
                '<option value="L" selected>Large</option>'.
                '<option value="S">Small</option>'.
                '</select>',
                'size', $list, 'L', [],
            ],
            [
                '<select name="size" id="select-id" class="class-name">'.
                '<option value="L">Large</option>'.
                '<option value="S">Small</option>'.
                '</select>',
                'size', $list, null, ['id' => 'select-id', 'class' => 'class-name'],
            ],
            [
                '<select name="sizes[]" id="select-id" multiple="multiple" class="class-name">'.
                '<option value="L" selected>Large</option>'.
                '<option value="S" selected>Small</option>'.
                '</select>',
                'sizes', $list, $selected, $attributes,
            ],
            [
                // Test select with a option Collection
                '<select name="sizes[]" id="select-id" multiple="multiple" class="class-name">'.
                '<option value="L" selected>Large</option>'.
                '<option value="S" selected>Small</option>'.
                '</select>',
                'sizes', new Collection($list), $selected, $attributes,
            ],
            [
                // Test selects with a selected Collection
                '<select name="sizes[]" id="select-id" multiple="multiple" class="class-name">'.
                '<option value="L" selected>Large</option>'.
                '<option value="S" selected>Small</option>'.
                '</select>',
                'sizes', $list, new Collection($selected), $attributes
            ],
        ];
    }

    /**
     * Provide opening form data
     *
     * @return array
     */
    public function provideOpeningForms(): array
    {
        $url = $this->baseUrl . '/foo';

        return [
            [
                '<form method="GET" action="' . $url . '" accept-charset="UTF-8">',
                ['method' => 'GET']
            ],[
                '<form method="POST" action="' . $url . '" accept-charset="UTF-8" class="form" id="id-form">'.
                '<input type="hidden" name="_token" value="abc">',
                ['method' => 'POST', 'class' => 'form', 'id' => 'id-form']
            ],[
                '<form method="GET" action="' . $url . '" accept-charset="UTF-16">',
                ['method' => 'GET', 'accept-charset' => 'UTF-16']
            ],[
                '<form method="GET" action="' . $url . '" accept-charset="UTF-16" enctype="multipart/form-data">',
                ['method' => 'GET', 'accept-charset' => 'UTF-16', 'files' => true]
            ],[
                '<form method="POST" action="' . $url . '" accept-charset="UTF-8">'.
                '<input type="hidden" name="_method" value="PUT">'.
                '<input type="hidden" name="_token" value="abc">',
                ['method' => 'PUT']
            ]
        ];
    }

    /**
     * Provide labels data
     *
     * @return array
     */
    public function provideLabels(): array
    {
        return [
            [
                '<label for="foo">Foobar</label>',
                'foo',
                'Foobar',
                [],
            ],[
                '<label for="foo" class="control-label">Foobar</label>',
                'foo',
                'Foobar',
                ['class' => 'control-label'],
            ],[
                '<label for="foo">Foobar <i>bar</i></label>',
                'foo',
                'Foobar <i>bar</i>',
                [],
                false
            ],
        ];
    }

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    /**
     * Mock the session store.
     *
     * @param  \Closure|null  $mock
     *
     * @return \Mockery\MockInterface
     */
    protected function mockSession(Closure $mock = null): MockInterface
    {
        return tap($this->mock(Session::class, $mock), function ($session) {
            $this->form->setSessionStore($session);
        });
    }

    /**
     * Set model to form
     *
     * @param  array  $data
     * @param  bool   $object
     */
    protected function setModel(array $data, $object = true): void
    {
        $object = $object ? new FormBuilderModelStub($data) : $data;

        $this->form->model($object, ['method' => 'GET']);

        static::assertEquals($object, $this->form->getModel());
    }
}
