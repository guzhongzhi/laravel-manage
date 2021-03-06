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
            "parent_id"=>array(
                "field_name"=>"parent_id",
                "input_type"=>"text",
                "type"=>"=",
                "value"=>"",
                "label"=>"Parent ID",
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
        $searchFormValue = PaginateHelper::initSearchFieldData($searchData,$searchForm);
        /**
         * @var \Illuminate\Database\Eloquent\Builder $queryBuilder
         */


        $paginateHelper = new PaginateHelper(Menu::class);
        $paginate = $paginateHelper->getPaginate($searchFormValue);

		return view('admin.system.menu', array("filter"=>$searchFormValue,"paginateHelper"=>$paginateHelper, "paginate"=>$paginate));
	}
    
    public function configuration() {
        return view('admin.system.configuration');
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
