<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Region extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('region', function(Blueprint $table)
		{
			$table->increments("id");
			$table->string("code");
			$table->string("name");
			$table->string("name_en")->nullable();
			$table->string("short_name_en")->nullable();
			$table->integer("parent_id")->default(0);
			$table->integer("level")->default(0);
			$table->integer("sort_order")->default(0);
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
		Schema::table('region', function(Blueprint $table)
		{
            $table->drop();
			//
		});
	}

}
