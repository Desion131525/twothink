<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27 0027
 * Time: 14:15
 */

namespace app\home\controller;


use think\Session;
use app\user\model\Member;
use think\Db;
use app\common\model\UcenterMember;
class Property extends Home
{

//获取openid
    public function info()
    {
        Session::set('return_url',url('home/property/Online'));
        if(!Session::has('openid'))
        {
            $appID = "wx25e9fed03f55b77b";
            //设置绝对路径url地址
            $callback = url('home/property/callback','',true,true);
            //获取用户code
            //引导用户打开https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".
                "appid={$appID}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";

            $this->redirect($url);
        }else{
            $openid = Session::get('openid');
            return $openid;
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






    //报修页面
    public function Online()
    {
        $result = Db::name('member')->where('uid',is_login())->find();
        /*$openid = self::info();


        if($result)
        {
            //如果查询结果有数据返回,说明帐号已经绑定openid 调用自动方法登录
            $ucm = new UcenterMember();
            $ucm->autoLogin($result['uid']);
            //将用户信息保存到session中
            $member = new Member();
            $member->login($result['uid']);
            //跳转到登录前页面
            return $this->fetch();
        }*/
        if(is_login()==0)
        {
            $this->redirect('user/login/index');
        }else{
            $is_owner = Db::name('member')->where('uid',is_login())->find();
            $row = Db::name('owner')->where('id',is_login())->find();
            if($is_owner['is_owner']!=1&&$row==null)
            {
                $this->error('请进行认证','user/user/owner');
            }elseif($is_owner['is_owner']!=1)
            {
                $this->error('已提交证,请耐心等待审核结果');
            }
        }
        if(request()->isPost()){
            $post_data=\think\Request::instance()->post();
            //自动验证

            $validate = validate('property');

            if(!$validate->check($post_data)){
                return $this->error($validate->getError());
            }
            $property = new \app\admin\model\Property();

            $data = $property->data($post_data)->save() ;

            if($data){

                $this->success('新增成功','my/index');

            } else {
                $this->error($property->getError());
            }
        } else {

            return $this->fetch();
        }


    }

    //取消绑定
    public function cancel_login()
    {
        $row = Db::name('member')->where('uid',is_login())->find();
        $row['openid'] = '';
        Db::name('member')->update(['openid'=>$row['openid'],'uid'=>is_login()]);
        session('user_auth', null);
        session('user_auth_sign', null);
        $this->success('取消绑定成功！','user/login/index');
    }
}