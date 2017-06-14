<?php namespace App\Model;

use App\Helper\ImageSeekHelper;
use Illuminate\Database\Eloquent\Model;
use DB;
use App\Model\HotelImage;

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

    public function getStar(){
        $star = $this->rate / 5 * 100;
        return $star;
    }

    public function getShortDesc($number=200) {
        $sd = $this->short_desc;
        if($sd == "") {
            $content = $this->description;
            $content = preg_replace('/<\/?.*?>/is','',$content);
            $content = str_replace(array("\r\n", "\r", "\n", " "), "", $content);
            $sd = mb_substr($content,0,$number);
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

	public function getHomePic(){
		if(!$this->pic){
            $this->homePic = '/skin/images/no_pic.png';
        }else{

            $foreign = false;
            $needToDownload = true;
            if (preg_match('/^http/sim', $this->pic)) { //foreign url do nothing
                $foreign = true;
                $thumbUrl = ImageSeekHelper::getThumFileSrc($this->pic, '_206_150');

            }else{
                $thumbUrl = ImageSeekHelper::getThumFileSrc($this->pic, '_206_150');
                $originalImageSrc = public_path() . $this->pic;
            }
            $thumbFullUrl = public_path() . $thumbUrl;
			
            if(!file_exists($thumbFullUrl)){
                if($foreign == true){
					
                    $originalImageSrc = ImageSeekHelper::savePic($this->pic, ImageSeekHelper::$foreignThumPath);
                    if (preg_match('/^http/sim', $originalImageSrc)) { //foreign url
                        $needToDownload = false;
                        $originalImageSrc =  $originalImageSrc;
                    }else{
                        $originalImageSrc = public_path() . $originalImageSrc;
                    }

                }
                if($needToDownload){
                    ImageSeekHelper::makeThumb($originalImageSrc, $thumbFullUrl, 1, 206, 150);
                }
                $this->homePic = $thumbUrl;
                if($foreign == true && $needToDownload){
                    unlink($originalImageSrc);
                }
            }else{
                $this->homePic = $thumbUrl;
            }
        }
        return $this->homePic;
		
	}
	
    public function getPic(){
        if(!$this->pic){
            $this->pic = '/skin/images/no_pic.png';
        }else{
            $foreign = false;
            if (preg_match('/^http/sim', $this->pic)) { //foreign url do nothing
                $foreign = true;
                $thumbUrl = ImageSeekHelper::getThumFileSrc($this->pic, '_150_130');

            }else{
                $thumbUrl = ImageSeekHelper::getThumFileSrc($this->pic, '_150_130');
                $originalImageSrc = public_path() . $this->pic;
            }
            $thumbFullUrl = public_path() . $thumbUrl;
            if(!file_exists($thumbFullUrl)){
                if($foreign == true){
                    $originalImageSrc = ImageSeekHelper::savePic($this->pic, ImageSeekHelper::$foreignThumPath);
                    $originalImageSrc = public_path() . $originalImageSrc;
                }
                ImageSeekHelper::makeThumb($originalImageSrc, $thumbFullUrl, 1, 150, 130);
                $this->pic = $thumbUrl;
                if($foreign == true){
                    unlink($originalImageSrc);
                }
            }else{
                $this->pic = $thumbUrl;
            }
        }
        return $this->pic;
    }
    
    public function getImages() {
        $hotelImages = array();
        $sql = "SELECT * FROM hotel_image WHERE hotel_id = ?";
        $resultes = DB::select($sql, array($this->id));
        foreach($resultes as $itemResult){
            preg_match('%(http://.*/.*?)_w0_h600_c0_t0.jpg%sim', $itemResult->url, $matchUrlKey);
            if(isset($matchUrlKey[1]) && $matchUrlKey[1]){
                $thumbUrl = $matchUrlKey[1] . "_w120_h120_c1_t0.jpg";
            }elseif(preg_match('%http://%sim', $itemResult->url)) {
                $thumbUrl = $itemResult->url;
            }else{
                $originalImageSrc = public_path() . $itemResult->url;
                $thumbUrl = ImageSeekHelper::getThumFileSrc($itemResult->url);
                $thumbFullUrl = public_path() . $thumbUrl;
                ImageSeekHelper::makeThumb($originalImageSrc, $thumbFullUrl, 1, 150, 100);
            }
            $hotelImages[] = array('src'=>$itemResult->url, 'thumb'=>$thumbUrl);
        }

        return $hotelImages;
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

    /**------------------**/

    public function saveHotelImages($images){
        foreach($images as $itemImage){
            $data = array('hotel_id'=>$this->id, 'url'=>$itemImage, 'created_at'=>date("Y-m-d H:i:s"), 'updated_at'=>date("Y-m-d H:i:s"));
            HotelImage::create($data);
        }

    }
}
