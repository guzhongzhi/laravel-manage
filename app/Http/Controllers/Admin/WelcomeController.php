<?php 
namespace App\Http\Controllers\Admin;
use Auth;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;


class WelcomeController extends AdminBaseController {
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		$this->middleware('admin');
        $this->setGlobalVariables();
	}

    public function login(Request $request) {
        return view('admin/login');
    }

    public function loginPost(Request $request) {
        $email = $request->get("email");

        if (Auth::attempt(array('email'=> $request->get("email") , 'password'=>$request->get("password")))) {
            $this->addSuccessMessage("login succeed");
            return redirect('admin/dashboard')->withInput();
        } else {
            $this->addErrorMessage("invalid username or password");
            return redirect('admin/login')->withInput();
        }
    }
}
