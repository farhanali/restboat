<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function(Blueprint $table) {
            // auto incrementing id
            $table->increments('id');

            $table->string('name');
            $table->string('email')->unique()->nullable();
            $table->string('avatar');
            $table->string('provider');
            $table->string('provider_id')->unique();

            // remember token
            $table->rememberToken();

            // timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('users');
    }
}
