<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2017/11/28 0028
 * Time: 20:33
 */

namespace app\home\controller;


use think\Db;

class Village extends Home
{
    //通知列表
   public function notice($category_id)
   {
      switch ($category_id)
      {
          case 43:
              $rows = Db::table('document')->where('category_id',$category_id)->select();
              break;
          case 45:
              $rows = Db::table('document')->where('category_id',$category_id)->select();
          break;
          case 46:
              $rows = Db::table('document')->where('category_id',$category_id)->where('status',1)->select();
          break;


      }

       $this->assign('rows',$rows);
       return $this->fetch();
   }

   //通知详情
    public function notice_detail($id)
    {

        $row = Db::table('document')->where('id',$id)->find();

        $rows = Db::name('document');
        $content = Db::table('document_article')->where('id',$row['id'])->find();
        $this->assign('content',$content);
        if( $row['view']+=1)
        {
            if($rows->update($row))
            {
                $this->assign('row',$row);
            }
        }
        return $this->fetch();
    }

    //小区活动列表
    public function village()
    {

        $rows = Db::table('document')->where('category_id',47)->where('status',1)->select();
        $this->assign('rows',$rows);
        return $this->fetch();
    }

    //ajax
    public function ajax_get_info($id)
    {

        if(is_login())  {
            $row = Db::name('m_vs_a')->where('member_id',is_login())->where('activity_id',$id)->find();
            if($row)
            {
                return 1;
            }else{
                return 0;
            }
        }



    }

    public function ajax_insert($id){
       $data['member_id'] =is_login();
       $data['activity_id'] =$id;
        $result = Db::name('m_vs_a')->insert($data);
        if($result)
        {
           echo '1';
        }
    }

    //小区活动详情
    public function village_detail($id)
    {
        $row = Db::table('document')->where('id',$id)->find();

        $rows = Db::name('document');
        $content = Db::table('document_article')->where('id',$row['id'])->find();
        $this->assign('content',$content);
        if( $row['view']+=1)
        {
            if($rows->update($row))
            {
                $this->assign('row',$row);
            }
        }
        return $this->fetch();
    }


    //服务
    public function service()
    {
        return $this->fetch();
    }

    //关于我们
    public function about()
    {
        $row = Db::table('document_article')->where('id',143)->find();
        $this->assign('row',$row);
        return $this->fetch();
    }

    //生活贴士
    public function life()
    {
        $rows = Db::table('document')->where('status',1)->select();
        $this->assign('rows',$rows);
        return $this->fetch();
    }

   //小区租售
    public function sale()
    {
        $rows = Db::table('document')->where('category_id',50)->where('status',1)->select();
        $this->assign('rows',$rows);
        return $this->fetch();
    }

    //ajax分页
    public function ajax_test()
    {

    }
}