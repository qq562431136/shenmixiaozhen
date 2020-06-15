<?php
namespace app\admin\controller;
use app\lib\exception\ErrorException;
use app\lib\exception\SuccessData;
use think\Db;

class User
{
    public function integral($is_integral, $degree, $job_mobile, $money, $store)
    {
        $db2 = Db::connect('db2');
        if ($is_integral == '1') {
            if ($degree == '1') {
                $name = $db2->name('ims_mc_members')->where('mobile', $job_mobile)->find();
                if (!empty($name)) {
                    $integral = $name['credit1'] + intval($money);
                    $experience = $name['experience'] + intval($money);
                    $data_3 = [
                        'credit1' => $integral,
                        'experience' => $experience,
                    ];
                    $db2->name('ims_mc_members')->where('mobile', $job_mobile)->update($data_3);
                    cash_message($name['openid'], $store, $money, $name['credit1'], $integral);
                    file_get_contents("http://jifen.meooh.com/web/index.php?c=user&a=login&");
                }
            } else {
                $name = $db2->name('ims_mc_members')->where('mobile', $job_mobile)->find();
                if (!empty($name)) {
                    $integral = $name['credit1'] + intval($money);
                    $experience = $name['experience'] + intval($money);
                    $data_3 = [
                        'credit1' => $integral,
                        'experience' => $experience,
                    ];
                    $db2->name('ims_mc_members')->where('mobile', $job_mobile)->update($data_3);
                    cash_message($name['openid'], $store, $money, $name['credit1'], $integral);
                    file_get_contents("http://jifen.meooh.com/web/index.php?c=user&a=login&");
                }
            }
        }
    }

    public function order($id)
    {
        $db2 = Db::connect('db2');
        $name = $db2->name('ims_zhou_integral_mall_order')->where('ordersn='."'$id'")->find();
        $good = $db2->name('ims_zhou_integral_mall_order_goods')->where('orderid=' . $name['id'])->find();
        $goods = $db2->name('ims_zhou_integral_mall_goods')->where('id=' . $good['goodsid'])->find();
        if (empty($name)) {
            return json(new ErrorException('查询不到订单'));
        }
        $data = [
            'status' => '4',
        ];
        $mobile = $name['phone'];
//        var_dump($mobile);exit;
        $integral = $db2->name('ims_mc_members')->where('mobile='.$mobile)->find();
//        var_dump($integral);exit;
//        $data_2=[
//            'credit1'=> $integral['credit1']- $name['price'],
//        ];
//        var_dump($integral['credit1']- $name['price']);exit;
        Db::startTrans();
        try {
            $db2->name('ims_zhou_integral_mall_order')->where('ordersn=' . $id)->update($data);
//            $db2->name('ims_mc_members')->where('mobile', $name['phone'])->update($data_2);
//            $integral_2 = $db2->name('ims_mc_members')->where('mobile='.$mobile)->find();
            $store_name = $db2->name('ims_zhou_integral_mall_merchant')->where('uniacid', $name['uniacid'])->find();
            $phone = $db2->name('ims_mc_members')->where('mobile=' . $name['phone'])->find();
            $credit1 = $phone['credit1'];
            cash_message_user($name['openid'], $name['ordersn'], $store_name['shopname'], $goods['goodsname'], $credit1);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        file_get_contents("http://jifen.meooh.com/web/index.php?c=user&a=login&");
        return json(new SuccessData('核销成功'));
    }

    public function register()
    {

        $db2 = Db::connect('db2');
        $mobile = input('mobile');
        $openid = input('openid');
        $name = input('name');
        $date = input('date');
        $birth = explode('-', $date);
        $open = $db2->name('ims_mc_mapping_fans')->where('openid=' . "'$openid'")->find();
        $data = [
            'mobile' => $mobile,
            'realname' => $name,
            'birthyear' => $birth['0'],
            'birthmonth' => $birth['1'],
            'birthday' => $birth['2'],
            'openid' => $openid,
        ];
        Db::startTrans();
        try {
            $db2->name('ims_mc_members')->where('uid=' . $open['uid'])->update($data);
            // 提交事务
            Db::commit();
        } catch (\Exception $e) {
            // 回滚事务
            Db::rollback();
            return json(new ErrorException($e->getMessage(), 401));
        }
        header("Location: http://jifen.meooh.com/app/./index.php?i=2&c=entry&eid=2");
        exit;
    }
}