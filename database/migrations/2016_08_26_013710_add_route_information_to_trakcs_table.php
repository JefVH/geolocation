<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRouteInformationToTrakcsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->boolean('processed')->after('name')->default(false);
            $table->integer('trip_id')->after('processed')->default(null);
            $table->integer('start_stop_id')->after('trip_id')->default(null);
            $table->integer('end_stop_id')->after('start_stop_id')->default(null);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tracks', function (Blueprint $table) {
            $table->dropColumn('processed');
            $table->dropColumn('trip_id');
            $table->dropColumn('start_stop_id');
            $table->dropColumn('end_stop_id');
        });
    }
}
