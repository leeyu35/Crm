<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/9/2
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class AgentcompanyController extends CommonController
{
        public function index(){
            $Diankuan=M("AgentCompany");

            $list=$Diankuan->field("id,companyname,title")->order("id asc")->select();

            $this->list=$list;
            $this->assign('page',$show);// 赋值分页输出
            $this->display();

    }
    public function add(){
        $this->display();
    }




    public function addru(){

        $Diankuan=M("AgentCompany");
        $Diankuan->create();




        if($insid=$Diankuan->add()){

            $this->success("提交成功",U("index"));

        }else
        {
            $this->error("提交失败");
        }


    }
    //修改操作
    public  function updata(){
        $id=I('get.id');
        $Diankuan=M("AgentCompany");
        $info=$Diankuan->find($id);
        $this->info=$info;



        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $Diankuan=M("AgentCompany");
        $Diankuan->create();
        if($Diankuan->where("id=$id")->save())
        {

            $this->success('修改成功',U('index'));
        }else{
            $this->error('修改失败');
        }


    }


    public function delete(){
        $id=I('get.id');
        $Diankuan=M("AgentCompany");
        if($Diankuan->delete($id))
        {
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
        }
    }


}