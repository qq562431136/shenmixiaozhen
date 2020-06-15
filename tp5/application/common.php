<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006-2016 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: 流年 <liu21st@gmail.com>
// +----------------------------------------------------------------------

// 应用公共文件


if (!function_exists('exception')) {
    /**
     * 抛出异常处理
     *
     * @param string $msg 异常消息
     * @param integer $code 异常代码 默认为0
     * @param string $exception 异常类
     *
     * @throws Exception
     */
    function exception($msg, $code = 0, $exception = '')
    {
        $e = $exception ?: '\think\Exception';
        throw new $e($msg, $code);
    }
}

if (!function_exists('filterEmoji')) {

    // 过滤掉emoji表情
    function filterEmoji($str)
    {
        $str = preg_replace_callback(    //执行一个正则表达式搜索并且使用一个回调进行替换
            '/./u',
            function (array $match) {
                return strlen($match[0]) >= 4 ? '' : $match[0];
            },
            $str);
        return $str;
    }
}


if (!function_exists('strReplace')) {
    /**
     * @param string $string 需要替换的字符串
     * @param int $start 开始的保留几位
     * @param int $end 最后保留几位
     * @return string
     */
    function strReplace($string, $start, $end)
    {
        $strlen = mb_strlen($string, 'UTF-8');//获取字符串长度
        $firstStr = mb_substr($string, 0, $start, 'UTF-8');//获取第一位
        $lastStr = mb_substr($string, -1, $end, 'UTF-8');//获取最后一位
        return $strlen == 2 ? $firstStr . str_repeat('*', mb_strlen($string, 'utf-8') - 1) : $firstStr . str_repeat("*", $strlen - 2) . $lastStr;

    }
}


function getMd5Password($password)
{
    return md5('mf*^c%gh%' . $password . 'yQSEyxVRe');
}

//门店收入总额
function Statistics($data)
{
    $cash = '0';
    $people = '0';
    $unionpay = '0';
    $meituan_money = '0';
    $other_money = '0';
    $other = '0';
    $amount = '0';
    $express = '0';
    $voucher_money = '0';
    $birthday_people = '0';
    $meituan_people = '0';
    $free = '0';
    $voucher_people = '0';
    $people_two_1 = '0';
    $people_two_2 = '0';
    $people_two_3 = '0';
    $people_two_4 = '0';
    $data_3 = '';
    $cash_two_1 = '';
    $cash_two_2 = '';
    $cash_two_3 = '';
    $cash_two_4 = '';
    $people_two = '';
    foreach ($data as $k => $v) {
        if ($v['store_id'] == '1') {
            $data_3 = '宝龙店';
        }
        if ($v['store_id'] == '2') {
            $data_3 = '三坊店';
        }
        if ($v['store_id'] == '3') {
            $data_3 = '泰禾店';
        }
        if ($v['store_id'] == '4') {
            $data_3 = '福清店';
        }
        if ($v['store_id'] == '5') {
            $data_3 = '安泰店';
        }
        if ($v['pay_type'] == '1') {
            $cash += $v['money'];
        }
        if ($v['pay_type'] == '2') {
            $unionpay += $v['money'];
        }
        if ($v['pay_type'] == '3') {
            $meituan_money += $v['money'];
            $meituan_people += $v['people'];
        }
        if ($v['pay_type'] == '4') {
            $voucher_money += $v['money'];
            $voucher_people += $v['people'];
        }
        if ($v['pay_type_1'] == '1') {
            $cash_two_1 += $v['money_1'];
            $people_two_1 += $v['people_1'];
        }
        if ($v['pay_type'] == '2') {
            $cash_two_2 += $v['money_1'];
            $people_two_2 += $v['people_1'];
        }
        if ($v['pay_type'] == '3') {
            $cash_two_3 += $v['money_1'];
            $people_two_3 += $v['people_1'];
        }
        if ($v['pay_type'] == '4') {
            $cash_two_4 += $v['money_1'];
            $people_two_4 += $v['people_1'];
        }
        if (empty($v['source'])) {
            $v['source'] = '1';
        }
        if (empty($v['age'])) {
            $v['age'] = '1';
        }
        $amount += $v['amount'];
        $express += $v['express'];
        $people += $v['people'];
        $people_two +=$v['people_1'];
        $free += $v['free'];
        $birthday_people += $v['birthday_people'];
        if ($v['other'] == '1') {
            $other_money += $v['other_money'];
        } elseif ($v['other'] == '3') {
            $other += $v['other_money'];
        }
    }
    $max = $cash + $other_money + $unionpay + $other + $meituan_money  + $voucher_money + $cash_two_1+$cash_two_2+$cash_two_3+$cash_two_4;
    $sum = $cash + $cash_two_1 + $other_money + (($unionpay + $other + $cash_two_2) * 0.9962) + (($meituan_money+$cash_two_3) * 0.95)
        + (($voucher_money+$cash_two_4) * 0.93) + (($birthday_people * 6.6) * 0.93);
    $amount_money = ($amount * 0.03) + $express;
    $other_sum = $other_money + $other;
    $unionpay_money = $unionpay+$cash_two_2;//银联
    $meituan_money_1 = $meituan_money+$cash_two_3;//美团预订
    $voucher_money_1 = $voucher_money+$cash_two_3;//美团券
    $cash_money = $cash+$cash_two_1;//现金
    $meituan_people_1 = $meituan_people + $people_two_3;//美团人数
    $voucher_people_1 =$voucher_people + $people_two_4;//美团券人数
    $people_1 = $people + $people_two_1 + $people_two_2;//现金银联人数
    $sum_people = $people+$people_two;
    $data = [
        'unionpay' => $unionpay_money,
        'store_name' => $data_3,
        'free' => $free,
        'meituan_people' => $meituan_people_1,
        'voucher_people' => $voucher_people_1,
        'people' => $people_1,
        'cash' => $cash_money,
        'meituan_money' => $meituan_money_1,
        'express' => $express,
        'voucher_money' => $voucher_money_1,
        'amount' => $amount,
        'birthday_people' => $birthday_people,
        'other_money' => $other_money,
        'other' => $other,
        'other_sum' => $other_sum,
        'max' => $max,
        'sum' => $sum,
        'sum_people' => $sum_people,
        'amount_money' => $amount_money,
    ];
    return $data;
}

