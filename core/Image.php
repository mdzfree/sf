<?php
class Core_Image extends Core_Base
{
    const CONVERT_PATH = 'asset/cache';
    public $config;
    public $_cache = true;
    function __construct()
    {
        ini_set('gd.jpeg_ignore_warning', true);
        parent::__construct();
        $this->config = array();
    }

    public function openCache()
    {
        $this->_cache = true;
        return $this;
    }

    public function closeCache()
    {
        $this->_cache = false;
        return $this;
    }

    public function getConvertPath($path, $baseDir = null)
    {
        if ($baseDir === null) {
            $baseDir = ROOT_DIR . DS;
        }
        if (empty($path)) {
            return $baseDir . 'asset/internal/no_image.png';
        }
        $path = str_replace('\\', DS, $path);
        $path = str_replace('/', DS, $path);
        $asset = DS . 'asset' . DS;
        //转换绝对为相对
        if (is_numeric(strpos($path, $asset))) {
            // /project_xxx/asset/xxxx -> array('/project','xxxx')
            $tmpArrs = explode($asset, $path);
            //wrong on time
            $path = 'asset' . DS . $tmpArrs[1];
        }
        if (empty($path) || !is_file($baseDir . $path)) {
            $path   =   'asset/internal/no_image.png';
        }
        $src = $baseDir . $path;
        return $src;
    }
    /**
     * 获取转换后的地址
     * @param String $path      图片路径
     * @param Integer $width    图片宽度，默认为null等比处理
     * @param Integer $height   图片高度，默认为null等比处理
     * @param Integer $opacity  0 - 127 原图到透明，默认为0不透明
     */
    public function getConvertUrl($path, $width = null, $height = null, $opacity = 127, $baseDir = null, $wm = true)
    {
        $first = substr($path, 0, 4);
        if ($first == 'http') {
            return $path;
        }
        $path = str_replace('\\', DS, $path);
        $path = str_replace('/', DS, $path);
        $pDir = dirname($path);
        $pDir = str_replace('\\', DS, $pDir);
        $pDir = str_replace('/', DS, $pDir);
        if ($baseDir === null) {
            $baseDir = ROOT_DIR . DS;
        }
        $asset = DS . 'asset' . DS;
        //转换绝对为相对
        if (is_numeric(strpos($path, $asset))) {
            // /project_xxx/asset/xxxx -> array('/project','xxxx')
            $tmpArrs = explode($asset, $path);
            //wrong on time
            $path = 'asset' . DS . $tmpArrs[1];
        }
        if (empty($path) || !is_file($baseDir . $path)) {
            $path   =   'asset/internal/no_image.png';
        }
        $src = $baseDir . $path;
        $url = null;
        if (strpos($path, ':')=='') {
            //local
            if ($width != null || $height != null) {
                if (is_numeric($width) && $height == null) {
                    $dir        =   '__w' . $width . '__';
                } else if (is_numeric($height) && $width == null) {
                    $dir        =   '__h' . $height . '__';
                } else if (is_numeric($width) && is_numeric($height)) {
                    $dir        =   '__' . $width . 'x' . $height . '__';
                } else {
                    $dir        =   '__default__';
                }
                $root   =   $baseDir . self::CONVERT_PATH . DS . $dir;
            } else {
                $dir    =   '__default__';
                $root   =   $baseDir . self::CONVERT_PATH . DS . $dir;
            }
            $names  =   explode(DS, $pDir);
            //remove asset
            if (count($names) > 0 && $names[0] == 'asset') {
                unset($names[0]);
                $path = implode(DS, $names) . DS . basename($path);
            }
            $cacheFile  =   $root . DS . $path;
            //create path dir
            if (!is_file($cacheFile) || !$this->_cache) {
                $cPath = pathinfo($cacheFile);
                if (!is_dir($cPath['dirname'])) {
                    mkdir($cPath['dirname'], 0777, TRUE);
                    chmod($cPath['dirname'], 0777);
                }
                $this->make($src, $cacheFile, $width, $height, $opacity, $wm);
            }
            $url    =   BASEURL . BASEDIR . DS . self::CONVERT_PATH . DS . $dir . DS . $path;
            $url = str_replace(DS, '/', $url);
            return $url;
        } elseif ($baseDir !== null) {
            if ($width != null || $height != null) {
                if (is_numeric($width) && $height == null) {
                    $dir        =   '__w' . $width . '__';
                } else if (is_numeric($height) && $width == null) {
                    $dir        =   '__h' . $height . '__';
                } else if (is_numeric($width) && is_numeric($height)) {
                    $dir        =   '__' . $width . 'x' . $height . '__';
                } else {
                    $dir        =   '__default__';
                }
                $root   =   ROOT_DIR . DS . self::CONVERT_PATH . DS . $dir;
            } else {
                $dir    =   '__default__';
                $root   =   ROOT_DIR . DS . self::CONVERT_PATH . DS . $dir;
            }
            $tmpArrs = explode($asset, $path);
            //wrong on time
            $tmpPath = 'asset' . DS . $tmpArrs[1];
            $names  =   explode(DS, $pDir);
            //remove asset
            if (count($names) > 0 && $names[0] == 'asset') {
                unset($names[0]);
                $path = implode(DS, $names) . DS . basename($path);
            }
            $cacheFile  =   $root . DS . $tmpPath;
            if (!is_file($cacheFile) || !$this->_cache) {
                $cPath = pathinfo($cacheFile);
                if (!is_dir($cPath['dirname'])) {
                    mkdir($cPath['dirname'], 0777, TRUE);
                    chmod($cPath['dirname'], 0777);
                }
                $this->make($path, $cacheFile, $width, $height, $opacity, $wm);
            }
            $url    =   BASEURL . BASEDIR . DS . self::CONVERT_PATH . DS . $dir . DS . $tmpPath;
            $url = str_replace(DS, '/', $url);
            return $url;
        } else {
            //external
            return $url;
        }
    }

