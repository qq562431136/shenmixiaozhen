<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Maintenance extends BaseController
{
    //支出数据添加--密室维护费用
    public function maintenance_add()
    {
        $data = [
            'program' => input('program'),
            'artificial' => input('artificial'),
            'type_remarks' => input('type_remarks'),
            'remarks' => input('remarks'),
            'carte_time'=>time(),
            'store_id' => input('store_id'),
            'add_time' => strtotime(date(input('add_time'))),
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
        return json(new SuccessData('采购支出填写成功'));
    }

    //支出数据修改--密室维护费用
    public function maintenance_edit()
    {
        $id = input('id');
        $data = [
            'program' => input('program'),
            'add_time' => strtotime(date(input('add_time'))),
            'artificial' => input('artificial'),
            'type_remarks' => input('type_remarks'),
            'remarks' => input('remarks'),
            'store_id' => input('store_id'),
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
        return json(new SuccessData('采购支出修改成功'));
    }

    //支出数据删除--密室维护费用
    public function maintenance_delete()
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

    //支出数据列表--密室维护费用
    public function maintenance_select()
    {

        $end_time = input('end_time');
        $start_time = input('start_time');
        $store_id = input('store_id');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $where = 'a.type_remarks=4 And c.store_id=a.store_id';
        if (!empty($store_id)) {
            $where .= ' And a.store_id=' . $store_id;
            if (!empty($start_time)) {
                $where = 'a.store_id=' . $store_id . '  And a.type_remarks=4 And c.store_id=a.store_id And a.add_time<=' . $end_time . ' And  a.add_time>=' . $start_time;
            }
        } else {
            if (!empty($start_time)) {
                $where = 'a.type_remarks=4 And c.store_id=a.store_id And  a.add_time<=' . $end_time . ' And  a.add_time>=' . $start_time;
            }
        }
        $money = Db::field('a.id,a.add_time,c.store_name,a.program,a.artificial,a.remarks,a.type_remarks,a.store_id,a.carte_time')//截取表s的name列 和表a的全部
        ->table(['expenditure' => 'a', 'store' => 'c'])
            ->where($where)//查询条件语句
            ->order( 'id desc')
            ->paginate($rows, false, $data);
        return json(new SuccessData($money));
    }
    //支出数据详情--营业费用
    public function maintenance_detail()
    {
        $id = input('id');
        $user= Db::table('expenditure')->field('store_id,carte_time,id,add_time,program,artificial,remarks,type_remarks')->where('id='.$id)->find();
        return json(new SuccessData($user));
    }
}