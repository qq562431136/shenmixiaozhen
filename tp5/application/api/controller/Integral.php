<?php
namespace app\api\controller;

use app\lib\exception\SuccessData;
use think\Db;

class Integral
{

    public function integral()
    {
        $list = Db::connect('db_con2')->name('eb_store_order')->paginate();
        return json(new SuccessData($list));
    }
    public function checkSignature()
    {
//1.将timestamp,nonce,toke按字典顺序排序
        $timestamp = $_GET['timestamp'];
        $nonce = $_GET['nonce'];
        $token = 'zPCiMJ7apdDSwjT2';
        $signature = $_GET['signature'];
        $array = array($timestamp, $nonce, $token);
//2.将排序后的三个参数拼接之后用sha1加密
        $tmpstr = implode('', $array);
        $tmpstr = sha1($tmpstr);
//3.将加密后的字符串与signature进行对比，判断该请求是否来自微信
        if ($tmpstr == $signature) {
            header('content-type:text');
            echo $_GET['echostr'];
            exit;
        }
    }
}
