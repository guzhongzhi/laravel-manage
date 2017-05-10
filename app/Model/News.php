<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class News extends Model {
    const CATEGORY_ID_SIGHT = 1;
    
	protected $table = "news";
    protected $fillable = [
        'category_id', 
        'province_id',
        'country_id',
        'rate',
        'title',
        'short_description',
        'editor',
        'source_url',
        'content',
        'created_at',
        'updated_at',
    ];
}
