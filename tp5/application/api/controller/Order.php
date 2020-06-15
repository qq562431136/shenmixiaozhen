<?php
namespace app\api\controller;

use app\lib\exception\SuccessData;
use think\Db;
use app\admin\controller\User;

class Order
{
    public function write()
    {
        $ordersn = input('ordersn');
        $new = new User();
        return $new->order($ordersn);
    }

    public function order_list()
    {
        $db2=Db::connect('db2');
        $status = input('status');
        $mobile =input('mobile');
        $time_end = input('time_end');
        $time = input('time');
        if (empty($time_end) && empty($time)) {
            $date = date("Y-m-d", time());//当前月份
            $time_2 = date('Y-m-01', strtotime($date));
            $time = strtotime(date('Y-m-01', strtotime($date)));
            $time_end = date(strtotime("$time_2 +1 month -1day"));
        }
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $where ='1';
        if(!empty($mobile)){
            $where.='  And  phone='.$mobile;
        }
        if(!empty($status)){
            $where.='  And  status='.$status;
        }
        if(!empty($time_end) && !empty($time)){
            $where .= '  And createtime <=' . $time_end . ' And createtime >=' . $time;
        }
        $list =  $db2->name('ims_zhou_integral_mall_order')->where($where)->order('id desc')->paginate($rows, false, $data);;
        return json(new SuccessData($list));
    }


}
