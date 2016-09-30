<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/16
 * Time: 17:39
 */
namespace Admin\Controller;
use Think\Controller;

class MvController extends CommonController
{
    public function index(){
        $coustomer=M('Customer');
        //查询所有客户 按提交人分组
        $subusers=$coustomer->field("submituser")->group("submituser")->select(false);
        $users=M("Users");
        $list=$users->field('id,name')->where("id in ($subusers)")->select();
        $this->list=$list;

        $this->display();


    }

    //新增客户返回
    public function addru(){
        $users1=I('post.users1');//被转移
        $users2=I('post.users2');//接受转移者
        if(is_array(I('post.kehu')))
        {
            foreach (I('post.kehu') as $key=>$val)
            {
                $Customer=M("Customer")->where("id=$val")->setField("submituser",$users2);//客户所属权
                
                $hetong=M("Contract")->where("advertiser=$val")->setField("submituser",$users2);//合同所属权
                $Diankuan=M("Diankuan")->where("advertiser=$val")->setField("submituser",$users2);//垫款所属权
                $Refund=M("Refund")->where("advertiser=$val")->setField("submituser",$users2);//退款所属权
                $Invoice=M("Invoice")->where("invoice_head=$val")->setField("submituser",$users2);//发票所属权
                $RefundInvoice=M("RefundInvoice")->where("invoice_head=$val")->setField("submituser",$users2);//退票所属权
                $this->success("转移成功");
            }

        }else
        {
           $this->error("您好像没有勾选客户哦~");
        }

        exit;
        /* 转移全部客户
        $Customer=D("Customer")->where("submituser=$users1")->setField("submituser",$users2);//客户所属权
        $hetong=M("Contract")->where("submituser=$users1")->setField("submituser",$users2);//合同所属权
        $Diankuan=M("Diankuan")->where("submituser=$users1")->setField("submituser",$users2);//垫款所属权
        $Refund=M("Refund")->where("submituser=$users1")->setField("submituser",$users2);//退款所属权
        $Invoice=M("Invoice")->where("submituser=$users1")->setField("submituser",$users2);//发票所属权
        $RefundInvoice=M("RefundInvoice")->where("submituser=$users1")->setField("submituser",$users2);//退票所属权
        $this->success("转移成功");
        */
    }

    public function kehu(){
        $coustomer=M('Customer');
        $id=I('post.id');
        $list=$coustomer->field('id,advertiser')->where("submituser=$id")->order("ctime desc")->select();
        foreach ($list as $key=>$val)
        {
            $str.='<label class="checkbox-inline"><input class="checkbox_bo" name="kehu[]" type="checkbox" id="inlineCheckbox1" value="'.$val['id'].'">'.$val['advertiser'].'</label>    ';
        }
        echo $str;
    }



}