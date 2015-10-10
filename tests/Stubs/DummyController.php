<?php namespace Arcanedev\LaravelHtml\Tests\Stubs;

use Illuminate\Routing\Controller;

/**
 * Class     DummyController
 *
 * @package  Arcanedev\LaravelHtml\Tests\Stubs
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DummyController extends Controller
{
    public function index()
    {
        return 'Home page';
    }
}
