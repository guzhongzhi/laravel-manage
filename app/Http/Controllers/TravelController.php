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

        
        $paginateHelper = new PaginateHelper(News::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);
        
        return view('travel.home', array(
                "provinces"=>$provinces,
                "paginateHelper"=>$paginateHelper,
                "paginate"=>$paginate,
                "news"=>$paginate,
                "provinceId"=>$provinceId,
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
        return view('travel.detail', array(
                "travel"=>$travel,
                'likeClass'=>$likeClass,
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
