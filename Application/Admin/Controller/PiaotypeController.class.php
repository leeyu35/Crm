<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/9/2
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class piaotypeController extends CommonController
{
        public function index(){
            $Diankuan=M("Piaotype");
            $list=$Diankuan->field("a.id,a.advertiser,a.name,b.companyname")->join(" a left join __AGENT_COMPANY__ b on b.id=a.advertiser")->order("id asc")->select();

            $this->list=$list;
            $this->assign('page',$show);// 赋值分页输出
            $this->display();

    }
    public function add(){
        $Diankuan=M("AgentCompany");
        $list=$Diankuan->field("id,companyname,title")->order("id asc")->select();
        $this->list=$list;
        $this->display();
    }




    public function addru(){

        $Diankuan=M("Piaotype");
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
        $Diankuan=M("Piaotype");
        $info=$Diankuan->find($id);
        $AgentCompany=M("AgentCompany");
        $list=$AgentCompany->field("id,companyname,title")->order("id asc")->select();
        $this->list=$list;

        $this->info=$info;



        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $Diankuan=M("Piaotype");
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
        $Diankuan=M("Piaotype");
        if($Diankuan->delete($id))
        {
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
        }
    }


}