    private function transparent(&$handler, $opacity = 127)
    {
        if ($opacity == 0) {
            return;
        }
        $c = @imagecolorallocatealpha($handler, 255, 255, 255, $opacity);//拾取一个完全透明的颜色
        @imagealphablending($handler , false);//关闭混合模式，以便透明颜色能覆盖原画布
        @imagefill($handler , 0 , 0 , $c);//填充
        @imagesavealpha($handler , true);//设置保存PNG时保留透明通道信息
    }

    public function make($srcImgPath, $targetImgPath, $targetW = null, $targetH = null, $a, $wm = true, $r = 255, $g = 255, $b = 255)
    {
        $imgSize = GetImageSize($srcImgPath);
        $ratio  =   $imgSize[0] / $imgSize[1];
        if (is_numeric($targetW) && !is_numeric($targetH)) {
            $targetH    =   intval($targetW/$ratio);
        } else if (!is_numeric($targetW) && is_numeric($targetH)) {
            $targetW    =   intval($targetH*$ratio);
        } else if (!is_numeric($targetW) && !is_numeric($targetH)) {
            copy($srcImgPath, $targetImgPath);
            return $this;
        }
         $imgType = $imgSize[2];
         //@ 使函数不向页面输出错误信息
        switch ($imgType)
        {
            case 1:
                $srcImg = @ImageCreateFromGIF($srcImgPath);
                break;
            case 2:
                $srcImg = @ImageCreateFromJpeg($srcImgPath);
                break;
            case 3:
                $srcImg = @ImageCreateFromPNG($srcImgPath);
                break;
        }
         //取源图象的宽高
        $srcW = ImageSX($srcImg);
        $srcH = ImageSY($srcImg);
        if ($imgType == 3) {
            $this->transparent($srcImg, $a);
        }
        if ($imgType == 2) {
            $a = 0;
            $this->transparent($srcImg, $a);
        }
        if ($srcW > $targetW || $srcH > $targetH) {
            $targetX = 0;
            $targetY = 0;
            if ($srcW > $srcH) {
                $finalW = $targetW;
                $finalH = round($srcH * $finalW / $srcW);
                $targetY = floor(($targetH - $finalH) / 2);
            } else if ($srcW < $srcH) {
                $finalH = $targetH;
                $finalW = round($srcW * $finalH / $srcH);
                $targetX = floor(($targetW - $finalW) / 2);
            } else {
                if ($targetW > $targetH) {
                    $finalH = $targetH;
                    $finalW = round($srcW * $finalH / $srcH);
                    $targetX = floor(($targetW - $finalW) / 2);
                } else {
                    $finalW = $targetW;
                    $finalH = round($srcH * $finalW / $srcW);
                    $targetY = floor(($targetH - $finalH) / 2);
                }
            }
              //function_exists 检查函数是否已定义
              //ImageCreateTrueColor 本函数需要GD2.0.1或更高版本
            if (function_exists("ImageCreateTrueColor")) {
                $targetImg = ImageCreateTrueColor($targetW, $targetH);
            } else {
                $targetImg = ImageCreate($targetW, $targetH);
            }
            $targetX = ($targetX < 0) ? 0 : $targetX;
            $targetY = ($targetX < 0) ? 0 : $targetY;
            $targetX = ($targetX > ($targetW / 2)) ? floor($targetW / 2) : $targetX;
            $targetY = ($targetY > ($targetH / 2)) ? floor($targetH / 2) : $targetY;
              //背景白色
            $opacity = imagecolorallocatealpha($targetImg,  $r , $g , $b , $a);
            ImageFilledRectangle($targetImg,0,0, $targetW, $targetH, $opacity);
            if ($imgType == 3) {
                $this->transparent($targetImg, $a);
            }
            if ($imgType == 2) {
                $a = 0;
                $this->transparent($srcImg, $a);
            }
            /*
                   PHP的GD扩展提供了两个函数来缩放图象：
                   ImageCopyResized 在所有GD版本中有效，其缩放图象的算法比较粗糙，可能会导致图象边缘的锯齿。
                   ImageCopyResampled 需要GD2.0.1或更高版本，其像素插值算法得到的图象边缘比较平滑，
                                                             该函数的速度比ImageCopyResized慢。
            */
            if (function_exists("ImageCopyResampled")) {
                ImageCopyResampled($targetImg, $srcImg, $targetX, $targetY, 0, 0, $finalW, $finalH, $srcW, $srcH);
            } else {
                ImageCopyResized($targetImg, $srcImg, $targetX, $targetY, 0, 0, $finalW, $finalH, $srcW, $srcH);
            }
            switch ($imgType) {
                case 1:
                    ImageGIF($targetImg, $targetImgPath);
                    break;
                case 2:
                    ImageJpeg($targetImg, $targetImgPath,100);
                    break;
                case 3:
                    ImagePNG($targetImg, $targetImgPath,9);
                    break;
            }
            ImageDestroy($srcImg);
            ImageDestroy($targetImg);
        } else {
            //不超出指定宽高按大小放大，原图大小不变，背景以透明填充
            //创建大小为目标大小的白色底纹
            if (function_exists("ImageCreateTrueColor")) {
                $targetImg = ImageCreateTrueColor($targetW, $targetH);
            } else {
                $targetImg = ImageCreate($targetW, $targetH);
            }
            if ($imgType == 3) {
                $this->transparent($targetImg, $a);
            }
            if ($imgType == 2) {
                $a = 0;
                $this->transparent($srcImg, $a);
            }
            $opacity = imagecolorallocatealpha($targetImg, $r, $g, $b, $a);
            ImageFilledRectangle($targetImg, 0, 0, $targetW, $targetH, $opacity);
            $imgSize = GetImageSize($srcImgPath);
            $finalW = $imgSize[0];
            $finalH = $imgSize[1];
            $targetY = floor(($targetH - $finalH) / 2);
            $targetX = floor(($targetW - $finalW) / 2);
            if (function_exists("ImageCopyResampled")) {
                ImageCopyResampled($targetImg, $srcImg, $targetX, $targetY, 0, 0, $finalW, $finalH, $finalW, $finalH);
            } else {
                ImageCopyResized($targetImg, $srcImg, $targetX, $targetY, 0, 0, $finalW, $finalH, $finalW, $finalH);
            }
            switch ($imgType) {
                case 1:
                    ImageGIF($targetImg, $targetImgPath);
                    break;
                case 2:
                    ImageJpeg($targetImg, $targetImgPath, 100);
                    break;
                case 3:
                    ImagePNG($targetImg, $targetImgPath, 9);
                    break;
            }
            ImageDestroy($srcImg);
            ImageDestroy($targetImg);
        }
     }

