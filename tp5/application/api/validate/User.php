<?php
namespace app\api\validate;

use think\Validate;
/**
 * 生成token参数验证器
 */
class User extends Validate
{
	
	protected $rule = [
        'user_id'           =>  'require',
        'company_name'      =>     'require',
        'legal_person'      =>     'require',
        'identity_card'      =>     'require',
        'business_licence'      =>     'require',
    ];

    protected $message  =   [
        'user_id.require'    => '用户id不能为空',
        'company_name.require'    => '公司名称不能为空',
        'legal_person.require'    => '法人名称不能为空',
        'identity_card.require'    => '身份证不能为空',
        'business_licence.require'    => '营业执照不能为空',
    ];
}