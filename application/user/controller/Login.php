<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2017 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 艺品网络 <http://www.twothink.cn>
// +----------------------------------------------------------------------

namespace app\user\controller;
use app\common\controller\UcApi;
use app\common\model\UcenterMember;
use app\user\model\Member;
use think\Controller;
use think\Cookie;
use think\Db;
use think\Session;


/**
 * 用户登入
 * 包括用户登录及注册
 */
class Login extends Controller {
    public function __construct(){
        /* 读取站点配置 */
        $config = api('Config/lists');
        config($config); //添加配置
        parent::__construct();
    }

    //获取openid
    public function info()
    {
        Session::set('return_url',url('user/login/info'));
        if(!Session::has('openid'))
        {
            $appID = "wx25e9fed03f55b77b";
            //设置绝对路径url地址
            $callback = url('user/login/callback','',true,true);
            //获取用户code
            //引导用户打开https://open.weixin.qq.com/connect/oauth2/authorize?appid=APPID&redirect_uri=REDIRECT_URI&response_type=code&scope=SCOPE&state=STATE#wechat_redirect
            $url = "https://open.weixin.qq.com/connect/oauth2/authorize?".
                "appid={$appID}&redirect_uri={$callback}&response_type=code&scope=snsapi_base&state=STATE#wechat_redirect";

            $this->redirect($url);
        }else{
            $openid = Session::get('openid');

        }

        return $openid;
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


    /* 登录页面 */
    public function index($username = '', $password = '', $verify = '',$type = 1){
        //检查微信用户是否已经绑定帐号,如果已经绑定则自动登录
          //获取openid
        $openid = $this->info();

        //根据openid查询用户
        $result = Db::name('member')->where('openid',$openid)->find();

        if($result)
        {
            //如果查询结果有数据返回,说明帐号已经绑定openid 调用自动方法登录
            $ucm = new UcenterMember();
            $ucm->autoLogin($result['uid']);
            //将用户信息保存到session中
            $member = new Member();
            $member->login($result['uid']);
            //跳转到登录前页面
            $this->success('登录成功！');
        }

        if($this->request->isPost()){ //登录验证
            /* 检测验证码 */
            if(!captcha_check($verify)){
                $this->error('验证码输入错误！');
            }

            /* 调用UC登录接口登录 */
            $user = new UcApi;
            $uid = $user->login($username, $password, $type);

            if(0 < $uid){ //UC登录成功
                /* 登录用户 */
                $Member = model('Member');
                if($Member->login($uid)){ //登录用户
                    //TODO:跳转到登录前页面

                    if(!$cookie_url = Cookie::get('__forward__')){
                        $cookie_url = url('Home/Index/index');
                    }
                    //成功登录绑定帐号
                    Db::name('member')->where('uid',$uid)->update(['openid'=>$openid]);


                    $this->success('登录成功！',$cookie_url);
                } else {
                    $this->error($Member->getError());
                }

            } else { //登录失败
                switch($uid) {
                    case -1: $error = '用户不存在或被禁用！'; break; //系统级别禁用
                    case -2: $error = '密码错误！'; break;
                    default: $error = '未知错误！'; break; // 0-接口参数错误（调试阶段使用）
                }
                $this->error($error);
            }

        } else { //显示登录表单
            return $this->fetch();
        }
    }

	/* 注册页面 */
	public function register($username = '', $password = '', $repassword = '', $email = '', $verify = ''){
        if(!config('user_allow_register')){
            $this->error('注册已关闭');
        }
		if($this->request->isPost()){ //注册用户
			/* 检测验证码 */
		   if(!captcha_check($verify)){
                $this->error('验证码输入错误！');
            }

			/* 检测密码 */
			if($password != $repassword){
				$this->error('密码和重复密码不一致！');
			}			

			/* 调用注册接口注册用户 */
            $User = new UcApi;
			$uid = $User->register($username, $password, $email); 
			if(0 < $uid){ //注册成功
				//TODO: 发送验证邮件
				$this->success('注册成功！',url('login/index'));
			} else { //注册失败，显示错误信息
				$this->error($uid);
			}

		} else { //显示注册表单
			return $this->fetch();
		}
	}
	/* 退出登录 */
	public function logout(){
		if(is_login()){
			model('Member')->logout();
			$this->success('退出成功！', url('User/login'));
		} else {
			$this->redirect('User/login');
		}
	}

}
