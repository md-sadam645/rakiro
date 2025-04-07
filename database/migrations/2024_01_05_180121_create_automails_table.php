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
        Schema::create('automails', function (Blueprint $table) {
            $table->id();
            $table->string("name")->unique();
            $table->string("from");
            $table->string("cus_category");
            $table->integer("days_from_last_invoice");
            $table->time("schedule_days");
            $table->integer("schedule_time");
            $table->mediumText("subject")->nullable();
            $table->mediumText("message");
            $table->string("attachment")->nullable();
            $table->integer("add_by");
            $table->date("last_executed_date")->nullable();
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
        Schema::dropIfExists('automails');
    }
};
