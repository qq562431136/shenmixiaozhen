<?php
namespace app\api\validate;

use think\Validate;
/**
 * 生成token参数验证器
 */
class Login extends Validate
{
	
	protected $rule = [
        'mobile'      =>     'require',
        'password'=>     'require'
    ];

    protected $message  =   [
        'mobile.require'    => '账号不能为空',
        'password.require'    => '密码不能为空',
    ];
}