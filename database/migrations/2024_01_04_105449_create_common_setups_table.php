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
        Schema::create('common_setups', function (Blueprint $table) {
            $table->id();
            $table->string("mailer");
            $table->string("host");
            $table->integer("port");
            $table->string("username");
            $table->string("password");
            $table->string("encryption");
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
        Schema::dropIfExists('common_setups');
    }
};
