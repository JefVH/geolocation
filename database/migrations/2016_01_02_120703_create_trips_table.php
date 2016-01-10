<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTripsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->integer('route_id')->unsigned();
            $table->integer('service_id')->unsigned();
            $table->increments('id');
            $table->string('headsign')->nullable();
            $table->string('short_name')->nullable();
            $table->integer('direction_id')->nullable();
            $table->integer('block_id')->nullable();
            $table->integer('shape_id')->nullable();

            $table->engine = 'InnoDB';

            $table->foreign('route_id')
                ->references('id')
                ->on('routes')
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
        Schema::drop('trips');
    }
}
