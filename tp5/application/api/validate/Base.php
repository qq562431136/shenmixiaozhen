<?php
namespace app\api\validate;

use think\Validate;
/**
 * 生成token参数验证器
 */
class Base extends Validate
{
	
	protected $rule = [
        'access_token'       =>  'require',
        'appid'        =>   'require',

    ];

    protected $message  =   [
        'access_token.require'    => 'access_token不能为空',
        'appid.require'    => 'appid不能为空',
    ];
}