<?php namespace Arcanedev\LaravelHtml\Tests;

use Arcanedev\LaravelHtml\FormBuilder;
use Arcanedev\LaravelHtml\Tests\Stubs\FormBuilderModelStub;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use StdClass;

/**
 * Class     FormBuilderTest
 *
 * @package  Arcanedev\LaravelHtml\Tests\Builders
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormBuilderTest extends TestCase
{
    /* ------------------------------------------------------------------------------------------------
     |  Properties
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @var FormBuilder
     */
    private $form;

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

        $this->form  = new FormBuilder(
            $this->htmlBuilder,
            $this->urlGenerator,
            'abc'
        );
    }

    /**
     * Destroy the test environment.
     */
    public function tearDown()
    {
        parent::tearDown();
    }

    /* ------------------------------------------------------------------------------------------------
     |  Test Functions
     | ------------------------------------------------------------------------------------------------
     */
    /** @test */
    public function it_can_be_instantiated()
    {
        $this->assertInstanceOf(FormBuilder::class, $this->form);
    }

    /** @test */
    public function it_can_set_and_get_session()
    {
        $this->assertEquals(
            $this->mockSession()->reveal(),
            $this->form->getSessionStore()
        );
    }

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
        $this->assertEquals($expected, $this->form->open($options));
    }

    /** @test */
    public function it_can_open_form_with_different_actions()
    {
        $expected = implode('', [
            '<form method="POST" action="http://localhost" accept-charset="UTF-8">',
            '<input name="_token" type="hidden" value="abc">',
        ]);

        $options = [
            ['route'  => 'home'],
            ['action' => 'Arcanedev\LaravelHtml\Tests\Stubs\DummyController@index'],
        ];

        foreach ($options as $option) {
            $this->assertEquals($expected, $this->form->open($option));
        }
    }

    /** @test */
    public function it_can_close_form()
    {
        $this->assertEquals('</form>', $this->form->close());
    }

    /**
     * @test
     *
     * @dataProvider provideLabels
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  string  $value
     * @param  array   $options
     */
    public function it_can_make_label($expected, $name, $value, $options)
    {
        $this->assertEquals(
            $expected,
            $this->form->label($name, $value, $options)
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
     * @param  array   $options
     */
    public function it_can_make_form_inputs($expected, $type, $name, $value, $options)
    {
        $this->assertEquals(
            $expected,
            $this->form->input($type, $name, $value, $options)
        );
    }

    /**
     * @return array
     */
    public function provideInputs()
    {
        return [
            [
                '<input name="foo" type="text">',
                'text',
                'foo',
                null,
                []
            ],[
                '<input name="foo" type="text" value="foobar">',
                'text',
                'foo',
                'foobar',
                []
            ],[
                '<input class="form-control" name="dob" type="date">',
                'date',
                'dob',
                null,
                ['class' => 'form-control']
            ]
        ];
    }

    /**
     * @test
     *
     * @dataProvider providePassword
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  array   $options
     */
    public function it_can_make_password_inputs($expected, $name, $options)
    {
        $this->assertEquals($expected, $this->form->password($name, $options));
    }

    /**
     * @return array
     */
    public function providePassword()
    {
        return [
            [
                '<input name="foo" type="password" value="">',
                'foo',
                []
            ],[
                '<input class="form-control" name="foo" type="password" value="">',
                'foo',
                ['class' => 'form-control']
            ]
        ];
    }

    /** @test */
    public function it_can_make_not_filled_passwords()
    {
        $session = $this->mockSession();
        $session->getOldInput()->shouldNotBeCalled();

        $this->assertEquals(
            '<input name="password" type="password" value="">',
            $this->form->password('password')
        );
    }

    /** @test */
    public function it_can_make_not_filled_files()
    {
        $session = $this->mockSession();
        $session->getOldInput()->shouldNotBeCalled();

        $this->assertEquals(
            '<input name="img" type="file">',
            $this->form->file('img')
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
     * @param  array   $options
     */
    public function it_can_make_text_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals(
            $expected,
            $this->form->text($name, $value, $options)
        );
    }

    /**
     * Provider text inputs data
     *
     * @return array
     */
    public function provideTextInputs()
    {
        return [
            [
                '<input name="foo" type="text">',
                'foo',
                null,
                []
            ],[
                '<input name="foo" type="text" value="foobar">',
                'foo',
                'foobar',
                []
            ],[
                '<input class="form-control" name="foo" type="text">',
                'foo',
                null,
                ['class' => 'form-control']
            ]
        ];
    }

    /** @test */
    public function it_can_make_populated_text_inputs()
    {
        $session = $this->mockSession();
        $session->getOldInput('name_with_dots')
            ->shouldBeCalledTimes(2)
            ->willReturn('some value');

        $this->setModel($model = [
            'relation'  => [
                'key'   => 'attribute'
            ],
            'other'     => 'val'
        ]);

        $this->assertEquals(
            '<input name="name.with.dots" type="text" value="some value">',
            $this->form->text('name.with.dots', 'default value')
        );

        $session->getOldInput('text.key.sub')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->assertEquals(
            '<input name="text[key][sub]" type="text" value="default value">',
            $this->form->text('text[key][sub]', 'default value')
        );

        $session->getOldInput('relation.key')->willReturn(null);

        $textInput = $this->form->text('relation[key]');

        $this->assertEquals(
            '<input name="relation[key]" type="text" value="attribute">',
            $textInput
        );

        $this->setModel($model, false);
        $this->assertEquals($textInput, $this->form->text('relation[key]'));
    }

    /**
     * @test
     *
     * @dataProvider provideTextArrayAndObjectModel
     */
    public function it_can_make_populated_text_inputs_with_mix_of_arrays_and_objects($expected, $model, $name)
    {
        $this->form->model($model);

        $this->assertEquals($expected, $this->form->text($name));
    }

    /**
     * @return array
     */
    public function provideTextArrayAndObjectModel()
    {
        return [
            [
                '<input name="user[password]" type="text" value="apple">',
                [
                    'user' => (object) [
                        'password' => 'apple'
                    ]
                ],
                'user[password]'
            ],[
                '<input name="letters[1]" type="text" value="b">',
                (object) [
                    'letters' => ['a', 'b', 'c']
                ],
                'letters[1]'
            ]
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideHiddenData
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $options
     */
    public function it_can_make_hidden_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals($expected, $this->form->hidden($name, $value, $options));
    }

    /**
     * @return array
     */
    public function provideHiddenData()
    {
        return $this->getInputData('hidden');
    }

    /**
     * @test
     *
     * @dataProvider provideEmailData
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $options
     */
    public function it_can_make_email_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals($expected, $this->form->email($name, $value, $options));

        $this->assertEquals(
            '<input class="span2" name="foo" type="email">',
            $this->form->email('foo', null, ['class' => 'span2'])
        );
    }

    /**
     * @return array
     */
    public function provideEmailData()
    {
        return $this->getInputData('email');
    }

    /**
     * @test
     *
     * @dataProvider provideTelInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $options
     */
    public function it_can_make_tel_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals($expected, $this->form->tel($name, $value, $options));
    }

    /**
     * @return array
     */
    public function provideTelInputs()
    {
        return $this->getInputData('tel');
    }

    /**
     * @test
     *
     * @dataProvider provideNumberInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $options
     */
    public function it_can_make_number_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals($expected, $this->form->number($name, $value, $options));
    }

    /**
     * @return array
     */
    public function provideNumberInputs()
    {
        return $this->getInputData('number', 1);
    }

    /**
     * @test
     *
     * @dataProvider provideDateInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $options
     */
    public function it_can_make_date_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals($expected, $this->form->date($name, $value, $options));
    }

    /**
     * @return array
     */
    public function provideDateInputs()
    {
        return $this->getInputData('date', '2015-02-20', [
            [
                '<input name="foo" type="date" value="' . Carbon::now()->format('Y-m-d') . '">',
                'foo',
                Carbon::now(),
                []
            ]
        ]);
    }

    /**
     * @test
     *
     * @dataProvider provideTimeInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $options
     */
    public function it_can_make_time_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals($expected, $this->form->time($name, $value, $options));
    }

    /**
     * @return array
     */
    public function provideTimeInputs()
    {
        return $this->getInputData('time', Carbon::now()->format('H:i'));
    }

    /**
     * @test
     *
     * @dataProvider provideUrlInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  array   $options
     */
    public function it_can_make_url_inputs($expected, $name, $options)
    {
        $this->assertEquals($expected, $this->form->url($name, null, $options));
    }

    /**
     * @return array
     */
    public function provideUrlInputs()
    {
        return [
            [
                '<input name="website" type="url">',
                'website',
                []
            ],[
                '<input class="form-control" name="website" type="url">',
                'website',
                ['class' => 'form-control']
            ]
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideFileInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  array   $options
     */
    public function it_can_make_files_inputs($expected, $name, $options)
    {
        $this->assertEquals($expected, $this->form->file($name, $options));
    }

    /**
     * @return array
     */
    public function provideFileInputs()
    {
        return [
            [
                '<input name="foo" type="file">',
                'foo',
                []
            ],[
                '<input class="form-control" name="foo" type="file">',
                'foo',
                ['class' => 'form-control']
            ]
        ];
    }

    /**
     * @test
     *
     * @dataProvider provideTextAreaInputs
     *
     * @param  string  $expected
     * @param  string  $name
     * @param  mixed   $value
     * @param  array   $options
     */
    public function it_can_make_textarea_inputs($expected, $name, $value, $options)
    {
        $this->assertEquals($expected, $this->form->textarea($name, $value, $options));
    }

    /**
     * @return array
     */
    public function provideTextAreaInputs()
    {
        return [
            [
                '<textarea name="foo" cols="50" rows="10"></textarea>',
                'foo', null, []
            ],[
                '<textarea name="foo" cols="50" rows="10">foobar</textarea>',
                'foo', 'foobar', []
            ],[
                '<textarea class="form-control" name="foo" cols="50" rows="10"></textarea>',
                'foo', null, ['class' => 'form-control']
            ],[
                '<textarea name="foo" cols="60" rows="15"></textarea>',
                'foo', null, ['size' => '60x15']
            ]
        ];
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
     * @param  array   $options
     */
    public function it_can_make_select_inputs($expected, $name, $list, $selected, $options)
    {
        $this->assertEquals(
            $expected,
            $this->form->select($name, $list, $selected, $options)
        );
    }

    /**
     * Provide form select data
     *
     * @return array
     */
    public function provideSelectData()
    {
        $list     = [
            'L' => 'Large',
            'S' => 'Small',
        ];
        $selected = ['L', 'S',];
        $options  = [
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
                'size', $list, null, []
            ],[
                '<select name="size">'.
                    '<option value="L" selected="selected">Large</option>'.
                    '<option value="S">Small</option>'.
                '</select>',
                'size', $list, 'L', []
            ],[
                '<select id="select-id" class="class-name" name="size">'.
                    '<option value="L">Large</option>'.
                    '<option value="S">Small</option>'.
                '</select>',
                'size', $list, null, ['id' => 'select-id', 'class' => 'class-name']
            ],[
                '<select name="sizes[]" id="select-id" multiple="multiple" class="class-name">'.
                    '<option value="L" selected="selected">Large</option>'.
                    '<option value="S" selected="selected">Small</option>'.
                '</select>',
                'sizes', $list, $selected, $options
            ],[
                // Test select with a option Collection
                '<select name="sizes[]" id="select-id" multiple="multiple" class="class-name">'.
                    '<option value="L" selected="selected">Large</option>'.
                    '<option value="S" selected="selected">Small</option>'.
                '</select>',
                'sizes', new Collection($list), $selected, $options
            ],[
                // Test selects with a selected Collection
                '<select name="sizes[]" id="select-id" multiple="multiple" class="class-name">'.
                    '<option value="L" selected="selected">Large</option>'.
                    '<option value="S" selected="selected">Small</option>'.
                '</select>',
                'sizes', $list, new Collection($selected), $options
            ]
        ];
    }

    /** @test */
    public function it_can_make_select_input_with_label()
    {
        $this->assertEquals(
            '<label for="select-name-id">Select-name-id</label>',
            $this->form->label('select-name-id')
        );

        $this->assertEquals(
            '<select name="select-name" id="select-name-id"></select>',
            $this->form->select(
                'select-name-id',
                [],
                null,
                ['name' => 'select-name']
            )
        );
    }

    /** @test */
    public function it_can_make_populated_select_inputs()
    {
        $list  = [
            'L' => 'Large',
            'M' => 'Medium',
            'S' => 'Small'
        ];
        $model = [
            'size'  => ['key' => 'S'],
            'other' => 'val'
        ];

        $this->setModel($model);
        $session = $this->mockSession();

        $session->getOldInput('size')
            ->shouldBeCalledTimes(2)
            ->willReturn('M');

        $this->assertEquals(
            implode('', [
                '<select name="size">',
                    '<option value="L">Large</option>',
                    '<option value="M" selected="selected">Medium</option>',
                    '<option value="S">Small</option>',
                '</select>',
            ]),
            $this->form->select('size', $list, 'S')
        );

        $session->getOldInput('size.multi')
            ->shouldBeCalledTimes(2)
            ->willReturn(['L', 'S']);

        $this->assertEquals(
            implode('', [
                '<select multiple="multiple" name="size[multi][]">',
                    '<option value="L" selected="selected">Large</option>',
                    '<option value="M">Medium</option>',
                    '<option value="S" selected="selected">Small</option>',
                '</select>'
            ]),
            $this->form->select('size[multi][]', $list, 'M', ['multiple' => 'multiple'])
        );

        $session->getOldInput('size.key')
            ->shouldBeCalled()
            ->willReturn(null);

        $this->assertEquals(
            implode('', [
                '<select name="size[key]">',
                    '<option value="L">Large</option>',
                    '<option value="M">Medium</option>',
                    '<option value="S" selected="selected">Small</option>',
                '</select>',
            ]),
            $this->form->select('size[key]', $list)
        );
    }

    /** @test */
    public function it_can_make_select_inputs_with_optional_placeholder()
    {
        $list    = ['L' => 'Large', 'S' => 'Small'];
        $options = ['placeholder' => 'Select One...'];

        $this->assertEquals(
            implode('', [
                '<select name="size">',
                    '<option selected="selected" value="">Select One...</option>',
                    '<option value="L">Large</option>',
                    '<option value="S">Small</option>',
                '</select>'
            ]),
            $this->form->select('size', $list, null, $options)
        );

        $this->assertEquals(
            implode('', [
                '<select name="size">',
                '<option value="">Select One...</option>',
                '<option value="L" selected="selected">Large</option>',
                '<option value="S">Small</option>',
                '</select>'
            ]),
            $this->form->select('size', $list, 'L', $options)
        );
    }

    /** @test */
    public function it_can_make_select_year_inputs()
    {
        $this->assertStringStartsWith(
            implode('', [
                '<select name="year">',
                    '<option value="2000">2000</option>',
                    '<option value="2001">2001</option>'
                    // To be continued...
            ]),
            $this->form->selectYear('year', 2000, 2020)
        );

        $this->assertStringStartsWith(
            implode('', [
                '<select id="foo" name="year">',
                    '<option value="2000">2000</option>',
                    '<option value="2001">2001</option>'
                    // To be continued...
            ]),
            $this->form->selectYear('year', 2000, 2020, null, ['id' => 'foo'])
        );

        $this->assertStringStartsWith(
            implode('', [
                '<select name="year">',
                    '<option value="2000" selected="selected">2000</option>',
                    '<option value="2001">2001</option>'
                    // To be continued...
            ]),
            $this->form->selectYear('year', 2000, 2020, '2000')
        );
    }

    /** @test */
    public function it_can_make_select_range_inputs()
    {
        $range = $this->form->selectRange('dob', 1900, 2013, 2000);

        $this->assertStringStartsWith('<select name="dob"><option value="1900">1900</option>', $range);
        $this->assertContains('<option value="2000" selected="selected">2000</option>', $range);
        $this->assertContains('<option value="2013">2013</option>', $range);
    }

    /** @test */
    public function it_can_make_select_month_inputs()
    {
        $this->assertStringStartsWith(
            implode('', [
                '<select name="month">',
                    '<option value="1">January</option>',
                    '<option value="2">February</option>'
                    // To be continued ...
            ]),
            $this->form->selectMonth('month')
        );

        $this->assertStringStartsWith(
            implode('', [
                '<select name="month">',
                    '<option value="1" selected="selected">January</option>',
                    '<option value="2">February</option>'
                    // To be continued ...
            ]),
            $this->form->selectMonth('month', '1')
        );

        $this->assertStringStartsWith(
            implode('', [
                '<select id="foo" name="month">',
                    '<option value="1">January</option>',
                    '<option value="2">February</option>'
                    // To be continued ...
            ]),
            $this->form->selectMonth('month', null, ['id' => 'foo'])
        );
    }

    /** @test */
    public function it_can_make_select_options_inputs()
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
        $this->assertEquals(implode('', [
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
        ]), $this->form->select('countries', $list, null));
    }

    /** @test */
    public function it_can_make_checkbox_inputs()
    {
        $session = $this->mockSession();

        $session->getOldInput()->willReturn([]);
        $session->getOldInput('foo')->willReturn(null);

        $this->assertEquals(
            '<input name="foo" type="checkbox">',
            $this->form->input('checkbox', 'foo')
        );

        $this->assertEquals(
            '<input name="foo" type="checkbox" value="1">',
            $this->form->checkbox('foo')
        );

        $this->assertEquals(
            '<input checked="checked" name="foo" type="checkbox" value="foobar">',
            $this->form->checkbox('foo', 'foobar', true)
        );

        $this->assertEquals(
            '<input class="span2" name="foo" type="checkbox" value="foobar">',
            $this->form->checkbox('foo', 'foobar', false, ['class' => 'span2'])
        );
    }

    /** @test */
    public function it_can_make_populated_checkbox_inputs()
    {
        $session = $this->mockSession();

        $session->getOldInput()->willReturn([1]);
        $session->getOldInput('check')->willReturn(null);

        $this->assertEquals(
            '<input name="check" type="checkbox" value="1">',
            $this->form->checkbox('check', 1, true)
        );

        $session->getOldInput('check.key')->willReturn('yes');

        $this->assertEquals(
            '<input checked="checked" name="check[key]" type="checkbox" value="yes">',
            $this->form->checkbox('check[key]', 'yes')
        );

        $session->getOldInput('multicheck')->willReturn([1, 3]);

        $this->assertEquals(
            '<input checked="checked" name="multicheck[]" type="checkbox" value="1">',
            $this->form->checkbox('multicheck[]', 1)
        );

        $this->assertEquals(
            '<input name="multicheck[]" type="checkbox" value="2">',
            $this->form->checkbox('multicheck[]', 2, true)
        );

        $this->assertEquals(
            '<input checked="checked" name="multicheck[]" type="checkbox" value="3">',
            $this->form->checkbox('multicheck[]', 3)
        );
    }

    /** @test */
    public function it_can_make_checkbox_inputs_without_session()
    {
        $this->assertEquals(
            '<input name="foo" type="checkbox" value="1">',
            $this->form->checkbox('foo')
        );

        $this->assertEquals(
            '<input checked="checked" name="foo" type="checkbox" value="foobar">',
            $this->form->checkbox('foo', 'foobar', true)
        );
    }

    /** @test */
    public function it_can_make_checkbox_with_model_relation()
    {
        $session = $this->mockSession();
        $session->getOldInput()->willReturn([]);
        $session->getOldInput('items')->willReturn(null);

        $models = [];

        foreach(range(2, 3) as $id) {
            $model     = new StdClass;
            $model->id = $id;
            $models[]  = $model;
        }

        $this->setModel([
            'items' => new Collection($models)
        ]);

        $this->assertEquals(
            '<input name="items[]" type="checkbox" value="1">',
            $this->form->checkbox('items[]', 1)
        );

        $this->assertEquals(
            '<input checked="checked" name="items[]" type="checkbox" value="2">',
            $this->form->checkbox('items[]', 2)
        );

        $this->assertEquals(
            '<input name="items[]" type="checkbox" value="3">',
            $this->form->checkbox('items[]', 3, false)
        );

        $this->assertEquals(
            '<input checked="checked" name="items[]" type="checkbox" value="4">',
            $this->form->checkbox('items[]', 4, true)
        );
    }

    /** @test */
    public function it_can_test_the_checkable_method()
    {
        $reflector = new \ReflectionMethod(FormBuilder::class, 'checkable');
        $reflector->setAccessible(true);

        $result = $reflector->invokeArgs($this->form, ['checkable', 'checkable', 1, true, []]);
        $this->assertEquals(
            '<input name="checkable" type="checkable" value="1">',
            $result
        );
    }


    /** @test */
    public function it_can_make_radio_inputs()
    {
        $this->assertEquals(
            '<input name="foo" type="radio">',
            $this->form->input('radio', 'foo')
        );

        $this->assertEquals(
            '<input name="foo" type="radio" value="foo">',
            $this->form->radio('foo')
        );

        $this->assertEquals(
            '<input checked="checked" name="foo" type="radio" value="foobar">',
            $this->form->radio('foo', 'foobar', true)
        );

        $this->assertEquals(
            '<input class="span2" name="foo" type="radio" value="foobar">',
            $this->form->radio('foo', 'foobar', false, ['class' => 'span2'])
        );
    }

    /** @test */
    public function it_can_make_populated_radio_inputs()
    {
        $session = $this->mockSession();
        $session->getOldInput('radio')
            ->willReturn(1);

        $this->assertEquals(
            '<input checked="checked" name="radio" type="radio" value="1">',
            $this->form->radio('radio', 1)
        );

        $this->assertEquals(
            '<input name="radio" type="radio" value="2">',
            $this->form->radio('radio', 2, true)
        );
    }

    /** @test */
    public function it_can_make_submit_inputs()
    {
        $this->assertEquals(
            '<input type="submit" value="foo">',
            $this->form->submit('foo')
        );

        $this->assertEquals(
            '<input class="span2" type="submit" value="foo">',
            $this->form->submit('foo', ['class' => 'span2'])
        );
    }

    /** @test */
    public function it_can_make_buttons()
    {
        $this->assertEquals(
            '<button type="button">foo</button>',
            $this->form->button('foo')
        );

        $this->assertEquals(
            '<button class="btn" type="button">foo</button>',
            $this->form->button('foo', ['class' => 'btn'])
        );

        $this->assertEquals(
            '<button class="btn" type="submit">foo</button>',
            $this->form->button('foo', ['class' => 'btn', 'type' => 'submit'])
        );
    }

    /** @test */
    public function it_can_make_reset_inputs()
    {
        $this->assertEquals(
            '<input type="reset" value="foo">',
            $this->form->reset('foo')
        );
    }

    /** @test */
    public function it_can_make_image_inputs()
    {
        $url   = 'http://laravel.com/';

        $this->assertEquals(
            '<input src="'. $url .'" type="image">',
            $this->form->image($url)
        );
    }

    /* ------------------------------------------------------------------------------------------------
     |  Provider Functions
     | ------------------------------------------------------------------------------------------------
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
                ['method' => 'GET']
            ],[
                '<form method="POST" action="' . $url . '" accept-charset="UTF-8" class="form" id="id-form">'.
                '<input name="_token" type="hidden" value="abc">',
                ['method' => 'POST', 'class' => 'form', 'id' => 'id-form']
            ],[
                '<form method="GET" action="' . $url . '" accept-charset="UTF-16">',
                ['method' => 'GET', 'accept-charset' => 'UTF-16']
            ],[
                '<form method="GET" action="' . $url . '" accept-charset="UTF-16" enctype="multipart/form-data">',
                ['method' => 'GET', 'accept-charset' => 'UTF-16', 'files' => true]
            ],[
                '<form method="POST" action="' . $url . '" accept-charset="UTF-8"><input name="_method" type="hidden" value="PUT">'.
                '<input name="_token" type="hidden" value="abc">',
                ['method' => 'PUT']
            ]
        ];
    }

    /**
     * Provide labels data
     *
     * @return array
     */
    public function provideLabels()
    {
        return [
            [
                '<label for="foo">Foobar</label>',
                'foo',
                'Foobar',
                []
            ],[
                '<label for="foo" class="control-label">Foobar</label>',
                'foo',
                'Foobar',
                ['class' => 'control-label']
            ]
        ];
    }

    /* ------------------------------------------------------------------------------------------------
     |  Other Functions
     | ------------------------------------------------------------------------------------------------
     */
    /**
     * @return \Prophecy\Prophecy\ObjectProphecy
     */
    protected function mockSession()
    {
        /** @var \Prophecy\Prophecy\ObjectProphecy $session */
        $session = $this->prophesize(\Illuminate\Session\Store::class);
        $this->form->setSessionStore($session->reveal());

        return $session;
    }

    /**
     * Set model to form
     *
     * @param  array  $data
     * @param  bool   $object
     */
    protected function setModel(array $data, $object = true)
    {
        if ($object) {
            $data = new FormBuilderModelStub($data);
        }

        $this->form->model($data, ['method' => 'GET']);
    }
}
