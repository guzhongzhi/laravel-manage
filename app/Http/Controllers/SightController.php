<?php 
namespace App\Http\Controllers;

use App\Model\Region;
use App\Model\News;
use App\Model\Hotel;
use DB;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper\PaginateHelper;

class SightController extends Controller {

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
        $provinces = Region::getProvinces();
        $searchForm = array(
            "category_id"=>array(
                "field_name"=>"category_id",
                "input_type"=>"text",
                "type"=>"=",
                "label"=>"Category",
                "value"=>News::CATEGORY_ID_SIGHT,
            ),
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

        $paginateHelper = new PaginateHelper(News::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);
        
        return view('sight.home', array(
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
        $searchForm = array(
            "category_id"=>array(
                "field_name"=>"category_id",
                "input_type"=>"text",
                "type"=>"=",
                "label"=>"Category",
                "value"=>News::CATEGORY_ID_SIGHT,
            ),
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

        $paginateHelper = new PaginateHelper(News::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);
        
        return view('sight.home', array(
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
    
    
    public function sightDetail($newId) {
        $sight = News::find($newId);
        
        $queryBuilder = $sight->newQuery();
        $queryBuilder->where("city_id",$sight->city_id);
        $queryBuilder->WhereNotIn("id",array($sight->id));
        $queryBuilder->getQuery()->limit(5);
        
        $relatedSight = $queryBuilder->get(array("*")); 
        return view('sight.detail', array(
                "sight"=>$sight,
				"trafficInfo"=>$sight->traffic_info,
                "recItems"=>$relatedSight,
                "hotels"=>$this->getHotels(),
                "travelNews"=>$this->getHotTravNews(),
                "controller"=>$this,
            )
        );
        
    }
    
    
    protected function getHotTravNews($provinceId = null,$cityId = null) {
        $sight = new News();
        $queryBuilder = $sight->newQuery();
        $queryBuilder->where("category_id",News::CATEGORY_ID_TRAVEL);
        if($cityId) {
            $queryBuilder->where("city_id",$cityId);
        }
        $queryBuilder->getQuery()->limit(12);
        $items = $queryBuilder->get(array('*'));
        return $items;
    }
    
    protected function getHotels($provinceId = null,$cityId = null) {
        
        $hotel = new Hotel();
        $queryBuilder = $hotel->newQuery();
        if($cityId) {
            $queryBuilder->where("city_id",$cityId);
        }
        $queryBuilder->getQuery()->limit(12);
        $items = $queryBuilder->get(array('*'));
        return $items;
    }
}
