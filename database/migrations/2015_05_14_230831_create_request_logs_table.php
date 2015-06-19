<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRequestLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_logs', function(Blueprint $table) {
            // auto incrementing id
            $table->increments('id');

            $table->integer('request_id')->unsigned();
            $table->string('method');
            $table->text('query_string')->nullable();
            $table->text('headers')->nullable();
            $table->string('content_type')->nullable();
            $table->longText('content')->nullable();

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
        Schema::drop('request_logs');
    }
}
