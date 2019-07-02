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
            $table->date('birth_date')->nullable();
            $table->string('phone')->unique();
            $table->string('ruc');
            $table->string('direction')->nullable()->default('');

            $table->boolean('type')->default(null); // 1- Client 2- Transporter 3- Admin

            // Client
            $table->string('city')->nullable()->default(null);
            $table->string('postal_code')->nullable()->default(null);

            // Transporter
            $table->unsignedBigInteger('license_types_id')->nullable()->default(null);
            $table->foreign('license_types_id')
                ->references('id')
                ->on('license_types')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->string('photo')->nullable();
            $table->string('image_driver_license')->nullable();
            $table->string('image_permit_circulation')->nullable();
            $table->string('image_certificate_background')->nullable()->default(null);
            $table->string('code_activation', 6)->nullable();
            $table->boolean('term_condition')->default(0);

            $table->timestamp('email_verified_at')->nullable();
            $table->timestamp('phone_verified_at')->nullable();

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
