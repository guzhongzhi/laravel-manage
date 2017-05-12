<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class News extends Model {
    const CATEGORY_ID_SIGHT = 1;
    const CATEGORY_ID_TRAVEL = 2;
    
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
    
    public function getSightUrl() {
        return "/sight/s-" . $this->id.".html";
    }
    
    public function getTravelUrl() {
        return "/travel/s-" . $this->id.".html";
    }
    
    public function getShortDesc() {
        if($this->short_description) {
            return $this->short_description;
        }
        $content = $this->content;
        $content = preg_replace('/<\/?.*?>/is','',$content);
        $content = str_replace(array("\r\n", "\r", "\n", " "), "", $content);
        return mb_substr($content,0,200);
    }
}