     public function getTmpConvertUrl($_FILES_name, $width = null, $height = null, $opacity = 0)
     {
         $tmpFile = $_FILES[$_FILES_name]['tmp_name'];
         $image = GetImageSize($tmpFile);
         $type = '.png';
         switch ($image[2]) {
            case 1:
                $type = '.gif';
                break;
            case 2:
                $type = '.jpg';
                break;
            case 3:
                $type = '.png';
                break;
        }
        $fileName = md5_file($tmpFile);
        $path = 'asset/internal/tmp/' . $fileName . $type;
        copy($_FILES[$_FILES_name]['tmp_name'], ROOT_DIR . DS . $path);
        $url = $this->getConvertUrl($path, $width, $height, $opacity);
        return $url;
     }

     public function saveTmpByName($name, $path)
     {
         $fileName = $name;
         copy(ROOT_DIR . DS . 'asset/internal/tmp/' . $fileName, ROOT_DIR . DS . 'asset/internal/' . $path);
     }

     public function clearCache()
     {
         Core_IoUtils::instance()->clearDir(ROOT_DIR . DS . self::CONVERT_PATH);
     }

    /**
     * @param String $targetPath 要添加水印的目标图片地址
     * @param String $tagetWithMarkPath 处理后添加上水印的图片的地址
     * @param String $markPath 水印原图地址
     * @param String $markThumbPath 水印压缩后图片地址
     * @param int $markW 水印压缩宽度
     * @param int $markH 水印压缩高度
     * @param int $markX 水印位置 相对位置
     * @param int $markY 水印位置 相对位置
     * @param bool $isShow 是否开启水印
     */
    public function insertImageWatermark($targetPath, $markPath, $scale, $markX, $markY)
    {
        $targetImageSize = GetImageSize($targetPath);
        $targetW = $targetImageSize[0];
        $targetH = $targetImageSize[1];
        //图片小于一定比例，不添加

        if($targetH <= 10 || $targetW <= 10) {
            $scale = 0;
        }
        $markPath = $this->getConvertUrl($markPath, $targetW * $scale, $targetH * $scale, 127, null, false);
        $markImgSize = GetImageSize($markPath);
        $markImgType = $markImgSize[2];

        $markW = $markImgSize[0];
        $markH = $markImgSize[1];

         //@ 使函数不向页面输出错误信息

        switch ($markImgType)
        {
            case 1:
                $markImg = @ImageCreateFromGIF($markPath);
                break;
            case 2:
                $markImg = @ImageCreateFromJpeg($markPath);
                break;
            case 3:
                $markImg = @ImageCreateFromPNG($markPath);
                break;
        }
        //背景透明化
        if ($markImgType == 3) {
            $this->transparent($markImg, 127);
        }
        if ($markImgType == 2) {
            $this->transparent($markImg, 0);
        }

        $targetImageType = $targetImageSize[2];
        //@ 使函数不向页面输出错误信息
        switch ($targetImageType)
        {
            case 1:
                $targetImg = @ImageCreateFromGIF($targetPath);
                break;
            case 2:
                $targetImg = @ImageCreateFromJpeg($targetPath);
                break;
            case 3:
                $targetImg = @ImageCreateFromPNG($targetPath);
                break;
        }

        if($markX == 3 && $markY == 3){
            $markX = floor($targetW - $markW - 10);
            $markY = floor($targetH - $markH - 10);
        }else if($markX == 2 && $markY == 3){
            $markX = floor($targetW / 2 - $markW / 2);
            $markY = floor($targetH - $markH - 10);
        }else if($markX == 1 && $markY == 3){
            $markX = 10;
            $markY = floor($targetH - $markH - 10);
        }else if($markX == 1 && $markY == 1){
            $markX = 10;
            $markY = 10;
        }else if($markX == 2 && $markY == 1){
            $markX = floor($targetW / 2 - $markW / 2);
            $markY = 10;
        }else if($markX == 3 && $markY == 1){
            $markX = floor($targetW - $markW - 10);
            $markY = 10;
        }else if($markX == 1 && $markY == 2){
            $markX = 10;
            $markY = floor($targetH / 2 - $markH / 2);
        }else if($markX == 2 && $markY == 2){
            $markX = floor($targetW / 2 - $markW / 2);
            $markY = floor($targetH / 2 - $markH / 2);
        }else if($markX == 3 && $markY == 2){
            $markX = floor($targetW - $markW - 10);
            $markY = floor($targetH / 2 - $markH / 2);
        }
        if(function_exists("ImageCopyResampled")) {
            ImageCopyResampled($targetImg , $markImg, $markX, $markY ,0,0 , $markW, $markH, $markW, $markH);
        } else {
            ImageCopyResized($targetImg , $markImg, $markX, $markY ,0,0 , $markW, $markH, $markW, $markH);
        }
        if ($targetImageType == 3) {
            $this->transparent($targetImg, 127);
        }
        if ($targetImageType == 2) {
            $this->transparent($targetImg, 0);
        }
        switch ($targetImageType)
        {
            case 1:
                ImageGIF($targetImg, $targetPath);
                break;
            case 2:
                ImageJpeg($targetImg, $targetPath, 100);
                break;
            case 3:
                ImagePNG($targetImg, $targetPath, 9);
                break;
        }
    }

