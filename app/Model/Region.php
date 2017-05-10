<?php 
namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Region extends Model {
	protected $table = "region";
    
    public static function getProvinces() {
        $rows = DB::select("SELECT * FROM region WHERE parent_id = 1");
        return $rows;
    }
}
