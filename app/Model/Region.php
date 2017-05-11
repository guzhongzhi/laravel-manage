<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;
use Illuminate\Pagination\AbstractPaginator;

class Region extends Model {
	protected $table = "region";
    
    public static function getProvinces() {
        
        $region = new Region();
        $queryBuilder = $region->newQuery();
        $queryBuilder->where("parent_id",1);
        $rows = $queryBuilder->get(array("*"));
        return $rows;
    }
    
    public function getSightUrlKey() {
        return "p".strtolower($this->id).".html";
    }
}
