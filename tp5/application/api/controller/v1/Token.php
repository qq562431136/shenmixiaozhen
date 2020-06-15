<?php
namespace app\api\controller\v1;


use think\Db;
use think\Request;
use app\api\controller\Oauth;
use think\Cache;
use app\lib\exception\SuccessData;
use app\lib\exception\SuccessMessage;



/**
 * 生成token
 */
class Token
{

	/**
	 * 请求时间差
	 */
	public static $timeDif = 10000;

	public static $accessTokenPrefix = 'accessToken_';
	public static $refreshAccessTokenPrefix = 'refreshAccessToken_';
	public static $expires = 2592000;
	public static $refreshExpires = 2592000;   //刷新token过期时间
	/**
	 * 测试appid，正式请数据库进行相关验证
	 */
//	public static $appid = 'tp5restfultest';
	/**
	 * appsercet
	 */
//	public static $appsercet = '123456';

	/**
	 * 生成token
	 */
	public function token($type=0,$data=[])
	{
	    if($type==0){
            //参数验证
            $validate = new \app\api\validate\Token;
            if(!$validate->check(input(''))){
                return json(new SuccessMessage($validate->getError(),401));
            }
            $user= Db::table('user')->where('mobile',input('mobile'))->find();
            //数据库已经有一个用户,这里需要根据input('mobile')去数据库查找有没有这个用户
            $userInfo = [
                'uid'   => $user['id'],
                'mobile'=> input('mobile'),
            ]; //虚拟一个uid返回给调用方
        }
        //登录返回token
        if($type==1){
            $userInfo = [
                'user_id'   => $data['user_id'],
                'mobile'=> $data['tel'],
                'access_token'=> $data['access_token'],
            ]; //虚拟一个uid返回给调用方
        }

        try {
			$accessToken = self::setAccessToken($userInfo);
            if($type==1){
                return $accessToken;
            }
            return json(new SuccessData($accessToken));
		} catch (Exception $e) {
            if($type==1){
                return false;
            }
            return json(new SuccessMessage($e,500));
		}
	}

	/**
	 * 刷新token
	 */
	public function refresh($refresh_token='',$appid = '')
	{
		$cache_refresh_token = Cache::get(self::$refreshAccessTokenPrefix.$appid);  //查看刷新token是否存在
		if(!$cache_refresh_token){
            return json(new SuccessMessage('refresh_token is null',401));
		}else{
			if($cache_refresh_token !== $refresh_token){
                return json(new SuccessMessage('refresh_token is error',401));
			}else{    //重新给用户生成调用token
				$data['appid'] = $appid;
				$accessToken = self::setAccessToken($data);
                return json(new SuccessData($accessToken));
			}
		}
	}

	/**
	 * 参数检测
	 */
	public static function checkParams($params = [],$data)
	{	
		//时间戳校验
		if(abs($params['timestamp'] - time()) > self::$timeDif){
            return json(new SuccessData('timestamp：'.time(),'请求时间戳与服务器时间戳异常',401), 401);
		}

		//appid检测，这里是在本地进行测试，正式的应该是查找数据库或者redis进行验证

		//签名检测
		$sign = Oauth::makeSign($params,$data['appsercet']);
		if($sign !== $params['sign']){
            return json(new SuccessData('sign：'.$sign,'sign错误',401));
		}
	}

	/**
     * 设置AccessToken
     * @param $clientInfo
     * @return int
     */
    protected function setAccessToken($clientInfo)
    {
        //生成令牌
        $accessToken = self::getRefreshAccessToken($clientInfo['access_token']);


        $accessTokenInfo = [
            'access_token'  => $accessToken,//访问令牌
            'expires_time'  => time() + self::$expires,      //过期时间时间戳

            'refresh_expires_time'  => time() + self::$refreshExpires,      //过期时间时间戳
            'client'        => $clientInfo,//用户信息
        ];
        self::saveAccessToken($accessToken, $accessTokenInfo,$clientInfo['mobile']);  //保存本次token


        $TokenInfo=[
            'access_token'  => $accessToken,//访问令牌
            'user_id'  => $clientInfo['user_id'],
        ];
        return $TokenInfo;
    }

    /**
     * 刷新用的token检测是否还有效
     */
    public static function getRefreshToken($appid = '')
    {
    	return Cache::get(self::$refreshAccessTokenPrefix.$appid) ? Cache::get(self::$refreshAccessTokenPrefix.$appid) : self::buildAccessToken(); 
    }

    /**
     * 刷新用的access_token检测是否还有效
     */
    public static function getRefreshAccessToken($accessToken = '')
    {
        if(empty($accessToken)){
            return self::buildAccessToken();
        }
        $accessTokenPre=Cache::get(self::$accessTokenPrefix . $accessToken);

        if($accessTokenPre==false){
            return self::buildAccessToken();
        }
       $res= $accessTokenPre['access_token'];
        return $res;
    }

    /**
     * 生成AccessToken
     * @return string
     */
    protected static function buildAccessToken($lenght = 32)
    {
        //生成AccessToken
        $str_pol = "1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ123456789abcdefghijklmnopqrstuvwxyz";
		return substr(str_shuffle($str_pol), 0, $lenght);
    }

    /**
     * 存储token
     * @param $accessToken
     * @param $accessTokenInfo
     */
    protected static function saveAccessToken($accessToken, $accessTokenInfo,$tel)
    {
        //存储accessToken
        $where = [
            'id'=>$accessTokenInfo['client']['user_id'],

        ];
        $add = [
            'token'=>$accessToken
        ];
        $update = Db::table('user')->where($where)->update($add);
        Cache::set($tel.'Token',$accessTokenInfo,self::$expires);

        Cache::set(self::$accessTokenPrefix.$accessToken,$accessTokenInfo,self::$expires);
    }

    /**
     * 刷新token存储
     * @param $accessToken
     * @param $accessTokenInfo
     */
    protected static function saveRefreshToken($refresh_token,$appid)
    {
        //存储RefreshToken
        cache::set(self::$refreshAccessTokenPrefix.$appid,$refresh_token,self::$refreshExpires);
    }
}