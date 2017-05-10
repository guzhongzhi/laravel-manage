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
	public function index()
	{
        $queryBuilder = News::select();
        $news = $queryBuilder ->paginate(30);
        
		return view('sight.home', array("provinces"=>Region::getProvinces(),"news"=>$news));
	}

    public function provinceList(Request $request, $provinceId) {
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
                "value"=>$provinceId,
            ),
        );
        $searchData = $request->get("filter", array());
        $searchFormValue = PaginateHelper::initSearchFieldData($searchData,$searchForm);


        $paginateHelper = new PaginateHelper(News::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);
        
        return view('sight.home', array("provinces"=>Region::getProvinces(),"news"=>$paginate));
        
		//return view('admin.system.menu', array("filter"=>$searchFormValue,"paginateHelper"=>$paginateHelper, "paginate"=>$paginate));
        
    }
    
    
    public function sightDetail($newId) {
        echo $newId;
        echo __METHOD__;die();
    }
}
