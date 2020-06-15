<?php
namespace app\api\controller;

use app\lib\exception\SuccessData;
use think\Db;
use app\lib\exception\ErrorException;

class Index extends BaseController
{
    //所有门店
    public function index()
    {
        $user_id = input('user_id');
        $name = Db::table('user')->where('id=' . $user_id)->find();
        if ($name['position'] == '1') {
            $select = Db::table('store')->select();
        } else {
            $select = Db::table('store')->where('store_id=' . $name['store_id'])->select();
        }
        return json(new SuccessData($select));
    }

    //指定门店所有主题
    public function theme()
    {
        $user_id = input('user_id');
        $name = Db::table('user')->where('id=' . $user_id)->find();
        $store_id = input('store_id');
        if(!empty($store_id)){
            $select = Db::table('theme')->where('store_id='.$store_id)->select();
        }else{
            if ($name['position'] == '1') {
                $select = Db::table('theme')->select();
            } else {
                $select = Db::table('theme')->where('store_id=' . $name['store_id'])->select();
            }
        }
        return json(new SuccessData($select));
    }

    //指定门店指定主题预约列表--当日
    public function time_slot()
    {
        $theme_id = input('theme_id');
        $time = input('time');
        if (!empty($time)) {
            $time_3 = ($time / 1000);
            $start_time = $time_3;
            $time_2 = $time_3 + 86399;
        } else {
            $start_time = strtotime(date('Ymd'));
            $time_2 = strtotime(date('Ymd')) + 86399;
        }
        $reserve = Db::table('reserve')->where('time_id!=0  And theme_id=' . $theme_id . ' And  carte_time<' . $time_2 . ' And carte_time>' . $start_time)->select();
        $select = Db::table('time_slot')->where('theme_id=' . $theme_id)->select();
        $data = [
            'reserve' => $reserve,
            'time_slot' => $select,
            'time' => time()
        ];
        return json(new SuccessData($data));
    }

    //菜单
    public function menu()
    {
        $power = Db::table('menu')->select();
        return json(new SuccessData($power));
    }

//    //全部菜单
//    public function menu_all()
//    {
//
//        $power = Db::table('menu')->select();
//        return json(new SuccessData($power));
//    }

    //测试
    public function demo()
    {
//        $url ='https://api.weixin.qq.com/cgi-bin/user/get?access_token=33_7kxy0wM5syZybMfVXDwQbg9vUSdBTxsefzyCJXiC1G0hg5NQtUuXxEuRCsZnQmtuBcuo7Q2Ux5MV6lvYNksCbimcl7ro3nUMVIAmtjCwmzCjt-tuZwvXtS1_Qgsvxfh4KNJrX26GR1DjUkDcELWjAFAQXI&next_openid';
//        $url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wx3037021d6fb0f836&secret=f3f1cf378e719a057b2087eac53a3402';
//      $name =  vget($url);
//        var_dump($name); exit;
        date_default_timezone_set('PRC');
        $start_time = '1591172510';
        $end_time = '1592901049';
        $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
        $start_time = date($start_time);
        //获取本月第一天时间戳
        $array = array();
        for ($i = 0; $i < $count_days; $i++) {
            $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
        }
        foreach ($array as $k => $v) {
            $time = strtotime($v);
            $data = [
                'user_id' => '1',
                'job_name' => rand(1, 50),
                'job_mobile' => '18876309770',
                'people' => rand(1, 10),
                'source' => rand(1, 5),
                'age' => rand(1, 4),
                'man_woman' => rand(1, 5),
                'pay_type' => rand(1, 4),
                'money' => rand(300, 500),
                'birthday_people' => '1',
                'free' => '0',
                'other' => rand(1, 3),
                'other_money' => rand(100, 300),
                'amount' => rand(10, 30),
                'express' => rand(20, 50),
                'start_time' => $start_time,
                'store_id' => rand(1, 5),
                'end_time' => $end_time,
                'overtime' => rand(1, 2),
                'help_second' => '3',
                'degree' => '5',
                'theme_id' => rand(1, 25),
                'carte_time' => $time
            ];
            Db::table('reserve')->insert($data);
        }

    }

    public function demo_count()
    {

        date_default_timezone_set('PRC');
        $start_time = '1591172510';
        $end_time = '1593504949';
        $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
        $start_time = date($start_time);
        //获取本月第一天时间戳
        $array = array();
        for ($i = 0; $i < $count_days; $i++) {
            $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
        }
        foreach ($array as $k => $v) {
            $time = strtotime($v);
            $data = [
                'rent' => rand(300, 500),
                'property' => rand(1000, 3000),
                'hydropower' => rand(300, 600),
                'platform' => rand(200, 600),
                'marketing_upper' => rand(2500, 600),
                'marketing_lower' => rand(310, 600),
                'stock_type' => rand(1, 2),
                'stock_id' => rand(1, 5),
                'stock_money' => rand(50, 100),
                'program' => rand(300, 600),
                'artificial' => rand(300, 600),
                'carte_time' => $time,
                'add_time' => $time,
                'remarks' => rand(10, 20),
                'store_id' => rand(1, 5),
                'type_remarks' => rand(1, 4),
            ];
            Db::table('expenditure')->insert($data);
        }

    }

    public function demo_slot()
    {
        date_default_timezone_set('PRC');
    $data =[
        'title'=>'采购费用列表',
        'type'=>'14'
        ];
        Db::table('menu')->insert($data);

    }

}
