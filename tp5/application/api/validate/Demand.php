<?php
namespace app\api\validate;

use think\Validate;
/**
 *
 */
class Demand extends Validate
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
        'media_specifications'       =>  'require',
        'mobile'       =>  'require',
        'light_mode'       =>  'require',
        'content'       =>  'require',
        'launch_time'       =>  'require',
        'end_time'       =>  'require',
    ];

    protected $message  =   [
        'media_name.require'    => '需求名称不能为空',
        'contacts.require'    => '联系人不能为空',
        'media_type_id.require'    => '媒体类型不能为空',
        'number.require'    => 'number不能为空',
        'province.require'    => 'province不能为空',
        'city.require'    => 'city不能为空',
        'area.require'    => 'district不能为空',
        'address.require'    => 'address不能为空',
        'journal_price.require'    => '刊例价不能为空',
        'media_specifications.require'    => '媒体规格不能为空',
        'mobile.require'    => 'mobile不能为空',
        'light_mode.require'    => 'light_mode不能为空',
        'content.require'    => 'content不能为空',
        'launch_time.require'    => '投放时间不能为空',
        'end_time.require'    => '结束时间不能为空',
    ];
}