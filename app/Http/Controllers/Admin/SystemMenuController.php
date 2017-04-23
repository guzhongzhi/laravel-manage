<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Menu;
use Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Helper\PaginateHelper;
class SystemMenuController extends AdminBaseController {

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index(Request $request)
	{

        $searchForm = array(
            "name"=>array(
                "field_name"=>"name",
                "input_type"=>"text",
                "type"=>"like",
                "label"=>"Name",
            ),
            "url"=>array(
                "field_name"=>"url",
                "input_type"=>"text",
                "type"=>"like",
                "label"=>"Url",
            ),
            "show_in_menu"=>array(
                "field_name"=>"show_in_menu",
                "input_type"=>"mutiselect",
                "type"=>"=",
                "label"=>"Menu",
                "options"=>array(
                    "1"=>"Yes",
                    "0"=>"No",
                ),
            ),

        );
        $searchData = $request->get("filter", array());
        foreach($searchData as $key=>$value) {
            $searchForm[$key]["value"] = $value;
        }
        //print_r($searchForm);
        /**
         * @var \Illuminate\Database\Eloquent\Builder $queryBuilder
         */


        $paginateHelper = new PaginateHelper(Menu::class);
        $paginate = $paginateHelper->getPaginate($searchForm);

		return view('admin.system.menu', array("filter"=>$searchForm, "paginate"=>$paginate));
	}

    public function logout() {
        Auth::logout();
        return redirect("admin/login")->withInput();
    }

    public function edit($id) {
        $menu = Menu::find($id);
        if(!$menu) {
            $menu = new Menu();
            $title = "Add New Menu";
        } else {
            $title = $menu->name . " (".$menu->id.") Edit";
        }
        $view = view("admin.system.menu-edit",array("menu"=>$menu));
        $html = $view->render();


        return new JsonResponse(array("title"=>$title,"content"=>$html));

    }

}
