<?php
namespace app\api\validate;

use think\Validate;
/**
 *
 */
class Hire extends Validate
{
	
	protected $rule = [
        'media_name'       =>  'require',
        'contacts'       =>  'require',
        'media_type_id'       =>  'require',
        'journal_price'       =>  'require',
        'number'       =>  'require',
        'province'       =>  'require',
        'city'       =>  'require',
        'area'       =>  'require',
        'address'       =>  'require',
        'is_monitor'       =>  'require',
       // 'is_select_content'       =>  'require',
        'field_properties_id'       =>  'require',
        'mobile'       =>  'require',
    ];

    protected $message  =   [
        'media_name.require'    => 'name不能为空',
        'contacts.require'    => '联系人不能为空',
        'media_type_id.require'    => 'media_type_id不能为空',
        'journal_price.require'    => 'journal_price不能为空',
        'number.require'    => '可售情况不能为空',
        'province.require'    => 'province不能为空',
        'city.require'    => 'city不能为空',
        'area.require'    => 'district不能为空',
        'address.require'    => 'address不能为空',
        'is_monitor.require'    => '是否有监控不能为空',
       // 'is_select_content.require'    => '是否显示选填内容不能为空',
        'field_properties_id.require'    => '场地属性不能为空',
        'mobile.require'    => '手机号不能为空',
    ];
}