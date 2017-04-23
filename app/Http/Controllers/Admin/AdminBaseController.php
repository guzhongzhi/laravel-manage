<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Model\Menu;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Psy\Exception\ErrorException;
use Illuminate\Http\Request;

class AdminBaseController extends Controller {
    /**
     * @var Request $request
     */
    protected  $request = null;
    public  $globalErrorMessage = "";
    public  $globalSuccessMessage = "";
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('auth.admin');
        $this->setGlobalVariables();
	}

    protected function setGlobalVariables() {
        $message = $actionName = "";
        /**
         * @var Request $request
         */
        $request = app("request");
        if(!$this->request) {
            $this->request = $request;
        }


        $this->globalSuccessMessage = Session::has("global_success_message") ? Session::get("global_success_message") : "";
        $this->globalErrorMessage = Session::has("global_error_message") ? Session::get("global_error_message") : "";
        $this->request->session()->flash("global_success_message","");
        $this->request->session()->flash("global_error_message","");


        $curentRequestUri = $request->getRequestUri();
        $curentRequestUri = explode("?",$curentRequestUri);
        $curentRequestUri = $curentRequestUri[0];
        $curentRequestUri = rtrim($curentRequestUri,"/") . "/";

        $user = $request->user();
        if($this->getRouter()) {
            $actionName =  $this->getRouter()->current()->getActionName();
            $temp = explode("@",$actionName);
            if(isset($temp[1])) {
                $actionName = "admin-" . $temp[1];
            }
        }
        view()->share('siteName', "My First Laravel");
        view()->share('htmlBodyCssName', $actionName);
        view()->share('authorizedUser', $user);
        view()->share('currentController', $this);

        view()->share("globalSuccessMessage",$this->globalSuccessMessage);
        view()->share("globalErrorMessage", $this->globalErrorMessage);

        view()->share("GlobalLeftMenuItems", $this->generateMenu($showAll = false));
        view()->share("currentUrl", $curentRequestUri);
    }

    static $rootMenuItems = null;

    protected function generateMenu($showAll = false) {
        if(self::$rootMenuItems && $showAll == false) {
            return self::$rootMenuItems;
        }
        /**
         * @var \Illuminate\Database\Eloquent\Collection $rows
         */
        $rows = Menu::orderBy("sort_order","ASC")->get();

        $rootMenuItems = $this->filterChildMenuItems(0, $rows, $showAll);
        foreach($rootMenuItems as $rootMenuItem) {
            $rootMenuItem->url = rtrim($rootMenuItem->url,"/") . "/";
            $childMenuItems = $this->filterChildMenuItems($rootMenuItem->id,$rows, $showAll);
            $rootMenuItem->setChilds($childMenuItems);
        }
        return $rootMenuItems;
    }

    protected function filterChildMenuItems($parentId,$rows, $showAll = false) {
        $childMenuItems = array();
        foreach($rows as $row) {
            if($row->show_in_menu == false && $showAll == false) {
                continue;
            }
            if($row->parent_id == $parentId) {
                $row->url = rtrim($row->url,"/") . "/";
                $childMenuItems[] = $row;
            }
        }
        return $childMenuItems;
    }

    protected function addErrorMessage($msg) {
        $this->request->session()->flash("global_error_message",$msg);
    }

    protected function addSuccessMessage($msg) {
        $this->request->session()->flash("global_success_message",$msg);
    }

    public function reinitGlobalMessages() {
        $this->globalSuccessMessage .= Session::has("global_success_message") ? Session::get("global_success_message") : "";
        $this->globalErrorMessage .= Session::has("global_error_message") ? Session::get("global_error_message") : "";
        $this->request->session()->flash("global_success_message","");
        $this->request->session()->flash("global_error_message","");
    }

}
