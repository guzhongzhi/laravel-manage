<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\Menu;
use App\Model\Role;

class MenuAndRoleTableSeeder extends Seeder {
    
    public function run() {
        DB::table("menu")->delete();
        DB::table("menu_role")->delete();
        
        $menuItems = array(
            array(
                "name"=>"Dashboard",
                "url"=>"/admin/dashboard",
                "icon"=>"icon-home",
                "show_in_menu"=>1
            ),
            
            array(
                "name"=>"System",
                "url"=>"/admin/system",
                "icon"=>"icon-cog",
                "show_in_menu"=>1
            ),
            array(
                "name"=>"Settings",
                "url"=>"/admin/system/configuration",
                "icon"=>"",
                "show_in_menu"=>1
            ),
            array(
                "name"=>"Menu",
                "url"=>"/admin/system/menu",
                "icon"=>"",
                "show_in_menu"=>1
            ),
            array(
                "name"=>"Menu Edit",
                "url"=>"/admin/system/menu/edit/{id}",
                "icon"=>"",
                "show_in_menu"=>0
            ),
        );
        foreach($menuItems as $menuItem) {
            $menu = new Menu();
            $menu->url = $menuItem["url"];
            $menu->name = $menuItem["name"];
            $menu->icon = $menuItem["icon"];
            $menu->show_in_menu = $menuItem["show_in_menu"];
            $menu->save();
        }
        
        $roleItems = array(
            array(
                "name"=>"Administrator",
            )
        );
        foreach($roleItems as $roleItem) {
            $role = new Role();
            $role->name = $roleItem["name"];
            $role->save();
        }
        
    }
    
}