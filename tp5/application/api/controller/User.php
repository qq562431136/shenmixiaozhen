<?php

namespace app\api\controller;

use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class User extends BaseController
{
    //客户游戏数据--列表
    public function investigation_select()
    {
        $db2=Db::connect('db2');
        $mobile = input('mobile');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $where ='';
        if(!empty($mobile)){
            $where .= " mobile like '%" . $mobile . "%'";
        }
        $list = $db2->name('ims_mc_members')->where($where)->paginate($rows, false, $data);;
        return json(new SuccessData($list));
    }
    //客户游戏数据--详情
    public function investigation_detail()
    {
        $mobile = input('mobile');
        $store_id = input('store_id');
        $theme_id = input('theme_id');
        $page = input('page');
        $data = [
            'page' => $page
        ];
        $rows = input('rows');
        $where = ' a.theme_id=b.theme_id And c.store_id=a.store_id  And b.store_id=a.store_id';
        if (!empty($mobile)) {
            $where .= " And a.job_mobile like '%" . $mobile . "%'";
        }
        if (!empty($theme_id)) {
            $where .= '  And a.theme_id=' . $theme_id;
        }
        if (!empty($store_id)) {
            $where .= '  And a.store_id=' . $store_id;
        }
        $list = Db::field('a.id,c.store_name,b.theme,a.people,a.money,a.carte_time,a.other_money,a.start_time,a.end_time,a.help_second,a.degree,a.job_mobile')//截取表s的name列 和表a的全部
        ->table(['reserve' => 'a', 'theme' => 'b', 'store' => 'c'])
            ->where($where)//查询条件语
            ->order('a.id desc')
            ->paginate($rows, false, $data);
        return json(new SuccessData($list));
    }
    //客户积分数据
    public function name_integral()
    {
        $data = [
            'name' => input('name'),
            'mobile' => input('mobile'),
            'integral' => input('integral'),
            'experience' => input('experience'),
        ];
        Db::startTrans();
        try {
            Db::table('customer')->insert($data);
            // 提交事务
            Db::commit();
        }catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        return json(new SuccessData('添加客户数据成功'));
    }


}