     public function drawCode($code = null)
     {
        if ($code == null) {
            $code = '';
            for ($a = 0; $a < 4; $a++) {
                $code .= chr(mt_rand(48, 57));
            }
        }
        ob_clean();
        header("Content-type: image/PNG");
        $imgWidth = 60;
        $imgHeight = 22;
        $img = imagecreate($imgWidth, $imgHeight);
        $bgColor = ImageColorAllocate($img, 255, 255, 255);
        $white = imagecolorallocate($img, 255, 255, 255);
        $orange = imagecolorallocate($img, 234, 185, 95);
        $red = imagecolorallocate($img, 200, 0, 0);
        $blue = imagecolorallocate($img, 0, 0, 150);
        $black = imagecolorallocate($img, 0, 0, 0);
        imagearc($img, 10, 8, 40, 20, 15, 10, $black);
        imagearc($img, 10, 7, 80, 30, 35, 5, $black);
        imageline($img, 0, 10, 100, 10, $blue);
        imageline($img, 0, 0, 100, 40, $red);
        imageline($img, 0, 20, 100, -20, $orange);
        $noise_num = 100;
        $line_num = 1;
        imagecolorallocate($img, 0xff, 0xff, 0xff);
        $noise_color=imagecolorallocate($img, 0x00, 0x00, 0x00);
        $font_color=imagecolorallocate($img, 0xFF, 0x00, 0x00);
        $line_color=imagecolorallocate($img, 0x00, 0x00, 0x00);
        for ($i = 0; $i < $noise_num; $i++) {
            imagesetpixel($img, mt_rand(0, $imgWidth), mt_rand(0, $imgHeight), $noise_color);
        }
        for ($i = 0; $i < $line_num; $i++) {
            imageline($img, mt_rand(0, $imgWidth), mt_rand(0, $imgHeight), mt_rand(0, $imgWidth), mt_rand(0, $imgHeight), $line_color);
        }
        $fontfile = ROOT_DIR . DS . 'core/Font/arialbd.ttf';
        $fontsiz = 16;
        $str    =   $code;
        imagettftext($img, $fontsiz, -50, 3, 10, $font_color, $fontfile, $str[0]);
        imagettftext($img, $fontsiz, -30, 17, 15, $font_color, $fontfile, $str[1]);
        imagettftext($img, $fontsiz, 10, 35, 18, $font_color, $fontfile, $str[2]);
        imagettftext($img, $fontsiz, -20, 44, 16, $font_color, $fontfile, $str[3]);
        ImagePNG($img);
        ImageDestroy($img);
        return $code;
    }

