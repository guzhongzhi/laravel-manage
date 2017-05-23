<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

    public function getConfig() {
        return array(
            "site_name"=>"AA旅游网",
            "site_keywords"=>"AA旅游网",
            "site_description"=>"AA旅游网",
        );
    }
}
