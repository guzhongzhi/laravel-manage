<?php 
namespace App\Http\Controllers;

use App\Model\Region;
use App\Model\News;
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
            )
        );
        
		//return view('admin.system.menu', array("filter"=>$searchFormValue,"paginateHelper"=>$paginateHelper, "paginate"=>$paginate));
        
    }
    
    
    public function sightDetail($newId) {
        $sight = News::find($newId);
        
        
        
        return view('sight.detail', array(
                "sight"=>$sight,
            )
        );
        
    }
}
