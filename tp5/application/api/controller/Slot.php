<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Slot  extends  BaseController
{
    //新增时间段
    public function slot_add()
    {
        $data = [
            'start_time' => input('start_time'),
            'end_time' => input('end_time'),
            'theme_id' => input('theme_id'),
            'type' => input('type'),
        ];
        $store_id =input('store_id');
        if(!empty($store_id)){$data['store_id']=$store_id;}
        $user_id =input('user_id');
        if(!empty($user_id)){$data['user_id']=$user_id;}
        Db::startTrans();
        try {
            Db::table('time_slot')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加时间段成功'));
    }

    //时间段修改
    public function slot_edit()
    {
        $id = input('id');
        $data = [
            'start_time' => input('start_time'),
            'end_time' => input('end_time'),
            'theme_id' => input('theme_id'),
            'type' => input('type'),
        ];
        $store_id =input('store_id');
        if(!empty($store_id)){$data['store_id']=$store_id;}
        $user_id =input('user_id');
        if(!empty($user_id)){$data['user_id']=$user_id;}
        Db::startTrans();
        try {
            Db::table('time_slot')->where('id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('修改时间段成功'));
    }

    //时间段删除
    public function slot_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('time_slot')->where('id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete = Db::table('time_slot')->where('id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }
    //列表
    public function slot_select()
    {
        $type = input('type');
        $theme_id = input('theme_id');
        $store_id =input('store_id');
        $where = '';
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $field = 'c.theme,a.id,a.start_time,a.end_time,a.type';
        if(!empty($store_id)){
            $where= 'c.theme_id=a.theme_id And d.store_id='.$store_id . ' And  c.store_id='.$store_id.'  And  a.store_id='.$store_id;
            $field ='d.store_name,c.theme,a.id,a.start_time,a.end_time,a.type';}
        if(!empty($type) && !empty($store_id)){
            $where= 'a.store_id='.$store_id.' And d.store_id=c.store_id And a.store_id=d.store_id And c.theme_id=a.theme_id And a.type='.$type;
        }elseif(!empty($type)&& empty($store_id)){
            $where= 'a.store_id=c.store_id And d.store_id=c.store_id And a.store_id=d.store_id And c.theme_id=a.theme_id And a.type='.$type;
        }elseif(empty($type)&& empty($store_id)){
            $field = 'c.theme,a.id,a.start_time,a.end_time,a.type,d.store_name';
            $where= 'a.store_id=c.store_id And d.store_id=c.store_id And a.store_id=d.store_id And c.theme_id=a.theme_id';
        }
        if(!empty($theme_id)){$where= 'a.store_id=c.store_id And d.store_id=c.store_id And a.store_id=d.store_id And c.theme_id=a.theme_id And a.theme_id='.$theme_id;}
        $name = Db::field($field)//截取表s的name列 和表a的全部
        ->table(['time_slot' => 'a', 'theme' => 'c','store'=>'d'])
            ->where($where)//查询条件语句
            ->order('id desc')
            ->paginate($rows, false, $data);
        return json(new SuccessData($name));
    }
    public function slot_detail()
    {
        $id  =input('id');
        $name = Db::field('c.theme,a.id,a.start_time,a.end_time,a.type,a.store_id,a.user_id,a.theme_id')//截取表s的name列 和表a的全部
        ->table(['time_slot' => 'a', 'theme' => 'c'])
            ->where('c.theme_id=a.theme_id And a.id='.$id)//查询条件语句
            ->find();
        return json(new SuccessData($name));
    }

}