<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helper\PinyinHelper;
use App\Helper\SeekerHelper;


class FoodSeek extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'food:seek';

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
        $shellCommand = "ps aux | grep 'artisan food:seek ".$type."' | awk '{print $2}'";
        $result = shell_exec($shellCommand);
        $resultArray = explode("\n", $result);
        $resultArray = array_diff($resultArray, array(""));
        $resultArray = array_unique($resultArray);
        $countNumber = count($resultArray);
        if($countNumber > 3){
            die("No need to process it, wait the process." . PHP_EOL);
        }
        if($type == 0){
            $this->grabPages($start);
        }else{
            $this->processQueue($start);
        }
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

            foreach($provinces as $province){
                $provinceName = $province->name;
                $pId = $province->id;
                echo "Start to seek the cncn food province - $provinceName: \n";
                $provicenPY = SeekerHelper::getCnCnUrlKey(strtolower($province->short_name_en));
                if(!$provicenPY){
                    $provicenPY = str_replace(' ', '', strtolower($province->name_en));
                    $provicenPY  = preg_replace('/sheng$/si', '', $provicenPY);
                    $provicenPY  = preg_replace('/diqu$/si', '', $provicenPY);
                    $provicenPY  = str_replace(array('(', ')', ','), '', $provicenPY);
                }
                
                $sqlCity = "SELECT * FROM region WHERE parent_id = '$pId'";
                $cities = DB::select($sqlCity);
                $checkHasCityUrlKey = false;
                
                foreach($cities as $city){
                    $cityId = $city->id;
                    $cityName = $city->name;
                    if($city->name_en == 'Shixiaqu' || $city->name_en == 'Xian' || $city->name_en == 'shengzhixiaxianjixingzhengquhua'){
                        continue;
                    }

                    //get it from the match
                    $cityPY = SeekerHelper::getCnCnUrlKey(strtolower($city->short_name_en));
                    if(!$cityPY){
                        $cityPY  = str_replace(' ', '', strtolower($city->name_en));
                        $cityPY  = preg_replace('/shi$/si', '', $cityPY);
                        $cityPY  = preg_replace('/diqu$/si', '', $cityPY);
                        $cityPY  = str_replace(array('(', ')', ','), '', $cityPY);
                    }




                    echo $city->id . " - " . $cityPY . PHP_EOL;
                    $mainDomainUrl = "http://$cityPY.cncn.com";
                    $grabUrl = "$mainDomainUrl/meishi/";
                    echo $grabUrl,PHP_EOL;
                    $checkValidate = SeekerHelper::checkUrlValidate($grabUrl);

                    if($checkValidate){
                        $checkHasCityUrlKey = true;
                        $mainData = array('main_domain_url'=>$mainDomainUrl, 'country_id'=>$cId, 'province_id'=>$pId, 'city_id'=>$cityId, 'type'=>SeekerHelper::SEEK_CNCN_FOOD_TYPE);
                        SeekerHelper::insertCNCNUrl($grabUrl, $mainData);
                        echo "Seek $cityName Done. \n";
                    }else{;
                        echo "Seek $cityName Failed. \n";
                    }
                    
                }
                if(!$checkHasCityUrlKey){
                    $mainDomainUrl = "http://$provicenPY.cncn.com";
                    $grabUrl = "$mainDomainUrl/meishi/";
                    $mainData = array('main_domain_url'=>$mainDomainUrl, 'country_id'=>$cId, 'province_id'=>$pId, 'type'=>SeekerHelper::SEEK_CNCN_FOOD_TYPE);
                    SeekerHelper::insertCNCNUrl($grabUrl, $mainData);
                }
                echo "End to seek the cncn food province - $provinceName.\n";
                break;
                
            }
            if(count($provinces) > 0){
                $start++;
                $cmd = "nohup php ".base_path()."/artisan food:seek 0 " .$start ."  1>> process.out 2>> process.err < /dev/null &";    //  
                echo $cmd,"\n";
                system($cmd);
                break;
            }
        
        }

    }
    
    
    protected function processQueue($start){
        $sql = "SELECT * FROM food_store_queue WHERE is_searched = 0 limit $start, 10";
        $rows = DB::select($sql);
        foreach($rows as $row){
            $foodId = $row->food_id;
            $urlSeceret = $row->store_secret;
            $store  = DB::selectOne("select id from store WHERE source_url_secret = ? limit 1", [$urlSeceret]);
            if($store){
                try{
                    $storeId = $store->id;
                    $sql = "INSERT INTO food_store(`food_id`, `store_id`) VALUE(?, ?)";
                    DB::insert($sql, [$foodId, $storeId]);
                }catch(\Exception $e){
                    //do nothing
                }
                //update the table
                $sql = "UPDATE food_store_queue SET is_searched = 1 WHERE id = ?";
                DB::update($sql, array($row->id));
            }
        }
        
        if(count($rows) > 0){
            //$start = $start + 50;
            $cmd = "nohup php ".base_path()."/artisan food:seek 1 " .$start ."  1>> process.out 2>> process.err < /dev/null &";    //  
            echo $cmd,"\n";
            system($cmd);
        }
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
