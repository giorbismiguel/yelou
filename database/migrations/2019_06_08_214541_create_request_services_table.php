<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequestServicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('request_services', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('route_id');
            $table->string('name_start');
            $table->double('lat_start');
            $table->double('lng_start');
            $table->string('name_end');
            $table->double('lat_end');
            $table->double('lng_end');
            $table->datetime('start_time');
            $table->unsignedBigInteger('payment_method_id');
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
        Schema::drop('request_services');
    }
}
