<?php 
namespace App\Http\Controllers;

use App\Model\Region;
use App\Model\News;
use App\Model\Hotel;
use App\Model\Food;
use DB;


class WelcomeController extends Controller {

	/*
	|--------------------------------------------------------------------------
	| Welcome Controller
	|--------------------------------------------------------------------------
	|
	| This controller renders the "marketing page" for the application and
	| is configured to only allow guests. Like most of the other sample
	| controllers, you are free to modify or remove it as you desire.
	|
	*/

	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('guest');
	}

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{   
        $data = array(
            "sight"=>array(
                "cities"=>$this->getCities(),
            ),
            "controller"=>$this,
        );
		return view('welcome', $data);
	}
    
    public function getCities() {
        $hostCityNames = array(
            '成都',
            '天津',
            '上海',
            '北京',
            '广州',
            '三亚',
            '丽江',
            '青岛',
            '桂林',
            '西安',
        );
        $city = array();
        foreach($hostCityNames as $cityName) {
            $city[] = Region::getCityByName($cityName);
        }
        return $city;
    }
    
    public function getSights($cityId = 0, $limit = 8) {
        try {
            $sight = new News();
            $queryBuilder = $sight->newQuery();
            $queryBuilder->where("category_id",News::CATEGORY_ID_SIGHT);
            if($cityId) {
                $queryBuilder->where("city_id",$cityId);
            }
            $queryBuilder->getQuery()->limit($limit);
            
            $relatedSight = $queryBuilder->get(array("*"));
            $items = $relatedSight->all();
            return $items;
        } catch (\Exception $ex) {
            echo $ex->__toString();
            die();
        }
        return array();
    }
    
    public function getTravels($cityId,$limit = 8) {
        try {
            $sight = new News();
            $queryBuilder = $sight->newQuery();
            $queryBuilder->where("category_id",News::CATEGORY_ID_TRAVEL);
            if($cityId) {
                $queryBuilder->getQuery()->whereRaw("(city_id = ".($cityId * 1)." OR province_id = ".($cityId * 1).")");
            }
            $queryBuilder->getQuery()->limit($limit);
            
            $relatedSight = $queryBuilder->get(array("*"));
            $items = $relatedSight->all();
            return $items;
        } catch (\Exception $ex) {
            echo $ex->__toString();
            die();
        }
        return array();
        
    }
    
    
    
    
    public function getFoods($cityId,$limit = 8) {
        try {
            $sight = new Food();
            $queryBuilder = $sight->newQuery();
            if($cityId) {
                $queryBuilder->getQuery()->whereRaw("(city_id = ".($cityId * 1)." OR province_id = ".($cityId * 1).")");
            }
            $queryBuilder->getQuery()->limit($limit);
            
            $relatedSight = $queryBuilder->get(array("*"));
            $items = $relatedSight->all();
            return $items;
        } catch (\Exception $ex) {
            echo $ex->__toString();
            die();
        }
        return array();
        
    }
    
    public function getHotels($cityId = 0, $limit = 8) {
        try {
            $sight = new Hotel();
            $queryBuilder = $sight->newQuery();
            if($cityId) {
                $queryBuilder->where("city_id",$cityId);
            }
            $queryBuilder->getQuery()->limit($limit);
            
            $relatedSight = $queryBuilder->get(array("*"));
            $items = $relatedSight->all();
            return $items;
        } catch (\Exception $ex) {
            echo $ex->__toString();
            die();
        }
        return array();
    }
}
