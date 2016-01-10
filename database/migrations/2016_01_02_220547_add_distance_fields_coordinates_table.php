<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDistanceFieldsCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('coordinates', function(Blueprint $table)
        {
            $table->integer('stop_id')->unsigned();
            $table->float('stop_distance')->nullable();

            $table->foreign('stop_id')
                ->references('id')
                ->on('stops');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('coordinates', function(Blueprint $table)
        {
            $table->dropColumn('stop_id');
            $table->dropColumn('stop_distance');
        });
    }
}
