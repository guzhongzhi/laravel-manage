<?php
namespace App\Helper;
use Illuminate\Support\Facades\DB;
class ImageSeekHelper {
//for image seek
    static $sightImgPath = '/upload/sight/';
    static $secretImgPath = '/upload/travel/';
    static $foodImgPath = '/upload/food/';
    static $hotelImgPath = '/upload/hotel/';
    static $hotelImgLimitNumber = 50; //for each city, the number of hotel we will download.
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

    public static function seekPicArray($imgs, $savepath=''){
        $pics = array();
        foreach($imgs as $itemImg){
            $pics[] = self::savePic($itemImg, $savepath);
        }
        return $pics;
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
        if(count($picRrr) > 40 ){ //如果图片过多，则不下载该游记图片
            echo "Pic too many - " . count($picRrr) . PHP_EOL;
            //$content = preg_replace('%<div id="img.*?</div>%si', '', $content);
            $contentPic = array('pic'=>$picRrr[0], 'content'=>$content);
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


    /**
    +----------------------------------------------------------
     * 取得图像信息
     *
    +----------------------------------------------------------
     * @static
     * @access public
    +----------------------------------------------------------
     * @param string $image 图像文件名
    +----------------------------------------------------------
     * @return mixed
    +----------------------------------------------------------
     */
    public static function getImageInfo($img) {
        $imageInfo = getimagesize($img);
        if( $imageInfo!== false) {
            $imageType = strtolower(substr(image_type_to_extension($imageInfo[2]),1));
            $imageSize = filesize($img);
            $info = array(
                "width"		=>$imageInfo[0],
                "height"	=>$imageInfo[1],
                "type"		=>$imageType,
                "size"		=>$imageSize,
                "mime"		=>$imageInfo['mime'],
            );
            return $info;
        }else {
            return false;
        }
    }

    /**
    +----------------------------------------------------------
     * 生成缩略图
    +----------------------------------------------------------
     * @static
     * @access public
    +----------------------------------------------------------
     * @param string $image  原图

     * @param string $dstFile 缩略图文件
     * @param string $maxWidth  宽度
     * @param string $maxHeight  高度
     * @param string $type 图像格式
     * @param boolean $interlace 启用隔行扫描

    +----------------------------------------------------------
     * @return void
    +----------------------------------------------------------
     */
    public static function makeThumb($srcFile, $dstFile, $suofang=0,$maxWidth=500,$maxHeight=500,$type='',$interlace=true){
        // 获取原图信息
        if(file_exists($dstFile)){
            return $dstFile;
        }
        $info  = self::getImageInfo($srcFile);
        if($info !== false) {
            $srcWidth  = $info['width'];
            $srcHeight = $info['height'];
            $type = empty($type)?$info['type']:$type;
            $type = strtolower($type);
            $interlace  =  $interlace? 1:0;
            unset($info);
            if ($suofang==0) {
                $width  = $srcWidth;
                $height = $srcHeight;
            } else {
                $scale = min($maxWidth/$srcWidth, $maxHeight/$srcHeight); // 计算缩放比例
                if($scale>=1) {
                    // 超过原图大小不再缩略
                    $width   =  $srcWidth;
                    $height  =  $srcHeight;
                }else{
                    // 缩略图尺寸
                    $width  = (int)($srcWidth*$scale);	//147
                    $height = (int)($srcHeight*$scale);	//199
                }
                $width = $maxWidth; //固定高宽
                $height = $maxHeight; //固定高宽
            }

            // 载入原图
            $createFun = 'ImageCreateFrom'.($type=='jpg'?'jpeg':$type);

            $srcImg     = $createFun($srcFile);

            //创建缩略图
            if($type!='gif' && function_exists('imagecreatetruecolor'))
                $thumbImg = imagecreatetruecolor($width, $height);
            else
                $thumbImg = imagecreate($width, $height);

            // 复制图片
            if(function_exists("ImageCopyResampled"))
                imagecopyresampled($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height, $srcWidth,$srcHeight);
            else
                imagecopyresized($thumbImg, $srcImg, 0, 0, 0, 0, $width, $height,  $srcWidth,$srcHeight);
            if('gif'==$type || 'png'==$type) {
                //imagealphablending($thumbImg, false);//取消默认的混色模式
                //imagesavealpha($thumbImg,true);//设定保存完整的 alpha 通道信息
                $background_color  =  imagecolorallocate($thumbImg,  0,255,0);  //  指派一个绿色
                imagecolortransparent($thumbImg,$background_color);  //  设置为透明色，若注释掉该行则输出绿色的图
            }
            // 对jpeg图形设置隔行扫描
            if('jpg'==$type || 'jpeg'==$type) 	imageinterlace($thumbImg,$interlace);
            //$gray=ImageColorAllocate($thumbImg,255,0,0);
            //ImageString($thumbImg,2,5,5,"ThinkPHP",$gray);
            // 生成图片
            $imageFun = 'image'.($type=='jpg'?'jpeg':$type);
            $imageFun = 'imagejpeg';
            $length = strlen("00.".$type) * (-1);
            $_type = substr($srcFile,-4);
            $length = ($type != $_type ? $length+1 : $length);
            $imageFun($thumbImg,$dstFile,100);

            imagedestroy($thumbImg);
            imagedestroy($srcImg);
            return $dstFile ;					//返回缩略图的路径，字符串


        }
        return false;
    }

    public static function getThumFileSrc($originalSrc){
        $position = strripos($originalSrc, '/');
        $fileDir  = substr($originalSrc, 0, $position+1);
        $thumbSrc = $fileDir . sha1(substr($originalSrc, $position+1)).".jpg";
        return $thumbSrc;
    }
    
}