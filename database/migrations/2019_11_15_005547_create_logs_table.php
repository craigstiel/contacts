<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('logs', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('updated_contact')->nullable(true);
            $table->string('updated_contact_name')->nullable(true);
            $table->integer('updated_by')->unsigned();
            $table->integer('changes')->unsigned();
            $table->string('act')->nullable(true);
            $table->string('response')->nullable(true);
            $table->timestamps();

            $table->foreign('updated_by')->references('id')->on('users');
            $table->foreign('changes')->references('id')->on('actions');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('logs');
    }
}
