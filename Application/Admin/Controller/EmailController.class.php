<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 14:10
 */
namespace Admin\Controller;
use Think\Controller;
class EmailController extends CommonController
{
    public function index(){
        $users=M('Email');

        $count      =$users->field('id,title,users,s_users,time')->where('users='.cookie('u_id')." and f_show=0")->order('time desc')->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$users->field('id,title,users,s_users,state,time')->where('users='.cookie('u_id')."  and f_show=0 ")->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list  as $key=>$val) {
            $info=users_info($val['s_users']);
            $list[$key]['s_users']=$info['name'];
            $list[$key]['s_images']=$info['image'];
        }

        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function add(){
        $Groupl=M('Groupl');
        $listgroup=$Groupl->field('id,group_name')->select();
		$users=M('Users');
		$this->list=$users->where(" id != ".cookie("u_id"))->field('id,name,image')->select();
		
        $this->grouplist=$listgroup;
        $this->display();
    }
    //添加用户返回
    public function addru(){
        $users=M("Email");

        $s_users=I('post.users');
        $q_users=explode(",",$s_users);
        foreach ($q_users as $key=>$val)
        {
            $users->create();
            $users->time=time();
            $users->users=cookie('u_id');


            $users->s_users=$val;
            $users->add();

        }

        $this->success('发送成功',U('index'));
        exit;






    }

    //删除用户
    public function delete(){
        $users=M("Email");
        $id=I("get.id");
        $type=I("get.type");

        if($users->where("id=$id")->setField($type,1))
        {
            $this->success('删除成功');
        }else
        {
            $this->error('删除失败');
        }


    }

    //修改用户
    public function show(){
        $id=I('get.id');
        $users=M("Email");
        $info=$users->find($id);
        $info2=users_info($info['s_users']);
        $this->info=$users->find($id);
        $this->info2=$info2;
        $this->display();
    }
    public function show2(){
        $id=I('get.id');
        $users=M("Email");
        $info=$users->find($id);
        $info2=users_info($info['users']);
        $this->info=$users->find($id);
        $this->info2=$info2;
        //修改消息状态

        $users->where("id=$id")->setField('state',1);

        $this->display();
    }

    public  function message(){
        $email=M("Email");
        $count      =$email->where("s_users=".cookie('u_id')."  and s_show=0 ")->order("time desc")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出


        $list=$email->where("s_users=".cookie('u_id')."  and s_show=0 ")->order("time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list  as $key=>$val) {
            $info=users_info($val['users']);
            $list[$key]['users']=$info['name'];
            $list[$key]['f_images']=$info['image'];
        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

}