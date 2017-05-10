<?php 
namespace App\Http\Controllers;

use App\Model\Region;
use App\Model\News;
use DB;

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
        $sql = "SELECT * FROM news WHERE category_id = " . News::CATEGORY_ID_SIGHT . " limit 30";
        $news = DB::select($sql);
		return view('sight.home', array("provinces"=>Region::getProvinces(),"news"=>$news));
	}

}
