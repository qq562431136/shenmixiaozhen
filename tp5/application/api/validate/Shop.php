<?php
namespace app\api\validate;

use think\Validate;

class Shop extends Validate
{
    protected $rule = [
        ['shop_name', 'require', '商品名称不能为空'],
        ['shop_price', 'require', '商品价格不能为空'],
        ['shop_mktprice', 'require', '商品原价不能为空'],
        ['shop_popular', 'require', '是否热门不能为空'],
        ['shop_brief', 'require', '商品简介不能为空'],
        ['storejoin_id', 'require', '商家ID不能为空'],
        ['shop_stock', 'require', '库存不能为空'],
        ['unit', 'require', '商品单位不能为空'],
    ];
    protected $scene = [
        'edit' => [
            'shop_name',
            'shop_price',
            'shop_mktprice',
            'shop_popular',
            'shop_brief',
            'storejoin_id',
            'unit',
        ],
        'add' => [
            'shop_name',
            'shop_price',
            'shop_mktprice',
            'shop_popular',
            'shop_brief',
            'storejoin_id',
            'unit',
        ]
    ];
}