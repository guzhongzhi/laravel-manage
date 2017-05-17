<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
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
    
    
    
    public function getFoodUrl() {
        return "/food/d-" . $this->id.".html";
    }
    
    public function getStores(){
        if($this->stores){
            return $this->stores;
        }
        $sql = "SELECT store.* FROM store LEFT JOIN food_store ON store.id = food_store.store_id WHERE food_store.food_id = ?";
        $stores = DB::select($sql, array($this->id));
        $this->stores = $stores;
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
}
