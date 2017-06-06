<?php namespace App\Model;

use Illuminate\Database\Eloquent\Model;
use App\Helper\ImageSeekHelper;
use DB;

class News extends Model {
    const CATEGORY_ID_SIGHT = 1;
    const CATEGORY_ID_STRATEGY = 3;
    const CATEGORY_ID_TRAVEL = 2;
    const CATEGORY_ID_FOOD = 5;
    const CATEGORY_ID_HOTEL = 6;
    
    public function getMetaKeywords() {
        return $this->meta_keywords;
    }
    
    public function getMetaDescription() {
        return $this->meta_description;
    }
    
	protected $table = "news";
    protected $fillable = [
        'category_id', 
        'province_id',
        'country_id',
        'city_id',
        'rate',
        'title',
        'short_description',
        'editor',
        'source_url',
        'content',
        'created_at',
        'updated_at',
    ];
    
    public function getImages() {
        if($this->sightImages){
            return $this->sightImages;
        }
        $sightImages = array();
        $sql = "SELECT * FROM news_image WHERE news_id = ?";
        $resultes = DB::select($sql, array($this->id));
        foreach($resultes as $itemResult){
            $thumbUrl = $itemResult->url;
            $sightImages[] = array('src'=>$itemResult->url, 'thumb'=>$thumbUrl);
        }
        $this->sightImages = $sightImages;
        return $sightImages;
    }

    public function getStar(){
        $star = $this->rate / 5 * 100;
        return $star;
    }

    public function getSameCityNews() {
        $news = new News();
        $queryBuilder = $news->newQuery();
        $queryBuilder->where("city_id",($this->city_id * 1));
        $rows = $queryBuilder->get(array("*"));
        return $rows;
    }
    
    public function getSightUrl() {
        return "/sight/s-" . $this->id.".html";
    }
    
    
    public function getTravelUrl() {
        return "/travel/s-" . $this->id.".html";
    }

    public function getPic(){
        if(!$this->pic){
            $this->pic = '/skin/images/no_pic.png';
        }else{
            $foreign = false;
            $needToDownload = true;
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
                    if (preg_match('/^http/sim', $originalImageSrc)) { //foreign url
                        $needToDownload = false;
                        $originalImageSrc =  $originalImageSrc;
                    }else{
                        $originalImageSrc = public_path() . $originalImageSrc;
                    }

                }
                if($needToDownload){
                    ImageSeekHelper::makeThumb($originalImageSrc, $thumbFullUrl, 1, 150, 130);
                }
                $this->pic = $thumbUrl;
                if($foreign == true && $needToDownload){
                    unlink($originalImageSrc);
                }
            }else{
                $this->pic = $thumbUrl;
            }
        }
        return $this->pic;
    }
    
    public function getShortDesc($number=200) {
        if($this->short_description) {
            return $this->short_description;
        }
        $content = $this->content;
        $content = preg_replace('/<\/?.*?>/is','',$content);
        $content = str_replace(array("\r\n", "\r", "\n", " "), "", $content);
        return mb_substr($content,0,$number);
    }
    
    public function getProvince() {
        $province = Region::find($this->province_id);
        if(!$province) {
            $province = new Region();
        }
        return $province;
    }
}
