<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Goods extends  BaseController
{
    //物品预设
    public function good_add()
    {
        $data = [
            'goods_name' => input('goods_name'),
            'type' => input('type'),
            'carte_time' => time(),
        ];
        Db::startTrans();
        try {
            Db::table('goods')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('新增成功'));
    }
    public function good_edit()
    {
        $id = input('id');
        $data = [
            'goods_name' => input('goods_name'),
            'type' => input('type'),
        ];
        Db::startTrans();
        try {
            Db::table('goods')->where('id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('修改成功'));
    }
    public function good_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('goods')->where('id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete = Db::table('goods')->where('id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }
    public function good_detail()
    {
        $id = input('id');
        $name =Db::table('goods')->where('id='.$id)->find();
        return json(new SuccessData($name));
    }
    public function good_select()
    {
        $name = input('name');
        $where ='';
        if(!empty($name)){
            $where['goods_name']=array('like','%'.$name.'%');
        }
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $type=  input('type');
        if(!empty($type)){
            $where['type']=array('=',$type);
        }
        $status = input('status');
        if(!empty($status)){
            $select =Db::table('goods')->where($where)->select();
        }else{
            $select =Db::table('goods')->where($where)->paginate($rows, false, $data);
        }

        return json(new SuccessData($select));
    }

}