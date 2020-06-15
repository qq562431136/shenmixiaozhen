<?php
namespace app\api\validate;

use think\Validate;

/**
 * 生成token参数验证器
 */
class Reserve extends Validate
{
    protected $rule = [
        ['user_id', 'require', '员工工号不能为空'],
//        ['job_name', 'require','预约人不能为空'],
        ['job_mobile', 'require','手机号码不能为空'],
        ['people', 'require','人数不能为空'],
        ['source', 'require','来源不能为空'],
        ['age', 'require','分类不能为空'],
        ['man_woman', 'require','密码不能为空'],
        ['time_start', 'require','预约开始时间不能为空'],
        ['time_end', 'require','预约结束时间不能为空'],
//        ['business_status', 'require','选择是否启用不能为空'],
//        ['business_catalog', 'require','客户分类不能为空'],
    ];
    protected $scene = [
        'edit' => [
            'user_id',
            'job_name',
            'job_mobile',
            'people',
        ],
        'add'=>[
            'user_id',
            'job_name',
            'job_mobile',
            'people',
            'time_start',
            'time_end',
        ],
    ];
}