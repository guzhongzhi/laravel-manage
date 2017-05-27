<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
use App\Helper\ImageSeekHelper;
use App\Model\Food;
use App\Model\Store;
class SeekerHelper {

    const SEEK_CNCN_TRAVEL_TYPE = 'cncn';
    const SEEK_CNCN_FOOD_TYPE = 'cncn_food';
    const SEEK_CNCN_STORE_TYPE = 'cncn_store';
    const SEEK_CTRIP_TRAVEL_TYPE = 'ctrip';
    public static function curlInitData($url, $retry=5){
        sleep(rand(1,2));
        global $con;
        $binfo = array(
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)',
            'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C; Alexa Toolbar)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
            '*Baiduspider+(+http://www.baidu.com/search/spider.htm")',
        );

        $cipRandA = '123';
        //$cipRandA =  mt_rand(110,230);
        $cipRandB = '125';
        //$cipRandB = mt_rand(8,254);
        $cipRandC = mt_rand(8,254);
        $cip = $cipRandA.'.'.$cipRandB.'.'.$cipRandC.'.'.mt_rand(0,254);
        //$xip = $cip;
        //$xip = $cipRandA.'.'.$cipRandB.'.'.$cipRandC.'.'.mt_rand(0,254);
        #$cip = '180.97.33.107';
        #$xip = '180.97.33.107';
        $xip = '127.0.0.1';
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
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);//不直接输出，返回到变量
        curl_setopt($ch, CURLOPT_REFERER, "http://www.baidu.com");
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 3);
        $curl_result = curl_exec($ch);
        curl_close($ch);
        if($curl_result){
            $wordCountNumber = strlen($curl_result);
            if($wordCountNumber > 5000000){
                return false;
            }
        }else{
            if($retry > 0){
                $retry--;
                $curl_result = self::curlInitData($url, $retry);
            }

        }
        return $curl_result;
    }
    
    public static function checkUrlValidate($url){
        $content = shell_exec("curl -I $url");
        if (preg_match('%HTTP/1.1 200 OK%si', $content)) {
            return true;
        }else{
            return false;
        }
        
    }
    
    public static function insertCNCNUrl($url, $mainData, $increase=0){
        $mainDomainUrl = $mainData['main_domain_url'];
        $countryId     = $mainData['country_id'];
        $provinceId    = $mainData['province_id'];
        $cityId        = isset($mainData['city_id']) ? $mainData['city_id'] : 0;
        $type          = isset($mainData['type']) ? $mainData['type'] : self::SEEK_CNCN_TRAVEL_TYPE;
        $content = self::curlInitData($url);
        if($content){
            $content = mb_convert_encoding($content, 'utf8', 'gbk');
            $content = preg_replace('%<div class="t">.*?</div>%si', '', $content);
            $content = preg_replace('%<div class="nofind.*?</div>%si', '', $content);
            $html = str_get_html($content);//获得解析的文档
            if($type == self::SEEK_CNCN_FOOD_TYPE){
                $itemAttr = "class=food_li";
            }else{
                $itemAttr = "class=zixun";
            }
            $ret = $html->find('div['.$itemAttr.']', 0);
            if($ret){
                $retA = $ret->find('a');
                if($retA){
                    $calcTotal = 0;
                    foreach($retA as $item){

                        $increase++;
                        if($increase > 5){
                            //return true; //temp to insert the url, will remove it on live
                        }
                        $url   = $mainDomainUrl . $item->href;
                        echo $url .  PHP_EOL;
                        //echo $url . PHP_EOL;continue;
                        try{
                            $sql = "INSERT INTO search_url(`id`, `url`, `url_secret`, `type`, `country_id`, `province_id`, `city_id`, `is_searched`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())";
                            $p = array($url, sha1($url), $type, $countryId, $provinceId, $cityId);
                            DB::insert($sql, $p);
                        }catch(\Exception $e){
                            //do nothing
                            $calcTotal++;
                        }

                    }
                    if($calcTotal > 4){
                        echo "Duplicate Seek cncn Url, Stop Seek. \n";
                        return false;
                    }
                } 
            }

            $itemAttr = "class=page_con";
            $pav = $html->find('div['.$itemAttr.']', 0);
            if($pav){
                preg_match('/<a class="num next" href="(.*?)"/si', $pav->innertext, $matchNext);
                $nextUrl = isset($matchNext[1]) ? $matchNext[1] : "";
                //echo $nextUrl;die();   
                if($nextUrl){
                    $url   = $mainDomainUrl . $nextUrl;
                    self::insertCNCNUrl($url, $mainData, $increase);
                }
            }
        }
    }
    
    
    public static function insertCNCNContent($data){
        $url = $data->url;
        //$url = "http://beijing.cncn.com/article/148144/";
        //echo $url;die();
        $content = self::curlInitData($url);
        if($content){  
            $content = mb_convert_encoding($content, 'utf8', 'gbk');
            preg_match('/<meta name="description" content="(.*?)"/si', $content, $matchMeta);
            $newsKeywords = '';
            $newsDescription = isset($matchMeta[1]) ? $matchMeta[1] : "";
            $newsDescription = str_replace('欣欣旅游网游记攻略：', '', $newsDescription);
            //echo $newsDescription ."<br />";
            
            $html = str_get_html($content);//获得解析的文档
            $itemAttr = "id=content";
            $ret = $html->find('div['.$itemAttr.']', 0);
            if($ret){
                preg_match('%<title>(.*?)</title>%si', $content, $matchTitle);
                $newsTitle = isset($matchTitle[1]) ? $matchTitle[1] : "";
                $newsContent = $ret->innertext;
                $newsContent = preg_replace('%<a.*?href=".*?>(.*?)</a>%si', '$1', $newsContent);

                preg_match('/<div id="showinfo">.*?([0-9]{4}-[0-9]{2}-[0-9]{2})/si', $newsContent, $matchTime);
                $createdAt = $updatedAt = trim(isset($matchTime[1]) ? $matchTime[1] : date("Y-m-d H:i:s"));
                
                $newsContent = ImageSeekHelper::seekPicAndSave($newsContent, 'secret');       
                if($newsContent){
                    $pic = $newsContent['pic'];
                    $newsContent = $newsContent['content'];
                    if(mb_strlen($newsContent,'utf8') > 100){
                        $sql = "INSERT INTO news(`id`, `category_id`, `city_id`, `province_id`, `country_id`, `rate`, `title`, `meta_keywords`, `meta_description`, `short_description`, `editor`, `source_url`, `pic`, `content`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
                        $newsShortDescription = '';
                        $p = array('2', $data->city_id, $data->province_id, $data->country_id, 0, $newsTitle, $newsKeywords, $newsDescription, $newsShortDescription, '', $url, $pic, $newsContent, $createdAt, $updatedAt);
                        DB::insert($sql, $p);  
                        return true;
                    }
                } 
            }
            
        }
        return false;
    }


    public static function insertCNCNFoodContent($data){
        $url = $data->url;
        //$url = "http://beijing.cncn.com/article/148144/";
        //echo $url;die();
        $content = self::curlInitData($url);
        if($content){
            $content = mb_convert_encoding($content, 'utf8', 'gbk');

            $html = str_get_html($content);//获得解析的文档
            $itemAttr = "class=produce_info";
            $ret = $html->find('div['.$itemAttr.']', 0);
            if($ret){
                foreach($ret->find('img') as $element){
                    $pic = $element->src;
                    if (preg_match('/nopic_210x140/si', $pic)) {
                        $pic = '';
                    }else{
                        $pic = ImageSeekHelper::savePic($pic, ImageSeekHelper::$foodImgPath);
                    }
                }

                foreach($ret->find('h1') as $element){
                    $newsTitle = $element->innertext;
                }

                foreach($ret->find('dd') as $element){
                    $newsContent = $element->innertext;
                    $newsContent = preg_replace('%<div.*?</div>(.*)%si', '$1', $newsContent);

                }

                $createdAt = $updatedAt = date("Y-m-d H:i:s");
                $rate = '4.' . rand(0, 9);

                $foodData = array('city_id'=>$data->city_id, 'province_id'=>$data->province_id, 'country_id'=>$data->country_id, 'title'=>$newsTitle,
                                    'content'=>$newsContent, 'source_url'=>$url, 'pic'=>$pic, 'rate'=>$rate,
                    );
                $food = Food::create($foodData);
                $matchId = $food->id;
            }

            //for store
            $itemAttr = "class=txt_tw";
            $ret = $html->find('div['.$itemAttr.']', 0);
            if($ret){
                foreach($ret->find('a') as $element){
                    $href = $element->href;
                    try{
                        //insert the store
                        $sql = "INSERT INTO search_url(`id`, `url`, `url_secret`, `type`, `country_id`, `province_id`, `city_id`, `is_searched`, `match_id`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, 0, ?,NOW(), NOW())";
                        $p = array($href, sha1($href), self::SEEK_CNCN_STORE_TYPE, $data->country_id, $data->province_id, $data->city_id, $matchId);
                        DB::insert($sql, $p);
                    }catch(\Exception $e){
                        //insert the queue
                        $sql= "INSERT INTO food_store_queue(`id`, `food_id`, `store_secret`, `created_at`, `updated_at`) VALUE(NULL, ?, ?, NOW(), NOW())";
                        $p = array($matchId,sha1($href));
                        DB::insert($sql, $p);
                    }
                }
            }
        }
    }
    
    
    
    public static function insertCNCNStoreContent($data){
        $url = $data->url;
        //echo $url;die();
        $content = self::curlInitData($url);
        $matchId = $data->match_id;
        if($content){
            $content = mb_convert_encoding($content, 'utf8', 'gbk');

            $html = str_get_html($content);//获得解析的文档
            $itemAttr = "class=produce_info";
            $ret = $html->find('div['.$itemAttr.']', 0);
            if($ret){
                foreach($ret->find('img') as $element){
                    $pic = $element->src;
                    if (preg_match('/nopic_210x140/si', $pic)) {
                        $pic = '';
                    }else{
                        $pic = ImageSeekHelper::savePic($pic, ImageSeekHelper::$foodImgPath);
                    }
                }

                foreach($ret->find('h1') as $element){
                    $newsTitle = $element->innertext;
                }

                foreach($ret->find('dd') as $element){
                    $newsContent = $element->innertext;
                    $newsContent = preg_replace('%<div.*?</div>(.*)%si', '$1', $newsContent);
                }

                $createdAt = $updatedAt = date("Y-m-d H:i:s");
                $rate = '4.' . rand(0, 9);
            }
            
            $itemAttr = "class=produce_con";
            $ret = $html->find('div['.$itemAttr.']', 0);
            if($ret){
                $description = $ret->innertext;
            }
            
            try{
                $storeData = array(
                                    'city_id'=>$data->city_id, 'province_id'=>$data->province_id, 'country_id'=>$data->country_id, 'title'=>$newsTitle, 'content'=>$newsContent,
                                    'description'=>$description, 'source_url'=>$url, 'source_url_secret'=>sha1($url), 'pic'=>$pic, 'rate'=>$rate, 'is_seeked'=>1,
                            );
                $store = Store::create($storeData);
                $storeId = $store->id;
                //insert the food_store
                $sql = "INSERT INTO food_store(`food_id`, `store_id`) VALUE(?, ?)";
                DB::insert($sql, [$matchId, $storeId]);

            }catch(Exception $e){
                //do nothing
                echo $e->getMessage();
            }
        }
    }


    public static function getCtripUrlKey($str){
        $urlKey = '';
        $str = str_replace('市', '', $str);
        $str = substr($str, 0, 9);
        $str = urlencode($str);
        $url = 'http://you.ctrip.com/SearchSite/Service/Tip2?Jsoncallback=jQuery171004985534546071313_1494326360116&keyword='.$str.'&_=1494326406199';
        $content = SeekerHelper::curlInitData($url);
        preg_match('/jQuery.*?\((.*)\)/si', $content, $matchJson);
        $jsonCode = isset($matchJson[1]) ? $matchJson[1] : "";
        $jsonCode = json_decode($jsonCode);
        try{
            if($listItem = $jsonCode->List[0]){
                $urlKey = strtolower($listItem->DestEName) . $listItem->DestId;
            }
        }catch(\Exception $e){
            var_dump($e->getMessage());
            var_dump($content);
        }
        return $urlKey;
    }
    
    
    public static function insertCtripUrl($url, $mainData, $increase=0){
        $mainDomainUrl = $mainData['main_domain_url'];
        $countryId     = $mainData['country_id'];
        $provinceId    = $mainData['province_id'];
        $cityId        = isset($mainData['city_id']) ? $mainData['city_id'] : 0;
        $content = self::curlInitData($url);        
        if($content){
            
            preg_match_all('%<a class="journal-item cf".*?href="(.*?)">.*?</a>%si', $content, $matches);
            if(isset($matches[1])){
                $calcTotal = 0;
                foreach($matches[1] as $itemUrl){
                    $increase++;
                    if($increase > 21){
                        //return true; //temp to insert the url, will remove it on live
                    }
                    try{
                        $itemUrl = $mainDomainUrl . $itemUrl;
                        echo $itemUrl . PHP_EOL;
                        $sql = "INSERT INTO search_url(`id`, `url`, `url_secret`, `type`, `country_id`, `province_id`, `city_id`, `is_searched`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())";
                        $p = array($itemUrl, sha1($itemUrl), 'ctrip', $countryId, $provinceId, $cityId);
                        DB::insert($sql, $p);
                    }catch(\Exception $e){
                        //do nothing
                        $calcTotal++;
                    }
                }
                
                if($calcTotal > 4){
                    echo "Duplicate Seek Url, Stop Seek. \n";
                    return false;
                }
            }
            
            //get the next page
            preg_match('/<a.*?class="nextpage" href="(.*?)">/i', $content, $matches);
            if(isset($matches[1])){
                $nextUrl = $mainDomainUrl . $matches[1];
                self::insertCtripUrl($nextUrl, $mainData, $increase);
            }
            return true;
        }
        
    }
    
    
    public static function insertCtripContent($data){
        $url = $data->url;
        $url = 'http://you.ctrip.com/travels/tianjin154/3195663.html';
        $content = self::curlInitData($url);
        echo "Search $url \n";
        if($content){            
            preg_match('/<meta name="keywords" content="(.*?)".*<meta name="description" content="(.*?)"/si', $content, $matchMeta);
            $newsKeywords    = isset($matchMeta[1]) ? $matchMeta[1] : "";
            $newsDescription = isset($matchMeta[2]) ? $matchMeta[2] : "";
            $newsDescription = str_replace('携程攻略社区!', '', $newsDescription);
            $newsDescription = str_replace('携程', '这里', $newsDescription);
                
           /*  preg_match('%<div class="ctd_head_left">.*?<h2>(.*?)</h2>%si', $content, $matchTitle);
            $newsTitle = isset($matchTitle[1]) ? $matchTitle[1] : "";
            if(!$newsTitle){
                preg_match('%<div class="ctd_head_con.*?<h1 class="title1">(.*?)</h1>%si', $content, $matchTitle);
                $newsTitle = isset($matchTitle[1]) ? $matchTitle[1] : "";
            } */
            preg_match('%<title>(.*?)</title>%si', $content, $matchTitle);
            $newsTitle = isset($matchTitle[1]) ? $matchTitle[1] : "";
            $newsTitle = str_replace('【携程攻略】', '', $newsTitle);

            preg_match('%<h3>.*?发表于(.*?)</h3>%si', $content, $matchTime);
            $createdAt = $updatedAt = trim(isset($matchTime[1]) ? $matchTime[1] : date("Y-m-d H:i:s"));
        
            preg_match('%(<div class="ctd_content.*)<div class="ctd_theend">%si', $content, $matchContent);
            $newsContent = isset($matchContent[1]) ? $matchContent[1] : "";
            $newsContent = preg_replace('%<div class="ctd_content.*?</h3>%si', '', $newsContent);
            $newsContent = preg_replace('%<a((?!share).)*?class="gs_a_poi.*?href=".*?>(.*?)</a>%si', '$2', $newsContent);
            echo $newsContent;die("windy");
            //$newsContent = strip_tags($newsContent, '<p><br><div><img><dd><h3><h2><h1><ul><li><span>');

            $newsContent = ImageSeekHelper::seekPicAndSave($newsContent, 'secret');  
            if($newsContent){
                $pic = $newsContent['pic'];
                $newsContent = $newsContent['content'];  
                if(mb_strlen($newsContent,'utf8') > 100){
                    //insert into news
                    $sql = "INSERT INTO news(`id`, `category_id`, `city_id`, `province_id`, `country_id`, `rate`, `title`, `meta_keywords`, `meta_description`, `short_description`, `editor`, `source_url`, `pic`, `content`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
                    $newsShortDescription = '';
                    $p = array('2', $data->city_id, $data->province_id, $data->country_id, 0, $newsTitle, $newsKeywords, $newsDescription, $newsShortDescription, '', $url, $pic, $newsContent, $createdAt, $updatedAt);
                    DB::insert($sql, $p);
                    return true;
                }
                
                
            }
            
        }
        return false;
    }
    public static function getCnCnUrlKey($cityKey){
        $maps = array('aba'=>'aba', 'gaz'=>'ganzi', 'lsy'=>'liangshan', 'hk'=>'hongkong', 'am'=>'macao', 
                       'xj'=>'xinjiang', 'nx'=>'ningxia', 'xz'=>'xizang', 'sn'=>'shannxi', 'hhht'=>'huhehaote',
                       'cfs'=>'chifeng', 'ordos'=>'ordos', 'hlbr'=>'hulunbuir', 'byar'=>'bayannur',
                       'wlcb'=>'ulanqab', 'hin'=>'hinggan', 'xgo'=>'xilingol', 'alm'=>'alashan', 'ybz'=>'yanbian',
                       'jmu'=>'jiamusi', 'dhl'=>'daxinganling', 'hns'=>'maanshan', 'liuan'=>'luan', 'esh'=>'enshi',
                       'xxz'=>'xiangxi', 'qxz'=>'qianxinan', 'qnd'=>'qiandongnan', 'qnz'=>'qiannan', 'cxd'=>'chuxiong',
                       'hhz'=>'honghe', 'wsz'=>'wenshan', 'xsb'=>'xishuangbanna', 'dlz'=>'dali', 'dhg'=>'dehong',
                       'nuj'=>'nujiang', 'lxh'=>'linxia', 'gnz'=>'gannan', 'hbz'=>'haibei', 'hnz'=>'huangnan',
                       'hnn'=>'hainanzhou', 'gol'=>'golog', 'ysz'=>'yushu', 'hxz'=>'haixi', 'tud'=>'tulufan',
                       'hmd'=>'hami', 'cjz'=>'changji', 'bor'=>'bortala', 'akd'=>'akesu', 'ksi'=>'kashi', 'hod'=>'hetian',
                       'ild'=>'yili', 'tcd'=>'tacheng', 'ald'=>'altay'
                       
                );
        $cityUrlKey = isset($maps[$cityKey]) ? $maps[$cityKey] : "";
        return $cityUrlKey;
    }


   
   
   
}
