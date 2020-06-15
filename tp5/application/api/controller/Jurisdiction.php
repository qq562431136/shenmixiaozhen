<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Jurisdiction extends  BaseController
{
    //权限角色新增
    public function jurisdiction_add()
    {
        $data = [
            'power_name'=>input('power_name'),
            'power_ids'=>input('power_ids'),
            'user_id'=>input('user_id'),
            'store_id'=>input('store_id'),
        ];
        Db::startTrans();
        try {
            Db::table('power')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加角色成功'));
    }
    //权限角色修改
    public function jurisdiction_edit()
    {
        $id = input('id');
        $data = [
            'power_name'=>input('power_name'),
            'power_ids'=>input('power_ids'),
            'user_id'=>input('user_id'),
            'store_id'=>input('store_id'),
        ];
        Db::startTrans();
        try {
            Db::table('power')->where('id='.$id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('修改角色成功'));
    }
    //权限角色删除
    public function jurisdiction_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('power')->where('id='.$v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete =   Db::table('power')->where('id='.$id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }
    //权限列表
    public function jurisdiction_select()
    {
//        echo '321';exit;
        $type = input('type');
        $user_id = input('user_id');
        $store_id = input('store_id');
        $where = [];
        if (!empty($store_id)) {
            $where['store_id']  = ['=',$store_id];
        }
        if (!empty($user_id)) {
            $where['user_id']  = ['=',$user_id];
        }
//        var_dump($where);exit;
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $authority = input('authority');
        if(!empty($authority)){
            $where['power_name']  = ['like','%'.$authority.'%'];
        }
        if (!empty($type)) {
            $power = Db::table('power')->where($where)->select();
        } else {
            $power = Db::table('power')->where($where)->paginate($rows, false, $data);
        }
        return json(new SuccessData($power));
    }
    public function jurisdiction_detail()
    {
        $id = input('id');
        $user= Db::table('power')->where('id='.$id)->find();
        return json(new SuccessData($user));
    }
}