<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmbassyTmpDatabase extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('embassy_tmp', function (Blueprint $table) {
            $table->increments('id');
            $table->string('country');
            $table->string('url');
            $table->boolean('done')->default(0);
            $table->longText('source');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('embassy_tmp');
    }
}
