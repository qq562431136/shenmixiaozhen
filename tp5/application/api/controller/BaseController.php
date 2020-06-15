<?php
namespace app\api\controller;
use app\lib\exception\ErrorException;
use think\Cache;
use think\Db;
use think\Controller;
class BaseController extends Controller
{
    public static $accessTokenPrefix = 'accessToken_';

    public function __construct()
    {
//        $token = input('access_token');
//        $cache = new cache;
//        $accessTokenPre = $cache::get(self::$accessTokenPrefix.$token);
//        if($accessTokenPre==false){
//            echo json_encode(['code' => 301, 'msg' => 'token已过期重新登录'],JSON_UNESCAPED_UNICODE);
//            exit;
//        }
    }
}