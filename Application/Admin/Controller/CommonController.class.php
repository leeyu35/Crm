<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 14:11
 */
namespace Admin\Controller;
use Think\Controller;
class CommonController extends Controller
{
    //前置操作方法
    public function _initialize(){
     if(cookie("u_name")!='')
     {
         //检查权限
         $module=__CONTROLLER__;


         $rbac=M("Rbac");
         $one=$rbac->where("module='$module'")->find();
         if($one['title']!='')
         {
             $title="(".$one['title'].")";
         }

         crm_record(cookie('u_name').'执行了'.$module."{$title}的".ACTION_NAME.'操作');
         if($one!="")
         {
              switch (ACTION_NAME)
              {
                  case 'index' :
                      $array=explode(",",$one['show_']);
                      if(in_array(cookie('u_groupid'),$array))
                      {

                      }else
                      {
                          $this->error("您没有这个权限哦");
                          exit();
                      }

                      break;
                  case 'add'  :
                      $array=explode(",",$one['add_']);
                      if(in_array(cookie('u_groupid'),$array))
                      {

                      }else
                      {
                          $this->error("您没有这个权限哦");
                          exit();
                      }

                      break;
                  case 'updata' :
                      $array=explode(",",$one['update_']);
                      if(in_array(cookie('u_groupid'),$array))
                      {

                      }else
                      {
                          $this->error("您没有这个权限哦");
                          exit();
                      }

                      break;
                  case 'delete' :
                  $array=explode(",",$one['delete_']);
                  if(in_array(cookie('u_groupid'),$array))
                  {

                  }else
                  {
                      $this->error("您没有这个权限哦",U("Public/usermessage"));
                      exit();
                  }

                  break;

              }


         }
        //echo $one;
        //echo $module;
        //echo cookie('u_groupid');


     }else{
         $this->error('您还没有登录',U("/login"));
         exit;
     }
     //默认设置

        $this->web_title=C('WEB_NAME');
        $this->confirm='onClick="if(confirm(\'确定要删除吗\')){return true}else{return false}"';
    }



}