<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/31
 * Time: 9:16
 */

namespace Admin\Controller;
use Think\Controller;

class RenewController extends  CommonController
{
    //属于某合同续费列表
    public function index(){
        //检查该合同是否已经通过审核
        $hetong=M("Contract");
        $info=$hetong->field("a.*,b.advertiser as gongsi,c.name")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.id'))->find();
        if($info[audit_1]!='1' or $info[audit_2]!='1')
        {
            $this->error("该合同还未审核，请完成审核再进行操作");
            exit();
        }
        $this->info=$info;

        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $coustomer=M('Customer');
                $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='hjd2' and a.advertiser in($zsql)";

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
        $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.xf_hetonghao='$info[contract_no]' and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$hetong->field('a.id,a.advertiser as aid,a.users2,a.rebates_proportion,a.contract_no,a.account,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.xf_hetonghao='$info[contract_no]'  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    //属于某合同续费列表
    public function index2(){
        //检查该合同是否已经通过审核
        $hetong=M("Contract");


        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $coustomer=M('Customer');
                $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='hjd2' and a.advertiser in($zsql)";

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
        
        $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.isxufei=1 and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$hetong->field('a.id,a.advertiser as aid,a.users2,a.xf_hetonghao,a.rebates_proportion,a.contract_no,a.account,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.isxufei=1  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        //echo $hetong->_sql();
        $this->list=$list;
        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
        }
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function add(){
        //要续费合同信息
        $hetong=M("Contract");
        $info=$hetong->field("a.*,b.advertiser as gongsi,c.name")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.id'))->find();
        $this->info=$info;
        //产品线列表
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $this->display();

    }
    //该合同续费合同编号第几份
    public function Contract_num(){
        $hetong=M("Contract");
        $advertiser=I('get.advertiser');
        $on=I('get.on');
        $num=$hetong->where("advertiser=$advertiser and xf_hetonghao='$on'")->count();

        $num=$num+1;
        if($num<10)
        {
            $num="0".$num;
        }

        echo $num;
    }

    public function addru(){
        $hetong=M("Contract");
        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=time();
        $hetong->users2=cookie('u_id');

        if($hetong->add()){
            $this->success("添加成功",U("index?id=".I('post.htid')));

        }else
        {
            $this->error("添加失败");
        }

    }
    //修改操作
    public  function updata(){
        //要续费合同信息
        $hetong=M("Contract");
        $info=$hetong->field("a.*,b.advertiser as gongsi,c.name")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.id'))->find();
        $this->info=$info;

        $this->yid=I('get.yid');

        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //公司名称

        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $hetong=M("Contract");
        //检查是否有这个客户

        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->users2=cookie('u_id');
        if($hetong->where("id=$id")->save())
        {
            $this->success('修改成功',U("index?id=".I('post.htid')));
        }else{
            $this->error('修改失败');
        }


    }

    //删除操作
    public function delete(){
        $id=I('get.id');
        $group=M("Contract");
        $yid=I('get.yid');
        if($group->delete($id))
        {
            $this->success("删除成功",U("index?id=$yid"));
        }else
        {
            $this->error("删除失败");
        }
    }
    //审核操作
    public function shenhe(){
        $type=I('get.type');
        $id=I('get.id');
        $yid=I('get.yid');
        //检查是否有权限执行审核操作
        $ispw=shenhe(__CONTROLLER__,$type);
        if($ispw!='200')
        {
            $this->error("您没有权限执行审核操作哦");
        }else
        {
            $table=M("Contract");
            if($table->where("id=$id")->setField($type,1))
            {
                if($yid!='')
                {
                    $this->success('审核成功',U("index?id=$yid"));
                }else
                {
                    $this->success('审核成功',U("index2?shenhe=0"));
                }

            }else
            {
                $this->error('审核失败');
            }
        }
    }
    //查看合同
    public function show(){
        $id=I('get.id');
        $hetong=M("Contract");
        $info=$hetong->find($id);
        $this->info=$info;
        $this->yid=I('get.yid');
        //销售
        $submitusers=users_info($info[submituser]);
        $this->users_info=$submitusers['name'];
        //提交人
        $submitusers2=users_info($info[users2]);
        $this->users_info2=$submitusers2['name'];
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //原合同
        $this->yinfo=$hetong->find(I('get.yid'));

        if(I('get.yid2')){
            $this->yinfo=array("contract_no"=>I('get.yid2'));

        }
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        $this->display();

    }
}