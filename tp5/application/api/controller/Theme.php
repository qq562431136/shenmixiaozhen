<?php
namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class Theme
{
    //新增主题
    public function theme_add()
    {
        $data = [
            'theme' => input('theme'),
            'store_id' => input('store_id'),
            'image_url' => input('image_url'),
        ];
        Db::startTrans();
        try {
            Db::table('theme')->insert($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加主题成功'));
    }

    //主题修改
    public function theme_edit()
    {
        $id = input('id');
        $data = [
            'theme' => input('theme'),
            'store_id' => input('store_id'),
            'image_url' => input('image_url'),
        ];
        Db::startTrans();
        try {
            Db::table('theme')->where('theme_id=' . $id)->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('修改主题成功'));
    }

    //主题删除
    public function theme_delete()
    {
        $id = input('id');
        if (strstr($id, ',')) {
            $data = explode(',', $id);
            foreach ($data as $a => $v) {
                Db::table('theme')->where('theme_id=' . $v)->delete();
            }
            return json(new SuccessData('删除成功'));
        } else {
            $delete = Db::table('theme')->where('theme_id=' . $id)->delete();
            if ($delete == '1') {
                return json(new SuccessData('删除成功'));
            } else {
                return json(new ErrorException('删除失败'));
            }
        }
    }

    //列表
    public function theme_select()
    {
        $theme = input('theme');
        $store_id = input('store_id');
        $where = 'a.store_id = b.store_id';
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $field = 'a.theme,a.theme_id,a.store_id,store_name';
        if (!empty($theme)) {
            $where .= " And a.theme like '%".$theme . "%'";
        }
        if (!empty($store_id)) {
            $where .= " And  a.store_id =".$store_id;
        }

        $name = Db::field($field)//截取表s的name列 和表a的全部
        ->table(['theme'=>'a' , 'store'=>'b'])
            ->where($where)//查询条件语句
            ->paginate($rows, false, $data);
        return json(new SuccessData($name));
    }

    public function theme_detail()
    {
        $id = input('id');
        $name = Db::table('theme')->where('theme_id=' . $id)->find();
        return json(new SuccessData($name));
    }

}