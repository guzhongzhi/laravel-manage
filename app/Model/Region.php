<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\AbstractPaginator;

class Region extends Model {
	protected $table = "region";
    
    public static function getProvinces() {
        return self::getRetionsByParentId(1);
    }
    
    public static function getRetionsByParentId($id) {
        $region = new Region();
        $queryBuilder = $region->newQuery();
        $queryBuilder->where("parent_id",$id);
        $rows = $queryBuilder->get(array("*"));
        return $rows;
    }
    
    public function getChilds() {
        $data =  self::getRetionsByParentId($this->id * 1);
        return $data;
    }
    
    public function getHotelUrlKey($city = null) {
        return ($city ? "c" : "p").strtolower($this->id).".html"; 
    }
    public function getSightUrlKey($city = null) {
        return ($city ? "c" : "p").strtolower($this->id).".html";
    }
    
    public function getFirstChildItem() {
        $childs = $this->getChilds();
        $item = $childs->pop();
        if($item) {
            return $item;
        }
        return $this;
    }
    
    public static function getCityByName($name,$provinceId = null) {
        $sql = "SELECT * FROM region WHERE name like '%".$name."%' ";
        
        if($provinceId) {
            $sql .=" AND parent_id = " . ($provinceId * 1);
        }
        
        //echo $sql,PHP_EOL;
        $rows = DB::select($sql);
        $data = isset($rows[0]) ? $rows[0] : new Region();
        if($data->id) {
            return Region::find($data->id);
        }
        return $data;
    }
    
    public static function getProvinceByName($name) {
        $sql = "SELECT * FROM region WHERE name like '%".$name."%' AND parent_id = 1";
        //echo $sql,PHP_EOL;
        $rows = DB::select($sql);
        $data = isset($rows[0]) ? $rows[0] : new Region();
        if($data->id) {
            return Region::find($data->id);
        }
        return $data;
    }
	
	public function getSightUrl(){
		if($this->parent_id != 1){
			$url = "/sight/c".$this->id.".html";
		}else{
			$url = "/sight/p".$this->id.".html";
		}
		return $url;
	}
	
	public function getHotelUrl(){
		if($this->parent_id != 1){
			$url = "/hotel/c".$this->id.".html";
		}else{
			$url = "/hotel/p".$this->id.".html";
		}
		return $url;
	}
	
	public function getTravelUrl(){
		if($this->parent_id != 1){
			$url = "/travel/c".$this->id.".html";
		}else{
			$url = "/travel/p".$this->id.".html";
		}
		return $url;
	}
	
	public function getFoodUrl(){
		if($this->parent_id != 1){
			$url = "/food/c".$this->id.".html";
		}else{
			$url = "/food/p".$this->id.".html";
		}
		return $url;
	}
}
