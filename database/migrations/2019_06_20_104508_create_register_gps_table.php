<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRegisterGpsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('register_gps', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->double('lat');
            $table->double('lng');
            $table->unsignedBigInteger('driver_id');
            $table->unsignedBigInteger('service_id');
            $table->dateTime('registered_at');

            $table->foreign('driver_id')
                ->references('id')
                ->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('service_id')
                ->references('id')
                ->on('request_services')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('register_gps');
    }
}
