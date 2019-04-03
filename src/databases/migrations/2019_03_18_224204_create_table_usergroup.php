<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableUsergroup extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('usergroup')) {
            Schema::create('usergroup', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->string('name');
                $table->boolean('is_online')->default(1);
                $table->boolean('nologin')->default(0);
                $table->timestamps();
            });
        }else{
	        Schema::table('usergroup', function (Blueprint $table) {
		        $table->unsignedInteger('id')->change();
	        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
