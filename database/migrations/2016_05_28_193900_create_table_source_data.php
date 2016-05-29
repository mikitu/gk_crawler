<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableSourceData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('source_data', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("source_id")->unsigned();
            $table->integer("city_id")->unsigned();
            $table->integer("country_id")->unsigned();
            $table->string("address");
            $table->string("phone");
            $table->string("zipcode");
            $table->string("latitude");
            $table->string("longitude");
            $table->timestamps();

            $table->foreign('source_id')
                ->references('id')->on('source');

            $table->foreign('country_id')
                ->references('id')->on('country');

            $table->foreign('city_id')
                ->references('id')->on('city');

            $table->unique(array('source_id', 'country_id', 'city_id', 'zipcode'));

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('source_data');
    }
}
