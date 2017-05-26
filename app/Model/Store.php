<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Store extends Model {

    
	protected $table = "store";
    protected $fillable = [
        'province_id',
        'country_id',
        'city_id',
        'rate',
        'like',
        'title',
        'user_id',
        'is_seeked',
        'source_url',
        'source_url_secret',
        'pic',
        'content',
        'description',
        'created_at',
        'updated_at',
    ];


    public function getMetaKeywords() {
        return $this->meta_keywords;
    }
    
    public function getMetaDescription() {
        return $this->meta_description;
    }
    public function getStoreUrl() {
        return "/store/d-" . $this->id.".html";
    }
    
    public function getStar(){
        $star = $this->rate / 5 * 100;
        return $star;
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

    public function getPic(){
        if(!$this->pic){
            $this->pic = '/skin/images/no_pic.gif';
        }
        return $this->pic;
    }
}
