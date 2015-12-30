<?php

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
    public function up()
    {
        Schema::create('models', function (Blueprint $table) {
            $table->increments('id');
            $table->string('string');
            $table->string('email');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop('models');
    }
}
