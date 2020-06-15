<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Marketing extends  BaseController
{
    //支出数据添加--营销费用
    public function marketing_add()
    {

        $data = [
            'marketing_upper' => input('marketing_upper'),
            'marketing_lower' => input('marketing_lower'),
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
        return json(new SuccessData('营销支出填写成功'));
    }

    //支出数据修改--营销费用
    public function marketing_edit()
    {

        $id = input('id');
        $data = [
            'marketing_upper' => input('marketing_upper'),
            'marketing_lower' => input('marketing_lower'),
            'type_remarks' => input('type_remarks'),
            'remarks' => input('remarks'),
            'add_time' => strtotime(date(input('add_time'))),
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
        return json(new SuccessData('营销支出修改成功'));
    }

    //支出数据删除--营销费用
    public function marketing_delete()
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

    //支出数据列表--营销费用
    public function marketing_select()
    {
        $end_time = input('end_time');
        $start_time = input('start_time');
        $store_id = input('store_id');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $where = 'a.type_remarks=2 And c.store_id=a.store_id ';
        if (!empty($store_id)) {
            $where .= ' And a.store_id=' . $store_id;
            if (!empty($start_time)) {
                $where = 'a.store_id='.$store_id .' And a.type_remarks=2 And c.store_id=a.store_id And  a.add_time<=' . $end_time . ' And  a.add_time>=' . $start_time;
            }
        } else {
            if (!empty($start_time)) {
                $where = 'a.type_remarks=2 And c.store_id=a.store_id And  a.add_time<=' . $end_time . ' And  a.add_time>=' . $start_time;
            }
        }
        $money = Db::field('a.id,a.add_time,c.store_name,a.marketing_upper,a.marketing_lower,a.remarks,a.type_remarks,a.store_id,a.carte_time')//截取表s的name列 和表a的全部
        ->table(['expenditure' => 'a', 'store' => 'c'])
            ->where($where)//查询条件语句
            ->order( 'id desc')
            ->paginate($rows, false, $data);

        return json(new SuccessData($money));
    }

    //支出数据详情--营业费用
    public function marketing_detail()
    {

        $id = input('id');
        $user = Db::table('expenditure')->field('store_id,carte_time,id,add_time,marketing_upper,marketing_lower,remarks,type_remarks')->where('id=' . $id)->find();
        return json(new SuccessData($user));
    }
}