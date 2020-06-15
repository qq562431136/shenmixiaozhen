<?php
namespace app\api\validate;

use think\Validate;

class Order extends Validate
{
    protected $rule = [
        ['user_id', 'require', '用户ID不能为空'],
        ['goods_amount', 'require', '商品总价不能为空'],
        ['cost_freight', 'require', '配送费用不能为空'],
        ['seller_id', 'require', '店铺ID不能为空'],
        ['ship_id', 'require', '地址ID不能为空'],

    ];
    protected $scene = [
        'edit' => [
            'user_id',
            'goods_amount',
            'cost_freight',
            'seller_id',
            'ship_address',
            'ship_name',
            'ship_mobile',
        ],
        'add' => [
            'user_id',
            'goods_amount',
            'cost_freight',
            'seller_id',
            'ship_id',],
    ];
}