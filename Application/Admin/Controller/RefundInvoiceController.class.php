<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/1
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class RefundInvoiceController extends CommonController
{
        public function index(){
            $Refund=M("RefundInvoice");
            //搜索条件
            $type=I('get.searchtype');
            if($type!='')
            {
                if($type=='advertiser')
                {
                    $coustomer=M('Customer');
                    $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                    $where.=" and  a.id!='0' and a.invoice_head in($zsql)";

                }
                if($type=='contract_no')
                {
                    $where.=" and a.id!='0' and a.contract_no like '%".I('get.search_text')."%'";
                }
                if($type=='appname')
                {
                    $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
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
                $time_start=strtotime("-1 days",$time_start);
                $time_end=strtotime($time_end);
                $time_end=strtotime("+1 days",$time_end);

                $where.=" and a.ctime >= $time_start and a.ctime <= $time_end";
                $this->time_start=I('get.time_start');
                $this->time_end=I('get.time_end');
            }
            //审核条件
            $type2=I('get.shenhe');
            if($type2!='')
            {
                if($type2=='k')
                {
                    $where.=" and a.id!='0' ";
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
            $count      = $Refund->field('a.id,a.invoice_head,a.contract_no,a.money,a.ctime,a.audit_1,a.audit_2,b.advertiser')->join("a left join __CUSTOMER__ b on a.invoice_head = b.id ")->where("a.id!='0' and ".$q_where.$where)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$Refund->field('a.id,a.invoice_head as aid,a.users2,a.appname,a.invoice_head,a.contract_no,a.money,a.ctime,a.audit_1,a.audit_2,b.advertiser')->join("a left join __CUSTOMER__ b on a.invoice_head = b.id ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();
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
    public function add(){
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();

        $this->display();
    }
    //输入公司名称
    public function keyup_adlist(){
        $Blog = R('Contract/keyup_adlist');
        echo $Blog;
    }
    //匹配公司发票信息
    public function fp_info(){
        $id=I('get.id');
        $Customer=M('Customer')->find($id);
        $this->ajaxReturn($Customer);
    }
    //匹配发票类型
    public function fptype(){
        $str=R("Invoice/fptype");

        echo $str;
    }
    //匹配发票编号
    public function no_list(){
        $NOLIST=R('Refund/no_list');
        echo $NOLIST;

    }
    //添加返回
    public function addru(){

        $Refund=M("RefundInvoice");
        $Refund->create();
        $Refund->ctime=time();
        $Refund->kp_time=strtotime($Refund->kp_time);
        $Refund->users2=cookie('u_id');
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        if($Refund->invoice_head=='')
        {
            $this->error('提交失败，公司名称不能为空，或您没有按规定操作');
            exit;
        }
        if($Refund->add()){
            $this->success("申请成功",U("index"));

        }else
        {
            $this->error("提交失败");
        }


    }
//修改操作
    public  function updata(){
        $id=I('get.id');
        $Refund=M("RefundInvoice");
        $info=$Refund->find($id);
        $this->info=$info;

        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //开票类型
        $p=M("piaotype");
        $id=I("post.id");
        $this->list=$p->where("advertiser = $info[main_company]")->select();

        //公司名称
        $gs=kehu($info[invoice_head]);
        $this->gongsi=$gs[advertiser];
        $this->kaipinfo=$gs;
        //一级审核人
        $submitusers3=users_info($info[susers1]);
        $this->users_info3=$submitusers3['name'];
        //二级审核人
        $submitusers4=users_info($info[susers2]);
        $this->users_info4=$submitusers4['name'];
        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $Refund=M("RefundInvoice");
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();

        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        $Refund->create();
        $Refund->kp_time=strtotime($Refund->kp_time);
        $Refund->users2=cookie('u_id');
        if($Refund->where("id=$id")->save())
        {
            $this->success('修改成功',U('index'));
        }else{
            $this->error('修改失败');
        }


    }


    public function delete(){
        $id=I('get.id');
        $Refund=M("RefundInvoice");
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
            $table=M("RefundInvoice");
            if(I('get.ju')!=''){
                $shenhe=2;
            }else
            {
                $shenhe=1;
            }
            if($table->where("id=$id")->setField($type,$shenhe))
            {
                $this->success('审核成功',U('index'));
                //修改审核者
                if($type=='audit_1')
                {
                    $table->where("id=$id")->setField('susers1',cookie('u_id'));
                }
                if($type=='audit_2')
                {
                    $table->where("id=$id")->setField('susers2',cookie('u_id'));
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
        $Refund=M("RefundInvoice");
        $info=$Refund->find($id);
        $this->info=$info;
        //销售
        $submitusers=users_info($info[submituser]);
        $this->users_info=$submitusers['name'];
        //提交人
        $submitusers2=users_info($info[users2]);
        $this->users_info2=$submitusers2['name'];
        //一级审核人
        $submitusers3=users_info($info[susers1]);
        $this->users_info3=$submitusers3['name'];
        //二级审核人
        $submitusers4=users_info($info[susers2]);
        $this->users_info4=$submitusers4['name'];
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //开票类型
        $p=M("piaotype");
        $id=I("post.id");
        $this->list=$p->where("advertiser = $info[main_company]")->select();
        //公司名称
        $gs=kehu($info[invoice_head]);
        $this->gongsi=$gs[advertiser];
        $this->kaipinfo=$gs;

        $this->display();

    }


    public function excel(){
        $Refund=M("RefundInvoice");
        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $coustomer=M('Customer');
                $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='0' and a.invoice_head in($zsql)";

            }
            if($type=='contract_no')
            {
                $where.=" and a.id!='0' and a.contract_no like '%".I('get.search_text')."%'";
            }
            if($type=='appname')
            {
                $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
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
            $time_start=strtotime("-1 days",$time_start);
            $time_end=strtotime($time_end);
            $time_end=strtotime("+1 days",$time_end);

            $where.=" and a.ctime >= $time_start and a.ctime <= $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }
        //审核条件
        $type2=I('get.shenhe');
        if($type2!='')
        {
            if($type2=='k')
            {
                $where.=" and a.id!='0' ";
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

        $list=$Refund->field('a.id,a.invoice_head as aid,a.users2,a.appname,a.submituser,a.is_kuayue,a.is_renzheng,a.invoice_head,a.contract_no,a.money,a.ctime,a.audit_1,a.audit_2,b.advertiser')->join("a left join __CUSTOMER__ b on a.invoice_head = b.id ")->where("a.id!='0' and ".$q_where.$where)->order("a.ctime desc")->select();

        //主体公司
        $agentcompany=M("AgentCompany");
        foreach($list as $key => $val)
        {
            //发票抬头
            $list2[$key]['advertiser']=$val['advertiser'];
            //开票主体
            $zhuti=$agentcompany->field("id,companyname,title")->find($val['main_company']);
            $list2[$key]['companyname']=$zhuti['companyname'];
            //合同编号
            $list2[$key]['contract_no']=$val['contract_no'];
            //appname
            $list2[$key]['appname']=$val['appname'];
            //退票金额
            $list2[$key]['money']=num_format($val['money']);
            //开票类型
            $list2[$key]['type2']=$val['type2']==1?'专票':'普票';
            //税目
            $p=M("piaotype");
            $shuimu=$p->field('name')->find($val['type']);
            $list2[$key]['shuimu']=$shuimu['name'];
            //是否回款
            $list2[$key]['state']=$val['state']==0?'未回款':'已回款';
            //发票号
            $list2[$key]['fp_on']=$val['fp_on'];
            //开票时间
            $list2[$key]['kp_time']=$val['kp_time']?date("Y-m-d H:i:s",$val['kp_time']):'';

            //是否夸月
            $list2[$key]['is_kuayue']=$val['is_kuayue']==0?'本月':'跨月';

            //是否认证
            $list2[$key]['is_renzheng']=$val['is_renzheng']==0?'未认证':'已认证';

            //提交时间
            $list2[$key]['ctime']=date("Y-m-d H:i:s",$val['ctime']);



            //销售
            $submitusers=users_info($val[submituser]);
            $list2[$key]['submitusers2']=$submitusers['name'];

            //提交人
            $uindo=users_info($val['users2']);
            $list2[$key]['submituser']=$uindo[name];
        }

        $filename="tuipiao_excel";
        $headArr=array("发票抬头",'开票主体',"合同编号",'APP名称','退票金额','开票类型','税目','是否回款','发票号','开票时间','是否跨月','是否认证','提交时间','销售','提交人');
        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }
}