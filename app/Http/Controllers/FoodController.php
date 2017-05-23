<?php 
namespace App\Http\Controllers;

use App\Model\Region;
use App\Model\Food;
use App\Model\Store;
use DB;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper\PaginateHelper;
use Illuminate\Support\Facades\Cookie;

class FoodController extends Controller {

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
	public function index(Request $request)
	{
		return $this->provinceList($request, $provinceId=0);
	}

    public function provinceList(Request $request, $provinceId) {
        $provinces = Region::getProvinces();
        $searchForm = array(

            "province_id"=>array(
                "field_name"=>"province_id",
                "input_type"=>"text",
                "type"=>"=",
                "label"=>"Province Id",
                "value"=>($provinceId>0) ? $provinceId : null,
            ),
        );
        $searchData = $request->get("filter", array());
        $searchFormValue = PaginateHelper::initSearchFieldData($searchData,$searchForm);

        $cityId  = $city = $province = null;
        $province = Region::find($provinceId);
        if($province && $province->parent_id != 1) {
            $city = $province;
            $cityId = $city->id;
            $province = Region::find($city->parent_id);
        }
        
        $cities = Region::getRetionsByParentId($provinceId);
        
        $paginateHelper = new PaginateHelper(Food::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);

        $currentTitleName = '';
        if($city){
            $currentTitleName = $city->name;
        }elseif($province){
            $currentTitleName = $province->name;
        }

        
        return view('food.home', array(
                "provinces"=>$provinces,
                "cities"=>$cities,
                "paginateHelper"=>$paginateHelper,
                "paginate"=>$paginate,
                "news"=>$paginate,
                "provinceId"=>$provinceId,
                "provinceId"=>$provinceId,
                "province"=>$province,
                "city"=>$city,
                "cityId"=>$cityId,
                "currentTitleName"=>$currentTitleName,
                "controller"=>$this,
            )
        );
        
		//return view('admin.system.menu', array("filter"=>$searchFormValue,"paginateHelper"=>$paginateHelper, "paginate"=>$paginate));
        
    }
    
    public function cityList(Request $request, $cityId) {
        $provinces = Region::getProvinces();
        $searchForm = array(
            "city_id"=>array(
                "field_name"=>"city_id",
                "input_type"=>"text",
                "type"=>"=",
                "label"=>"City Id",
                "value"=>($cityId>0) ? $cityId : null,
            ),
        );
        $searchData = $request->get("filter", array());
        $searchFormValue = PaginateHelper::initSearchFieldData($searchData,$searchForm);
        $city = $province = null;
        $city = Region::find($cityId);
        
        
        $province = Region::find($city->parent_id);
        $provinceId = $province->id;
        
        $cities = Region::getRetionsByParentId($province->id);

        $paginateHelper = new PaginateHelper(Food::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);

        $currentTitleName = $city->name;

        return view('food.home', array(
                "provinces"=>$provinces,
                "cities"=>$cities,
                "paginateHelper"=>$paginateHelper,
                "paginate"=>$paginate,
                "news"=>$paginate,
                "provinceId"=>$provinceId,
                "province"=>$province,
                "city"=>$city,
                "cityId"=>$cityId,
                "currentTitleName"=>$currentTitleName,
                "controller"=>$this,
            )
        );
    }
    
    
    public function foodDetail($newId) {
        
        if(Cookie::get('food_'.$newId)){
            $likeClass = 'link_like click_like';
        }else{
            $likeClass = 'link_like';
        }
        
        $food = Food::find($newId);
        $city = $province = null;
        $city = Region::find($food->city_id);
        $province = Region::find($food->province_id);
        if($city){
            $cityId = $city->id;
        }else{
            $cityId = 0;
        }
        $provinceId = $province->id;
        return view('food.detail', array(
                "food"=>$food,
                'likeClass'=>$likeClass,
                'city'=>$city,
                'province'=>$province,
                'cityId'=>$cityId,
                'provinceId'=>$provinceId,
                "controller"=>$this,
            )
        );
        
    }
    
    
    public function storeDetail($newId){
        if(Cookie::get('store_'.$newId)){
            $likeClass = 'link_like click_like';
        }else{
            $likeClass = 'link_like';
        }
        
        $store = Store::find($newId);
        $city = $province = null;
        $city = Region::find($store->city_id);
        $province = Region::find($store->province_id);
        if($city){
            $mapCity = $city->name;
            $cityId = $city->id;
        }else{
            $mapCity = $province->name;
            $cityId = 0;
        }
        $provinceId = $province->id;
        return view('food.store_detail', array(
                "store"=>$store,
                'likeClass'=>$likeClass,
                'city'=>$city,
                'province'=>$province,
                'mapCity'=>$mapCity,
                'cityId'=>$cityId,
                'provinceId'=>$provinceId,
                "controller"=>$this,
            )
        );
    }
    
    public function foodEnjoy(){
        $newId = \Request::input('newId');
        Cookie::queue('food_'.$newId, 1, 3600*24);
        $food = Food::find($newId);
        $food->like = $food->like + 1;
        $food->save();
        return $food->like;
    }
    
    public function storeEnjoy(){
        $newId = \Request::input('newId');
        Cookie::queue('store_'.$newId, 1, 3600*24);
        $store = Store::find($newId);
        $store->like = $store->like + 1;
        $store->save();
        return $store->like;
    }
    
}
