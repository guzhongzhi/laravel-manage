<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Menu extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('menu', function(Blueprint $table)
		{
            $table->increments("id");
            $table->integer("parent_id");
            $table->string("name");
            $table->string("url");
            $table->string("icon");
            $table->string("show_in_menu");
            $table->integer("sort_order");
            $table->timestamps();
			//
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('menu', function(Blueprint $table)
		{
			$table->drop();
		});
	}

}
