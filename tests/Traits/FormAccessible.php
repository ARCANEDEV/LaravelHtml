<?php namespace Arcanedev\LaravelHtml\Tests\Traits;

use Arcanedev\LaravelHtml\FormBuilder;
use Arcanedev\LaravelHtml\Tests\Stubs\ModelThatDoesntUseForms;
use Arcanedev\LaravelHtml\Tests\Stubs\ModelThatUsesForms;
use Arcanedev\LaravelHtml\Tests\TestCase;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class     FormAccessible
 *
 * @package  Arcanedev\LaravelHtml\Tests\Traits
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class FormAccessible extends TestCase
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    /**
     * The model data.
     *
     * @var array
     */
    protected $modelData = [];

    /**
     * The Carbon instance.
     *
     * @var \Carbon\Carbon
     */
    protected $now;

    /**
     * The Form builder instance.
     *
     * @var \Arcanedev\LaravelHtml\FormBuilder
     */
    protected $form;

    /* -----------------------------------------------------------------
     |  Main Methods
     | -----------------------------------------------------------------
     */

    public function setUp()
    {
        parent::setUp();

        $this->migrate();

        Model::unguard();

        $this->now = Carbon::now();
        $this->modelData = [
            'string'     => 'abcdefghijklmnop',
            'email'      => 'tj@tjshafer.com',
            'address'    => [
                'street' => 'abcde st'
            ],
            'created_at' => $this->now,
            'updated_at' => $this->now,
        ];

        /** @var \Illuminate\Contracts\Session\Session  $session */
        $session = $this->app['session.store'];
        $session->put('_token', 'abc');

        $this->form = new FormBuilder($this->html, $this->urlGenerator, $session);
    }

    /* -----------------------------------------------------------------
     |  Tests
     | -----------------------------------------------------------------
     */

    /** @test */
    public function it_can_mutate_values_for_forms()
    {
        $model = new ModelThatUsesForms($this->modelData);
        $this->form->setModel($model);

        static::assertSame('ponmlkjihgfedcba', $model->getFormValue('string'));
        static::assertSame($this->now->timestamp, $model->getFormValue('created_at'));
    }

    /** @test */
    public function it_can_still_mutate_values_for_views()
    {
        $model = new ModelThatUsesForms($this->modelData);
        $this->form->setModel($model);

        static::assertSame('ABCDEFGHIJKLMNOP', $model->string);
        static::assertSame('1 second ago', $model->created_at);
    }

    /** @test */
    public function it_does_not_require_the_use_of_this_feature()
    {
        $model = new ModelThatDoesntUseForms($this->modelData);
        $this->form->setModel($model);

        static::assertSame('ABCDEFGHIJKLMNOP', $model->string);
        static::assertSame('1 second ago', $model->created_at);
    }

    /** @test */
    public function it_can_get_related_value_for_forms()
    {
        $model = new ModelThatUsesForms($this->modelData);
        $this->form->setModel($model);

        static::assertSame('abcde st', $model->getFormValue('address.street'));
    }

    /** @test */
    public function it_can_mutate_related_values_for_forms()
    {
        $model = new ModelThatUsesForms($this->modelData);
        $model->setRelation(
            'related',
            new ModelThatUsesForms($this->modelData)
        );
        $this->form->setModel($model);

        static::assertSame($this->form->getValueAttribute('related[string]'), 'ponmlkjihgfedcba');
        static::assertSame($this->form->getValueAttribute('related[created_at]'), $this->now->timestamp);
    }
}
