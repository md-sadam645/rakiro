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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable()->unique();
            $table->string('mob_prefix')->comment('country code');
            $table->string('country_name')->comment('country name');
            $table->string('mobile');
            $table->timestamp('email_verified_at')->nullable();
            $table->integer('role')->nullable()->comment('1=admin;2=subadmin;');
            $table->string('password');
            $table->string('photo')->nullable();
            $table->string('avatar')->nullable();
            $table->string('refcode')->nullable();
            $table->string('refby')->nullable();
            $table->integer('mob_otp')->nullable();
            $table->integer('email_otp')->nullable();
            $table->integer('account_otp_verified')->default(0);
            $table->integer('reset_otp')->nullable();
            $table->string('reset_token')->nullable();
            $table->string('address')->nullable();
            $table->date('dob')->nullable();
            $table->string('gender')->nullable();
            $table->string('alt_no')->nullable();
            $table->integer('status')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
