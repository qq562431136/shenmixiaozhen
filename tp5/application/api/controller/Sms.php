<?php
namespace app\api\controller;
use think\Db;

class Sms{
    //自动发送短信
    public function sms()
    {
        $start_time = strtotime(date('Ymd'));
        $time = strtotime(date('Ymd')) + 86399;
        $send = Db::table('reserve')->where('carte_time<' . $time . ' And carte_time>' . $start_time)->select();
//        var_dump($send);exit;
        foreach ($send as $k => $v) {
            if ($v['send'] == '1') {
            } else {
                if (!empty($v['time_start'])) {
                    $date = date('H:i');
                    $curTime = strtotime($date);//当前时分
                    $assignTime1 = strtotime($v['time_start']);//获得指定分钟时间戳，00:00
                    $time_2 = $assignTime1 - $curTime;
                    if ($time_2 < 3600) {
                        $code = rand(100000, 999999);
                        $tel = $v['job_mobile'];
                        send_sms_code($tel, $code);
                        $data = [
                            'send' => '1'
                        ];
                        Db::table('reserve')->where('id=' . $v['id'])->update($data);
                    }
                }
            }
        }
    }
}