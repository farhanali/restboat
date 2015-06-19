<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserPreferencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_preferences', function(Blueprint $table) {
            // auto incrementing id
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            // Used for identifying from which user the mock requests are coming
            $table->string('user_identifier')->unique()->nullable();
            $table->string('timezone')->default(config('app.timezone'));
            $table->integer('request_limit')->default(30);
            $table->integer('request_log_limit')->default(30);

            $table->integer('default_response_status')->default(200);
            $table->string('default_response_type')->default('application/json');
            $table->string('default_response_content', 2000)
                ->default("{\"response_code\":200, \"response_message\":\"Success\"}");

            $table->boolean('boat_token_enable')->default(false);
            $table->string('boat_token')->nullable();

            // timestamps
            $table->timestamps();

            // indexes
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_preferences');
    }
}
