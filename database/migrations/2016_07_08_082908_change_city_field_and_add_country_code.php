<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeCityFieldAndAddCountryCode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('hospital', function (Blueprint $table) {
            $table->unsignedInteger('city')->nullable()->change();
            $table->renameColumn('city', 'city_id');
            $table->unsignedInteger('country_id')->nullable();
            $table->foreign('country_id')
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
    }
}
