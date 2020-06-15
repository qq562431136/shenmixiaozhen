<?php
namespace app\api\controller;

use app\lib\exception\SuccessData;
use app\lib\exception\SuccessMessage;
use think\Cache;
use think\Db;
class Upload extends \app\common\controller\Upload
{

    /**
     * 上传图片
     * @url http://demo.meooh.com/api/uploadImg
     * @method POST
     * @param string $user_id  用户ID(必须)
     * @param string $token  登陆TOKEN(必须)
     * @param string $image 上传的图片(必须)
     * @param string $sign 加密参数(必须)
     * * @return string $data 返回消息
     */
    public function uploadImg()
    {

        $file= $_FILES['image'];
            $info=$this->uploadImgOss($file);
            if($info['code']!=0){
                return json(new SuccessMessage($info['msg'],401));
            }
            $img_arr['img_url']=$info['url'];

        return json(new SuccessData($img_arr));
    }

}
