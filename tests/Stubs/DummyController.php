<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Tests\Stubs;

use Illuminate\Routing\Controller;

/**
 * Class     DummyController
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class DummyController extends Controller
{
    public function index()
    {
        return 'Home page';
    }
}
