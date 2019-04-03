<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsergroupPermission extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usergroup_permissions', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('resource_id')->unsigned();
	        $table->integer('usergroup_id')->unsigned();
	        $table->string('act');
            $table->timestamps();
        });
        Schema::table('usergroup_permissions', function (Blueprint $table) {
            $table->foreign('resource_id')->references('id')->on('resources');
            $table->foreign('usergroup_id')->references('id')->on('usergroup');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('usergroup_permissions');
    }
}
