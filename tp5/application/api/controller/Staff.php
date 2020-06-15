<?php
/**
 * Created by PhpStorm.
 * User: Svtar
 * Date: 2019/11/25
 * Time: 9:43
 */
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Staff extends BaseController
{
    //员工添加
    public function staff_add()
    {
        $data = [
            'staff_name' => input('staff_name'),
            'mobile' => input('mobile'),
            'password' => getMd5Password(input('password')),
            'position' => input('position'),
            'staff_years' => input('staff_years'),
            'staff_place' => input('staff_place'),
            'store_id' => input('store_id'),
            'power' => input('power'),
            'is_login' => input('is_login'),
            'number' => input('number'),
            'carte_time' => time(),
//            'staff_name'=>input('staff_name'),
        ];

        Db::startTrans();
        try {
            Db::table('user')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加员工成功'));
    }

    //修改员工
    public function staff_edit()
    {
        $data = [
            'staff_name' => input('staff_name'),
            'mobile' => input('mobile'),
            'position' => input('position'),
            'staff_years' => input('staff_years'),
            'staff_place' => input('staff_place'),
            'store_id' => input('store_id'),
            'power' => input('power'),
            'is_login' => input('is_login'),
            'number' => input('number'),
        ];
        $id = input('id');
        $password = input('password');
        if (!empty($password)) {
            $data['password'] = getMd5Password($password);
        } else {

        }
        Db::startTrans();
        try {
            Db::table('user')->where('id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('修改员工数据成功'));
    }

    //员工删除
    public function staff_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('user')->where('id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete = Db::table('user')->where('id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }

    //员工详情
    public function staff_detail()
    {
        $id = input('id');
        $user = Db::table('user')->where('id=' . $id)->find();
        return json(new SuccessData($user));
    }

    //员工列表
    public function staff_select()
    {
        $type = input('type');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $mobile = input('mobile');
        $where = 'a.store_id=c.store_id';
        if (!empty($mobile)) {
            $where .= " And a.mobile like '%" . $mobile . "%'";
        }
        if (!empty($type)) {
            $name = Db::field('*')//截取表s的name列 和表a的全部
            ->table(['user' => 'a', 'store' => 'c'])
                ->where($where)//查询条件语句
                ->order('carte_time desc')
                ->select();
        } else {
            $name = Db::field('c.store_name,a.*')//截取表s的name列 和表a的全部
            ->table(['user' => 'a', 'store' => 'c'])
                ->where($where)//查询条件语句
                ->order('carte_time desc')
                ->paginate($rows, false, $data);
        }
        return json(new SuccessData($name));
    }

    //新增员工工资条
    public function wages_add()
    {
        $data = [
            'wages' => input('wages'),
            'user_id' => input('user_id'),
            'staff_id' => input('staff_id'),
            'security' => input('security'),
            'insurance' => input('insurance'),
            'accumulation' => input('accumulation'),
            'bonus' => input('bonus'),
            'remarks' => input('remarks'),
            'room' => input('room'),
            'traffic' => input('traffic'),
            'store_id' => input('store_id'),
            'carte_time' => time(),
            'add_time' => strtotime(date(input('add_time'))),
        ];
        Db::startTrans();
        try {
            Db::table('wages')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加工资条成功'));
    }

    //修改员工工资条
    public function wages_edit()
    {

        $id = input('id');
        $data = [
            'wages' => input('wages'),
            'user_id' => input('user_id'),
            'staff_id' => input('staff_id'),
            'security' => input('security'),
            'insurance' => input('insurance'),
            'accumulation' => input('accumulation'),
            'bonus' => input('bonus'),
            'remarks' => input('remarks'),
            'room' => input('room'),
            'traffic' => input('traffic'),
            'store_id' => input('store_id'),
            'carte_time' => time(),
            'add_time' => strtotime(date(input('add_time'))),
        ];
        Db::startTrans();
        try {
            Db::table('wages')->where('id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加工资条成功'));
    }

    //删除员工工资条
    public function wages_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('wages')->where('id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete = Db::table('wages')->where('id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }

    //工资条查询
    public function wages_select()
    {
        $store_id = input('store_id');
        $start_time = input('start_time');
        $end_time = input('end_time');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        if (empty($start_time) && empty($end_time)) {
            $start_time = strtotime(date('Y-m-01', strtotime(date("Y-m-d"))));
            $end_time = mktime(23, 59, 59, date('m', $start_time) + 1, 00);
        }
        $where = 'c.id=a.staff_id And a.carte_time<' . $end_time . ' And a.carte_time>' . $start_time;
        $time = input('add_time');
        $add_time = strtotime(date($time));
        $end_add_time = mktime(23, 59, 59, date('m', $add_time) + 1, 00);
        if (!empty($store_id)) {
            $where .= ' And a.store_id=' . $store_id;
            $staff_id = input('staff_id');
            if (!empty($staff_id)) {
                $where .= ' And a.staff_id=' . $staff_id;
            }
            if (!empty($add_time)) {
                $where = 'a.store_id=' . $store_id . '  And  c.id=a.staff_id  And  a.add_time<' . $end_add_time . ' And  a.add_time>' . $add_time;
            }
            if (!empty($add_time) && !empty($staff_id)) {
                $where = 'a.store_id=' . $store_id . '  And  c.id=a.staff_id  And  a.add_time<' . $end_add_time . ' And  a.add_time>' . $add_time . ' And a.staff_id=' . $staff_id;
            }
        } else {
            $staff_id = input('staff_id');
            if (!empty($staff_id)) {
                $where .= ' And a.staff_id=' . $staff_id;
            }
            $add_time = strtotime(date(input('add_time')));
            if (!empty($add_time)) {
                $where = 'c.id=a.staff_id  And  a.add_time<' . $end_add_time . ' And  a.add_time>' . $add_time;
            }
            if (!empty($add_time) && !empty($staff_id)) {
                $where = 'c.id=a.staff_id  And  a.add_time<' . $end_add_time . ' And  a.add_time>' . $add_time . ' And a.staff_id=' . $staff_id;
            }
        }
//        var_dump($where);exit;
        $money = Db::field('c.staff_name,a.*')//截取表s的name列 和表a的全部
        ->table(['wages' => 'a', 'user' => 'c'])
            ->where($where)//查询条件语句
            ->paginate($rows, false, $data);
        return json(new SuccessData($money));
    }

    //工资条详情接口
    public function wages_detail()
    {
        $id = input('id');
        $detail = Db::table('wages')->where('id=' . $id)->find();
        return json(new SuccessData($detail));
    }


}