<?php
namespace app\api\validate;

use think\Validate;
/**
 * 生成token参数验证器
 */
class Verb extends Validate
{
	
	protected $rule = [
        'verd_name'       =>  'require',
        'address'        =>   'require',

    ];

    protected $message  =   [
        'verd_name.require'    => '举报名称不能为空',
        'address.require'    => '详细地址不能为空',
    ];
}