<?php 
namespace App\Http\Controllers;

use App\Model\Region;
use App\Model\News;
use DB;


use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper\PaginateHelper;
use Illuminate\Support\Facades\Cookie;

class TravelController extends Controller {

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
                "value"=>News::CATEGORY_ID_TRAVEL,
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
                "value"=>News::CATEGORY_ID_TRAVEL,
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
        
        return view('travel.home', array(
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
            )
        );
        
		//return view('admin.system.menu', array("filter"=>$searchFormValue,"paginateHelper"=>$paginateHelper, "paginate"=>$paginate));
        
    }
    
    
    public function travelDetail($newId) {
        
       
       
        if(Cookie::get('travel_'.$newId)){
            $likeClass = 'link_like click_like';
        }else{
            $likeClass = 'link_like';
        }
        
        $travel = News::find($newId);
        $travel->click = $travel->click + 1;
        $travel->save();
        $city = $province = null;
        $city = Region::find($travel->city_id);
        $province = Region::find($travel->province_id);
        if($city){
            $mapCity = $city->name;
            $cityId = $city->id;
        }else{
            $mapCity = $province->name;
            $cityId = 0;
        }
        $provinceId = $province->id;
        return view('travel.detail', array(
                "travel"=>$travel,
                'likeClass'=>$likeClass,
                'city'=>$city,
                'province'=>$province,
                'cityId'=>$cityId,
                'provinceId'=>$provinceId,
            )
        );
        
    }
    
    public function travelEnjoy(){
        $newId = \Request::input('newId');
        Cookie::queue('travel_'.$newId, 1, 3600*24);
        $travel = News::find($newId);
        $travel->like = $travel->like + 1;
        $travel->save();
        return $travel->like;
    }
}