//门店支出总额
function expenditure_count($data)
{
    $rent = '0';
    $property = '0';
    $hydropower = '0';
    $platform = '0';
    $marketing_upper = '0';
    $marketing_lower = '0';
    $stock_money = '0';
    $program = '0';
    $artificial = '0';
    $data_3 = '';
    foreach ($data as $k => $v) {
        $rent += $v['rent'];
        if (empty($v['store_name'])) {

        } else {
            $data_3 = $v['store_name'];//门店名称
        }
        $property += $v['property'];
        $hydropower += $v['hydropower'];
        $platform += $v['platform'];
        $marketing_upper += $v['marketing_upper'];
        $marketing_lower += $v['marketing_lower'];
        $stock_money += $v['money'];
        $program += $v['program'];
        $artificial += $v['artificial'];
    }
    $sum = $rent + $property + $hydropower + $platform + +$marketing_upper + $marketing_lower + $stock_money + $program + $artificial;
    $business = $rent + $property + $hydropower + $platform;
    $marketing = $marketing_upper + $marketing_lower;
    $maintain =   $program + $artificial;
    $data_2 = [
        'store_name' => $data_3,
        'sum' => $sum,//门店支出总额
        'business' => $business,//营业费用总额
        'marketing' => $marketing,//营销费用总额
        'purchase' => $stock_money,//采购费用总额
        'maintain' => $maintain,//密室维护费用总额
    ];
    if (empty($data_3)) {
        unset($data_2['store_name']);
    }
    return $data_2;
}

//人工费用总额
function expenditure_wages($data)
{
    $wages = '0';
    $security = '0';
    $insurance = '0';
    $accumulation = '0';
    $bonus = '0';
    $room = '0';
    $traffic = '0';
    foreach ($data as $k => $v) {
        $wages += $v['wages'];
        $security += $v['security'];
        $insurance += $v['insurance'];
        $accumulation += $v['accumulation'];
        $bonus += $v['bonus'];
        $room += $v['room'];
        $traffic += $v['traffic'];
    }
    $Labor = $wages + $security + $insurance + $accumulation + $bonus + $room + $traffic;
    $data_2 = [
        'Labor' => $Labor,//人工费用总额
    ];
    return $data_2;
}

