<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSavedxmlTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('savedxml', function (Blueprint $table) {
			//Incremental ID per saved XML and setting it as primary key
			$table->increments('id');
			
			//User/Owner of the saved XML
			$table->integer('user_id')->unsigned();
			//Foreign key check
			$table->foreign('user_id')->references('id')->on('users');
			
			//Name of saved XML
			$table->string('savedxml_name', 100);
			
			//Creation and Update Time of saved XML
			$table->timestamps();
			
			//Actual saved XML
			$table->longText('xml');
			
			//TODO: Decide if want to use composite keys, if want, just uncomment below
			//$table->unique(array('user_id', 'savedxml_name'));
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::drop('savedxml');
    }
}
