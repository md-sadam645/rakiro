<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_composes', function (Blueprint $table) {
            $table->id();
            $table->string("from");
            $table->string("to");
            $table->mediumText("to_group_name")->nullable();
            $table->mediumText("cc")->nullable();
            $table->mediumText("subject")->nullable();
            $table->mediumText("message");
            $table->string("attachment")->nullable();
            $table->dateTime("schedule_time");
            $table->integer("add_by");
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
        Schema::dropIfExists('email_composes');
    }
};
