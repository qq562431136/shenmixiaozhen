<?php
namespace app\api\controller;
use app\api\controller\v1\Token;
use think\Db;
use app\lib\exception\SuccessMessage;
use app\lib\exception\SuccessData;
class Login
{
    //登陆
    public function login()
    {
        $validate = new \app\api\validate\Login();
        if (!$validate->check(input(''))) {
            return json(new SuccessMessage($validate->getError(), 401));
        }

        $tel = input('mobile');
        $password = input('password');
        $test_where = [
            'mobile' => $tel,
        ];
        $test_user = Db::table('user')->where($test_where)->find();
        if (empty($test_user)) {
            return json(new SuccessMessage('用户不存在', 401));
        }

        $user_where = [
            'mobile' => $tel,
        ];
        $user = Db::table('user')->where($user_where)->find();
        // 密码登陆
            if (empty($password)) {
                return json(new SuccessMessage('请输入密码', 401));
            }
            if (getMd5Password($password) != $user['password']) {
                return json(new SuccessMessage('密码错误', 401));
            }

        //生成TOKEN
        $token=new Token;

        $data = [
            'user_id'=> $user['id'],
            'tel'=> $tel,
            'access_token'=> '',
        ];
        $accessToken= $token->token(1,$data);
        $accessToken['mobile'] =$tel;
        $power = Db::table('power')->where('id='.$user['power'])->find();
        if(!empty($user['store_id'])){
            $name = Db::table('store')->where('store_id='.$user['store_id'])->find();
            $accessToken['store_name'] = $name['store_name'];
        }
        $accessToken['staff_name'] = $test_user['staff_name'];
        $accessToken['position'] = $test_user['position'];
        $accessToken['store_id'] = $test_user['store_id'];
        $accessToken['power'] = $power['power_ids'];
        if($accessToken==false){
            return json(new SuccessMessage('生成token错误',500));
        }

        return json(new SuccessData($accessToken));
    }
}