//男女比例
function column($data, $sql)
{
    $count_2 = \think\Db::table('reserve')->count('id');
    $array_4 = array();
    foreach ($data as $k => $v) {
        $theme = \think\Db::table($sql)->where('id=' . $k)->find();
        $count = [
            $sql => $theme[$sql],
            'sum' => $v,//总人数
            'persent' => round($v / $count_2 * 100, 2)//统计百分比
        ];
        array_push($array_4, $count);
    }

    return $array_4;
}

//发送短信
function send_sms_code($tel, $code)
{

    require_once VENDOR_PATH . '../vendor/aliyunsms/vendor/autoload.php';
    require_once VENDOR_PATH . '../vendor/aliyunsms/lib/Api/Sms/Request/V20170525/SendSmsRequest.php';
    \Aliyun\Core\Config::load();
    $config = \config('aliyun_sms'); //获取配置信息

    $accessKeyId = $config['KeyID'];//阿里云短信keyId
    $accessKeySecret = $config['KeySecret'];//阿里云短信keysecret

    //短信API产品名
    $product = "Dysmsapi";
    //短信API产品域名
    $domain = "dysmsapi.aliyuncs.com";
    //暂时不支持多Region
    $region = "cn-hangzhou";
//    var_dump($config);exit;
    //初始化访问的acsCleint
    $profile = DefaultProfile::getProfile($region, $accessKeyId, $accessKeySecret);
    DefaultProfile::addEndpoint("cn-hangzhou", "cn-hangzhou", $product, $domain);
    $acsClient = new DefaultAcsClient($profile);

    $request = new SendSmsRequest();

    $request->setPhoneNumbers($tel);//必填-短信接收号码

    $request->setSignName($config['SignName']);//必填-短信签名: 如何添加签名可以参考阿里云短信或TPshop官方文档
    //必填-短信模板Code
    $request->setTemplateCode($config['TemplateCode']);//必填-短信签名: 如何添加签名可以参考阿里云短信或TPshop官方文档
    //选填-假如模板中存在变量需要替换则为必填(JSON格式)
    $request->setTemplateParam("{\"code\":$code}");//短信签名内容:

    //发起访问请求
    $resp = $acsClient->getAcsResponse($request);

    //短信发送成功返回True，失败返回false
    if ($resp && $resp->Code == 'OK') {
        return array('status' => 1, 'msg' => $resp->Code);
    } else {
        return array('status' => -1, 'msg' => $resp->Message . ' subcode:' . $resp->Code);
    }

}

function getDateInfo($type)
{
    $data = array(
        array(
            'firstday' => date('Ym01', strtotime('-1 month')),
            'lastday' => date('Ymt', strtotime('-1 month')),
        ),
        array(
            'firstday' => date('Ym01', strtotime(date("Y-m-d"))),
            'lastday' => date('Ymd', strtotime((date('Ym01', strtotime(date("Y-m-d")))) . " +1 month -1 day")),
        ),
        array(
            'firstday' => date('Ymd', strtotime("-15 day")),
            'lastday' => date('Ymd', strtotime('-1 day')),
        ),
        array(
            'firstday' => date('Ymd', strtotime("-30 day")),
            'lastday' => date('Ymd', strtotime('-1 day')),
        ),
    );
    return is_null($type) ? $data : $data[$type - 1];
}

