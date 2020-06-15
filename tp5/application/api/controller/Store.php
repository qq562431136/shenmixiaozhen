<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Store
{
    //新增主题
    public function store_add()
    {
        $data = [
            'store_name' => input('store_name'),
            'image_url' => input('image_url'),
        ];
        Db::startTrans();
        try {
            Db::table('store')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加门店成功'));
    }

    //主题修改
    public function store_edit()
    {
        $id = input('store_id');
        $data = [
            'store_name' => input('store_name'),
            'image_url' => input('image_url'),
        ];
        Db::startTrans();
        try {
            Db::table('store')->where('store_id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('修改门店成功'));
    }

    //主题删除
    public function store_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('store')->where('store_id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete = Db::table('store')->where('store_id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }

    //列表
    public function store_select()
    {
        $store_id = input('store_id');
        $store_name =input('store_name');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $where = '1';
        if(!empty($store_id)){
            $where .= " And store_id=".$store_id;
        }
        if(!empty($store_name)){
            $where .= " And store_name like '%".$store_name . "%'";
        }
        if($where=='1'){
            $list = Db::table('store')->paginate($rows, false, $data);
        }else{
            $list = Db::table('store')->where($where)->paginate($rows, false, $data);
        }

        return json(new SuccessData($list));
    }
    public function store_detail()
    {
        $store_id = input('store_id');
        $name = Db::table('store')->where('store_id=' . $store_id)->find();
        return json(new SuccessData($name));
    }
}