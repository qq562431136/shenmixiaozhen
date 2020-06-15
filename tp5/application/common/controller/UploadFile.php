<?php
namespace app\common\controller;



class UploadFile
{

    //oss相关配置
    public $bucket;
    public $accessKeyId;
    public $accessKeySecret;
    public $endpoint;
    public $ossClient;
    public $ossUrlDomain;

    //上传配置
    public $maxSize = '10485760';//文件大小, 默认1M
    public $ossPath = 'file/';//OSS文件保存路径
    public $savePath = '';//本地临时文件保存路径
    public $extensions = ['jpg', 'jpeg', 'png','gif','txt','pfx','docx','xls','json'];//文件类型


    public function __construct()
    {
        $config=config('aliyun_oss');

        empty($this->bucket)            && $this->bucket            = $config['Bucket'];
        empty($this->accessKeyId)       && $this->accessKeyId       = $config['KeyId'];
        empty($this->accessKeySecret)   && $this->accessKeySecret   = $config['KeySecret'];
        empty($this->ossUrlDomain)    && $this->ossUrlDomain    = $config['ossUrlDomain'];
        empty($this->endpoint)          && $this->endpoint          = $config['Endpoint'];

        if (empty($this->ossClient)) {
            $this->ossClient = new \OSS\OssClient($this->accessKeyId, $this->accessKeySecret, $this->endpoint);
        }
    }

    /**
     * OSS文件上传接口
     * @param  string $field [上传字段]
     * @param  string $model [上传模型]
     * @return [type]        [description]
     */
    public function uploadOss($imgFile)
    {
        #正则表达式匹配出上传文件的扩展名
        preg_match('|\.(\w+)$|', $imgFile['name'], $ext);
        # print_r($ext);
        #转化成小写
        $imgFile['extension'] = strtolower($ext[1]);
        $path=$imgFile['tmp_name'];
//        var_dump($imgFile);
        // 先把本地的example.jpg上传到指定$bucket, 命名为$object
//        $info=upload($this->bucket, '213231', $path);

//        $info=upload('yihuwai',$object, $path);
//        var_dump($info);
//        //验证文件
        $check = $this->checkFile($imgFile);
        if ($check['code']) {
            return $check;
        }
        $ossfile = $this->uniqueOssFileName($check['uniqid_name'], $check['ext']);
        try {
           $info= $this->ossClient->uploadFile($this->bucket, $ossfile, $path);
        } catch (\Exception $e) {
            $error_message = $e->getMessage();
        }
        @unlink($check['filePath']);
        if (!empty($error_message)) {
            return ['code' => 1, 'msg' => $error_message];
        }
//        var_dump($info);
        $img_url = $this->ossUrlDomain.'/'. $ossfile;
        return ['code' => 0, 'url' => $img_url, 'attachment' => $img_url];
    }

    /**
     * 文件验证
     * @param  OBJECT $file [FILE OBJECT]
     * @return [type]       [description]
     */
    public function checkFile($file)
    {
        if (empty($file)) {
            return ['code' => 1, 'msg' => '文件对象为空'];
        }

        //PHP上传失败
        if (!empty($file->error)) {
            switch ($file->error) {
                case '1':
                    $error = '超过php.ini允许的大小。';
                    break;
                case '2':
                    $error = '超过表单允许的大小。';
                    break;
                case '3':
                    $error = '图片只有部分被上传。';
                    break;
                case '4':
                    $error = '请选择图片。';
                    break;
                case '6':
                    $error = '找不到临时目录。';
                    break;
                case '7':
                    $error = '写文件到硬盘出错。';
                    break;
                case '8':
                    $error = '文件类型错误';
                    break;
                case '999':
                default:
                    $error = '未知错误。';
            }
            return ['code' => 1, 'msg' => $error];
        }

        //检查文件名
//        print_r($file);
        if (!$file['name']) {
            return ['code' => 1, 'msg' => '请选择文件。'];
        }
//        //检查目录
//        if (@is_dir($this->savePath) === false) {
//            return ['code' => 1, 'msg' => '上传目录不存在。'];
//        }
//        //检查目录写权限
//        if (@is_writable($this->savePath) === false) {
//            return ['code' => 1, 'msg' => '上传目录没有写权限。'];
//        }
        //检查文件是否上传
        if ($this->check_allow_type($file['tmp_name']) === false) {
            return ['code' => 1, 'msg' => '文件类型不对！'];
        }
        //检查是否已上传
        if (@is_uploaded_file($file['tmp_name']) === false) {
            return ['code' => 1, 'msg' => '本地文件上传失败。'];
        }
        //检查文件大小
        if ($file['size'] > $this->maxSize) {
            return ['code' => 1, 'msg' => '上传文件大小超过限制。'];
        }
        //文件上传路径
        $uniqid_name = $this->getrandnums();
        $filePath = $this->savePath. '/' .$uniqid_name. '.' .'png';
//        if (false === $file->saveAs($filePath)) {
//            return ['code' => 1, 'msg' => '上传文件失败。'];
//        }
        return ['code' => 0, 'filePath' => $filePath, 'uniqid_name' => $uniqid_name, 'ext' => $file['extension']];
    }

