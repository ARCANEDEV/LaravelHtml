<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Class     CreateUsersTable
 *
 * @author   ARCANEDEV <arcanedev.maroc@gmail.com>
 */
class CreateModelsTable extends Migration
{
    public function up(): void
    {
        Schema::create('models', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('string');
            $table->string('email');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('models');
    }
}