// URL
function vget($url)
{
    $info = curl_init();
    curl_setopt($info, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($info, CURLOPT_HEADER, 0);
    curl_setopt($info, CURLOPT_NOBODY, 0);
    curl_setopt($info, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($info, CURLOPT_SSL_VERIFYHOST, false);
    curl_setopt($info, CURLOPT_URL, $url);
    $output = curl_exec($info);
    curl_close($info);
    return $output;
}

function workno($data)
{

    $work = array_column($data, 'workno');
    $data_2 = array();
    foreach ($work as $k => $v) {
        if (!empty($v)) {
            $number = explode(',', $v);
            array_push($data_2, $number);
        }
    }
    $result[] = '0';
    foreach ($data_2 as $val) {
        foreach ($val as $k => $v) {
            if (!empty($v)) {
                array_push($result, $v);
            }
        }
    }
    $num = array_count_values($result);

    return $num;
}
function cash_message($openid,$store,$money,$credit1,$integral){
    $appid = "wx3037021d6fb0f836";
    $secret = "f3f1cf378e719a057b2087eac53a3402";
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
    $token =getJson($url);
    $token = json_decode($token, true);
    $uri ='https://api.weixin.qq.com/cgi-bin/message/template/send';
    $access_token = $token["access_token"];
    $uri = $uri.'?access_token='.$access_token;
    $data=  array('touser'=>$openid,   //发给谁
        'template_id'=>'KgvAuRZtegEqFIuNzs5S9rQuREdykBmMYqYeRUn5mvM',   //模板id
        'url'=>'http://jifen.meooh.com/app/./index.php?i=2&c=entry&eid=2',     //这个是你发送了模板消息之后，当用户点击时跳转的连接
        'topcolor'=>"#FF0000",   //颜色
        'miniprogram' => '',
        'data'=>array(
            'first'=>array(
                'value'=>'尊敬的客户您好',
                'color'=>'#173177'
            ),
            'keyword1'=>array(
                'value'=>date('Y-m-d H:i:s',time()),
                'color'=>'#173177'
            ),
            'keyword2'=>array(
                'value'=>$store,
                'color'=>'#173177'
            ),
            'keyword3'=>array(
                'value'=>intval($money),
                'color'=>'#173177'
            ),
            'keyword4'=>array(
                'value'=>intval($credit1),
                'color'=>'#173177'
            ),
            'keyword5'=>array(
                'value'=>$integral,
                'color'=>'#173177'
            ),
            'remark'=>array(
                'value'=>'本次增加'.$money.'积分,进入“个人账户”查询积分及消费详情',
                'color'=>'#173177'
            )
        )
    );
    $res_data = getJson($uri,$data);
    $res_data = json_decode($res_data, true);
    if ($res_data['errcode'] != 0) {
        return false;
    }
    return true;
}
function cash_message_user($openid,$ordersn,$store_name,$goods,$phone){
    $appid = "wx3037021d6fb0f836";
    $secret = "f3f1cf378e719a057b2087eac53a3402";
    $url = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=$appid&secret=$secret";
    $token =getJson($url);
    $token = json_decode($token, true);
    $uri ='https://api.weixin.qq.com/cgi-bin/message/template/send';
    $access_token = $token["access_token"];
    $uri = $uri.'?access_token='.$access_token;
    $data=  array('touser'=>$openid,   //发给谁
        'template_id'=>'Ee2ABXGnUoGKtHl6Uw_L9BX--Dp81cE-0R_7VDsni0g',   //模板id
        'url'=>'http://jifen.meooh.com/app/index.php?i=2&c=entry&status=4&do=Newmyorder&m=zhou_integral_mall',     //这个是你发送了模板消息之后，当用户点击时跳转的连接
        'topcolor'=>"#FF0000",   //颜色
        'miniprogram' => '',
        'data'=>array(
            'first'=>array(
                'value'=>'恭喜您，积分兑换成功。',
                'color'=>'#173177'
            ),
            'keyword1'=>array(
                'value'=>$ordersn,
                'color'=>'#173177'
            ),
            'keyword2'=>array(
                'value'=>$store_name,
                'color'=>'#173177'
            ),
            'keyword3'=>array(
                'value'=>$goods,
                'color'=>'#173177'
            ),
            'keyword4'=>array(
                'value'=>date('Y-m-d H:i:s',time()),
                'color'=>'#173177'
            ),
            'keyword5'=>array(
                'value'=>$phone,
                'color'=>'#173177'
            ),
            'remark'=>array(
                'value'=>'谢谢惠顾，点击查看详情',
                'color'=>'#173177'
            )
        )
    );
    $res_data = getJson($uri,$data);
    $res_data = json_decode($res_data, true);
    if ($res_data['errcode'] != 0) {
        return false;
    }
    return true;
}
function getJson($url = '', $param = [] ,$contentType = 'json'){
    $ch = curl_init();
    // 请求地址
    curl_setopt($ch, CURLOPT_URL, $url);
    // 请求参数类型
    $param = $contentType == 'json' ? urldecode(json_encode($param)) : http_build_query($param);
    // 关闭https验证
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
    // post提交
    if($param){
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
    }
    // 返回的数据是否自动显示
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    // 执行并接收响应结果
    $output = curl_exec($ch);
    // 关闭curl
    curl_close($ch);
    return $output !== false ? $output : false;

}


