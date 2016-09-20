<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/1
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class HolidayController extends CommonController
{
        public function index(){
            $Refund=M("Holiday");
            //搜索条件
            $type=I('get.searchtype');
            if($type!='')
            {
                if($type=='advertiser')
                {
                    $coustomer=M('Users');
                    $zsql=$coustomer->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                    $where.=" and  a.id!='hjd2' and a.submituser in($zsql)";

                }
                if($type=='contract_no')
                {
                    $where.=" and a.id!='hjd2' and a.contract_no like '%".I('get.search_text')."%'";
                }
                $this->type=$type;
                $this->ser_txt=I('get.search_text');

            }
            //时间条件
            $time_start=I('get.time_start');
            $time_end=I('get.time_end');
            if($time_start!="" and $time_end!="")
            {
                $time_start=strtotime($time_start);
                $time_end=strtotime($time_end);

                $where.=" and a.ctime > $time_start and a.ctime < $time_end";
                $this->time_start=I('get.time_start');
                $this->time_end=I('get.time_end');
            }
            //审核条件
            $type2=I('get.shenhe');
            if($type2!='')
            {
                if($type2=='k')
                {
                    $where.=" and a.id!='hjd3' ";
                }
                if($type2=='0')
                {
                    $where.=" and (a.audit_1=0 or a.audit_2=0)";
                }
                if($type2=='1')
                {
                    $where.=" and a.audit_1=1 and a.audit_2=1";
                }
                $this->type2=$type2;
                $this->ser_txt2=I('get.search_text');

            }
            //权限条件
            $q_where=quan_where(__CONTROLLER__,"a");
            $count      = $Refund->field('a.id,a.bumen,a.zhiwu,a.type,a.starttime,a.endtime,a.ctime,a.audit_1,a.audit_2,b.name')->join("a left join __USERS__ b on a.submituser = b.id ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$Refund->field('a.id,a.bumen,a.zhiwu,a.type,a.starttime,a.endtime,a.ctime,a.audit_1,a.audit_2,b.name')->join("a left join __USERS__ b on a.submituser = b.id  ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();

            $this->list=$list;
            $this->assign('page',$show);// 赋值分页输出
            $this->display();

    }
    public function add(){
        $this->display();
    }

    //添加返回
    public function addru(){

        $Refund=M("Holiday");
        $vo=$Refund->create();


        $Refund->starttime=strtotime($Refund->starttime);
        $Refund->endtime=strtotime($Refund->endtime);
        $Refund->ctime=time();
        if($Refund->add()){

            $this->success("申请成功,请等待审核",U("index"));

        }else
        {
            $this->error("提交失败");
        }


    }

    public function delete(){
        $id=I('get.id');
        $Refund=M("Holiday");
        if($Refund->delete($id))
        {
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
        }
    }
    //审核操作
    public function shenhe(){
        $type=I('get.type');
        $id=I('get.id');
        //检查是否有权限执行审核操作
        $ispw=shenhe(__CONTROLLER__,$type);
        if($ispw!='200')
        {
            $this->error("您没有权限执行审核操作哦");
        }else
        {
            $table=M("Holiday");
            if($table->where("id=$id")->setField("audit_1",1) and $table->where("id=$id")->setField("audit_2",1))
            {
                $this->success('审核成功',U('index'));
            }else
            {
                $this->error('审核失败');
            }
        }
    }

    //查看合同
    public function show(){
        $id=I('get.id');
        $Refund=M("Holiday");
        $info=$Refund->find($id);
        $this->info=$info;
        //销售
        $submitusers=users_info($info[submituser]);
        $this->users_info=$submitusers['name'];


        $this->display();

    }
}