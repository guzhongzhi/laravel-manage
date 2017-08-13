<?php namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

    use DispatchesCommands, ValidatesRequests;

    public function getConfig() {
        return array(
            "site_name"=>"愈惜旅游网",
            "site_keywords"=>"愈惜旅游网,旅游,旅游网,旅游线路,旅游攻略,国内游",
            "site_description"=>"愈惜旅游网为您提供最新的旅游资讯，旅游线路，旅游攻略。",
        );
    }
}
