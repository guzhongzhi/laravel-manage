<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use DB;
include "Pinyin.php";
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
	public function fire()
	{
        try {
            $sql = "SELECT * FROM region WHERE parent_id = 0";
            $countries = DB::select($sql);
        
        foreach($countries as $country) {
            $countryId = $country->id;
            
            $sql = "SELECT * FROM region WHERE parent_id = " . $countryId;
            
            $provinces = DB::select($sql);
            
            foreach($provinces as $province) {
                $provinceName = $province->name."";
                $provinceName = rtrim($provinceName,"市");
                $provinceName = rtrim($provinceName,"省");
                $enname = \CUtf8_PY::encode($provinceName,"all","");
                $page = 1;
                
                do {
                    sleep(5);
                    $urlHead = "http://" . $enname . ".cncn.com/jingdian/";
                    $url = $urlHead ."1-".$page."-0-0.html";
                    
                    $urlRegex = $enname . ".cncn.com\/jingdian\/";
                    echo $url,PHP_EOL;
                    try {
                        $content = file_get_contents($url);
                        $content = iconv("gbk","utf-8",$content);
                        
                        preg_match_all('/共(\d+)页/is',$content,$matches);
                        $totalPage = isset($matches[1][0]) ? $matches[1][0] : 1;
                    } catch (\Exception $ex) {
                        continue;
                    }
                    $this->processListContent($content, $province, $country,$urlRegex);
                    $page++;
                }while($page <= $totalPage);
                
            }
            
        }
        }catch(\Exception $ex) {
            echo $ex->__toString();
        }
	}
    
    protected function processListContent($content, $province, $country,$urlRegex) {
        
        //echo $content;die();
        
        $content = str_replace('<a ',PHP_EOL.'<a ',$content);
        //echo $content;
        //echo PHP_EOL,PHP_EOL,PHP_EOL,PHP_EOL,PHP_EOL;
        
        $regex = '/<a.*?href="http:\/\/'.$urlRegex.'.*?>(.*?)<\/a>/i';
        echo $regex,PHP_EOL;
        preg_match_all($regex,$content,$matches);
        print_r($matches);
        $matchedLines = $matches[0];
        
        foreach($matchedLines as $lineContent) {
            preg_match('/href="(.*?)"/is',$lineContent,$matches);
            $jinDianUrl = $matches[1];
            
            if(substr($jinDianUrl,-10) == "/jingdian/") {
                continue;
            }
            
            try {
                $this->seekJianDian($jinDianUrl . "profile", $province, $country);
            } catch (\Exception $ex) {
                echo $ex->__toString();
            }
        }
    }
    
    function seekJianDian($url, $province, $country) {
        
        if($url == "http://Beijing.cncn.com/jingdian/8haowenquanshangwuhuiguan/profile") {
            return ;
        }
        
        if($url == "http://Beijing.cncn.com/jingdian/dongyuemiao/profile") {
            return ;
        }
        
        
        
        echo $url,PHP_EOL;
        
        $sql = "SELECT * FROM news WHERE source_url = ?";
        $row = DB::select($sql,[$url]);
        if(!empty($row)) {
            return ;
        }
        
        $content = file_get_contents($url);
        $content = iconv("GBK","UTF-8",$content);
        
        $content = preg_replace('/<div[^>]*?class="hide_box"[^>]*?>.*?<\/div>/is','',$content);
        
        
        preg_match_all('/<div[^>]*?ndwz[^>]*?>(.*?)<\/div>/is',$content,$matches);
        
        $bread = isset($matches[1][0]) ? $matches[1][0] : "";;
        
        if($bread == "") {
            return;
        }
        
        preg_match_all('/<a.*?>(.*?)<\/a>/is',$bread,$matches);
        
        $matches = $matches[1];
        
        
        $sightName = $matches[count($matches) - 1];
        
        echo $sightName;
        
        preg_match_all('/<h1>(.*?)<\/h1>/is',$content,$matches);
        $title = $matches[1][0];
        
        preg_match_all('/<div[^>]*?class="top"[^>]*?>(.*?)<\/div>/is',$content,$matches);
        $shotTopDesc = $matches[1][0];
        $content = str_replace($matches[0][0],'',$content);
        
        preg_match_all('/<div[^>]*?class="type"[^>]*?>(.*?)<\/div>/is',$content,$matches);
        $content = $matches[1][0];
        News::create(array(
            "category_id"=>1,
            "province_id"=>$province->id,
            "country_id"=>$country->id,
            "content"=> '<div class="jindian-base">'.$shotTopDesc . '</div><div class="jiandian-content">' . $content . '</div>',
            "title"=>$title,
            "source_url"=>$url,
        ));
    }

	/**
	 * Get the console command arguments.
	 *
	 * @return array
	 */
	protected function getArguments()
	{
		return [
			['CountryCode', InputArgument::REQUIRED, 'country of the sight.'],
			['ProvinceId', InputArgument::REQUIRED, 'province of the sight.'],
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
