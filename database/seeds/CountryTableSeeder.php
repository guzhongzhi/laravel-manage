<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\Region;

class CountryTableSeeder extends Seeder {
    
    function _fgetcsv(&$file, $length = null, $d = ',', $e = '"') {
        
        $line = fgets($file,$length);
        if($line == "") {
            return array();
        }
        $temp = explode(",",$line);
        $data =array();
        foreach($temp as $key=>$v) {
            $v = trim($v);
            $data[] = trim($v,'"');
        }
        return $data;
    }

    public function run() {
        DB::table("region")->delete();
        setlocale(LC_ALL,"en_US.UTF-8"); 
        
        $fileName = dirname(__FILE__)."/../sqls/region.sql";
        $file = fopen($fileName,"r");
        
        $fields = array(
              "id",
              "code",
              "name",
              "parent_id",
              "level",
              "sort_order",
              "name_en",
              "short_name_en",
        );
        
        while($row = $this->_fgetcsv($file,10240,",",'"')) {
            $rowData = array();
            foreach($fields as $index=>$fieldName) {
                $rowData[$fieldName] = isset($row[$index]) ? $row[$index] : "";
            }
            Region::create($rowData);
        }
        
    }
    
}