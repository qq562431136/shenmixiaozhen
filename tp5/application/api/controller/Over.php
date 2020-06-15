<?php
namespace app\api\controller;

use app\lib\exception\SuccessData;
use think\Db;

class Over
{
    //加班列表
    public function over_work()
    {
        $staff_id = input('staff_id');
        $time_end = input('time_end');
        $time = input('time');
        if (empty($time_end) && empty($time)) {
            $date = date("Y-m-d", time());//当前月份
            $time_2 = date('Y-m-01', strtotime($date));
            $time = strtotime(date('Y-m-01', strtotime($date)));
            $time_end = date(strtotime("$time_2 +1 month -1day"));
        }
        $where = 'a.theme_id=b.theme_id';
        if(!empty($staff_id)){
            $where .= " And  overtime=1 or overtime=2  And  a.staff_id=".$staff_id.'  And a.carte_time <=' . $time_end . ' And a.carte_time>=' . $time;
        }
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $list = Db::field('a.id,a.overtime,a.carte_time,a.staff_id,b.theme')//截取表s的name列 和表a的全部
        ->table(['over_records' => 'a', 'theme' => 'b', ])
            ->where($where)//查询条件语
            ->order('a.id desc')
            ->paginate($rows, false, $data);
        return json(new SuccessData($list));
    }
    public function over_list()
    {
        $time_end = input('time_end');
        $time = input('time');
        if (empty($time_end) && empty($time)) {
            $date = date("Y-m-d", time());//当前月份
            $time_2 = date('Y-m-01', strtotime($date));
            $time = strtotime(date('Y-m-01', strtotime($date)));
            $time_end = date(strtotime("$time_2 +1 month -1day"));
        }
        $list = Db::table('user')->field('number,staff_name,mobile as staff_mobile')->where('is_login=2')->select();
        $array = array();
        foreach ($list as $k => $v) {
            $count_1 = Db::table('over_records')->where('staff_id=' . $v['number'] . '  And  overtime=1'.'  And carte_time <=' . $time_end . ' And carte_time>=' . $time)->count('overtime');
            $count_2 = Db::table('over_records')->where('staff_id=' . $v['number'] . '  And  overtime=2'.'  And carte_time <=' . $time_end . ' And carte_time>=' . $time)->count('overtime');
            $name['over_money'] = ($count_1 * 10) + ($count_2 * 20);
            $name['staff_id'] = $v['number'];
            $list[$k]['over_money']=$name['over_money'];
            array_push($array, $name);
        }
        return json(new SuccessData($list));
    }
}