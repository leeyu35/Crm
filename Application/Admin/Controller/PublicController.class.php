<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/8/22
 * Time: 16:15
 */

namespace Admin\Controller;
use Think\Controller;

class PublicController extends Controller
{
        //登录
        public function login(){

            $this->display();
        }
        public function loginrn(){
            $users=M("Users");
            $u=I('post.users');
            $p=md5(I('post.password'));
            $vo=$users->where("users='$u' and password='$p'")->find();
            if($vo)
            {
                session("u_id",$vo['id']);  //用户ID
                session("u_name",$vo['name']); //用户姓名
                session("u_image",$vo['image']);//用户图片
                session("u_groupid",$vo['groupid']); //用户组id
                echo session("u_name");
            }else
            {
                $this->error("用户名或密码错误");
            }
        }
}