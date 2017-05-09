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
        $args = $this->argument();
        
        $sName = $args["SourceName"];
        switch(strtolower($sName)) {
            case 'cncn': {
                $this->seekCnCn();
                break;
            }
            case "ctrip": {
                $this->seekCtrip();
                break;
            }
            default: {
                echo "Invalid Params";
                die();
            }
        }
	}
    
    public function getFileContent($url){
        $binfo = array(
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)',
            'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C; Alexa Toolbar)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
        );
        $cipRandA = 45;
        $cipRandB = 79;
        $cipRandC = mt_rand(8,254);
        $cip = $cipRandA.'.'.$cipRandB.'.'.$cipRandC.'.'.mt_rand(0,254);
        $xip = $cipRandA.'.'.$cipRandB.'.'.$cipRandC.'.'.mt_rand(0,254);
        #$cip = '180.97.33.107';
        #$xip = '180.97.33.107';
        $header = array(
            'CLIENT-IP:'.$cip,
            'X-FORWARDED-FOR:'.$xip,
        );
        $userinfo = $binfo[mt_rand(0,4)];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, "$userinfo");
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, "http://www.baidu.com");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        $curl_result = curl_exec($ch);
        curl_close($ch);
        return $curl_result;
    }
    
    protected function getProvinceByName($name) {
        $sql = "SELECT * FROM region WHERE name like '%".$name."%' AND parent_id = 1";
        $rows = DB::select($sql);
        return $rows[0];
    }
    
    protected function seekCtrip() {
        for($i=1;$i<=190;$i++) {
            $url = "http://you.ctrip.com/countrysightlist/china110000/p".$i.".html";
            $content = $this->getFileContent($url);
            
            preg_match_all('/<div[^>]*?list_wide_mod1[^>]*>(.*?)<div[^>]*?ttd_pager.*?>/is',$content,$matches);
            $listHtml = $matches[0][0];
            
            preg_match_all('/<a[^>]*?\/place\/([a-z0-9]*?).html.*?>(.*?)<\/a>/is',$listHtml,$matches);
            $sightNames = $matches[1];
            $sightNames = array_unique($sightNames);
            
            foreach($sightNames as $sightName) {
                $url = "http://you.ctrip.com/sight/".$sightName."/s0-p1.html";
                
                echo $url,PHP_EOL;
                $content = $this->getFileContent($url);
                
                preg_match_all('/<b\s+class="numpage">(\d+)<\/b>/is',$content,$matches);
                $maxPage = $matches[1][0];
                
                $this->seekCtripSight($content,$sightName);
                
                for($p = 2;$p<=$maxPage;$p++) {
                    
                    echo PHP_EOL;
                    echo PHP_EOL;
                    $url = "http://you.ctrip.com/sight/".$sightName."/s0-p".$p.".html";
                    echo $url,PHP_EOL;
                    
                    $content = $this->getFileContent($url);
                    $this->seekCtripSight($content,$sightName);
                    sleep(1);
                }
            }
        }
    }
    
    protected function seekCtripSight($content,$sightName) {

        preg_match_all('/\/sight\/'.$sightName.'\/(\d+).html/',$content,$matches);
        $sightUrls = $matches[0];
        $sightUrls = array_unique($sightUrls);
        foreach($sightUrls as $sightUrl) {
            $sightUrl = "http://you.ctrip.com".$sightUrl;
            echo $sightUrl,PHP_EOL;
            
                
            $sql = "SELECT * FROM news WHERE source_url = ?";
            $row = DB::select($sql,[$sightUrl]);
            if(!empty($row)) {
                //continue;
            }
            
            $content = $this->getFileContent($sightUrl);
            
            preg_match_all('/<div[^>]*?class="breadbar_v1[^>]*?>(.*?)<div.*?dest_toptitle/is',$content,$matches);            
            
            $bread = preg_replace('/<div[^?]*?bread_hover[^?]*?>.*?<\/div>/is','',$matches[0][0]);
            $bread = str_ireplace('<i class="arrow"></i>','',$bread);
            
            preg_match_all('/<a.*?>(.*?)<\/a>/is',$bread,$matches);
            
            
            $provinceName = $matches[1][1];
            $provinceName = trim($provinceName,"景点");
            $cityName = isset($matches[1][2]) ? $matches[1][2]: "";
            
            $province = $this->getProvinceByName($provinceName);
            
            preg_match('/<title>(.*?)<\/title>/is',$content,$matches);
            preg_match('/<h1>(.*?)<\/h1>/is',$content,$matches);
            
            $title = $matches[1];
            $title = str_ireplace("【携程攻略】","",$title);
            
            $title = preg_replace('/<\/?a\s?.*?>/is','',$title);
            
            $content2 = preg_replace('/<div[^>]*?none[^>]*?>.*?<\/div>/is','',$content);
            preg_match_all('/<div[^>]*?itemprop="description".*?>(.*?)<\/div>/is',$content2,$matches);
            
            
            
            preg_match_all('/<div[^>]*?toggle_l.*?>(.*?)<\/div>/is',$content,$matches);
            
            $lines = array();
            foreach($matches[1] as $line) {
                $line = preg_replace('/<\/?div\s?.*?>/is','',$line);
                
                
                $line = trim($line);
                $line = preg_replace('/\s+/is'," ",$line);
                $lines[] = trim($line);
            }
            $lines = array_unique($lines);
            
            $sightContent = implode(PHP_EOL, $lines);
            file_put_contents("1.log",$sightContent);
            
            News::create(array(
                "category_id"=>1,
                "province_id"=>$province->id,
                "country_id"=>1,
                "content"=> $sightContent,
                "title"=>$title,
                "source_url"=>$sightUrl,
            ));
        }
        
    }
    
    protected function seekCnCn() {
        try {
        $args = $this->argument();
        $countryCode = $args["CountryCode"];
        $provinceId = isset($args["ProvinceId"]) ? $args["ProvinceId"] :"";
        
        $sql = "SELECT * FROM region WHERE parent_id = 0";
        if($countryCode) {
            //$sql .= " AND "
        }
        $countries = DB::select($sql);
        
        foreach($countries as $country) {
            $countryId = $country->id;
            
            $sql = "SELECT * FROM region WHERE parent_id = " . $countryId;
            $provinces = DB::select($sql);
            
            foreach($provinces as $province) {
                if($provinceId && $province->id != $provinceId) {
                    continue;
                }
                
                $childCities = $this->getProvinceEnNames($province->id);
                
                $provinceName = $province->name."";
                $provinceName = rtrim($provinceName,"市");
                $provinceName = rtrim($provinceName,"省");
                $enname = \CUtf8_PY::encode($provinceName,"all","");
                $page = 1;
                
                do {
                    sleep(5);
                    $childCities[] = $enname;
                    $urlHead = "http://" . $enname . ".cncn.com/jingdian/";
                    $url = $urlHead ."1-".$page."-0-0.html";
                    
                    $urlRegex = "(" . implode("|",$childCities) . ").cncn.com\/jingdian\/";
                    echo $url,PHP_EOL;
                    try {
                        $content = $this->getFileContent($url);
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
    
    static $cache = array();
    protected function getProvinceEnNames($pid) {
        $key ="child_province_en_name_".$pid;
        if(isset(self::$cache[$key])) {
            return self::$cache[$key];
        }
        $sql = "SELECT * FROM region WHERE parent_id = " . $pid;
        $rows = DB::select($sql);
        $enNames = array();
        foreach($rows as $row) {
            $enName = trim(trim($row->name_en),"Shi");
            $enName = trim($enName);
            $enNames[] = $enName;
        }
        return self::$cache[$key] = $enNames;
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
        //print_r($matchedLines);die();
        foreach($matchedLines as $lineContent) {
            preg_match('/href="(.*?)"/is',$lineContent,$matches);
            $jinDianUrl = $matches[1];
            
            if(substr($jinDianUrl,-4) == ".htm") {
                continue;
            }
            
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
        
        $content = $this->getFileContent($url);
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
			['SourceName', InputArgument::REQUIRED, 'cncn|ctrip'],
			['CountryCode', InputArgument::REQUIRED, 'country of the sight.'],
			['ProvinceId', InputArgument::OPTIONAL, 'province of the sight.'],
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
