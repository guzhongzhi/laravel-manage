<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use DB;
include_once "Pinyin.php";
use App\Model\Region;
use App\Model\News;
use App\Model\Hotel;

class HotelSeek extends Command {

	/**
	 * The console command name.
	 *
	 * @var string
	 */
	protected $name = 'seek:hotel';

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
            case 'nuomi': {
                $this->seekNuoMI();
                break;
            }
            default: {
                echo "Invalid Params";
                die();
            }
        }
	}
    
    function seekNuoMi() {
        $k = 65;
        for($k=65;$k<91;$k++) {
            $chr = chr($k);
            $fileName = dirname(__FILE__) . "/nuomi/" . $chr.".txt";
            $content = file_get_contents($fileName);
            $cities = json_decode($content, true);
            $cities = $cities["data"]["list"];
            
            foreach($cities as $city) {
                $siteCity = $this->getCityByName($city["city_name"]);
                if(!$siteCity || !$siteCity->id ) {
                    continue;
                }
                $cityUrl = "https://t.nuomi.com/". $city["domain_url"];
                echo $cityUrl,PHP_EOL;
                
                $content = $this->getFileContent($cityUrl);
                file_put_contents("1.log",$content);
                
                preg_match_all('/<div[^>]*?w-goods-area[^>]*?>(.*?)<div[^>]*?w-channel-pager.*?>/is',$content,$matches);
                $content = $matches[0][0];
                $items = explode("\n", $content);
                foreach($items as $item) {
                    
                    if(strpos($item,"data-original") === false) {
                        continue;
                    }
                    
                    preg_match_all('/href="(.*?)"/is',$item,$matches);
                    $hotelUrl = $matches[1][0];
                    try {
                        $this->seekItem($hotelUrl, $siteCity);
                    }catch(\Exception $ex) {
                        echo $ex->__toString();
                    }
                }
                
            }
        }
    }
    function seekItem($url,$city) {
        $url = "https:" . $url;
        echo $url,PHP_EOL;
        $content = $this->getFileContent($url);
        preg_match_all('/<h2>(.*?)<\/h2>/is',$content,$matches);
        $title = $matches[1][0];
        
        preg_match_all('/<img[^>]*?\s*src="(.*?)" [^>]*?item-img-large[^>]*?>/is',$content,$matches);
        $pic = $matches[1][0];
        $pic = "https:" . $pic;
        
        
        preg_match_all('/<div[^>]*?"price"[^>]*?>(.*?)<\/div>/is',$content,$matches);
        $price = $matches[1][0];
        $price = preg_replace('/[^0-9]/is','',$price);
        
        preg_match_all('/<p[^>]*?"branch-address"[^>]*?>(.*?)<\/p>/is',$content,$matches);
        $address = $matches[1][0];
        
        preg_match_all('/<div[^>]*?"rt-content"[^>]*?>(.*?)<\/div>/is',$content,$matches);
        $description = "";
        if(isset($matches[1][1])) {
            $description = $matches[1][1];
        }
        if(isset($matches[1][2])) {
            $description .= $matches[1][2];
        }
        if($description == "" && isset($matches[1][0])) {
            $description = $matches[1][0];
        }
        $description = preg_replace('/<\/?div.*?>/is','',$description);
        $description = trim(preg_replace('/\s+/is',' ',$description));
        
        $allDesc = $matches[1][0] . $description;
        
        echo $description;
        
        preg_match_all('/电话\s*:\s*([0-9\-]*)/is',$allDesc,$matches);
        $phone = trim($matches[1][0],"-");
        
        
        $data = array(
            "title"=>$title,
            "min_price"=>$price,
            "pic"=>$pic,
            "url_key"=>md5($url),
            "phone"=>$phone,
            "address"=>$address,
            "province_id"=>$city->parent_id,
            "city_id"=>$city->id,
            "address"=>$address,
            "description"=>$description,
        );
        Hotel::create($data);
        
    }
    
    public function getFileContent($url, $referer="http://www.baidu.com"){
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_USERAGENT, "$userinfo");
        curl_setopt($ch, CURLOPT_TIMEOUT,10);
        curl_setopt($ch, CURLOPT_POST, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_REFERER, $referer);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        $curl_result = curl_exec($ch);
        curl_close($ch);
        return $curl_result;
    }
    
    protected function getProvinceNameById($id) {
        $sql = "SELECT * FROM region WHERE  id= " . ($id * 1);
        $rows = DB::select($sql);
        $row = $rows[0];
        return $row->name;
    }
    
    protected function getProvinceByName($name) {
        $sql = "SELECT * FROM region WHERE name like '%".$name."%' AND parent_id = 1";
        echo $sql,PHP_EOL;
        $rows = DB::select($sql);
        $data = isset($rows[0]) ? $rows[0] : new Region();
        if($data->id) {
            return Region::find($data->id);
        }
        return $data;
    }
    
    protected function getCityByName($name,$provinceId = null) {
        $sql = "SELECT * FROM region WHERE name like '%".$name."%' ";
        
        if($provinceId) {
            $sql .=" AND parent_id = " . ($provinceId * 1);
        }
        
        echo $sql,PHP_EOL;
        $rows = DB::select($sql);
        $data = isset($rows[0]) ? $rows[0] : new Region();
        if($data->id) {
            return Region::find($data->id);
        }
        return $data;
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
        $sight = News::create(array(
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
			['SourceName', InputArgument::OPTIONAL, 'cncn|ctrip'],
			['CountryCode', InputArgument::OPTIONAL, 'country of the sight.'],
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
