<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
use App\Helper\ImageSeekHelper;
use App\Model\News;
use App\Model\Food;
use App\Model\Store;
class TravelHelper {
    public static function getRandFoodList($cityId=0, $provinceId=0, $limit=10){
        $andSqlMin1 = '';
        $andSqlMin2 = '';
        $queryBuilder = Food::query();
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
        $sqlInt = "SELECT ROUND(RAND() * ((SELECT count(*) FROM `food` WHERE 1 $andSqlMin1))+(SELECT id FROM `food` WHERE 1 $andSqlMin2 LIMIT 1)) AS int_number";
        $intNumberObj = DB::selectOne($sqlInt);
        $intNumber = $intNumberObj->int_number;
        if($intNumber){
            $queryBuilder->where('id', '>=', $intNumber);
        }


        if($limit){
            $queryBuilder->take($limit);
        }
        $resultData = $queryBuilder->get();
        return $resultData;
    }

    public static function getNewsList($cityId=0, $provinceId=0, $categoryId=0, $limit=10, $orderType='rand'){
        $queryBuilder = News::query();
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
        if($categoryId && $provinceId && $cityId){
            $indexName = 'idx_1';
        }elseif($categoryId && $provinceId){
            $indexName = 'idx_2';
        }else{
            $indexName = '';
        }

        if($orderType == 'recommand'){
            $sqlInt = "SELECT ROUND(RAND() * ((SELECT count(*) FROM `news` WHERE 1 $andSqlMin1))+(SELECT id FROM `news` WHERE 1 $andSqlMin2 LIMIT 1)) AS int_number";
            $intNumberObj = DB::selectOne($sqlInt);
            $intNumber = $intNumberObj->int_number;
            if($intNumber){
                $queryBuilder->where('id', '>=', $intNumber);
            }

        }else{
			//order by id desc
            if($indexName){
                $queryBuilder->from(\DB::raw("news FORCE INDEX ($indexName)"));
            }
            $queryBuilder->orderBy('id', 'desc');

        }

        if($limit){
            $queryBuilder->take($limit);
        }
        $resultData = $queryBuilder->get();
        return $resultData;

    }


    public static function utf8Substr($string, $start, $length) {
        $out = mb_substr($string, $start, $length, 'utf-8') . '...';
        return $out;
    }



}