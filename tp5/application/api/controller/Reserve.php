<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use app\lib\exception\SuccessMessage;
use think\Db;
use app\admin\controller\User;

class Reserve extends BaseController
{
    //添加预约
    public function reserve_add()
    {

        $user_id = input('user_id');
        $name = Db::table('user')->where('id=' . $user_id)->find();
        $job_mobile = input('job_mobile');
        $type = input('type');
        $data = [
            'user_id' => input('user_id'),
            'type' => $type,
            'time_start' => input('time_start'),
            'time_end' => input('time_end'),
            'job_name' => input('job_name'),
            'job_mobile' => $job_mobile,
            'store_id' => $name['store_id'],
            'people' => input('people'),
            'theme_id' => intval(input('theme_id')),
            'carte_time' => time(),
            'overtime' => intval(input('overtime')),
            'time_id' => intval(input('time_id')),
            'man_woman' => intval(input('man_woman')),
        ];
        $reserve_add = validate('Reserve');
        if (!$reserve_add->scene('add')->check($data)) {
            return json(new SuccessMessage($reserve_add->getError(), 401));
        }
        Db::startTrans();
        try {
            $id = Db::table('reserve')->insert($data);
            if ($id == '1') {
                $code = rand(100000, 999999);
                $tel = $job_mobile;
//                    send_sms_code($tel,$code);
            }

            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('预约成功'));
    }

    //修改预约
    public function reserve_edit()
    {

        $id = input('id');
        $man_woman = input('man_woman');
        $people = input('people');
        $degree = input('degree');
        $pay_type_1 = input('pay_type_1');
        $money_1 = input('money_1');
        $people_1 = input('people_1');
        $start_time = input('start_time');
        $money = input('money');
        $type = input('pay_type');
        $type_2 = input('type');
        $is_integral = input('is_integral');
        $job_mobile = input('job_mobile');
        $source = input('source');
        $age = input('age');
        if (empty($type)) {
            $type = '1';
        }
        if (empty($is_integral)) {
            $is_integral = '0';
        }
        if (empty($start_time)) {
            $start_time = intval($start_time);
        }
        if (empty($source)) {
            $source ='1';
        }
        if (empty($age)) {
            $age ='1';
        }
        $data = [
            'user_id' => input('user_id'),
            'job_name' => input('job_name'),
            'job_mobile' => $job_mobile,
            'people' => intval($people),
            'people_1' => $people_1,
            'pay_type_1' => intval($pay_type_1),
            'money_1' => intval($money_1),
            'source' =>$source,
            'age' => $age,
            'man_woman' => intval($man_woman),
            'pay_type' => intval($type),
            'money' => $money,
            'birthday_people' => intval(input('birthday_people')),
            'free' => intval(input('free')),
            'other' => intval(input('other')),
            'other_money' => input('other_money'),
            'amount' => input('amount'),
            'express' => input('express'),
            'start_time' => $start_time,
            'help_second' => intval(input('help_second')),
            'degree' => intval($degree),
            'type' => $type_2,
            'is_integral' => $is_integral,
            'update_time' => time(),
        ];
        $workno = input('workno');
        $rese = Db::table('reserve')->where('id=' . $id)->find();
        if (!empty($workno) && $workno!='null') {
            $data['workno'] = $workno;
            $work_data = explode(',', $workno);
            foreach ($work_data as $k => $v) {
                $user = Db::table('wages')->where('staff_id=' . $v)->find();
                if (empty($user)) {
                    return json(new ErrorException('员工ID' . $v . '不存在'));
                }
                $data_2 = [
                    'workno' => $workno,
                    'carte_time' => time(),
                    'overtime' => $rese['overtime'],
                    'staff_id' => $v,
                    'theme_id' => $rese['theme_id'],
                ];
                Db::table('over_records')->insert($data_2);
            }
        }
        if (!empty($start_time)) {
            $data['end_time'] = intval($start_time) + 3600;
        }
        if($is_integral=='1'){
            $SmsController = new User();
            $store = Db::table('store')->where('store_id='.$rese['store_id'])->find();
            $SmsController ->integral($is_integral,$degree,$job_mobile,$money,$store['store_name']);
        }
        $reserve_add = validate('Reserve');
        if (!$reserve_add->scene('edit')->check($data)) {
            return json(new SuccessMessage($reserve_add->getError(), 401));
        }
        Db::startTrans();
        try {
            Db::table('reserve')->where('id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('修改成功'));
    }

    //删除预约
    public function reserve_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('reserve')->where('id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete = Db::table('reserve')->where('id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }

    //预约详情
    public function reserve_detail()
    {
        $id = input('id');
        $time = strtotime(date('Ymd')) + 86399;
        $start_time = strtotime(date('Ymd'));
        $select = Db::table('reserve')->where('id=' . $id . '  And carte_time <' . $time . ' And carte_time>' . $start_time)->find();
        return json(new SuccessData($select));
    }

    //每日营业额
    public function money_day()
    {
        $time = strtotime(date('Ymd')) + 86399;
        $start_time = strtotime(date('Ymd'));
        $store_id = input('store_id');
        if (!empty($store_id)) {
            $select = Db::table('reserve')->where('store_id=' . $store_id . ' And carte_time <' . $time . ' And carte_time>' . $start_time)->select();
            $data_2 = Statistics($select);
            $data = [
                'store_name' => $data_2['store_name'],//门店名称
                'sum' => $data_2['sum'],//门店总营业额
                'cash' => $data_2['cash'],//现金总额
                'unionpay' => $data_2['unionpay'],//银联总额
                'meituan_money' => $data_2['meituan_money'],//美团预定总额
                'voucher_money' => $data_2['voucher_money'],//大众/美团券总额
                'amount_money' => $data_2['amount_money'],//开票费用总额
                'other_sum' => $data_2['other_sum'],//其他总额
            ];
        } else {
            $select = Db::table('reserve')->where('carte_time <' . $time . ' And carte_time>' . $start_time)->select();
            $data_2 = Statistics($select);
            $data = [
//                'store_name' => $data_2['store_name'],//门店名称
                'sum' => $data_2['sum'],//门店总营业额
                'cash' => $data_2['cash'],//现金总额
                'unionpay' => $data_2['unionpay'],//银联总额
                'meituan_money' => $data_2['meituan_money'],//美团预定总额
                'voucher_money' => $data_2['voucher_money'],//大众/美团券总额
                'amount_money' => $data_2['amount_money'],//开票费用总额
                'other_sum' => $data_2['other_sum'],//其他总额
            ];
        }

        return json(new SuccessData($data));
    }
}
