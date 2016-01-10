<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCoordinatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coordinates', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('track_id')->unsigned();

            $table->double('lat', 10, 8)->nullable();
            $table->double('lon', 11, 8)->nullable();
            $table->datetime('time')->nullable();

            $table->timestamps();

            $table->engine = 'InnoDB';

            $table->foreign('track_id')
                ->references('id')
                ->on('tracks')
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
        Schema::drop('coordinates');
    }
}
