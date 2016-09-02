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
                //记录登录日志
                $log=M("Log");
                $data[ip]=$_SERVER["REMOTE_ADDR"];
                $data[users]=$vo['id'];
                $data[dizhi]=getIPLoc_QQ($_SERVER["REMOTE_ADDR"]);
                $data[time]=time();
                $log->add($data);

                $this->success('登录成功',U("/adminIndex"));
            }else
            {
                $this->error("用户名或密码错误");
            }
        }
        //登录成功欢迎页面
        public  function index(){
            $this->web_title=C('WEB_NAME');
            $this->sessionuid=session("u_id");
            $this->display();
        }

        public function usermessage(){
            //组
            $group=M("Groupl")->find(session("u_groupid"));
            $this->group=$group;
            $this->sessionuid=session("u_id");
            $this->display();
        }
        //退出
        public function login_out(){
            session("u_id",null);  //用户ID
            session("u_name",null); //用户姓名
            session("u_image",null);//用户图片
            session("u_groupid",null); //用户组id
            $this->success('退出成功',U('/login'));
        }
        //日志
        public function log(){
            $log=M("Log");
            $users=M('Users');

            $count      = $log->field('a.id,a.ip,a.dizhi,a.time,b.name,b.image,b.groupid')->join("a left join jd_users b on a.users=b.id")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$log->field('a.id,a.ip,a.dizhi,a.time,b.name,b.image,b.groupid')->join("a left join jd_users b on a.users=b.id")->limit($Page->firstRow.','.$Page->listRows)->order("a.time desc")->select();

            foreach ($list as $key=>$val)
            {
                $list[$key]['groupname']=group_name($val[groupid]);
            }
            $this->list=$list;
            $this->assign('page',$show);// 赋值分页输出
            $this->display();
        }

}