<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Tests\Stubs;

use Illuminate\Database\Eloquent\Model;

/**
 * Class     ModelThatDoesntUseForms
 *
 * @package  Arcanedev\LaravelHtml\Tests\Stubs
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModelThatDoesntUseForms extends Model
{
    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected $table = 'models';

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    public function getStringAttribute($value)
    {
        return strtoupper($value);
    }

    public function getCreatedAtAttribute($value)
    {
        return '1 second ago';
    }
}
