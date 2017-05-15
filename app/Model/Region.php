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
        return self::getRetionsByParentId($this->id * 1);
    }
    
    public function getSightUrlKey($city = null) {
        return ($city ? "c" : "p").strtolower($this->id).".html";
    }
}
