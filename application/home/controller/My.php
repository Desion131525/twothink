<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27 0027
 * Time: 19:38
 */

namespace app\home\controller;


use think\Session;

class My extends Home
{
    public function index()
    {
      if(is_login()==0){
          $this->error('请先登录','User/login/index');
      }
        return $this->fetch();
    }
}