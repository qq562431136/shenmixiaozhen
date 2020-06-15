<?php
namespace app\api\validate;

use think\Validate;
class Address extends Validate
{
    protected $rule = [
        ['name', 'require','联系人不能为空'],
        ['mobile', 'require','联系电话不能为空'],
        ['user_id', 'require', '用户ID不能为空'],
        ['province', 'require','省不能为空'],
        ['city', 'require','市不能为空'],
        ['district', 'require','区不能为空'],
        ['address', 'require','详细地址不能为空'],
    ];
    protected $scene = [
        'add' => [
            'name',
            'mobile',
            'user_id',
            'province',
            'city',
            'district',
            'address',
        ],
        'edit'=>[
            'name',
            'mobile',
            'user_id',
            'province',
            'city',
            'district',
            'default',
        ],
];
}