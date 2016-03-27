<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConversionsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    Schema::create('conversions', function (Blueprint $table) {
      $table->increments('id');
      $table->timestamps();
      $table->integer('user_id')->unsigned();
      $table->longText('dtd');
      $table->longText('xml');
      $table->boolean('public');
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::drop('conversions');
  }
}
