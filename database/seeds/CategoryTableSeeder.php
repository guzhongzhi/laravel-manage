<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\Country;
use App\Model\Province;
use App\Model\Category;

class CategoryTableSeeder extends Seeder {
    
    public function run() {
        DB::table("category")->delete();
        
        $categories = array(
            "旅游景点",
            "旅游攻略",
            "酒点住宿",
            "旅行团",
            "游记",
        );
        foreach($categories as $c) {
            $category = new Category();
            $category->name = $c;
            $category->parent_id = 0;
            $category->save();
        }
    }
    
}