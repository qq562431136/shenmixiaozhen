<?php
namespace app\api\validate;

use think\Validate;

class Store extends Validate
{
    protected $rule = [
        ['user_id', 'require', '用户ID不能为空'],
        ['member_name', 'require', '用户名不能为空'],
        ['store_name', 'require', '店铺名称不能为空'],
        ['area_info', 'require', '所在地区不能为空'],
        ['store_addtime', 'require', '店铺开启时间不能为空'],
        ['store_endtime', 'require', '店铺结束时间不能为空'],
        ['store_logo', 'require', '店铺LOGO不能为空'],
        ['store_keywords', 'require', '店铺SEO关键字不能为空'],
        ['store_qq', 'require', '店铺QQ不能为空'],
        ['store_ww', 'require', '店铺微信不能为空'],
    ];
    protected $scene = [
        'edit' => [
            'store_name',
            'store_logo',
        ],
        'add' => [
            'user_id',
            'member_name',
            'store_name',
            'area_info',
            'store_addtime',
            'store_endtime',
            'store_logo',
            'store_keywords',
            'store_qq',
            'store_ww',
       ],
    ];
}