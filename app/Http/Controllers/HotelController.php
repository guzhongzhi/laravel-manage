<?php 
namespace App\Http\Controllers;

use App\Helper\TravelHelper;
use App\Model\Region;
use App\Model\News;
use App\Model\Hotel;

use DB;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper\PaginateHelper;

class HotelController extends Controller {

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

    public function cityList(Request $request, $cityId) {
        if($cityId){
            $forceIndex = array(
                'table_name'=>'hotel',
                'index_name'=>'idx_1',
            );
        }else{
            //do nothing
            $forceIndex = array();
        }
        $provinces = Region::getProvinces();
        $searchForm = array(
            "city_id"=>array(
                "field_name"=>"city_id",
                "input_type"=>"text",
                "type"=>"=",
                "label"=>"City Id",
                "value"=>($cityId>0) ? $cityId : null,
            ),
            "_force_index"=>$forceIndex,
        );
        $searchData = $request->get("filter", array());
        $searchFormValue = PaginateHelper::initSearchFieldData($searchData,$searchForm);
        $city = $province = null;
        $city = Region::find($cityId);
        
        
        $province = Region::find($city->parent_id);
        $provinceId = $province->id;
        
        $cities = Region::getRetionsByParentId($province->id);

        $paginateHelper = new PaginateHelper(Hotel::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);
        
        return view('hotel.home', array(
                "provinces"=>$provinces,
                "cities"=>$cities,
                "paginateHelper"=>$paginateHelper,
                "paginate"=>$paginate,
                "news"=>$paginate,
                "provinceId"=>$provinceId,
                "province"=>$province,
                "city"=>$city,
                "cityId"=>$cityId,
                "controller"=>$this,
            )
        );
    }
    
    public function provinceList(Request $request, $provinceId) {
        $provinces = Region::getProvinces();
        if($provinceId){
            $forceIndex = array(
                'table_name'=>'hotel',
                'index_name'=>'idx_2',
            );
        }else{
            //do nothing
            $forceIndex = array();
        }
        $searchForm = array(
            "province_id"=>array(
                "field_name"=>"province_id",
                "input_type"=>"text",
                "type"=>"=",
                "label"=>"Province Id",
                "value"=>($provinceId>0) ? $provinceId : null,
            ),
            "_force_index"=>$forceIndex,
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

        $paginateHelper = new PaginateHelper(Hotel::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);
        
        return view('hotel.home', array(
                "provinces"=>$provinces,
                "cities"=>$cities,
                "paginateHelper"=>$paginateHelper,
                "paginate"=>$paginate,
                "news"=>$paginate,
                "provinceId"=>$provinceId,
                "province"=>$province,
                "city"=>$city,
                "cityId"=>$cityId,
                "controller"=>$this,
            )
        );
        
		//return view('admin.system.menu', array("filter"=>$searchFormValue,"paginateHelper"=>$paginateHelper, "paginate"=>$paginate));
        
    }
    
    
    public function hotelDetail($newId) {
        $hotel = Hotel::find($newId);
        
        $queryBuilder = $hotel->newQuery();
        $queryBuilder->where("city_id",$hotel->city_id);
        $queryBuilder->WhereNotIn("id",array($hotel->id));
        $queryBuilder->getQuery()->limit(5);

        $city = $province = null;
        $city = Region::find($hotel->city_id);
        $province = Region::find($hotel->province_id);

        if($city){
            $mapCity = $city->name;
            $cityId = $city->id;
        }else{
            $mapCity = $province->name;
            $cityId = 0;
        }
        $relatedSight = $queryBuilder->get(array("*"));
        
        return view('hotel.detail', array(
                "hotel"=>$hotel,
                "recItems"=>$relatedSight,
                "sights"=>$this->getSights($province->id, $cityId),
                "travelNews"=>$this->getHotTravNews($province->id, $cityId),
                "controller"=>$this,
                'mapCity'=>$mapCity,
            )
        );
        
    }
    
    protected function getHotTravNews($provinceId = null,$cityId = null) {
        $items = TravelHelper::getNewsList($cityId, $provinceId, News::CATEGORY_ID_TRAVEL, $limit=12, $orderType='rand');
        if(count($items) == 0){
            $items = TravelHelper::getNewsList(0, $provinceId, News::CATEGORY_ID_TRAVEL, $limit=12, $orderType='rand');
        }
        return $items;
    }
    
    protected function getSights($provinceId = null,$cityId = null) {
        $items = TravelHelper::getNewsList($cityId, $provinceId, News::CATEGORY_ID_SIGHT, $limit=12, $orderType='rand');
        if(count($items) == 0){
            $items = TravelHelper::getNewsList(0, $provinceId, News::CATEGORY_ID_SIGHT, $limit=12, $orderType='rand');
        }
        return $items;
    }
    
    protected function getHotels($provinceId = null,$cityId = null) {
        
        $hotel = new Hotel();
        $queryBuilder = $hotel->newQuery();
        if($provinceId) {
            $queryBuilder->where("province_id",$provinceId);
        }
        if($cityId) {
            $queryBuilder->where("city_id",$cityId);
        }
        $queryBuilder->getQuery()->limit(5);
        $items = $queryBuilder->get(array('*'));
        return $items;
    }
}
