<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUserPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::table('user', function (Blueprint $table) {
		    $table->unsignedInteger('id')->change();
	    });
	    
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource_id')->unsigned();
	        $table->integer('user_id')->unsigned();
            $table->string('act');
            $table->timestamps();
        });
        Schema::table('user_permissions', function (Blueprint $table) {
            $table->foreign('resource_id')->references('id')->on('resources');
	        $table->foreign('user_id')->references('id')->on('user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_permissions');
    }
}
