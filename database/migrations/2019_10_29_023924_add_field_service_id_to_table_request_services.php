<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldServiceIdToTableRequestServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('request_services', function (Blueprint $table) {
            $table->unsignedBigInteger('status_id')
                ->default(1); // 1- Pending 2- Accepted  3- Rejected 4- Begin 5- Ended
            $table->foreign('status_id')
                ->references('id')
                ->on('requested_service_statuses')
                ->onDelete('no action')
                ->onUpdate('no action');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_services', function (Blueprint $table) {
            $table->dropForeign('status_id');
            $table->dropColumn('status_id');
        });
    }
}
