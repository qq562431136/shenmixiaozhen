<?php
namespace app\api\validate;

use think\Validate;
/**
 * 生成token参数验证器
 */
class AdExamine extends Validate
{
	
	protected $rule = [
        'company'       =>  'require',
        'company_address'        =>   'require',
        'legal_person'        =>   'require',
        'tel'        =>   'require',
        'mobile'        =>   'require',
        'email'        =>   'require',
        'contacts'        =>   'require',
        'fax'        =>   'require',
        'advertiser_production_company'        =>   'require',
        'advertiser_company'        =>   'require',
        'ad_class_id'        =>   'require',
        'ad_from_id'        =>   'require',
        'place'        =>   'require',
        'number'        =>   'require',
        'specifications'        =>   'require',
        'content'        =>   'require',
        'age_limit'        =>   'require',
    ];

    protected $message  =   [
        'company.require'    => '申请单位不能为空',
        'company_address.require'    => '单位地址不能为空',
        'legal_person.require'    => '法人不能为空',
        'tel.require'    => '电话不能为空',
        'mobile.require'    => '手机号码不能为空',
        'email.require'    => '邮箱不能为空',
        'contacts.require'    => '联系人不能为空',
        'fax.require'    => '传真不能为空',
        'advertiser_production_company.require'    => '广告制作单位不能为空',
        'advertiser_company.require'    => '广告客户单位不能为空',
        'ad_class_id.require'    => '广告种类不能为空',
        'ad_from_id.require'    => '广告形式不能为空',
        'age_limit.require'    => '年限不能为空',
        'number.require'    => '数量不能为空',
        'specifications.require'    => '规格不能为空',
        'content.require'    => '发布内容不能为空',
        'age_limit.require'    => '年限不能为空',
        'place.require'    => '地点不能为空',
    ];
}