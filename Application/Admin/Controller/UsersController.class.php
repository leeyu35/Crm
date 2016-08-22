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
        $this->list=$users->field('id,name,users,image,groupid,ctime')->order('ctime desc')->select();
        $this->display();
    }

    public function add(){

        $this->display();
    }
    //添加用户返回
    public function addru(){
        $users=D("Users");

        if($vo=$users->create())
        {

           if($_FILES['image']['name']!=""){

               $upload = new \Think\Upload();// 实例化上传类
               $upload->maxSize   =     3145728 ;// 设置附件上传大小
               $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
               $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
               $upload->savePath  =     '/portrait/'; // 设置附件上传（子）目录
               // 上传文件
               $info   =   $upload->upload();
               if(!$info) {// 上传错误提示错误信息
                   $this->error($upload->getError());
               }else{// 上传成功
                   $image='/Uploads'.$info['image']['savepath'].$info['image']['savename'];
               }
           }else
           {
               $image=C('Portrait');
           }

            $users->image=$image;
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
        if($users->delete(I('get.id')))
        {
            $this->success('删除成功');
        }else
        {
            $this->error('删除失败');
        }


    }

    //修改用户
    public function updata(){
        $id=I('get.id');
        $users=M("Users");
        $this->info=$users->find($id);
        $this->display();

    }

    //修改用户返回
    public  function upusers(){
        $users=D("Users");


        if($users->create())
        {
            if($_FILES['image2']['name']!=""){
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize   =     3145728 ;// 设置附件上传大小
                $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
                $upload->savePath  =     '/portrait/'; // 设置附件上传（子）目录
                // 上传文件
                $info   =   $upload->upload();
                if(!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                }else{// 上传成功
                    //删除旧图
                    unlink(".".I('post.image'));
                    $image='/Uploads'.$info['image2']['savepath'].$info['image2']['savename'];
                }
            }else
            {
                $image=I('post.image');
            }
            $users->image=$image;
            if($users->password=='')
            {
                unset($users->password);
            }
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