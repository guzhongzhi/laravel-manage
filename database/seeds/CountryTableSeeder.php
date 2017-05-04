<?php
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use App\Model\Country;
use App\Model\Province;
use App\Model\City;

class CountryTableSeeder extends Seeder {
    
    public function run() {
        DB::table("country")->delete();
        DB::table("province")->delete();
        DB::table("city")->delete();
        
        $countries = array(
            "zh"=>array(
                "name"=>"中国",
                "code2"=>"cn",
                "code3"=>"cn",
                "provinces"=>'北京市,天津市,上海市,重庆市,河北省,山西省,辽宁省,吉林省,黑龙江省,江苏省,浙江省,安徽省,福建省,江西省,山东省,河南省,湖北省,湖南省,广东省,海南省,四川省,贵州省,云南省,陕西省,甘肃省,青海省,台湾省,内蒙古自治区,广西壮族自治区,西藏自治区,宁夏回族自治区,新疆维吾尔自治区,香港特别行政区,澳门特别行政区',
            ),
        );
        foreach($countries as $countryData) {
            $country = new Country();
            $country->name = $countryData['name'];
            $country->code2 = $countryData['code2'];
            $country->code3 = $countryData['code3'];
            $country->created_at = date("Y-m-d H:i:s");
            $country->updated_at = date("Y-m-d H:i:s");
            $country->save();
            $ps = explode(",", $countryData["provinces"]);
            foreach($ps as $name) {
                $province = new Province();
                $province->name = $name;
                $province->country_id = $country->id;
                $province->save();
                
                $oldP = DB::select('select * from city_source where name = :name', [':name'=>$name]);
                if(isset($oldP[0])) {
                    $oldP = $oldP[0];
                } else {
                    continue;
                }
                $pars = array(
                    ":parent_id"=>$oldP->id,
                );
                $cities = DB::select('select * from city_source where parent_id = :parent_id', $pars);
                
                foreach($cities as $cityData) {
                    $city = new City();
                    $city->name = $cityData->name;
                    $city->province_id = $province->id;
                    $city->country_id = $country->id;
                    $city->save();
                }
            }
        }
        
    }
    
}