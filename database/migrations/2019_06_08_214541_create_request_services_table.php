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
            $table->bigIncrements('id');

            $table->string('name_start');
            $table->double('lat_start');
            $table->double('lng_start');

            $table->string('name_end');
            $table->double('lat_end');
            $table->double('lng_end');

            $table->date('start_date');
            $table->time('start_time')->nullable();

            $table->unsignedBigInteger('payment_method_id');
            $table->foreign('payment_method_id')
                ->references('id')
                ->on('payment_methods')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->unsignedBigInteger('route_id')->nullable();
            $table->foreign('route_id')
                ->references('id')
                ->on('routes')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users')
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
        Schema::drop('request_services');
    }
}
