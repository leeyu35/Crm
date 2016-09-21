<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/8/16
 * Time: 10:02
 */
class ContractController extends CommonController
{
    public function index(){
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();

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
        //合同类型
        $httype=I('get.httype');
        if($httype!='')
        {
            $where.=" and a.type=2 ";
            $this->httype=$httype;
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

        $type3=I('get.pr_line');
        if($type3!='')
        {
            $where.="and a.product_line =$type3";
            $this->type3=$type3;
        }
        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");
        $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.isxufei='0' and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$hetong->field('a.id,a.advertiser as aid,a.contract_no,a.users2,a.isguidang,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.isxufei='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
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
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $this->display();
    }
    public function addru(){
        $hetong=M("Contract");
        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=time();
        $hetong->users2=session('u_id');
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }

         if($hetong->add()){
             $this->success("添加成功",U("index"));

         }else
         {
             $this->error("添加失败");
         }

    }
    public function keyup_adlist(){

        $val=I('post.val');
        $Customer=M("Customer");
        //权限条件
        $q_where=quan_where(__CONTROLLER__);

        $list=$Customer->field("id,advertiser,submituser")->where("id!=0 and advertiser like '%$val%' and ".$q_where)->select();

        foreach ($list as $key=>$val)
        {
            $str.="<li><a id='".$val[id]."' title='".$val[submituser]."'>$val[advertiser]</a></li>";
        }
        echo $str;
      //  echo "<li><a id="">$val</a></li>";


    }

    public function Contract_num(){
        $hetong=M("Contract");
        $advertiser=I('get.advertiser');
        $prid=I('get.prid');
        $num=$hetong->where("advertiser=$advertiser and product_line=$prid")->count();

        $num=$num+1;
        if($num<10)
        {
            $num="0".$num;
        }

        echo $num;
    }
    //修改操作
    public  function updata(){
        $id=I('get.id');
        $hetong=M("Contract");
        $info=$hetong->find($id);
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
        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $hetong=M("Contract");
        //检查是否有这个客户
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->users2=session('u_id');
        if($hetong->where("id=$id")->save())
        {
            $this->success('修改成功',U('index'));
        }else{
            $this->error('修改失败');
        }


    }

    public function delete(){
    $id=I('get.id');
    $group=M("Contract");
    if($group->delete($id))
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
            $table=M("Contract");
            if($table->where("id=$id")->setField($type,1))
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
        $hetong=M("Contract");
        $info=$hetong->find($id);
        $this->info=$info;
        //销售
        $submitusers=users_info($info[submituser]);
        $this->users_info=$submitusers['name'];
        //提交人
        $submitusers2=users_info($info[users2]);
        $this->users_info2=$submitusers2['name'];
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
}