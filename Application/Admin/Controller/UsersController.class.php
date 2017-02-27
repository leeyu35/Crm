<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 14:10
 */
namespace Admin\Controller;
use Think\Controller;
class UsersController extends CommonController
{
    public function index(){
        $users=M('Users');
        //搜索条件
        $type=I('get.searchtype');
        if($type!='') {
            if ($type == 'name') {
                $where .= " and name like '%" . I('get.search_text') . "%'";
            }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');
        }

        //权限条件
        $q_where=quan_users_where(__CONTROLLER__);
        $count      =$users->field('id,name,users,image,groupid,ctime,is_delete')->where("id !=0 and $q_where $where")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$users->field('id,name,users,image,groupid,manager,ctime,manager,is_delete,nianjia')->order('ctime desc')->where("id!=0 and $q_where $where")->limit($Page->firstRow.','.$Page->listRows)->select();


        foreach ($list as $key=>$val)
        {
            $list[$key]['groupname']=group_name($val[groupid]);
        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function add(){
        $Groupl=M('Groupl');
        $listgroup=$Groupl->field('id,group_name')->select();
        $this->grouplist=$listgroup;
        $this->display();
    }
    //添加用户返回
    public function addru(){
        $users=D("Users");

        if($vo=$users->create())
        {

           if($_FILES['image']['name']!=""){

               $upload = new \Think\Upload();// 实例化上传类
               $upload->maxSize   =     2097152 ;// 设置附件上传大小
               $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
               $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
               $upload->savePath  =     '/portrait/'; // 设置附件上传（子）目录
               // 上传文件
               $info   =   $upload->upload();
               if(!$info) {// 上传错误提示错误信息
                   $this->error($upload->getError());
               }else{// 上传成功
                   $image=C('Upload_path').$info['image']['savepath'].$info['image']['savename'];
               }
           }else
           {
               $image=C('Portrait');
           }

            $users->image=$image;
            $users->jobtime=strtotime($users->jobtime);
            $users->intime=strtotime($users->intime);

           if($users->add())
           {

               $this->success('添加用户成功',U('index'));
           }else
           {
               $this->error('添加失败');
           }
        }else
        {
            $this->error($users->getError());
        }

    }

    //删除用户
    public function delete(){
        $users=M("Users");
        if($users->where("id=".I('get.id'))->setField('is_delete','1'))
        {
            $this->success('删除成功');
        }else
        {
            $this->error('删除失败');
        }


    }

    //修改用户
    public function updata(){
        $Groupl=M('Groupl');
        $listgroup=$Groupl->field('id,group_name')->select();
        $this->grouplist=$listgroup;



        $id=I('get.id');
        $users=M("Users");
        $info=$users->find($id);
        $this->info=$info;
        $this->userszu=$Groupl->find(cookie('u_groupid'));

        $this->display();

    }

    //修改用户返回
    public  function upusers(){
        $users=D("Users");
        if($users->create())
        {
            if($_FILES['image2']['name']!=""){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     2097152 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     '/portrait/'; // 设置附件上传（子）目录
                // 上传文件
                $info   =   $upload->upload();
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功
                    //删除旧图
                    if(I('post.image')!='/Uploads/portrait/wu.png')
                    {
                        unlink(".".I('post.image'));
                    }

                    $image='/Uploads'.$info['image2']['savepath'].$info['image2']['savename'];
                }
            }else
            {
                $image=I('post.image');
            }
            $users->image=$image;
            if(I('post.password')=='')
            {

                $users->password=I('post.jpassword');
            }
            $users->jobtime=strtotime($users->jobtime);
            $users->intime=strtotime($users->intime);
            if($users->save())
            {
                $this->success("修改成功",U('index'));
            }else{
                $this->error("修改失败");
            }
        }else
        {
            $this->error($users->getError());
        }

    }
}