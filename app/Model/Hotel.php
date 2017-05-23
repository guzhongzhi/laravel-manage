<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use DB;

class Hotel extends AutoModel {
    
	protected $table = "hotel";
    
    public function getMetaKeywords() {
        return $this->meta_keywords;
    }
    
    public function getMetaDescription() {
        return $this->meta_description;
    }
    
    public function getUrl() {
        return "/hotel/s-" . $this->id .".html";
    }
    
    public function getHotelUrl() {
        return "/hotel/s-" . $this->id.".html";
    }
    
    public function getShortDesc() {
        $sd = $this->short_desc;
        if($sd == "") {
            $sd = $this->title;
        }
        return $sd;
    }
    
    public function getProvince() {
        $pid = $this->province_id;
        $p = Region::find($pid);
        if(!$p) {
            $p = new Region();
        }
        return $p;
    }
    
    public function getPic() {
        $pic = $this->pic;
        $this->getImages();
        return $pic;
    }
    
    public function getImages() {
        
        $content = $this->description.'';
        preg_match_all('/<img.*?src=(\'|")(.*?)(\'|").*?>/is',$content,$matches);
        if(isset($matches[2])) {
            $data = array();
            foreach($matches[2] as $url) {
                $data[] = array(
                    "url"=>$url,
                );
            }
            if($this->pic != "") {
                $data[]  = array(
                    "url"=>($this->pic),
                );
            }
            try {
                $newMap = $this->saveRemoteFileToLocal($data);
            } catch (\Exception $ex) {
                echo $ex->__toString();
                die();
            }
            if(!empty($newMap)) {
                foreach($newMap as $oldUrl=>$newUrl) {
                    $content = str_replace($oldUrl,$newUrl,$content);
                }
                $this->description = $content;
                
                if(isset($newMap[$this->pic])) {
                    $this->pic = $newMap[$this->pic];
                }                
                $this->save();
            }
            return json_decode(json_encode($data));
        }
        return array();
    }
    
    protected function filterPicUrl($url) {
        $url = str_replace('https:http:',"https:",$url);
        return $url;
    }
    
    protected function saveRemoteFileToLocal($images) {
        $rootDirName = dirname(__FILE__) . "/../../public";
        
        $fileDirName = "/uploads/hotel/" . date("Ymd") . "/";
        if(!file_exists($rootDirName . $fileDirName)) {
            mkdir($rootDirName . $fileDirName, 0777,true);
        }
        $newMap = array();
        
        foreach($images as $image) {
            $url = $image["url"];
            
            if(substr($url,0,1) == "/") {
                continue;
            }
            $realUrl = $this->filterPicUrl($url);
            
            $file = $this->getFileContent($realUrl);
            
            $tempFile = explode(".",$realUrl);
            $fileExt = $tempFile[count($tempFile) - 1];
            
            $relativeFileName = $fileDirName . md5($realUrl) ."_" . date("His") . mt_rand(1000,9999) . "." . $fileExt;
            $newFileName = $rootDirName . $relativeFileName;
            
            file_put_contents($newFileName,$file);
            $newMap[$url] = $relativeFileName;
        }
        return $newMap;
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
}
