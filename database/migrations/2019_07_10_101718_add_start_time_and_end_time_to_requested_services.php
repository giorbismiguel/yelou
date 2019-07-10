<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddStartTimeAndEndTimeToRequestedServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('requested_services', function (Blueprint $table) {
            $table->dateTime('start_at')->nullable()->default(null);
            $table->dateTime('end_at')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('requested_services', function (Blueprint $table) {
            $table->removeColumn('start_at');
            $table->removeColumn('end_at');
        });
    }
}
