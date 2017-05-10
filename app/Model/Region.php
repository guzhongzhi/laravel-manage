<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Region extends Model {
	protected $table = "region";
    
    public static function getProvinces() {
        $queryBuilder = Region::select();
        $queryBuilder->where("parent_id",1);
        $rows = $queryBuilder->paginate(1000);
        return $rows;
    }
    
    public function getUrlKey() {
        return "p".strtolower($this->id).".html";
    }
}
