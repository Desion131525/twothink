<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/27 0027
 * Time: 14:15
 */

namespace app\home\controller;



use think\Db;

class Property extends Home
{

    //报修页面
    public function Online()
    {

        if(is_login()==0)
        {
            $this->success('请先登录',"user/login/index");
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
}