<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRequestedServicesTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requested_services', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('transporter_id');
            $table->unsignedBigInteger('service_id');
            $table->unsignedBigInteger('status_id')
                ->default(1); // 1- Pending 2- Accepted  3- Rejected 4- Begin 5- Ended

            $table->foreign('client_id')
                ->references('id')
                ->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('transporter_id')
                ->references('id')
                ->on('users')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('service_id')
                ->references('id')
                ->on('request_services')
                ->onDelete('no action')
                ->onUpdate('no action');

            $table->foreign('status_id')
                ->references('id')
                ->on('requested_service_statuses')
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
        Schema::drop('requested_services');
    }
}
