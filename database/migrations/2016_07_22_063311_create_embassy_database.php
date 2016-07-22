<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmbassyDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('embassy', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('embassy_name');
            $table->string('embassy_of');
            $table->unsignedInteger('embassy_of_country_id')->nullable();
            $table->string('embassy_in');
            $table->unsignedInteger('embassy_in_country_id')->nullable();
            $table->string('address');
            $table->string('city');
            $table->unsignedInteger('city_id')->nullable();
            $table->string('postcode');
            $table->string('phone');
            $table->string('fax');
            $table->string('email');
            $table->string('website');
            $table->string('office_hours');
            $table->string('details');
            $table->string('latitude');
            $table->string('longitude');
            $table->timestamps();
            $table->foreign('embassy_of_country_id')
                ->references('id')->on('country');
            $table->foreign('embassy_in_country_id')
                ->references('id')->on('country');
            $table->foreign('city_id')
                ->references('id')->on('city');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('embassy');
    }
}
