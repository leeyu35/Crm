<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/1
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class RefundController extends CommonController
{
        public function index(){
            $Refund=M("Refund");
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
                if($type=='appname')
                {
                    $where.=" and a.id!='hjd3' and a.appname like '%".I('get.search_text')."%'";
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
            $count      = $Refund->field('a.id,a.advertiser,a.contract_no,a.r_money,a.r_time,a.ctime,a.audit_1,a.audit_2,b.advertiser')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$Refund->field('a.id,a.users2,a.advertiser as aid,a.advertiser,a.appname,a.contract_no,a.r_money,a.r_time,a.ctime,a.audit_1,a.audit_2,b.advertiser')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();
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

    public function keyup_adlist(){
        $Blog = R('Contract/keyup_adlist');
        echo $Blog;
    }

    public function no_list(){

        $id=I('post.id');
        $Contract=M("Contract");
        //权限条件
        $q_where=quan_where(__CONTROLLER__);

        $list=$Contract->field("id,advertiser,submituser,contract_no")->where("advertiser = '$id' and isxufei=0 and ".$q_where)->select();
        $str.='<option value="">--选择合同编号--</option>';
        foreach ($list as $key=>$val)
        {
            $str.="<option id='".$val[id]."' title='".$val[submituser]."' value='".$val[contract_no]."'>$val[contract_no]</></option>";
        }
        echo $str;
        //  echo "<li><a id="">$val</a></li>";


    }

    public function addru(){

        $Refund=M("Refund");
        $Refund->create();
        $Refund->r_time=strtotime($Refund->r_time);
        $Refund->ctime=time();
        $Refund->users2=cookie('u_id');
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        if($Refund->advertiser=='')
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
        $Refund=M("Refund");
        $info=$Refund->find($id);
        $this->info=$info;

        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //公司名称
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
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
        $Refund=M("Refund");
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        $Refund->create();
        $Refund->r_time=strtotime($Refund->r_time);
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
        $Refund=M("Refund");
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
            $table=M("Refund");
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
        $Refund=M("Refund");
        $info=$Refund->find($id);
        $this->info=$info;
        //所属销售
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

        //公司名称
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        $this->display();

    }

    public function excel(){
        $Refund=M("Refund");
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
            if($type=='appname')
            {
                $where.=" and a.id!='hjd3' and a.appname like '%".I('get.search_text')."%'";
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
        $list=$Refund->field('a.id,a.users2,a.advertiser as aid,a.advertiser,a.r_company,a.r_open_account,a.r_account,a.baiduhu,a.baidubi,a.submituser,a.r_company,a.appname,a.contract_no,a.r_money,a.r_time,a.ctime,a.audit_1,a.audit_2,b.advertiser')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0' and ".$q_where.$where)->order("a.ctime desc")->select();


        //主体公司
        $agentcompany=M("AgentCompany");
        foreach($list as $key => $val)
        {
            //公司
            $list2[$key]['advertiser']=$val['advertiser'];
            //退款主体
            $zhuti=$agentcompany->field("id,companyname,title")->find($val['r_company']);
            $list2[$key]['companyname']=$zhuti['r_company'];
            //合同编号
            $list2[$key]['contract_no']=$val['contract_no'];
            //appname
            $list2[$key]['appname']=$val['appname'];
            //退款金额
            $list2[$key]['r_money']=num_format($val['r_money']);
            //退款开户行
            $list2[$key]['r_open_account']=$val['r_open_account'];
            //退款开户账号
            $list2[$key]['r_account']=$val['r_account'];
            //百度账户
            $list2[$key]['baiduhu']=$val['baiduhu'];
            //百度币
            $list2[$key]['baidubi']=$val['baidubi'];


            //是否开票
            $list2[$key]['ispiao']=$val['ispiao']==0?'未开票':'已开票';
            //提交时间
            $list2[$key]['ctime']=date("Y-m-d H:i:s",$val['ctime']);

            //退款日期
            $list2[$key]['r_time']=date("Y-m-d",$val['r_time']);


            //销售
            $submitusers=users_info($val[submituser]);
            $list2[$key]['submitusers2']=$submitusers['name'];

            //提交人
            $uindo=users_info($val['users2']);
            $list2[$key]['submituser']=$uindo[name];
        }

        $filename="tuikuan_excel";
        $headArr=array("公司",'退款主体',"合同编号",'APP名称','退款金额','退款开户行','退款开户账号','百度账户','百度币','是否开票','提交时间','退款日期','销售','提交人');
        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }
}