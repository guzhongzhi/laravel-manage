<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Model\Store;
use DB;

class Food extends Model {

    
	protected $table = "food";
    protected $fillable = [
        'province_id',
        'country_id',
        'city_id',
        'rate',
        'like',
        'title',
        'user_id',
        'source_url',
        'pic',
        'content',
        'created_at',
        'updated_at',
    ];
    
  
    public function getMetaKeywords() {
        return $this->meta_keywords;
    }
    
    public function getMetaDescription() {
        return $this->meta_description;
    }
    
    
    public function getFoodUrl() {
        return "/food/d-" . $this->id.".html";
    }
    
    public function getStores(){
        if($this->stores){
            return $this->stores;
        }
        $buildQuery = Store::query();
        $buildQuery->where('food_store.food_id', $this->id);
        $buildQuery->join('food_store', 'food_store.store_id', '=', 'store.id');
        $this->stores = $buildQuery->get();
        return $this->stores;
    }
    
    public function getShortDesc() {
        if($this->short_description) {
            return $this->short_description;
        }
        $content = $this->content;
        $content = preg_replace('/<\/?.*?>/is','',$content);
        $content = str_replace(array("\r\n", "\r", "\n", " "), "", $content);
        return mb_substr($content,0,30);
    }
    
    public function getProvince() {
        $province = Region::find($this->province_id);
        if(!$province) {
            $province = new Region();
        }
        return $province;
    }

    public function getPic(){
        if(!$this->pic){
            $this->pic = '/skin/images/no_pic.gif';
        }
        return $this->pic;
    }
}
