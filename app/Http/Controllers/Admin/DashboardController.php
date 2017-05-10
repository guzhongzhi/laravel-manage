<?php 
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Auth;

class DashboardController extends AdminBaseController {

	/**
	 * Show the application welcome screen to the user.
	 *
	 * @return Response
	 */
	public function index()
	{
		return view('admin.dashboard');
	}

    public function logout() {
        Auth::logout();
        return redirect("admin/login")->withInput();
    }
}
