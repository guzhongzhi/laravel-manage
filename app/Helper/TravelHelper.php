<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
use App\Helper\ImageSeekHelper;
use App\Model\News;
use App\Model\Food;
use App\Model\Store;
class TravelHelper {
    public static function getRandFoodList($cityId=0, $provinceId=0, $limit=10){
        $andSql = '';
        $p = array();
        $andSqlMax = '';
        $andSqlMin1 = '';
        $andSqlMin2 = '';
        $andSql = '';
        if($provinceId){
            $andSqlMax .= ' AND province_id = ?';
            $p[] = $provinceId;
            
            $andSqlMin1 .= ' AND province_id = ?';
            $p[] = $provinceId;
            
            $andSqlMin2 .= ' AND province_id = ?';
            $p[] = $provinceId;
            
            $andSql .= ' AND province_id = ?';
            $p[] = $provinceId;
        }
        
        
        if($cityId){
            $andSqlMax .= ' AND city_id = ?';
            $p[] = $cityId;
            
            $andSqlMin1 .= ' AND city_id = ?';
            $p[] = $cityId;
            
            $andSqlMin2 .= ' AND city_id = ?';
            $p[] = $cityId;
            
            $andSql .= ' AND city_id = ?';
            $p[] = $cityId;
        }
        
        
        $sql = "SELECT t1.* 
                    FROM `food` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `food` WHERE 1 $andSqlMax)-(SELECT MIN(id) FROM `food` WHERE 1  $andSqlMin1))+(SELECT MIN(id) FROM `food` WHERE 1 $andSqlMin2)) AS id) AS t2  
                    WHERE t1.id >= t2.id $andSql 
                    ORDER BY t1.id LIMIT $limit; ";
        $result = DB::select($sql, $p);
        return $result;
    }

    public static function getNewslList($cityId=0, $provinceId=0, $categoryId=0, $limit=10, $orderType='rand'){
        $queryBuilder = News::query();
        $andSqlMax = '';
        $andSqlMin1 = '';
        $andSqlMin2 = '';

        if($categoryId){
            $queryBuilder->where('category_id', '=', $categoryId);
            $andSqlMin1 .= " AND category_id = '$categoryId'";
            $andSqlMin2 .= " AND category_id = '$categoryId'";
        }
        if($provinceId){
            $queryBuilder->where('province_id', '=', $provinceId);
            $andSqlMin1 .= " AND province_id = '$provinceId'";
            $andSqlMin2 .= " AND province_id = '$provinceId'";
        }
        if($cityId){
            $queryBuilder->where('city_id', '=', $cityId);
            $andSqlMin1 .= " AND city_id = '$cityId'";
            $andSqlMin2 .= " AND city_id = '$cityId'";
        }

        if($orderType == 'recommand'){
            $sqlInt = "SELECT ROUND(RAND() * ((SELECT count(*) FROM `news` WHERE 1 $andSqlMin1))+(SELECT MIN(id) FROM `news` WHERE 1 $andSqlMin2)) AS int_number";
            $intNumberObj = DB::selectOne($sqlInt);
            $intNumber = $intNumberObj->int_number;
            $queryBuilder->where('id', '>=', $intNumber);
        }else{
            $queryBuilder->orderBy('id', 'desc');
        }

        if($limit){
            $queryBuilder->paginate($limit);
        }
        $resultData = $queryBuilder->get();
        return $resultData;

    }


    public static function utf8Substr($string, $start, $length) {
        $out = mb_substr($string, $start, $length, 'utf-8') . '...';
        return $out;
    }



}