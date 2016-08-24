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
     if(session("u_name")!='')
     {



     }else{
         $this->error('您还没有登录',U("/login"));
         exit;
     }
     //默认设置

        $this->web_title=C('WEB_NAME');

    }


}