<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
error_reporting(0);
date_default_timezone_set('PRC');
// [ 应用入口文件 ]
$originList = [
    'http://localhost:3001',
    'http://localhost:3000',
    'http://192.168.1.26:3001',
    'http://192.168.1.26',
    'http://192.168.1.26:3000',
    'http://192.168.1.27:10',
    'http://jjt.meooh.com',
    'http://mishi.meooh.com',
    'http://mi.meooh.com',
];
if(in_array($_SERVER['HTTP_ORIGIN'], $originList)){
    header('Access-Control-Allow-Origin:'.$_SERVER['HTTP_ORIGIN']);
    header("Access-Control-Allow-Credentials: true");
    header('Access-Control-Allow-Headers:x-requested-with,content-type');
    header("Access-Control-Allow-Methods: POST,GET，OPTIONS");
}
//下面的语句设置此页面的过期时间(用格林威治时间表示)，只要是已经过去的日期即可。
header("Expires: Mon, 26 Jul 1970 05:00:00 GMT");

//下面的语句设置此页面的最后更新日期(用格林威治时间表示)为当天，可以强迫浏览器获取最新资料
//header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header('Last-Modified: '.gmdate('D, d M Y H:i:s',time())." GMT");
////告诉客户端浏览器不使用缓存，HTTP 1.1 协议
//header("Cache-Control: no-cache, must-revalidate");

////告诉客户端浏览器不使用缓存，兼容HTTP 1.0 协议
//header("Pragma: no-cache");
// 定义应用目录
define('APP_PATH', __DIR__ . '/../application/');
// 加载框架引导文件
require __DIR__ . '/../thinkphp/start.php';
