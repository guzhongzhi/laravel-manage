<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class News extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('news', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('category_id');
			$table->integer('province_id');
			$table->integer('country_id');
			$table->integer('city_id')->nullable()->default(0);
			$table->double('rate')->nullable()->default(0);
			$table->string('title');
			$table->string('pic');
			$table->string('meta_keywords')->nullable();
			$table->string('meta_description')->nullable();
			$table->string('short_description')->nullable();
			$table->string('editor')->nullable();
			$table->string('source_url')->nullable();
            $table->longText('content');
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
		Schema::drop('news');
	}

}
