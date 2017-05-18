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
        if($cityId){
            $andSql .= ' AND city_id = ?';
            $p[] = $cityId;
        }
        if($provinceId){
            $andSql .= ' AND province_id = ?';
            $p[] = $provinceId;
        }
        /*
        $sql = "SELECT t1.* 
                    FROM `food` AS t1 JOIN (SELECT ROUND(RAND() * ((SELECT MAX(id) FROM `food` $andSql)-(SELECT MIN(id) FROM `food` $andSql))+(SELECT MIN(id) FROM `food` $andSql)) AS id) AS t2 
                    WHERE t1.id >= t2.id $andSql 
                    ORDER BY t1.id LIMIT $limit; ";
        */
        $sql = "SELECT food.* FROM food WHERE 1 $andSql ORDER BY RAND() limit $limit";
        //ECHO $sql;var_dump($p);die();
        $result = DB::select($sql, $p);
        return $result;
    }

    public static function getRandSightList($cityId=0, $provinceId=0, $limit=10){
        $andSql = '';
        $p = array();
        $p[] = News::CATEGORY_ID_SIGHT;
        if($cityId){
            $andSql .= ' AND city_id = ? ';
            $p[] = $cityId;
        }
        if($provinceId){
            $andSql .= ' AND province_id = ?';
            $p[] = $provinceId;
        }

        $sql = "SELECT * FROM news WHERE category_id = ? $andSql ORDER BY RAND() LIMIT $limit";
        $result = DB::select($sql, $p);
        return $result;
    }

    public static function getRandTravelList($cityId=0, $provinceId=0, $limit=10, $orderType='rand'){
        $andSql = '';
        $p = array();
        $p[] = News::CATEGORY_ID_TRAVEL;
        if($cityId){
            $andSql .= ' AND city_id = ?';
            $p[] = $cityId;
        }
        if($provinceId){
            $andSql .= ' AND province_id = ?';
            $p[] = $provinceId;
        }

        $orderBy = '';
        if($orderType == 'rand'){
            $orderBy = 'ORDER BY RAND()';
        }elseif($orderType == 'recommand'){
            $orderBy = 'ORDER BY `like` DESC ';
        }else{
            $orderBy = 'ORDER BY `id` DESC ';
        }

        $sql = "SELECT * FROM news WHERE category_id = ? $andSql  $orderBy LIMIT $limit";
        $result = DB::select($sql, $p);
        $resultData = array();
        foreach($result  as $resultObj){
            $resultData[] = News::find($resultObj->id);
        }
        return $resultData;
    }


    public static function utf8Substr($string, $start, $length) {
        $out = mb_substr($string, $start, $length, 'utf-8') . '...';
        return $out;
    }



}