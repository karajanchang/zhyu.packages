<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableResources extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('resources', function (Blueprint $table) {
		    $table->Increments('id')->unsigned();
		    $table->string('name');
		    $table->string('route')->nullable();
            $table->integer('parent_id')->unsigned()->nullable();
            $table->integer('orderby')->nullable();
            $table->string('icon_css')->nullable();
	    });

        Schema::table('resources', function (Blueprint $table) {
            $table->foreign('parent_id')->references('id')->on('resources');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
    	Schema::dropIfExists('resources');
    }
}
