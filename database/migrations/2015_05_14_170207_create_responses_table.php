<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function(Blueprint $table) {
            // auto incrementing id
            $table->increments('id');

            $table->integer('request_id')->unsigned();
            $table->integer('status');
            $table->string('content_type');
            $table->longText('content');

            // timestamps
            $table->timestamps();

            // indexes
            $table->foreign('request_id')->references('id')->on('requests')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('responses');
    }
}
