<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
use App\Helper\ImageSeekkHelper;
class SeekerHelper {
    
   public static function curlInitData($url, $retry=5){
        global $con;
        $binfo = array(
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET CLR 2.0.50727; InfoPath.2; AskTbPTV/5.17.0.25589; Alexa Toolbar)',
            'Mozilla/5.0 (Windows NT 5.1; rv:22.0) Gecko/20100101 Firefox/22.0',
            'Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; .NET4.0C; Alexa Toolbar)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1)',
            'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)',
        );

        //$cipRandA = mt_rand(8,254);
        $cipRandA =  mt_rand(110,230);
        //$cipRandB = 97;
        $cipRandB = mt_rand(8,254);
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
        $content = self::curlInitData($url);
        if($content){
           
            $html = str_get_html($content);//获得解析的文档
            $itemAttr = "class=zixun";
            $ret = $html->find('div['.$itemAttr.']', 0);
            if($ret){
                //var_dump($ret->innertext);
                $retA = $ret->find('a');
                if($retA){
                    $calcTotal = 0;
                    foreach($retA as $item){         
                        $increase++;
                        if($increase > 5){
                            return true; //temp to insert the url, will remove it on live
                        }
                        $url   = $mainDomainUrl . $item->href;
                        //echo $url . "\n";continue;
                        try{
                           $sql = "INSERT INTO search_url(`id`, `url`, `url_secret`, `type`, `country_id`, `province_id`, `city_id`, `is_searched`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, 0, NOW(), NOW())";
                            $p = array($url, sha1($url), 'cncn', $countryId, $provinceId, $cityId);
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
                preg_match('/<div id="showinfo">.*?([0-9]{4}-[0-9]{2}-[0-9]{2})/si', $newsContent, $matchTime);
                $createdAt = $updatedAt = trim(isset($matchTime[1]) ? $matchTime[1] : date("Y-m-d H:i:s"));
                
                $newsContent = ImageSeekHelper::seekPicAndSave($newsContent, 'secret');           
                $pic = $newsContent['pic'];
                $newsContent = $newsContent['content'];
                
                $sql = "INSERT INTO news(`id`, `category_id`, `city_id`, `province_id`, `country_id`, `rate`, `title`, `meta_keywords`, `meta_description`, `short_description`, `editor`, `source_url`, `pic`, `content`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
                $newsShortDescription = '';
                $p = array('2', $data->city_id, $data->province_id, $data->country_id, 0, $newsTitle, $newsKeywords, $newsDescription, $newsShortDescription, '', $url, $pic, $newsContent, $createdAt, $updatedAt);
                DB::insert($sql, $p);       
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
                    if($increase > 45){
                        return true; //temp to insert the url, will remove it on live
                    }
                    try{
                        $itemUrl = $mainDomainUrl . $itemUrl;
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
        
            preg_match('%(<div class="ctd_content.*)<div class="ctd_theend">%si', $content, $matchContent);
            $newsContent = isset($matchContent[1]) ? $matchContent[1] : "";
            $newsContent = preg_replace('%<div class="ctd_content_controls.*?</h3>%si', '', $newsContent);
            $newsContent = preg_replace('%<a target="_blank" class="gs_a_poi.*?href=".*?>(.*?)</a>%si', '$1', $newsContent);
            //$newsContent = strip_tags($newsContent, '<p><br><div><img><dd><h3><h2><h1><ul><li><span>');
            
            preg_match('%<h3>.*?发表于(.*?)</h3>%si', $newsContent, $matchTime);
            $createdAt = $updatedAt = trim(isset($matchTime[1]) ? $matchTime[1] : date("Y-m-d H:i:s"));
            
            $newsContent = ImageSeekHelper::seekPicAndSave($newsContent, 'secret');           
            $pic = $newsContent['pic'];
            $newsContent = $newsContent['content'];  
            
            //insert into news
            $sql = "INSERT INTO news(`id`, `category_id`, `city_id`, `province_id`, `country_id`, `rate`, `title`, `meta_keywords`, `meta_description`, `short_description`, `editor`, `source_url`, `pic`, `content`, `created_at`, `updated_at`) VALUE (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?); ";
            $newsShortDescription = '';
            $p = array('2', $data->city_id, $data->province_id, $data->country_id, 0, $newsTitle, $newsKeywords, $newsDescription, $newsShortDescription, '', $url, $pic, $newsContent, $createdAt, $updatedAt);
            DB::insert($sql, $p);
        }
    }
    public static function ctripMapsCity($cityKey){
        $maps = array('beijing'=>'beijing1', 'tianjin'=>'tianjin154', 'jiazhuang'=>'shijiazhuang199', 'tangshan'=>'tangshan200', );
        $cityUrlKey = isset($maps[$cityKey]) ? $maps[$cityKey] : "";
        return $cityUrlKey;
    }
   
   
   
   
}