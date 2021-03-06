<?php
namespace Admin\Controller;
use Think\Controller;
use Think\Model;

/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/8/16
 * Time: 10:02
 */
class ContractmjController extends CommonController
{
    public function index(){
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();

        $hetong=M("Contract");


        //搜索条件
        $type=I('get.searchtype');
        if($type!='') {
            if ($type == 'advertiser') {
                $coustomer = M('Customer');
                $zsql = $coustomer->field("id")->where(" advertiser like '%" . I('get.search_text') . "%'")->select(false);
                $where .= " and  a.id!='0' and a.advertiser in($zsql)";

            }
            if ($type == 'contract_no') {
                $where .= " and a.id!='0' and a.contract_no like '%" . I('get.search_text') . "%'";
            }
            if ($type == 'appname') {
                $where .= " and a.id!='0' and a.appname like '%" . I('get.search_text') . "%'";
            }
            if ($type == 'submitname')
            {
                $coustomer=M('Users');
                $zsql=$coustomer->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='0' and a.submituser in($zsql)";
            }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');

        }

        //合同类型
        /*
        $httype=I('get.httype');
        if($httype!='')
        {
            if($httype!=3)
            {
                $where.=" and a.type=2  and a.isxufei='0'";
                $this->httype=$httype;
            }else
            {
                $where.=" and a.isxufei='0'";
            }

        }else
        {

            $where.=" and a.type=1  and a.isxufei='0'";
            $this->httype=$httype;
        }
        */

        $where.=" and a.is_meijie=1";

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
        //归档条件
        $type4=I('get.guidang');
        if($type4!='')
        {
            if($type4=='k')
            {
                $where.=" and a.id!='0' ";
            }
            if($type4=='0')
            {
                $where.=" and a.isguidang=0 ";
            }
            if($type4=='1')
            {
                $where.=" and a.isguidang=1 ";
            }
            $this->type4=$type4;

        }

        $type3=I('get.pr_line');
        if($type3!='')
        {
            $where.="and a.product_line =$type3";
            $this->type3=$type3;
        }
        //echo $where;
        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");
        $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where($q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$hetong->field('a.id,a.advertiser as aid,a.contract_no,a.users2,a.isguidang,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where($q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        
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
        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=2 or groupid=15  or groupid=9")->select();
        $this->display();
    }
    public function addru(){
        $hetong=M("Contract");
        $postdate=$hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=time();
        $hetong->users2=cookie('u_id');
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        //检查合同编号是否重复
        $biaohaocon=$hetong->where("contract_no='".I('post.contract_no')."'")->count();
        if($biaohaocon>0)
        {
            $this->error("合同编号重复!");
            exit;
        }
        /*
        if(I('post.payment_type')==2);
        {
            // 映射垫款表
            $dk['d_company']=$postdate['agent_company'];//代理公司
            $dk['d_account_name']=' ';
            $dk['note']=' ';
            $dk['d_money']=$postdate['fk_money'];
            $dk['back_money_time']=strtotime(I('post.back_money_time'));
            $dk['d_time']=strtotime($postdate['payment_time']);
            $dk['advertiser']=$postdate['advertiser'];
            $dk['appname']=$postdate['appname'];
            $dk['contract_no']=$postdate['contract_no'];
            $dk['ctime']=time();
            $dk['submituser']=$postdate['submituser'];
            $dk['ispiao']=I("post.ispiao");
            $dk['state']=0;
            $dk['users2']=cookie('u_id');
        }

        */
        if($hetong->advertiser=='')
        {
            $this->error('提交失败，公司名称不能为空，或您没有按规定操作');
            exit;
        }
         if($insid=$hetong->add()){
             /*
              * 添加合同生成垫款记录，已取消此功能，2016年11月15日（由王秋月建议）。如果要改回 取消模板注释和此注释即可
             if($insid==1)
             {
                 $result = $hetong->query("select currval('jd_contract_id_seq')");
                 $insid=$result[0][currval];
             }

             if(I('post.payment_type')==2)
             {


                 $dk['contract_id']=$insid;
                 //映射垫款  添加
                 $diankuan=M("Diankuan");


                 if($diankuan->add($dk))
                 {
                     $success_str="并生成垫款一条垫款记录";
                 }else
                 {
                     $success_str="但生成垫款记录失败，请联系管理员";
                 }
             }
             */
             $this->success("添加成功".$success_str,U("index"));

         }else
         {
             $this->error("添加失败");
         }

    }
    public function keyup_adlist(){

        $val=I('post.val');
        $Customer=M("Customer");
        //权限条件
        $setype=I("post.setype");

        if($setype=="")
        {
            $q_where=quan_where(__CONTROLLER__);
        }else
        {
            $q_where=quan_where(__CONTROLLER__,$json='',$setype);
        }


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
        $today = strtotime(date('Y-m-d', time()));//获取当天0点

        $max=$hetong->field('contract_no')->where("product_line=$prid and ctime>$today and isxufei=0 ")->order("ctime desc")->find();
        $maxsun=substr($max['contract_no'],-2,2);
        $num=$maxsun+1;

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
        //一级审核人
        $submitusers3=users_info($info[susers1]);
        $this->users_info3=$submitusers3['name'];
        //二级审核人
        $submitusers4=users_info($info[susers2]);
        $this->users_info4=$submitusers4['name'];
        //显示垫付信息
        $diankuan=M("Diankuan");
        $this->dinfo=$diankuan->find($info['contract_id']);

        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=2 or groupid=15 or groupid=9")->select();
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
        //检查合同编号是否重复
        $biaohaocon=$hetong->where("contract_no='".I('post.contract_no')."'")->count();
        if($biaohaocon>1)
        {
            $this->error("合同编号重复!");
            exit;
        }
        //判断合同归档的时候是否已经审核过
        if(I('post.isguidang')==1)
        {
            //
            $het=$hetong->field('audit_1,audit_2')->find($id);
            if($het['audit_1']!=1 or $het['audit_2']!=1)
            {
                $this->error('此合同还未全部通过审核，无法操作归档');
                exit;
            }
        }

        $postdate=$hetong->create();
        if($hetong->advertiser=='')
        {
            $this->error('提交失败，公司名称不能为空，或您没有按规定操作');
            exit;
        }
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->users2=cookie('u_id');
        //如果是垫款
        /*
        if(I('post.payment_type')==2)
        {
            // 映射垫款表
            $dk['d_company']=$postdate['agent_company'];//代理公司
            $dk['d_account_name']=' ';
            $dk['note']=' ';

            $dk['d_money']=$postdate['fk_money'];
            $dk['back_money_time']=strtotime(I('post.back_money_time'));
            $dk['d_time']=strtotime($postdate['payment_time']);
            $dk['advertiser']=$postdate['advertiser'];
            $dk['appname']=$postdate['appname'];

            $dk['submituser']=$postdate['submituser'];
            $dk['ispiao']=I("post.ispiao");
            $dk['state']=0;
            $dk['users2']=cookie('u_id');
        }else
        {
            //删除相应垫款记录
            M("Diankuan")->where("contract_id=$id")->delete();
        }

        //映射垫款  添加
        if(I('post.payment_type')==2){

            $dk['contract_id']=$id;
            $diankuan=M("Diankuan");
            //先检查是修改还是添加
            $cou=$diankuan->where("contract_id=$id")->count();
            if($cou>0)
            {
                if($diankuan->where("contract_id=$id")->save($dk))
                {
                    $success_str="并修改了一条垫款记录";
                }else
                {
                    $success_str="但修改垫款记录失败";
                }
            }else
            {
                $dk['contract_no']=I("post.contract_no");
                $dk['ctime']=time();

                if($diankuan->add($dk))
                {
                    $success_str="并生成垫款一条垫款记录";
                }else
                {
                    $success_str="但生成垫款记录失败，请联系管理员";
                }
            }

        }
        */
        if($hetong->where("id=$id")->save())
        {
            $this->success('修改成功'.$success_str,U('index'));
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
            if(I('get.ju')!=''){
                $shenhe=2;
            }else
            {
                $shenhe=1;
            }
            if($table->where("id=$id")->setField($type,$shenhe))
            {
                //修改审核者
                if($type=='audit_1')
                {
                    $table->where("id=$id")->setField('susers1',cookie('u_id'));
                }
                if($type=='audit_2')
                {
                    $table->where("id=$id")->setField('susers2',cookie('u_id'));
                }
                $this->success('审核成功',U('index'));
            }else
            {
                $this->error('审核失败');
            }
        }
    }

    //操作合同归档
    public function guidang(){
        $id=I('get.id');
        if(!is_numeric($id))
        {
            $this->error('参数类型错误');
        }
        $hetong=M("Contract");
        $hetong->where("id=$id")->setField('isguidang','1');
        $this->success('归档成功');
    }
    //操作合同归档
    public function zuofei(){
        $id=I('get.id');
        if(!is_numeric($id))
        {
            $this->error('参数类型错误');
        }
        $hetong=M("Contract");
        $hetong->where("id=$id")->setField('iszuofei','1');
        $this->success('已将该合同作废');
    }
    //修改合同所属销售
    public function upmarket(){
        $id=I('get.id');
        if(!is_numeric($id))
        {
            $this->error('参数类型错误');
        }
        $hetong=M("Contract");
        $info=$hetong->find($id);
        $this->info=$info;
        $this->id=$id; //合同id
        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=2 or groupid=15  or groupid=9")->select();
        $this->display();
    }
    //修改合同所属销售返回
    public function upmarketru(){
        $hetong=M("Contract");

        $postdate=$hetong->create();
        if($hetong->where("id=$postdate[id]")->setField('market',$postdate['market']))
        {
           echo 1;
        }else
        {
           echo 2;
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
        //一级审核人
        $submitusers3=users_info($info[susers1]);
        $this->users_info3=$submitusers3['name'];
        //二级审核人
        $submitusers4=users_info($info[susers2]);
        $this->users_info4=$submitusers4['name'];
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();

        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //公司名称
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=2")->select();
        $this->display();

    }
    public function excel(){
        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $coustomer=M('Customer');
                $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='0' and a.advertiser in($zsql)";

            }
            if($type=='contract_no')
            {
                $where.=" and a.id!='0' and a.contract_no like '%".I('get.search_text')."%'";
            }
            if($type=='appname')
            {
                $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
            }
            if ($type == 'submitname')
            {
                $coustomer=M('Users');
                $zsql=$coustomer->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='0' and a.submituser in($zsql)";
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
        }else
        {
            $where.=" and a.type=1  and a.isxufei='0'";
            $this->httype=$httype;
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
        //归档条件
        $type4=I('get.guidang');
        if($type4!='')
        {
            if($type4=='k')
            {
                $where.=" and a.id!='0' ";
            }
            if($type4=='0')
            {
                $where.=" and a.isguidang=0 ";
            }
            if($type4=='1')
            {
                $where.=" and a.isguidang=1 ";
            }
            $this->type4=$type4;

        }

        $type3=I('get.pr_line');
        if($type3!='')
        {
            $where.="and a.product_line =$type3";
            $this->type3=$type3;
        }
        $hetong=M("Contract");
        $where.=" and a.is_meijie=1 and iszuofei=0";
        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");

        $list=$hetong->field('a.id,a.advertiser as aid,a.fk_money,a.payment_time,a.agent_company,a.contract_no,a.contract_start,a.contract_end,a.type,a.users2,a.isguidang,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where($q_where.$where)->order("a.ctime desc")->select();

        foreach($list as $key => $val)
        {



            //公司
            $list2[$key]['advertiser']=$val['advertiser'];
            //合同编号
            $list2[$key]['contract_no']=$val['contract_no'];
            //appname
            $list2[$key]['appname']=$val['appname'];
            //合同金额
            $list2[$key]['contract_money']=num_format($val['contract_money']);
            //显示百度币
            $list2[$key]['show_money']=num_format($val['show_money']);
            //付款金额
            $list2[$key]['fk_money']=num_format($val['fk_money']);
            //产品线
            $list2[$key]['product_line']=$val['name'];
            //返点
            $list2[$key]['rebates_proportion']=$val['rebates_proportion'];

            //提交时间
            $list2[$key]['ctime']=date("Y-m-d H:i:s",$val['ctime']);
            //代理公司
            $agentcompany=M("AgentCompany");
            $aagentcompany=$agentcompany->field("companyname")->find($val[agent_company]);
            $list2[$key]['daili']=$aagentcompany['companyname'];
            //合同类型
            $list2[$key]['type']=$val['type']==1?'普通合同':'框架合同';
            //保证金
            $list2[$key]['margin']=$val['margin'];
            //合同开始时间
            $list2[$key]['contract_start']=date("Y-m-d",$val['contract_start']);
            //合同结束时间
            $list2[$key]['contract_end']=date("Y-m-d",$val['contract_end']);
            //付款方式
            $list2[$key]['payment_type']=$val['payment_type']==1?'预付':'垫付';
            //付款时间
            $list2[$key]['payment_time']=$val['payment_time']?date("Y-m-d",$val['payment_time']):'';
            //是否归档
            $list2[$key]['isguidang']=$val['isguidang']==0?'未归档':'已归档';

            //销售
            $submitusers=users_info($val[submituser]);
            $list2[$key]['submitusers2']=$submitusers['name'];

            //提交人
            $uindo=users_info($val['users2']);
            $list2[$key]['submituser']=$uindo[name];

        }


        $filename="hetong_excel";
        $headArr=array("公司","合同编号",'APP名称','合同金额','显示百度币','付款金额','产品线','返点','提交时间','代理公司','合同类型','保证金','合同开始时间','合同结束时间','付款方式','付款时间','是否归档','销售','提交人');
        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }


}