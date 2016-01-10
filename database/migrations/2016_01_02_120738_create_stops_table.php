<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stops', function(Blueprint $table) {
            $table->increments('id');

            $table->string('code')->nullable();
            $table->string('name')->nullable();
            $table->string('description')->nullable();
            $table->string('lat')->nullable();
            $table->string('lon')->nullable();
            $table->integer('zone_id')->nullable();
            $table->string('url')->nullable();
            $table->string('location_type')->nullable();
            $table->string('parent_station')->nullable();

            $table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('stops');
    }
}
