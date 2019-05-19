<?php

use Illuminate\Support\Facades\Schema;
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
        Schema::create('users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->string('first_name'); // Name or Social reason
            $table->string('last_name');
            $table->string('phone');
            $table->string('ruc');
            $table->string('direction')->nullable()->default(null);

            // Client
            $table->string('city')->nullable()->default(null);
            $table->string('postal_code')->nullable()->default(null);

            // Transporter
            $table->string('type_driver_license')->nullable()->default(null);
            $table->string('photo')->nullable();
            $table->string('image_driver_license')->nullable();
            $table->string('image_permit_circulation')->nullable();
            $table->string('image_certificate_background')->nullable()->default(null);

            $table->timestamp('email_verified_at')->nullable();
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
}
