<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoutesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('routes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('agency_id')->unsigned();

            $table->string('short_name')->nullable();
            $table->string('long_name')->nullable();
            $table->string('description')->nullable();
            $table->integer('type')->nullable();
            $table->string('url')->nullable();
            $table->string('color')->nullable();
            $table->string('text_color')->nullable();

            $table->engine = 'InnoDB';

            $table->foreign('agency_id')
                ->references('id')
                ->on('agencies')
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
        Schema::drop('routes');
    }
}
