<?php

declare(strict_types=1);

namespace Arcanedev\LaravelHtml\Tests\Stubs;

use Arcanedev\LaravelHtml\Traits\FormAccessible;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

/**
 * Class     ModelThatUsesForms
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class ModelThatUsesForms extends Model
{
    /* -----------------------------------------------------------------
     |  Traits
     | -----------------------------------------------------------------
     */

    use FormAccessible;

    /* -----------------------------------------------------------------
     |  Properties
     | -----------------------------------------------------------------
     */

    protected $table = 'models';

    /* -----------------------------------------------------------------
     |  Other Methods
     | -----------------------------------------------------------------
     */

    public function formStringAttribute($value)
    {
        return strrev($value);
    }

    public function getStringAttribute($value)
    {
        return strtoupper($value);
    }

    public function formCreatedAtAttribute(Carbon $value)
    {
        return $value->timestamp;
    }

    public function getCreatedAtAttribute($value)
    {
        return '1 second ago';
    }
}
