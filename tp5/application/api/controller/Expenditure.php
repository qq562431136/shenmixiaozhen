<?php
/**
 * Created by PhpStorm.
 * User: Svtar
 * Date: 2019/11/25
 * Time: 9:33
 */
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Expenditure extends BaseController
{
    //支出数据添加--营业费用
    public function expenditure_add()
    {
        $data = [
            'rent' => input('rent'),
            'property' => input('property'),
            'hydropower' => input('hydropower'),
            'platform' => input('platform'),
            'type_remarks' => input('type_remarks'),
            'remarks' => input('remarks'),
            'carte_time' => time(),
            'add_time' => strtotime(date(input('add_time'))),
            'store_id' => input('store_id'),

        ];
        Db::startTrans();
        try {
            Db::table('expenditure')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('营业支出填写成功'));
    }

    //支出数据修改--营业费用
    public function expenditure_edit()
    {
        $id = input('id');
        $data = [
            'rent' => input('rent'),
            'property' => input('property'),
            'hydropower' => input('hydropower'),
            'platform' => input('platform'),
            'type_remarks' => input('type_remarks'),
            'remarks' => input('remarks'),
            'store_id' => input('store_id'),
            'add_time' => strtotime(date(input('add_time'))),
        ];
        Db::startTrans();
        try {
            Db::table('expenditure')->where('id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('营业支出修改成功'));
    }

    //支出数据删除--营业费用
    public function expenditure_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('expenditure')->where('id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete =  Db::table('expenditure')->where('id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }

    //支出总计--按时间和门店ID
    public function expenditure_count()
    {
        $store_id = input('store_id');
        $end_time = input('end_time');
        $start_time = input('start_time');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        }
        if (!empty($store_id)) {
            $list = Db::field('*')//截取表s的name列 和表a的全部
            ->table(['expenditure' => 'a', 'store' => 'c'])
                ->where('a.store_id=' . $store_id . ' And c.store_id=a.store_id And add_time<=' . $end_time . ' And add_time>=' . $start_time)//查询条件语句
                ->select();
            $code = Db::table('wages')->where('store_id=' . $store_id.' And add_time<=' . $end_time . ' And add_time>=' . $start_time)->select();
            $name = expenditure_count($list);
            $name_2 = expenditure_wages($code);
            $data_20= $name + $name_2;
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
            $theme = Db::table('store')->where('store_id=' . $store_id)->select();
            $array_2 = array();
            foreach ($array as $k => $v) {
                foreach ($theme as $key => $value) {
                    $time = strtotime($v);
                    $time_end = $time + 86401;
                    $list = Db::field('*')//截取表s的name列 和表a的全部
                    ->table(['expenditure' => 'a', 'store' => 'c'])
                        ->where('a.store_id=' . $store_id . ' And c.store_id=a.store_id And add_time<=' . $time_end . ' And add_time>=' . $time)//查询条件语句
                        ->select();
                    $code = Db::table('wages')->where('store_id=' . $store_id.' And add_time<=' . $time_end . ' And add_time>=' . $time)->select();
                    $name = expenditure_count($list);
                    $name_2 = expenditure_wages($code);

                    $data= $name + $name_2;
                    $data['time'] = $v;//时间
                    array_push($array_2, $data);
                }
            }
            $data_10 = [
                'sum' => $data_20,
                'day' => $array_2,
            ];
            return json(new SuccessData($data_10));
        } else {
            $list = Db::table('expenditure')->where('add_time<=' . $end_time . ' And add_time>=' . $start_time)->select();
            $code = Db::table('wages')->where('add_time<=' . $end_time . ' And add_time>=' . $start_time)->select();
            $name = expenditure_count($list);
            $name_2 = expenditure_wages($code);

            $data_20 = $name + $name_2;
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
//            echo '312';exit;
            $array_2 = array();
            foreach ($array as $k => $v) {
                    $time = strtotime($v);
                    $time_end = $time + 86401;
                    $list = Db::field('*')//截取表s的name列 和表a的全部
                    ->table(['expenditure' => 'a', 'store' => 'c'])
                        ->where('add_time<=' . $time_end . ' And add_time>=' . $time)//查询条件语句
                        ->select();
                    $code = Db::table('wages')->where('add_time<=' . $time_end . ' And add_time>=' . $time)->select();
                    $name = expenditure_count($list);
                    $name_2 = expenditure_wages($code);
                    $data= $name + $name_2;
                    $data['time'] = $v;//时间
                    array_push($array_2, $data);
            }
            $data_10 = [
                'sum' => $data_20,
                'day' => $array_2,
            ];
            return json(new SuccessData($data_10));
        }

    }

    //支出数据列表--营业费用
    public function expenditure_select()
    {
        $add_time= input('add_time');
        $store_id = input('store_id');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        if (empty($add_time)) {
            $date = date("Y-m-d", time());//当前月份
            $time_2 = date('Y-m-01', strtotime($date));
            $start_time = strtotime(date('Y-m-01', strtotime($date)));
            $end_time = date(strtotime("$time_2 +1 month -1day"));
        }else{
            $add = explode('-',$add_time);
            $month_start = strtotime($add['0'].'-'.$add['1']);
            $date = date("Y-m-d", $month_start);//当前月份
            $time_2 = date('Y-m-01', strtotime($date));
            $start_time = strtotime(date('Y-m-01', strtotime($date)));
            $end_time = date(strtotime("$time_2 +1 month -1day"));
        }
        $rows = input('rows');
        $where = 'a.type_remarks=1 And c.store_id=a.store_id ';
        if (!empty($store_id)) {
            $where .= ' And a.store_id=' . $store_id;
            if (!empty($start_time)) {
                $where = 'a.store_id='.$store_id .' And a.type_remarks=1  And c.store_id=a.store_id  And  a.add_time<=' . $end_time . ' And  a.add_time>=' . $start_time;
            }
        } else {
            if (!empty($start_time)) {
                $where = 'a.type_remarks=1 And c.store_id=a.store_id And  a.add_time<=' . $end_time . ' And  a.add_time>=' . $start_time;
            }
        }
        $money = Db::field('a.id,a.add_time,c.store_name,a.rent,a.property,a.hydropower,a.platform,a.remarks,a.type_remarks,a.store_id,a.carte_time')//截取表s的name列 和表a的全部
        ->table(['expenditure' => 'a', 'store' => 'c'])
            ->where($where)//查询条件语句
            ->order('id desc')
            ->paginate($rows, false, $data);
        return json(new SuccessData($money));
    }

    //支出数据详情--营业费用
    public function expenditure_detail()
    {
        $id = input('id');
        $user = Db::table('expenditure')->field('store_id,carte_time,id,add_time,rent,property,hydropower,platform,remarks,type_remarks')->where('id=' . $id)->find();
        return json(new SuccessData($user));
    }

}