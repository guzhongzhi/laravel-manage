<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helper\PinyinHelper;
use App\Helper\SeekerHelper;
use App\Model\News;


class SightSeek extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'sight:seek';

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
        $type  = $arguments['type'];
        $start = $arguments['start'];
        $regionId = $arguments['regionId'];
        $shellCommand = "ps aux | grep 'artisan sight:seek $type $start ' | awk '{print $2}'";
        $result = shell_exec($shellCommand);
        $resultArray = explode("\n", $result);
        $resultArray = array_diff($resultArray, array(""));
        $resultArray = array_unique($resultArray);
        $countNumber = count($resultArray);
        if($countNumber > 3){
            die("No need to process it, wait the process." . PHP_EOL);
        }
        if($type == 0){
            $this->grabPages($type, $start, $regionId);
        }else{
            $this->grabImages($type, $start);
        }

    }

    /**
     * Get the pages.
     *
     * @return
     */

    protected function grabPages($type, $start, $regionId=0){

        //get the citys
        $sql = "SELECT * FROM region WHERE parent_id = 0";
        $countries = DB::select($sql);
        foreach($countries as $country){
            $cId = $country->id;
            $regionSql = '';
            if($regionId){
                $regionSql = " AND id='$regionId' ";
            }
            $sqlProvince = "SELECT * FROM region WHERE parent_id = '$cId' $regionSql limit $start, 1";
            $provinces = DB::select($sqlProvince);
            //seek the url from the site url: http://you.ctrip.com/searchsite/Sight?query=

            foreach($provinces as $province){
                $provinceName = $province->name;
                $pId = $province->id;
                echo "Start to seek the ctrip sight province - $provinceName.\n";
                $provinceQuery = str_replace(' ', '', ($province->name));
                $provinceQuery  = preg_replace('/省$/si', '', $provinceQuery);
                $provinceQuery  = preg_replace('/地区$/si', '', $provinceQuery);
                $provinceQuery  = preg_replace('/区$/si', '', $provinceQuery);
                $provinceQuery  = preg_replace('/市$/si', '', $provinceQuery);
                $provinceQuery  = str_replace(array('(', ')', ','), '', $provinceQuery);
                $provinceQuery = mb_substr( $provinceQuery, 0, 2, 'utf8');


                $sqlCity = "SELECT * FROM region WHERE parent_id = '$pId'";
                $cities = DB::select($sqlCity);
                $checkHasCityUrlKey = false;

                foreach($cities as $city){
                    $cityId = $city->id;
                    $cityName = $city->name;
                    if($city->name_en == 'Shixiaqu' || $city->name_en == 'shengzhixiaxianjixingzhengquhua'  || $city->name_en == 'zizhiquzhixiaxianjixingzhengquhua'){
                        continue;
                    }
                    $cityQuery  = str_replace(' ', '', ($city->name));
                    $cityQuery  = preg_replace('/市$/si', '', $cityQuery);
                    $cityQuery  = preg_replace('/地区$/si', '', $cityQuery);
                    $cityQuery  = preg_replace('/区$/si', '', $cityQuery);
                    $cityQuery  = preg_replace('/县$/si', '', $cityQuery);
                    $cityQuery  = str_replace(array('(', ')', ','), '', $cityQuery);
                    $cityQuery = mb_substr( $cityQuery, 0, 2, 'utf8');


                    echo $city->id . " - " . $cityQuery . PHP_EOL;
                    $mainDomainUrl = "http://you.ctrip.com";
                    $searchQuery = $provinceQuery . $cityQuery;
                    $grabUrl = "http://you.ctrip.com/searchsite/Sight?query=".$searchQuery;
                    echo $grabUrl . PHP_EOL;
                    $mainData = array('main_domain_url'=>$mainDomainUrl, 'country_id'=>$cId, 'province_id'=>$pId, 'page_no'=>1,
                                      'city_id'=>$cityId, 'type'=>SeekerHelper::SEEK_CTRIP_SIGHT_TYPE, 'search_query'=>$searchQuery,
                                );
                    $checkContent = SeekerHelper::insertCtripSightUrl($grabUrl, $mainData);
                    echo "Seek $cityName Done. \n";
                }

                //for province
                $searchQuery = $provinceQuery;
                $mainData = array('main_domain_url'=>$mainDomainUrl, 'country_id'=>$cId, 'province_id'=>$pId, 'page_no'=>1,
                    'city_id'=>0, 'type'=>SeekerHelper::SEEK_CTRIP_SIGHT_TYPE, 'search_query'=>$searchQuery,
                );
                $grabUrl = "http://you.ctrip.com/searchsite/Sight?query=".$searchQuery;
                SeekerHelper::insertCtripSightUrl($grabUrl, $mainData);
                echo "End to seek the ctrip sight province - $provinceName.\n";
                break;
            }
            if(count($provinces) > 0){
                $start++;
                $cmd = "nohup php ".base_path()."/artisan sight:seek $type $start $regionId 1>> process.out 2>> process.err < /dev/null &";    //
                echo $cmd,"\n";
                system($cmd);
                break;
            }

        }

    }

    protected function grabImages($type, $start){
        $sql = "SELECT id , title FROM news WHERE `category_id`=? limit $start, 10";
        $p = array(News::CATEGORY_ID_SIGHT);
        $rows = DB::select($sql, $p);
        foreach($rows as $row){
            echo "Start to process {$row->id} {$row->title}" .PHP_EOL;
            $urls = SeekerHelper::searchImages($row->title);
            $pic = isset($urls[0]) ? $urls[0] : "";
            if($pic){
                $this->saveSightImages($row->id, $urls);
                $sqlUpdate = "UPDATE news SET pic=? WHERE id = ?";
                $p = array($pic, $row->id);
                DB::update($sqlUpdate, $p);
            }
        }

        if(count($rows) > 0){
            $start = $start + 10;
            $cmd = "nohup php ".base_path()."/artisan sight:seek $type $start 1>> process_sight_$type_$start.out 2>> process_sight_$type_$start.err < /dev/null &";    //
            echo $cmd,"\n";
            system($cmd);
        }

    }

    protected function saveSightImages($sightId, $urls){
        try {
            foreach ($urls as $url) {
                $insertUrl = 'INSERT INTO news_image(`id`, `news_id`, `url`, `created_at`, `updated_at`) VALUES(NULL, ?, ?, NOW(), NOW());';
                $p = array($sightId, $url);
                DB::insert($insertUrl, $p);
            }
        }catch (\Exception $e){

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
            ['type', InputArgument::REQUIRED, '0->search url,1->search image'],
            ['start', InputArgument::REQUIRED, 'the start of the number'],
            ['regionId', InputArgument::OPTIONAL, 'the region id if we need it'],

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
