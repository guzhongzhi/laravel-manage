<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MenuRole extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
        Schema::create('role', function(Blueprint $table)
		{
			$table->increments("id");
            $table->string("name");
            $table->timestamps();            
		});
		Schema::create('menu_role', function(Blueprint $table)
		{
			$table->increments("id");
            $table->integer("menu_id");
            $table->integer("role_id");            
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('role', function(Blueprint $table)
		{
			$table->drop();
		});
        Schema::table('menu_role', function(Blueprint $table)
		{
			$table->drop();
		});
	}

}
