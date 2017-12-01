<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/26 0026
 * Time: 13:13
 */

namespace app\admin\controller;




use app\admin\model\Property;
use think\Db;

class Center extends Admin
{
//列表
    public function index(){

        $list = Db::name('property')->paginate(5);
        $this->assign('_list', $list);
        $this->assign('meta_title','用户信息');
        return $this->fetch('index');
    }


    public function test($id)
    {
        $property = Db::name("property");
        $row = Db::name('property')->where('id',$id)->find();
        switch ($row['static'])
        {

            case 0:
              $row['static'] = 1;
                if( $property->update($row))
                {
                    return '1';
                }
                break;
            case 1:
                $row['static'] = -1;
               if( $property->update($row))
               {
                   return '-1';
               }

                break;
        }


    }
//添加
    public function add(){
        if(request()->isPost()){
            $post_data=\think\Request::instance()->post();
            //自动验证
            $validate = validate('property');

            if(!$validate->check($post_data)){
                return $this->error($validate->getError());
            }
           $property = new Property();

            $data = $property->data($post_data)->save() ;

            if($data){
                $this->success('新增成功', url('index'));

            } else {
                $this->error($property->getError());
            }
        } else {

            return $this->fetch();
        }
    }

//删除
    public function del(){
        $id = array_unique((array)input('id/a',0));

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

        $map = array('id' => array('in', $id) );
        if(\think\Db::name('property')->where($map)->delete()){
            //记录行为
            action_log('update_property', 'property', $id, UID);
            $this->success('删除成功');
        } else {
            $this->error('删除失败！');
        }
    }
//编辑
    public function edit($id = 0){
        if($this->request->isPost()){
            $postdata = \think\Request::instance()->post();

            $property = \think\Db::name("property");

            $data = $property->update($postdata);

            if($data !== false){
                $this->success('编辑成功', url('index'));
            } else {
                $this->error('编辑失败');
            }
        } else {
            $info = array();
            /* 获取数据 */
            $info = \think\Db::name('property')->find($id);

            if(false === $info){
                $this->error('获取配置信息错误');
            }

            $this->assign('info', $info);
            $this->meta_title = '编辑导航';
            return $this->fetch();
        }
    }

    public function changeStatus($method=null){
        $data=input('id/a');
        $id = array_unique($data);

        if ( empty($id) ) {
            $this->error('请选择要操作的数据!');
        }

         $property = Property::get($id);
         $property->delete();

    }

    //商家活动
    public function seller()
    {
        $list = Db::table('document')->where('category_id',46)->paginate(5);;
        //var_dump($list);die;
        $this->assign('_list', $list);
        $this->assign('meta_title','用户信息');
        return $this->fetch();
    }

    public function ajax_activity($id)
    {
        $property = Db::name("document");
        $row = Db::name('document')->where('id', $id)->find();
        switch ($row['status']) {

            case 0:
                $row['status'] = 1;
                if ($property->update($row)) {
                    return '1';
                }
                break;
            case 1:
                $row['status'] = 0;
                if ($property->update($row)) {
                    return '0';
                }

                break;
        }
    }
    //小区活动

    public function community()
    {
        $list = Db::table('document')->where('category_id',47)->paginate(5);;
        //var_dump($list);die;
        $this->assign('_list', $list);
        $this->assign('meta_title','用户信息');
        return $this->fetch();
    }

    //业主认证
    public function approve()
    {
        $list = Db::table('owner')->paginate(5);;
        //var_dump($list);die;
        $this->assign('_list', $list);
        $this->assign('meta_title','用户信息');
        return $this->fetch();
    }

    public function ajax_approve($id)
    {
        $property = Db::name("owner");
        $row = Db::name('owner')->where('id', $id)->find();


        switch ($row['status']) {

            case 0:
                Db::table('member')->where('uid',$row['id'])->update(['is_owner'=>1]);
                $row['status'] = 1;
                if ($property->update($row)) {
                    return '1';
                }
                break;
            case 1:
                Db::table('member')->where('uid',$row['id'])->update(['is_owner'=>0]);
                $row['status'] = 0;
                if ($property->update($row)) {
                    return '0';
                }

                break;
        }
    }
}