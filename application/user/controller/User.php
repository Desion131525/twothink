<?php
// +----------------------------------------------------------------------
// | TwoThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.twothink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 艺品网络 <http://www.twothink.cn>
// +----------------------------------------------------------------------

namespace app\user\controller;
use app\common\controller\UcApi;
use think\Db;

/**
 * 用户控制器
 */
class User extends Base {
    /**
     * 修改密码提交
     * @author 艺品网络  <twothink.cn>
     */
    public function profile(){
		if ( !is_login() ) {
			$this->error( '您还没有登陆',url('User/login') );
		}
        if ($this->request->isPost()) {
            //获取参数
            $uid        =   is_login();
            $data = input('param.'); 
            $password   =  $data['old'];;
            $repassword = $data['repassword'];
            $data['password'] = $data['password'];
            empty($password) && $this->error('请输入原密码');
            empty($data['password']) && $this->error('请输入新密码');
            empty($repassword) && $this->error('请输入确认密码');

            if($data['password'] !== $repassword){
                $this->error('您输入的新密码与确认密码不一致');
            }

            $Api = new UcApi();
            $res = $Api->updateInfo($uid, $password, $data);
            if($res['status']){
                $this->success('修改密码成功！');
            }else{
                $this->error($res['info']);
            }
        }else{
            return $this->fetch();
        }
    }

    //业主认证
    public function owner()
    {
       $is_owner = Db::name('member')->where('uid',is_login())->find();
        $row = Db::name('owner')->where('id',is_login())->find();
       if($is_owner['is_owner']==1)
       {
           $this->error('您已经认证');
       }elseif($is_owner['is_owner']!=1&&$row)
       {
           $this->error('已提交证,请耐心等待审核结果');
       }

        if($this->request->isPost())
        {
            //接收数据
            $data = request()->post();
            //验证数据
            $validate = validate('Owner');
            if(!$validate->check($data))
            {
                //返回错误信息
                return $this->error($validate->getError());
            }

            //根据用户id查询用户该用户是否已经认证
            $row = Db::name('owner')->where('id',is_login())->find();
            if(!$row)
            {
                $data['id'] = is_login();
                $result = Db::name('owner')->insert($data);
                if($result)
                {
                    $this->success('认证提交成功,待审核','home/my/index');
                }else{
                    $this->error('认证提交失败,重新认证','user/owner');
                }
            }else{
                $this->error('已经认证,无需重复认证','home/my/index');
            }



        }
        return $this->fetch();
    }
}
