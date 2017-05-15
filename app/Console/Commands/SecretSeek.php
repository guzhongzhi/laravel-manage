<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helper\PinyinHelper;
use App\Helper\SeekerHelper;


class SecretSeek extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'secret:seek';

	/**
	 * The console command description.
	 *
	 * @var string
	 */
	protected $description = 'Command description.';

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * Execute the console command.
	 *
	 * @return mixed
	 */
	public function fire(){
        $arguments = $this->argument();
        $type = $arguments['type'];
        $start = $arguments['start'];
        if($type == 0){
            $this->grabPages($start);
        }else{
            $this->insertPageContent($start);
        }
        var_dump($arguments);
        die();
	}

    /**
	 * Get the pages.
	 *
	 * @return 
	 */
     
     protected function grabPages($start){
         
        //get the citys
        $sql = "SELECT * FROM region WHERE parent_id = 0";
        $countries = DB::select($sql);
        foreach($countries as $country){
            $cId = $country->id;
            $sqlProvice = "SELECT * FROM region WHERE parent_id = '$cId' limit $start, 1";
            $provinces = DB::select($sqlProvice);
            //seek the url from the site url:http://chengdu.cncn.com/lvyougonglue/1
           
            
            //for cncn
            
            //$grabUrl = 'http://you.ctrip.com/place/chengdu104.html';
            //$grabUrl = 'http://you.ctrip.com/travels/chengdu104/1703488.html';

            foreach($provinces as $province){
                $provinceName = $province->name;
                $pId = $province->id;
                echo "Start to seek the cncn province - $provinceName: \n";
                $provicenPY = str_replace(' ', '', strtolower($province->name_en));
                $provicenPY = rtrim($provicenPY, 'shi');
                $sqlCity = "SELECT * FROM region WHERE parent_id = '$pId'";
                $cities = DB::select($sqlCity);
                $checkHasCityUrlKey = false;
                
                foreach($cities as $city){
                    $cityId = $city->id;
                    $cityName = $city->name;
                    if($city->name_en == 'Shixiaqu' || $city->name_en == 'Xian' || $city->name_en == 'shengzhixiaxianjixingzhengquhua'){
                        continue;
                    }
                    $cityPY = str_replace(' ', '', strtolower($city->name_en));
                    $cityPY = rtrim($cityPY, 'shi');
                    //echo $city->id . " - " . $cityPY . "\n";
                    $mainDomainUrl = "http://$cityPY.cncn.com";
                    $grabUrl = "$mainDomainUrl/lvyougonglue/1";
                    //echo $grabUrl;
                    $checkValidate = SeekerHelper::checkUrlValidate($grabUrl);
                    
                    if($checkValidate){
                        $checkHasCityUrlKey = true;
                        $mainData = array('main_domain_url'=>$mainDomainUrl, 'country_id'=>$cId, 'province_id'=>$pId, 'city_id'=>$cityId);
                        SeekerHelper::insertCNCNUrl($grabUrl, $mainData);
                        echo "Seek $cityName Done. \n";
                    }else{;
                        echo "Seek $cityName Failed. \n";
                    }
                    
                }
                
                if(!$checkHasCityUrlKey){
                    $mainDomainUrl = "http://$provicenPY.cncn.com";
                    $grabUrl = "$mainDomainUrl/lvyougonglue/1";
                    $mainData = array('main_domain_url'=>$mainDomainUrl, 'country_id'=>$cId, 'province_id'=>$pId);
                    SeekerHelper::insertCNCNUrl($grabUrl, $mainData);
                }
                echo "End to seek the cncn province - $provinceName.\n";
            }
            
           
            //for http://you.ctrip.com/
           
            $ctripUrl = "http://you.ctrip.com/travels/URL_KEY/t2.html";
            $mainDomainUrl = "http://you.ctrip.com";
            foreach($provinces as $province){
                $pId = $province->id;
                $provinceName = $province->name;
                echo "Start to seek the ctrip province - $provinceName: \n";
                $mainData = array('main_domain_url'=>$mainDomainUrl, 'country_id'=>$cId, 'province_id'=>$pId);
                //echo "$pId - $provinceName \n";
                $sqlCity = "SELECT * FROM region WHERE parent_id = '$pId'";
                $cities = DB::select($sqlCity);
                $checkHasCityUrlKey = false;
                foreach($cities as $city){
                    $cityName = $city->name;
                    if($city->name_en == 'Shixiaqu' || $city->name_en == 'Xian' || $city->name_en == 'shengzhixiaxianjixingzhengquhua'){
                        continue;
                    }
                    $cityId = $city->id;
                    $urlKey = SeekerHelper::getCtripUrlKey($cityName);
                    $mainData['city_id'] = $cityId;
                    if(!$urlKey){
                        echo "$cityId -  $cityName has no urlKey \n";
                        continue;
                    }
                    $checkHasCityUrlKey = true;
                    //echo $city->id . " - " . $cityName   . " - $urlKey \n";
                    //continue;
                    $grabUrl = str_replace('URL_KEY', $urlKey, $ctripUrl);
                    SeekerHelper::insertCtripUrl($grabUrl, $mainData);                    
                }
             
                
                if(!$checkHasCityUrlKey){
                    $urlKey = SeekerHelper::getCtripUrlKey($provinceName);
                    if($urlKey){
                        //do the things
                        $grabUrl = str_replace('URL_KEY', $urlKey, $ctripUrl);
                        echo  "$pId - " . $provinceName   . " - $urlKey \n";
                        //continue;
                        SeekerHelper::insertCtripUrl($grabUrl, $mainData);
                    }else{
                        echo "Need to isnert: $provinceName \n";
                    }
                }
                echo "End to seek the ctrip province - $provinceName.\n";
            }
            
            if(count($provinces) > 0){
                $start++;
                $cmd = "nohup php artisan secret:seek 0 " .$start ."  1>> process.out 2>> process.err < /dev/null &";    //  
                echo $cmd,"\n";
                system($cmd);
            }
        
        }

    }
    
    protected function insertPageContent($start){
        $sql = "SELECT * FROM search_url WHERE is_searched = 0 limit $start, 50";
        $rows = DB::select($sql);
        foreach($rows as $row){
            $type = $row->type;
          
            if($type == 'ctrip'){
                $content = SeekerHelper::insertCtripContent($row);
            }else{
                $content = SeekerHelper::insertCNCNContent($row);
            }
            //update the table
            $sql = "UPDATE search_url SET is_searched = 1 WHERE id = ?";
            DB::update($sql, array($row->id));
        }
        
        die("Done");
        if(count($rows) > 0){
            $start = $start + 50;
            $cmd = "nohup php artisan secret:seek 1 " .$start ."  1>> process.out 2>> process.err < /dev/null &";    //  
            echo $cmd,"\n";
            system($cmd);
        }
    }
     
  

     /**
	 * Get the pages.
	 *
	 * @return 
	 */
     
     protected function seachPage(){
         
     }
     
     
    
	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['type', InputArgument::REQUIRED, 'the type of seek, 0->grab, 1->search'],
			['start', InputArgument::REQUIRED, 'the start of the number'],
			
		];
	}

	/**
	 * Get the console command options.
	 *
	 * @return array
	 */
	protected function getOptions()
	{
		return [
			//['example', null, InputOption::VALUE_OPTIONAL, 'An example option.', null],
		];
	}

}
