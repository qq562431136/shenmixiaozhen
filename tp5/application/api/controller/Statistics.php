<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Statistics extends BaseController
{
    //总数据
    public function statistics()
    {
        $store_id = input('store_id');
        $start_time = input('start_time');
        $end_time = input('end_time');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        }
        if (!empty($store_id)) {
            $select = Db::table('reserve')->where('store_id=' . $store_id . ' And carte_time <=' . $end_time . ' And carte_time>=' . $start_time)->select();
            $name = Db::table('store')->where('store_id=' . $store_id)->find();
            $data_2 = Statistics($select);
            $array_2 = array();
            $data_20 = [
                'store_name' => $name['store_name'],//门店名称
                'cash' => $data_2['cash'],//现金总额
                'unionpay' => $data_2['unionpay'],//银联总额
                'meituan_money' => $data_2['meituan_money'],//美团预定总额
                'voucher_money' => $data_2['voucher_money'],//大众/美团券总额
                'amount_money' => $data_2['amount_money'],//开票费用总额
                'other_sum' => $data_2['other_sum'],//其他总额
            ];
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
            $theme = Db::table('store')->where('store_id=' . $store_id)->select();
            foreach ($array as $k => $v) {
                foreach ($theme as $key => $value) {
                    $time = strtotime($v);
                    $time_end = $time + 86401;
                    $select_2 = Db::table('reserve')->where('store_id=' . $value['store_id'] . ' And carte_time<=' . $time_end . ' And carte_time>=' . $time)->select();
                    $data_3 = Statistics($select_2);
                    $data = [
//                    'sum' => $data_2['sum'],//总营业额
                        'cash' => $data_3['cash'],//现金总额
                        'unionpay' => $data_3['unionpay'],//银联总额
                        'meituan_money' => $data_3['meituan_money'],//美团预定总额
                        'voucher_money' => $data_3['voucher_money'],//大众/美团券总额
                        'amount_money' => $data_3['amount_money'],//开票费用总额
                        'other_sum' => $data_3['other_sum'],//其他总额
                    ];
                    $data['store_name'] = $value['store_name'];
//
                    $data['overtime_money'] = '0';//加班总额
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
            $select_2 = Db::table('reserve')->where(' carte_time<=' . $end_time . ' And carte_time>=' . $start_time)->select();
            $data_2 = Statistics($select_2);
            $array_2 = array();
            $array_20 = [
                'sum' => $data_2['sum'],//总营业额
                'cash' => $data_2['cash'],//现金总额
                'unionpay' => $data_2['unionpay'],//银联总额
                'meituan_money' => $data_2['meituan_money'],//美团预定总额
                'voucher_money' => $data_2['voucher_money'],//大众/美团券总额
                'amount_money' => $data_2['amount_money'],//开票费用总额
                'other_sum' => $data_2['other_sum'],//其他总额
            ];
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
            foreach ($array as $k => $v) {
                $time = strtotime($v);
                $time_end = $time + 86401;
                $select_2 = Db::table('reserve')->where(' carte_time <=' . $time_end . ' And carte_time >=' . $time)->select();
                $data_6 = Statistics($select_2);
                $data = [
//                    'sum' => $data_2['sum'],//总营业额
                    'cash' => $data_6['cash'],//现金总额
                    'unionpay' => $data_6['unionpay'],//银联总额
                    'meituan_money' => $data_6['meituan_money'],//美团预定总额
                    'voucher_money' => $data_6['voucher_money'],//大众/美团券总额
                    'amount_money' => $data_6['amount_money'],//开票费用总额
                    'other_sum' => $data_6['other_sum'],//其他总额
                ];
                $data['overtime_money'] = '0';//加班总额
                $data['time'] = $v;//时间
                array_push($array_2, $data);
            }
            $data_10 = [
                'sum' => $array_20,
                'day' => $array_2,
            ];
            return json(new SuccessData($data_10));
        }
    }

    //客户群体分析--按门店
    public function customer()
    {
        $store_id = input('store_id');
        $start_time = input('start_time');
        $end_time = input('end_time');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        } else {
            $end_time = $end_time + 86399;
        }
        if (!empty($store_id)) {
            $where = 'store_id=' . $store_id . ' And carte_time <=' . $end_time . ' And carte_time >=' . $start_time;
            $theme_id = input('theme_id');
            if (!empty($theme_id)) {
                $where .= ' And theme_id=' . $theme_id;
            }
            $name = Db::table('store')->field('store_name')->where('store_id=' . $store_id)->find();
            $select = Db::table('reserve')->where($where)->select();
            $man_woman = Db::table('reserve')->where('degree!=0  And  ' . $where)->sum('man_woman');
            $scene = Db::table('reserve')->where('degree!=0  And ' . $where)->count('id');
            $data_2 = Statistics($select);
            if (empty($data_2['people'])) {
                $data_2['people'] = '1';
            }
            $array = array_count_values(array_column($select, 'source'));
            $array_2 = array_count_values(array_column($select, 'age'));
            $source = column($array, 'source');
            $age = column($array_2, 'education');
            $data_20 = [
                'store_name' => $name['store_name'],//门店名称
                'scene' => $scene,//门店总场次
                'people' => $data_2['people'],//门店总人数
                'meituan_people' => $data_2['meituan_people'],//门店美团预定总人数
                'voucher_people' => $data_2['voucher_people'],//门店大众/美团券总人数
                'payment' => $data_2['sum_people'],//门店现金及银联总人数
                'free' => $data_2['free'],//门店免单人数
                'source' => $source,//门店来源
                'age' => $age,//门店年龄段
                'man_woman' => round(($man_woman / $data_2['people']) * 100),//门店男女比例
            ];
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
            $array_20 = array();
            foreach ($array as $k => $v) {
                $time = strtotime($v);
                $time_end = $time + 86401;
                $where_2 = 'store_id=' . $store_id . ' And carte_time <=' . $time_end . ' And carte_time >=' . $time;
                if (!empty($theme_id)) {
                    $where_2 .= ' And theme_id=' . $theme_id;
                }
                $select = Db::table('reserve')->where($where_2)->select();
                $data_2 = Statistics($select);
                if (empty($data_2['people'])) {
                    $data_2['people'] = '1';
                }
                $data = [
                    'store_name' => $name['store_name'],//门店名称
                    'meituan_people' => $data_2['meituan_people'],//门店美团预定总人数
                    'voucher_people' => $data_2['voucher_people'],//门店大众/美团券总人数
                    'payment' => $data_2['sum_people'],//门店现金及银联总人数
                    'free' => $data_2['free'],//门店免单人数
                ];
                $data['time'] = $v;//时间
                array_push($array_20, $data);
            }

            $data_10 = [
                'sum' => $data_20,
                'day' => $array_20,
            ];
        } else {
            $theme_id = input('theme_id');
            $where = ' carte_time <=' . $end_time . ' And carte_time >=' . $start_time;
            if (!empty($theme_id)) {
                $where .= ' And theme_id=' . $theme_id;
            }
            $select = Db::table('reserve')->where($where)->select();

            $man_woman = Db::table('reserve')->where('degree!=0  And  ' . $where)->sum('man_woman');
            $scene = Db::table('reserve')->where('degree!=0 And ' . $where)->count('id');
            $data_2 = Statistics($select);
            $array = array_count_values(array_column($select, 'source'));
            $array_2 = array_count_values(array_column($select, 'age'));
            $source = column($array, 'source');
            $age = column($array_2, 'education');
            if (empty($data_2['people'])) {
                $data_2['people'] = '1';
            }
            $data_20 = [
                'scene' => $scene,//总场次
                'people' => $data_2['people'],//总人数
                'meituan_people' => $data_2['meituan_people'],//美团预定总人数
                'voucher_people' => $data_2['voucher_people'],//大众/美团券总人数
                'payment' => $data_2['sum_people'],//现金OR银联总人数
                'free' => $data_2['free'],//免单人数
                'source' => $source,//来源
                'age' => $age,//年龄段
                'man_woman' => round(($man_woman / $data_2['people']) * 100),//男女比例
            ];
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
            $array_15 = array();
            foreach ($array as $k => $v) {
                $time = strtotime($v);
                $time_end = $time + 86401;
                $where_2 = ' carte_time <=' . $time_end . ' And carte_time >=' . $time;
                if (!empty($theme_id)) {
                    $where_2 .= ' And theme_id=' . $theme_id;
                }
                $select = Db::table('reserve')->where($where_2)->select();
                $data_2 = Statistics($select);
                if (empty($data_2['people'])) {
                    $data_2['people'] = '1';
                }
                $data = [

                    'meituan_people' => $data_2['meituan_people'],//门店美团预定总人数
                    'voucher_people' => $data_2['voucher_people'],//门店大众/美团券总人数
                    'payment' => $data_2['sum_people'],//门店现金及银联总人数
                    'free' => $data_2['free'],//门店免单人数
                ];
                $data['time'] = $v;//时间
                array_push($array_15, $data);
            }
            $data_10 = [
                'sum' => $data_20,
                'day' => $array_15,
            ];
        }

        return json(new SuccessData($data_10));
    }

    //客户群体分析--按主题
    public function total_field()
    {
        $theme_id = input('theme_id');
        $store_id = input('store_id');
        $start_time = input('start_time');
        $end_time = input('end_time');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        }
        if (!empty($store_id)) {
            $name = Db::table('theme')->field('theme')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id)->find();
            $sum = Db::table('reserve')->field('people')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id)->sum('people');
            $total = Db::table('reserve')->field('id')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id)->count('id');
            $select = Db::table('reserve')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id)->select();
            $man_woman = Db::table('reserve')->field('man_woman')->where('degree!=0 And theme_id=' . $theme_id . ' And store_id=' . $store_id)->sum('man_woman');
            $array = array_count_values(array_column($select, 'source'));
            $array_2 = array_count_values(array_column($select, 'age'));
            $array_3 = column($array, 'source');
            $array_4 = column($array_2, 'education');
            if (empty($sum)) {
                $sum = '1';
            }
            $array_20 = [
                'theme_name' => $name['theme'],
                'total_field' => $total,//每个主题总场次
                'sum_people' => $sum,//每个主题总人数
                'source' => $array_3,//每个主题客户来源统计
                'age' => $array_4,//每个主题年龄段统计
                'man_woman' => round(($man_woman / $sum) * 100),//每个主题男女比例统计
            ];
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
            $array_15 = array();
            foreach ($array as $k => $v) {
                $time = strtotime($v);
                $time_end = $time + 86401;
                $name = Db::table('theme')->field('theme')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id)->find();
                $sum = Db::table('reserve')->field('people')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id . ' And carte_time <=' . $time_end . ' And carte_time >=' . $time)->sum('people');
