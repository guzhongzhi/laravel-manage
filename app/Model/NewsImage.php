<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class NewsImage extends Model {
    
    protected $table = "news_image";
    protected $fillable = [
        'news_id', 
        'url',
    ];
    
}
