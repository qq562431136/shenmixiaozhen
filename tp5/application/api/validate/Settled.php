<?php
namespace app\api\validate;

use think\Validate;

class Settled extends Validate
{
    protected $rule = [
        ['user_id', 'require', '用户ID不能为空'],
        ['member_name', 'require', '用户名不能为空'],
        ['store_type', 'require', '店铺类型不能为空'],
        ['company_name', 'require', '公司名称不能为空'],
        ['company_address_detail', 'require', '公司详细地址不能为空'],
        ['company_registered_capital', 'require', '注册资金不能为空'],
        ['contacts_name', 'require', '联系人不能为空'],
        ['contacts_phone', 'require', '联系人电话不能为空'],
        ['contacts_email', 'require', '联系人邮箱不能为空'],
        ['business_licence_number_electronic', 'require', '营业执照电子版不能为空'],
        ['settlement_bank_account_name', 'require', '银行开户名不能为空'],
        ['settlement_bank_account_number', 'require', '公司银行账号不能为空'],
        ['settlement_bank_name', 'require', '开户银行支行名称不能为空'],
        ['settlement_bank_address', 'require', '开户行所在地不能为空'],
        ['store_name', 'require', '店铺名称不能为空'],
        ['joinin_year', 'require', '开店时长(年)不能为空'],
        ['store_entered_type', 'require', '请选择保证金or抽成方式'],
    ];
    protected $scene = [
        'edit' => [
            'business_name',
            'business_mobile',
            'business_email',
            'business_area',
            'business_address',
            'business_account',
            'business_authority',
            'business_status',
            'business_catalog'
        ],
        'add' => [
            'user_id',
            'member_name',
            'store_type',
            'company_name',
            'company_registered_capital',
            'company_address_detail',
            'contacts_name',
            'contacts_phone',
            'contacts_email',
            'business_licence_number_electronic',
            'settlement_bank_account_name',
            'settlement_bank_account_number',
            'settlement_bank_name',
            'settlement_bank_address',
            'joinin_year',
            'store_name',],
    ];
}