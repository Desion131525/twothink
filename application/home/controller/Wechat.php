<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/12/1 0001
 * Time: 15:04
 */

namespace app\home\controller;


use think\Session;

class Wechat extends Home
{
    public function info()
    {
        Session::set('return_url',url('home/wechat/info'));
        if(!Session::has('openid'))
        {
            $appID = "wx25e9fed03f55b77b";
            //设置绝对路径url地址
            $callback = url('home/wechat/callback','',true,true);
            //获取用户code
            //引导用户打开https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".
                "appid={$appID}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";

            $this->redirect($url);
        }else{
            $openid = Session::get('openid');

        }


    }

    //授权成功回调页
    public function callback()
    {

        $appID = "wx25e9fed03f55b77b";
        //获取code
        $code = request()->get('code');
        $secret = "5688e049ed13d28634a8c2e40b4dad1a";
        //通过code 换取网页授权access_token
        //请求: https://api.weixin.qq.com/sns/oauth2/access_token?appid=APPID&secret=SECRET&code=CODE&grant_type=authorization_code
        $url = "https://api.weixin.qq.com/sns/oauth2/access_token?".
            "appid={$appID}&secret={$secret}&code={$code}&grant_type=authorization_code";
        $str = file_get_contents($url);
        $json = json_decode($str);
        Session::set('openid',$json->openid);
        if(Session::has('return_url'))
        {
            $this->redirect(Session::get('return_url'));
        }
    }

}