    //检查文件类型
    public function check_allow_type($filename)
    {
        $file = fopen($filename, "rb");
        $bin = fread($file, 5); //只读2字节
        fclose($file);
        $strInfo = @unpack("C2chars", $bin);
        $typeCode = intval($strInfo['chars1'].$strInfo['chars2']);
        $fileType = '';
        switch ($typeCode)
        {
            case 7790:
                $fileType = 'exe';
                break;
            case 7784:
                $fileType = 'midi';
                break;
            case 8297:
                $fileType = 'rar';
                break;
            case 255216:
                $fileType = 'jpg';
                break;
            case 7173:
                $fileType = 'gif';
                break;
            case 6677:
                $fileType = 'bmp';
                break;
            case 13780:
                $fileType = 'png';
                break;
            case 8075:
                $fileType = 'docx';
                break;
            case 208207:
                $fileType = 'xls';
                break;
            case 12310:
                $fileType = 'json';
                break;
            default:
                $fileType = 'unknown: '.$typeCode;
        }
        if ($strInfo['chars1']=='-1' AND $strInfo['chars2']=='-40' ){ $fileType = 'jpg';}
        if ($strInfo['chars1']=='-119' AND $strInfo['chars2']=='80' ){ $fileType = 'png';}

        if(in_array($fileType, $this->extensions))
        {
            return true;
        }
        else
        {
            return false;
        }
    }


    //生成随机数
    protected function getrandnums()
    {
        return date('His').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
    }

    //获取不重复的oss文件名称
    public function uniqueOssFileName($fileName, $fileExt)
    {
        $ossfile = $this->ossPath. date('Ymd') .'/'. $fileName. '.' . $fileExt;
        if ($this->ossClient->doesObjectExist($this->bucket, $ossfile)) {
            $fileName = $this->getrandnums();
            $this->uniqueOssFileName($fileName, $fileExt);
        }
        return $ossfile;
    }

    /**
     * 图片缩放
     * @param $imgPath string 图片路径
     * @param string $thumbType string 图片缩放类型
     * @param string $type string 图片缩放类型（- lfit：等比缩放，限制在设定在指定w与h的矩形内的最大图片。- mfit：等比缩放，延伸出指定w与h的矩形框外的最小图片。- fill：固定宽高，将延伸出指定w与h的矩形框外的最小图片进行居中裁剪。- pad：固定宽高，缩略填充。- fixed：固定宽高，强制缩略）
     * @param string $height string 指定目标缩略图的高度（1-4096）
     * @param string $width string 指定目标缩略图的宽度 （1-4096）
     * @param string $limit string 指定当目标缩略图大于原图时是否处理。值是 1 表示不处理；值是 0 表示处理。
     * @param string $color string 缩放模式选择为pad（缩略填充）时，可以选择填充的颜色(默认是白色)参数的填写方式：采用16进制颜色码表示，如00FF00（绿色）
     * @return mixed
     */
    public function thumbImg($imgPath, $type = 'lfit', $height = 100, $width = 100, $color = 'FFFFFF')
    {
        $imgNewPath = $imgPath;
        switch ($type) {
            case 'lfit' : //lfit：等比缩放，限制在设定在指定w与h的矩形内的最大图片。
                $imgNewPath .= '?x-oss-process=image/resize,m_lfit,h_' . $height . ',w_' . $width;
                break;
            case 'mfit' : //mfit：等比缩放，延伸出指定w与h的矩形框外的最小图片。
                $imgNewPath .= '?x-oss-process=image/resize,m_mfit,h_' . $height . ',w_' . $width;
                break;
            case 'fill' : //fill：固定宽高，将延伸出指定w与h的矩形框外的最小图片进行居中裁剪
                $imgNewPath .= '?x-oss-process=image/resize,m_fill,h_' . $height . ',w_' . $width;
                break;
            case 'pad' : //pad：固定宽高，缩略填充。
                $imgNewPath .= '?x-oss-process=image/resize,m_pad,h_' . $height . ',w_' . $width . ',color_' . $color;
                break;
            case 'fixed' : //fixed：固定宽高，强制缩略）
                $imgNewPath .= '?x-oss-process=image/resize,m_fixed,h_' . $height . ',w_' . $width;
                break;
            default :
                break;
        }
        return $imgNewPath;
    }

    /**
     * 图片裁剪
     * @param $imgPath string 图片地址
     * @param $type string 背景色（png:透明背景，白色背景）
     * @param int $r int 半径
     * @return string
     */
    public function cutImg($imgPath, $type = 'png', $r = 100)
    {
        if ($type == 'png') {
            return $imgPath . '?x-oss-process=image/circle,r_' . $r . '/format,png';
        }
        return $imgPath . '?x-oss-process=image/circle,r_' . $r;
    }
}

