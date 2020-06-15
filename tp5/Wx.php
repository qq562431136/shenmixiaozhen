
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<?php
//第一步：用户同意授权，获取code
$code=$_REQUEST["code"];
//第二步：通过code换取网页授权access_token
$urlwx="https://api.weixin.qq.com/sns/oauth2/access_token?appid=wx3037021d6fb0f836" .
    "&secret=f3f1cf378e719a057b2087eac53a3402&code=".$code."&grant_type=authorization_code";
$jsonwx=json_decode(file_get_contents($urlwx),true);
$access_token=$jsonwx["access_token"];
$expires_in=$jsonwx["expires_in"];
$refresh_token=$jsonwx["refresh_token"];
$openid=$jsonwx["openid"];
$scope=$jsonwx["scope"];
$unionid=$jsonwx["unionid"];
//第四步：拉取用户信息(需scope为 snsapi_userinfo)
$urlwx="https://api.weixin.qq.com/sns/userinfo?access_token=".$access_token."&openid=".$openid."&lang=zh_CN";
$userinfo=json_decode(file_get_contents($urlwx),true);

?>