//                echo '312';exit;
                $total = Db::table('reserve')->field('id')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id . ' And carte_time <=' . $time_end . ' And carte_time >=' . $time)->count('id');
                $select = Db::table('reserve')->where('theme_id=' . $theme_id . ' And store_id=' . $store_id . '  And carte_time <=' . $time_end . ' And carte_time >=' . $time)->select();
                $man_woman = Db::table('reserve')->field('man_woman')->where('degree!=0 And theme_id=' . $theme_id . ' And store_id=' . $store_id . ' And carte_time <=' . $time_end . ' And carte_time >=' . $time)->sum('man_woman');
                $array_10 = array_count_values(array_column($select, 'source'));
                $array_2 = array_count_values(array_column($select, 'age'));
                $array_3 = column($array_10, 'source');
                $array_4 = column($array_2, 'education');
                if (empty($sum)) {
                    $sum = '1';
                }
                $data = [
                    'theme_name' => $name['theme'],
                    'total_field' => $total,
                    'sum_people' => $sum,
                    'source' => $array_3,
                    'age' => $array_4,
                    'man_woman' => round(($man_woman / $sum) * 100),
                ];
                $data['time'] = $v;//时间
                array_push($array_15, $data);
            }
            $data_10 = [
                'sum' => $array_20,
                'day' => $array_15,
            ];
            return json(new SuccessData($data_10));
        } else {
            $name = Db::table('theme')->field('theme')->where('theme_id=' . $theme_id)->find();
            $sum = Db::table('reserve')->where('theme_id=' . $theme_id)->sum('people');
            $total = Db::table('reserve')->where('theme_id=' . $theme_id)->count('id');
            $select = Db::table('reserve')->where('theme_id=' . $theme_id)->select();
            $man_woman = Db::table('reserve')->where('degree!=0 And theme_id=' . $theme_id)->sum('man_woman');
            $array = array_count_values(array_column($select, 'source'));
            $array_2 = array_count_values(array_column($select, 'age'));
            $array_3 = column($array, 'source');
            $array_4 = column($array_2, 'education');
            if (empty($sum)) {
                $sum = '1';
            }
            $array_20 = [
                'theme_name' => $name['theme'],
                'total_field' => $total,
                'sum_people' => $sum,
                'source' => $array_3,
                'age' => $array_4,
                'man_woman' => round(($man_woman / $sum) * 100),
            ];
            $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
            $start_time = date($start_time);
            //获取本月第一天时间戳
            $array = array();
            for ($i = 0; $i < $count_days; $i++) {
                $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
            }
            $array_15 = array();
            foreach ($array as $k => $v) {
                $time = strtotime($v);
                $time_end = $time + 86401;

                $name = Db::table('theme')->field('theme')->where('theme_id=' . $theme_id)->find();
                $sum = Db::table('reserve')->field('people')->where('theme_id=' . $theme_id . ' And carte_time <=' . $time_end . ' And carte_time >=' . $time)->sum('people');
                $total = Db::table('reserve')->field('id')->where('theme_id=' . $theme_id . '  And  carte_time <=' . $time_end . ' And carte_time >=' . $time)->count('id');
                $select = Db::table('reserve')->where('theme_id=' . $theme_id . ' And  carte_time <=' . $time_end . ' And carte_time >=' . $time)->select();
                $man_woman = Db::table('reserve')->field('man_woman')->where('degree!=0 And theme_id=' . $theme_id . '  And  carte_time <=' . $time_end . ' And carte_time >=' . $time)->sum('man_woman');
                $array_10 = array_count_values(array_column($select, 'source'));
                $array_2 = array_count_values(array_column($select, 'age'));
                $array_3 = column($array_10, 'source');
                $array_4 = column($array_2, 'education');
                if (empty($sum)) {
                    $sum = '1';
                }
                $data = [
                    'theme_name' => $name['theme'],
                    'total_field' => $total,
                    'sum_people' => $sum,
                    'source' => $array_3,
                    'age' => $array_4,
                    'man_woman' => round(($man_woman / $sum) * 100),
                ];
                $data['time'] = $v;//时间
                array_push($array_15, $data);
            }
            $data_10 = [
                'sum' => $array_20,
                'day' => $array_15,
            ];
            return json(new SuccessData($data_10));
        }
    }

    //主题营业额
    public function theme_money()
    {
        $start_time = input('start_time');
        $end_time = input('end_time');
        $store_id = input('store_id');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        }
        if (!empty($store_id)) {
            $theme = Db::table('theme')->where('store_id=' . $store_id)->select();
            $array_2 = array();
            foreach ($theme as $k => $v) {
                $select_2 = Db::table('reserve')->where('theme_id=' . $v['theme_id'] . ' And store_id=' . $store_id . '  And carte_time <' . $end_time . ' And carte_time>' . $start_time)->select();
                $data_3 = Statistics($select_2);
                $data['theme'] = $v['theme'];
                $data['sum'] = $data_3['sum'];
                array_push($array_2, $data);
            }
        } else {
            $theme = Db::table('theme')->select();
            $array_2 = array();
            foreach ($theme as $k => $v) {
                $select_2 = Db::table('reserve')->where('theme_id=' . $v['theme_id'] . ' And store_id=' . $v['store_id'] . '  And carte_time <' . $end_time . ' And carte_time>' . $start_time)->select();
                $data_3 = Statistics($select_2);
                $data['theme'] = $v['theme'];
                $data['sum'] = $data_3['sum'];
                array_push($array_2, $data);
            }
        }

        return json(new SuccessData($array_2));
    }

    //门店营业额
    public function store_money()
    {
        $start_time = input('start_time');
        $end_time = input('end_time');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        }
        $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
        $start_time = date($start_time);
        //获取本月第一天时间戳
        $array = array();
        for ($i = 0; $i < $count_days; $i++) {
            $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
        }
        $store_id = input('store_id');
        if (!empty($store_id)) {
            $theme = Db::table('store')->where('store_id=' . $store_id)->select();
            $array_2 = array();
            foreach ($array as $k => $v) {
                foreach ($theme as $key => $value) {
                    $time = strtotime($v);
                    $time_end = $time + 86401;
                    $select_2 = Db::table('reserve')->where('store_id=' . $value['store_id'] . ' And store_id=' . $store_id . '  And carte_time <' . $time_end . ' And carte_time>' . $time)->select();
                    $data_3 = Statistics($select_2);
                    $data['store_name'] = $value['store_name'];
                    $data['time'] = $v;
                    $data['sum'] = $data_3['sum'];
                    array_push($array_2, $data);
                }
            }
        } else {
            $theme = Db::table('store')->select();
            $array_2 = array();
            foreach ($array as $k => $v) {
                foreach ($theme as $key => $value) {
                    $time = strtotime($v);
                    $time_end = $time + 86401;
                    $select_2 = Db::table('reserve')->where('store_id=' . $value['store_id'] . '  And carte_time <' . $time_end . ' And carte_time>' . $time)->select();
                    $data_3 = Statistics($select_2);
                    $data['store_name'] = $value['store_name'];
                    $data['time'] = $v;
                    $data['sum'] = $data_3['sum'];
                    array_push($array_2, $data);
                }
            }
        }

        return json(new SuccessData($array_2));
    }

    //加班总额
    public function over_time()
    {
        date_default_timezone_set('PRC');
        $start_time = input('start_time');
        $end_time = input('end_time');
        $store_id = input('store_id');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        }
        $count_days = ceil(($end_time - $start_time) / 60 / 60 / 24);
        $start_time = date($start_time);
        //获取本月第一天时间戳
        $array = array();
        for ($i = 0; $i < $count_days; $i++) {
            $array[] = date('Y-m-d', $start_time + $i * 86400); //每隔一天赋值给数组
        }
        $array_2 = array();
        $over_money = '0';
        $over_money_2 = '0';
        $date = date("Y-m-d", time());//当前月份
        $time = date('Y-m-01', strtotime($date));
        $time_end = date(strtotime("$time +1 month -1day"));
        $select_3 = Db::table('reserve')->where('overtime=1 And degree!=0' . ' And store_id=' . $store_id . '  And carte_time <=' . $time_end . ' And carte_time>=' . $time)->select();
        $select_4 = Db::table('reserve')->where('overtime=2 And degree!=0' . ' And store_id=' . $store_id . '  And carte_time <=' . $time_end . ' And carte_time>=' . $time)->select();
        $data['overtime_money'] = $over_money + $over_money_2;//加班总额
        array_push($array_2, $data);

        return json(new SuccessData($array_2));

    }

    //统计
    public function count()
    {
        $store_id = input('store_id');
        $start_time = input('start_time');
        $end_time = input('end_time');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('d', $start_time) + 1, 00);
        } else {
            $end_time = $end_time + 86399;
        }
        if (!empty($store_id)) {

            $select = Db::table('reserve')->where('store_id=' . $store_id . ' And carte_time <=' . $end_time . ' And carte_time >=' . $start_time)->select();
            $man_woman = Db::table('reserve')->where('degree!=0  And store_id=' . $store_id . ' And carte_time <=' . $end_time . ' And carte_time >=' . $start_time)->sum('man_woman');
            $array_10 = array_count_values(array_column($select, 'source'));
            $data_2 = Statistics($select);
            $array_2 = array_count_values(array_column($select, 'age'));
            $source = column($array_10, 'source');
            $age = column($array_2, 'education');

            if (empty($data_2['people'])) {
                $data_2['people'] = '1';
            }
            $data = [
                'source' => $source,//门店来源
                'age' => $age,//门店年龄段
                'man_woman' => round(($man_woman / $data_2['people']) * 100),//门店男女比例
            ];
        } else {
            $select = Db::table('reserve')->where('carte_time <=' . $end_time . ' And carte_time >=' . $start_time)->select();
            $man_woman = Db::table('reserve')->where('degree!=0' . ' And carte_time <=' . $end_time . ' And carte_time >=' . $start_time)->sum('man_woman');
            $data_2 = Statistics($select);
            $array = array_count_values(array_column($select, 'source'));
            $array_2 = array_count_values(array_column($select, 'age'));
            $source = column($array, 'source');
            $age = column($array_2, 'education');
            if (empty($data_2['people'])) {
                $data_2['people'] = '1';
            }
            $data = [
                'source' => $source,//来源
                'age' => $age,//年龄段
                'man_woman' => round(($man_woman / $data_2['people']) * 100),//男女比例
            ];
        }

        return json(new SuccessData($data));
    }
}