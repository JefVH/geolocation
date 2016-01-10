<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStopTimesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stop_times', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('trip_id')->unsigned();

            $table->string('arrival_time')->nullable();
            $table->string('departure_time')->nullable();

            $table->integer('stop_id')->unsigned();

            $table->integer('stop_sequence')->nullable();
            $table->integer('stop_headsign')->nullable();
            $table->integer('pickup_type')->nullable();
            $table->integer('dropoff_type')->nullable();
            $table->string('shape_dist_travelled')->nullable();

            $table->engine = 'InnoDB';

            $table->foreign('trip_id')
                ->references('id')
                ->on('trips')
                ->onDelete('cascade');

            $table->foreign('stop_id')
                ->references('id')
                ->on('stops')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stop_times');
    }
}
