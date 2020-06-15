<?php
namespace app\api\validate;
use think\Validate;
/**
 * 生成token参数验证器
 */
class AddStaff extends Validate
{
    protected $rule = [
        ['employee_name', 'require', '员工名称不能为空'],
        ['employee_mobile', 'require','手机号码不能为空'],
        ['employee_password', 'require','密码不能为空'],
        ['employee_authority', 'require','员工权限不能为空'],
        ['employee_disable', 'require','选择是否启用不能为空'],
        ['seller_id', 'require','商家ID不能为空'],
    ];
    protected $scene = [
        'add' => [
            'employee_name',
            'employee_mobile',
            'employee_password',
            'employee_authority',
            'employee_disable',
            'seller_id',
        ],
        'edit'=>[
            'employee_name',
            'employee_mobile',
            'employee_authority',
            'employee_disable',
            'seller_id',
        ],
    ];
}