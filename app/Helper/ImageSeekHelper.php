<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
class ImageSeekHelper {
//for image seek
    static $sightImgPath = '/upload/sight/';
    static $secretImgPath = '/upload/travel/';
    static $foodImgPath = '/upload/food/';
    public static function makeDir($path){
        $path = public_path() . $path . date("Y") . date("m") . "/";
        if(!file_exists($path)){//不存在则建立 
            $mk=@mkdir($path,0777, true); //权限 
            if(!$mk){
                echo "No access for mkdir $path";die();
            }
            @chmod($path,0777); 
        } 
        return $path; 
    
    }
    
    
    public static function readFiletext($filepath){ 
        $filepath=trim($filepath); 
        $htmlfp=@fopen($filepath,"r"); 
        $string = '';
        //远程 
        if(strstr($filepath,"://")){ 
            while($data=@fread($htmlfp,500000)){ 
                $string.=$data; 
            } 
        } 
        //本地 
        else{ 
            $string=@fread($htmlfp,@filesize($filepath)); 
        } 
        @fclose($htmlfp); 
        return $string; 
    }

    public static function writeFiletext($filepath,$string){ 
        if(!file_exists($filepath)){
            $fp=@fopen($filepath,"w"); 
            @fputs($fp,$string); 
            @fclose($fp); 
        }
        return true;
        
    }
    
    public static function getFilename($filepath){ 
        $fr=explode("/",$filepath); 
        $count=count($fr)-1; 
        return $fr[$count]; 
    }
    
    public static function savePic($url,$savepath=''){ 
        //处理地址 
        $url=trim($url); 
        $url=str_replace(" ","%20",$url); 
        //读文件 
        $string = self::readFiletext($url); 
        if(empty($string)){ 
            echo "no access for the file $url" . PHP_EOL;return '';
        } 
        //文件名 
        $filename = self::getFilename($url); 
        //$filename =  date("dh").uniqid() . strrchr($filename, '.');
        $ext = strrchr($filename, '.');
        $filename =  date("d"). md5($filename) . $ext;
        //存放目录 
        $fileDir = self::makeDir($savepath); //建立存放目录 
        //文件地址 
        $filepath = $fileDir.$filename; 
        
        //存入数据库地址
        $dbFilePath = str_replace(public_path(), '', $filepath);
        //写文件         
        self::writeFiletext($filepath,$string); 
        return $dbFilePath; 
    }
    
    /**
    * @parm $seekType 'secret', 'sight' 
    *  
    */
    public static function seekPicAndSave($content, $seekType){ 

        $imgPath = '';
        switch($seekType){
            case 'secret':
                $imgPath = self::$secretImgPath;
                break;
            case 'sight':
                $imgPath = self::$sightImgPath;
                break;
            default:
                $imgPath = self::$secretImgPath;
                break;
        }
        $patternSrc = '/<[img|IMG].*?data-original=[\'|\"](.*?(?:[\.gif|\.png|\.jpg]))[\'|\"].*?[\/]?>/'; 
        preg_match_all($patternSrc, $content, $matchSrc); 
        $picRrr = isset($matchSrc[1]) ? $matchSrc[1] : array(); 
        $patternSrc = '/<[img|IMG].*?src=[\'|\"](.*?(?:[\.gif|\.png|\.jpg]))[\'|\"].*?[\/]?>/'; 
        preg_match_all($patternSrc, $content, $matchSrc); 
        $imgRrr = isset($matchSrc[1]) ? $matchSrc[1] : array(); 
        preg_match_all('/<a.*?share-pic.*?href=[\'|"](.*?)[\'|"]/si', $content, $matchSrc); //special for ctrip site
        $crtripRrr = isset($matchSrc[1]) ? $matchSrc[1] : array(); 
        $picFirst = '';
        $picRrr = array_merge($picRrr, $imgRrr, $crtripRrr);
        $picRrr = array_unique($picRrr);
        if(count($picRrr) > 40 ){ //如果图片过多，则不下载该游记
            $content = preg_replace('%<div id="img.*?</div>%si', '', $content);
            $contentPic = array('pic'=>'', 'content'=>$content);
            return $contentPic;
        }

        $matchImgUrls = array();
        foreach ($picRrr as $picItem) { //循环取出每幅图的地址 
            $dbFilePath = self::savePic($picItem,$imgPath); //下载并保存图片 如果需要添加图片域名，则直接加在dbFilePath前面
            if(!$dbFilePath) continue;
            if(!$picFirst){
                $picFirst = $dbFilePath;
            }
            //$matchImgUrls[$picItem] = $dbFilePath;
            $content = str_replace($picItem , $dbFilePath, $content);
            //echo $picItem . " - " . $dbFilePath . "\n";
        }
        //echo $content;
        $contentPic = array('pic'=>$picFirst, 'content'=>$content);
        return $contentPic;
    }
    
}