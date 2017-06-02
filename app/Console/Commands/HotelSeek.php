<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use App\Helper\PinyinHelper;
use App\Helper\SeekerHelper;


class HotelSeek extends Command {

    /**
     * The console command name.
     *
     * @var string
     */
    protected $name = 'hotel:seek';

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
        $shellCommand = "ps aux | grep 'artisan hotel:seek ".$type."' | awk '{print $2}'";
        $result = shell_exec($shellCommand);
        $resultArray = explode("\n", $result);
        $resultArray = array_diff($resultArray, array(""));
        $resultArray = array_unique($resultArray);
        $countNumber = count($resultArray);
        if($countNumber > 3){
            die("No need to process it, wait the process." . PHP_EOL);
        }
        if($type == 0){
            $this->grabPages();
        }else{
            //$this->processQueue($start);
        }
    }

    /**
     * Get the pages.
     *
     * @return
     */

    protected function grabPages(){
       // $t = 'http://hotel.tuniu.com/ajax/list?search%5BcityCode%5D=200&search%5BcheckInDate%5D=2017-06-02&search%5BcheckOutDate%5D=2017-06-03&search%5Bkeyword%5D=%E4%B8%9C%E5%9F%8E%E5%8C%BA&suggest=&sort%5Bfirst%5D%5Bid%5D=recommend&sort%5Bfirst%5D%5Btype%5D=&sort%5Bsecond%5D=&sort%5Bthird%5D=cash-back-after&page=2&returnFilter=0';
        //echo urldecode($t);die();
        $homeHotelUrl = 'http://hotel.tuniu.com/ajax/getCities';
        //insert the main hotel url into hotel_search_url
        SeekerHelper::seekHotelMainUrl($homeHotelUrl);

        //insert the city url into hotel_search_url
        $sql = "SELECT * FROM hotel_search_url WHERE is_searched=0";
        $result = DB::selectOne($sql);

        if($result){
            echo "Start to seek {$result->name}({$result->code}):" . PHP_EOL;
            if($result->region_parent_id == 1){
                //get the citys
                $sql = "SELECT * FROM region WHERE parent_id = ?";
                $regions = DB::select($sql, array($result->region_id));
                //check has city
                $checkHasCity = false;
                foreach($regions as $region){
                    $checkHasCityData = SeekerHelper::seekHotelCityUrl($result->code, $region, $addKeyword=true);
                    if($checkHasCityData){
                        $checkHasCity = true;
                    }
                }
                if(!$checkHasCity){
                    $sql = "SELECT * FROM region WHERE id = ?";
                    $region = DB::selectOne($sql, array($result->region_id));
                    SeekerHelper::seekHotelCityUrl($result->code, $region, $addKeyword=false);
                }
            }else{
                $sql = "SELECT * FROM region WHERE id = ?";
                $region = DB::selectOne($sql, array($result->region_id));
                $checkHasCityData = SeekerHelper::seekHotelCityUrl($result->code, $region, $addKeyword=false);
            }
            echo "End to seek {$result->name}({$result->code})." . PHP_EOL;

            //update the status
            $sql = "UPDATE hotel_search_url SET is_searched = 1 WHERE id = ?";
            DB::update($sql, array($result->id));
        }
        //echo $result->code . PHP_EOL;
        //start to insert the city url

        $cmd = "nohup php ".base_path()."/artisan hotel:seek 0  1>> process.out 2>> process.err < /dev/null &";    //
        echo $cmd,"\n";
        system($cmd);

        

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