    public function compress($image, $dw = 800, $dh = 600, $type = 1) {
        if (!file_exists($image)) {
            return false;
        }
        $imgSize = GetImageSize($image);
        if ($imgSize[0] < $dw) {
            $dw = $imgSize[0];
        }
        if ($imgSize[1] < $dh) {
            $dh = $imgSize[1];
        }
        //如果需要生成缩略图,则将原图拷贝一下重新给$image赋值
        if ($type != 1) {
            copy($image, str_replace(".", "_x.", $image));
            $image = str_replace(".", "_x.", $image);
        }
        //取得文件的类型,根据不同的类型建立不同的对象
        $imgInfo = GetImageSize($image);
        switch ($imgInfo[2]) {
            case 1:
                $img = @ImageCreateFromGif($image);
                break;
            case 2:
                $img = @ImageCreateFromJPEG($image);
                break;
            case 3:
                $img = @ImageCreateFromPNG($image);
                break;
        }
        //如果对象没有创建成功,则说明非图片文件
        if (empty($img)) {
            //如果是生成缩略图的时候出错,则需要删掉已经复制的文件
            if ($type != 1) {
                unlink($image);
            }
            return false;
        }
        //如果是执行调整尺寸操作则
        if ($type == 1) {
            $w = ImagesX($img);
            $h = ImagesY($img);
            $width = $w;
            $height = $h;
            if ($width > $dw) {
                $par = $dw / $width;
                $width = $dw;
                $height = $height * $par;
                if ($height > $dh) {
                    $par = $dh / $height;
                    $height = $dh;
                    $width = $width * $par;
                }
            } elseif ($height > $dh) {
                $par = $dh / $height;
                $height = $dh;
                $width = $width * $par;
                if ($width > $dw) {
                    $par = $dw / $width;
                    $width = $dw;
                    $height = $height * $par;
                }
            } else {
                $width = $width;
                $height = $height;
            }
            $nImg = ImageCreateTrueColor($width, $height); //新建一个真彩色画布
            ImageCopyReSampled($nImg, $img, 0, 0, 0, 0, $width, $height, $w, $h); //重采样拷贝部分图像并调整大小
            if (copy($image, str_replace(".", "_bak.", $image))) {
                unlink($image);
            }
            ImageJpeg($nImg, $image); //以JPEG格式将图像输出到浏览器或文件
            return true;
            //如果是执行生成缩略图操作则

        } else {
            $w = ImagesX($img);
            $h = ImagesY($img);
            $width = $w;
            $height = $h;
            $nImg = ImageCreateTrueColor($dw, $dh);
            if ($h / $w > $dh / $dw) { //高比较 大
                $width = $dw;
                $height = $h * $dw / $w;
                $intNH = $height - $dh;
                ImageCopyReSampled($nImg, $img, 0, -$intNH / 1.8, 0, 0, $dw, $height, $w, $h);
            } else { //宽比较大
                $height = $dh;
                $width = $w * $dh / $h;
                $intNW = $width - $dw;
                ImageCopyReSampled($nImg, $img, -$intNW / 1.8, 0, 0, 0, $width, $dh, $w, $h);
            }
            if (copy($image, str_replace(".", "_bak.", $image))) {
                unlink($image);
            }
            ImageJpeg($nImg, $image);
            return true;
        }
    }

    function qrcode($str, $mode = 'png', $level = null, $size = 4, $margin = 4, $saveandprint = false)
    {
        sfget_instance('Class_Qrcode_qrlib');
        if ($level === null) {
            $level = QR_ECLEVEL_L;
        }
        switch ($mode) {
            case 'png':
                QRcode::png($str, false, $level, $size, $margin, $saveandprint);
                break;
            case 'url':
            case 'file':
                $key = md5($str);
                $path = '/asset/cache/file/qrcode/' . Core_IoUtils::instance()->getMd5Dir($key) . '/' . $key . '.png';
                $file = ROOT_DIR . $path;
                if (!is_file($file)) {
                    $dir = dirname($file);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    QRcode::png($str, $file, $level, $size, $margin, $saveandprint);
                }
                if ($mode == 'url') {
                    return SITEURL_ROOT . $path;
                } else {
                    return $file;
                }
        }
    }

    function barcode($str, $size = 1, $font_size = 8, $code = 'BCGcode128', $font_family = 'Arial.ttf', $filetype = 'PNG', $dpi = 72, $rotation = null)
    {
        $image = sfget_instance('Class_Barcodegen_image');
        $image->finish($str, $size, $font_size, $code, $font_family, $filetype, $dpi, $rotation);
    }


}