<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/8
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class AccountController extends CommonController
{
        public function index(){
            $Refund=M("Account");
            //搜索条件
            $type=I('get.searchtype');
            if($type!='')
            {
                if($type=='advertiser')
                {
                    $where.=" and  a.id!='0' and a.appname like '%".I('get.search_text')."%'";
                }
                if ($type == 'gongsi') {
                    //客户表
                    $coustomer = M('Customer');
                    //查询like 搜索文字的客户公司名称
                    $zsql = $coustomer->field("id")->where(" advertiser like '%" . I('get.search_text') . "%'")->select(false);
                    //查询所有 带搜索文字客户公司名称 的合同id,
                    $hetong=M("Contract");
                    $ht_search_text=$hetong->field('id')->where("advertiser in($zsql)")->select(false);
                    //c查询合同id 等于 带搜索文字客户公司名称的合同id
                    $where .= " and  a.id!='0' and a.contract_id in($ht_search_text)";

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
                    $where.=" and a.id!='0' ";
                }
                if($type2=='0')
                {
                    $where.=" and a.audit_1=0 and a.audit_2=0";
                }
                if($type2=='1')
                {
                    $where.=" and a.audit_1=1 and a.audit_2=1";
                }
                $this->type2=$type2;
                $this->ser_txt2=I('get.search_text');

            }
            //从合同列表点击过来
            $contract_id=I('get.contract_id');
            if($contract_id!='')
            {
                $where.=' and contract_id='.$contract_id;
                ////合同编号
                $hetong=M("Contract");
                $hetong_on_name=$hetong->field('a.contract_no,b.advertiser,b.id')->join(" a left join __CUSTOMER__ b on a.advertiser=b.id")->where("a.id =".$contract_id)->find();
                $this->hetong=$hetong_on_name;
                $this->contract_id=$contract_id;
            }

            //权限条件
            $q_where=quan_where(__CONTROLLER__,"a");
            $count      = $Refund->field('a.id')->join("a left join __ACCOUNTTYPE__ b on a.type = b.id ")->where("a.id!='0' and ".$q_where.$where)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$Refund->field('a.id,a.appname,a.endtime,a.type,a.promote_url,a.a_users,a.ctime,a.a_password,a.ip,a.fandian,a.tel,a.contract_id,b.name')->join("a left join __ACCOUNTTYPE__ b on a.type = b.id ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();
            $hetong=M("Contract");

            foreach ($list as $key=>$val)
            {
                $ht=$hetong->field('a.contract_no,b.advertiser')->join(" a left join __CUSTOMER__ b on a.advertiser=b.id")->where("a.id =".$val['contract_id'])->find();
                $list[$key]['advertiser']=$ht['advertiser'];
                $list[$key]['contract_no']=$ht['contract_no'];
            }
            $this->list=$list;

            $this->assign('page',$show);// 赋值分页输出
            $this->display();

    }
    public function add(){
        //代理公司
        $Accounttype=M("Accounttype");
        $this->accounttype=$Accounttype->field("id,name")->order("id asc")->select();
        //从合同列表点击过来
        $contract_id=I('get.contract_id');
        if($contract_id!='')
        {

            ////合同编号
            $hetong=M("Contract");
            $hetong_on_name=$hetong->field('a.contract_no,b.advertiser,b.id')->join(" a left join __CUSTOMER__ b on a.advertiser=b.id")->where("a.id =".$contract_id)->find();
            $this->hetong=$hetong_on_name;
           // dump($hetong_on_name);
            $this->contract_id=$contract_id;
        }


        $this->display();
    }
    public function account_appid(){
        //获取账户列表及APPID
        $accountsem_list=hjd_curl('http://www.yushanapp.com/api/get/customer/c03d80f07c144cdab5e881866b92ad9f');
        if(!is_array($accountsem_list['customers']) or $accountsem_list=='')
        {
            $data['code']=403;
        }
        foreach ($accountsem_list['customers'] as $key=>$val)
        {
            $array_slist[$key]['l_app']=$val['username'];
            $array_slist[$key]['sem']=$val['sem'];
            $array_slist[$key]['appid']=$val['appid'];
            $array_slist[$key]['account']=$val['api_account'];
        }

        //接收的get 账户名
       // $account_name=I('post.account_name');


       $return_data= array_filter($array_slist,function($var){
            $account_name=I('post.account_name');
            if($var['account']==$account_name)
            {
                return true;
            }else{
                return false;
            }

        });

        if(!is_array($return_data) or count($return_data)<1)
        {
            $string=0;
        }else{
            foreach ($return_data as $key=>$val)
            {
                $string.="<option value='".$val['appid']."'>$val[appid]</option>";
            }
        }

        echo $string;


    }

    public function keyup_adlist(){
        $Blog = R('Contract/keyup_adlist');
        echo $Blog;
    }

    //检查账户名称是否被添加过
    public function keyup_isaddusersname(){
        //添加账户的时候 判断这个账户之前是否被添加过
        $a_users=trim(I('post.val'));

        $Refund=M("Account");
        $is_j=$Refund->where("a_users ='$a_users'")->count();

        if($is_j>0)
        {
            $str='<span style="color:#F00">该账户已经存在于某个合同,继续提交将结束之前合同的账户使用权，请谨慎操作</span>';
        }else
        {
            $str='<span style="color:#0F3">该账户可以提交</span>';
        }
        echo $str;
    }

    //检查账户名称是否被添加过
    public function keyup_isaddusersname_up(){
        //添加账户的时候 判断这个账户之前是否被添加过
        $a_users=trim(I('post.val'));
        $thisid=I('post.thisid');
        $Refund=M("Account");
        $is_j=$Refund->where("a_users ='$a_users' and endtime='4092599349' and id !=$thisid")->count();

        if($is_j>0)
        {
            $str='<span style="color:#F00">该账户已经存在于某个合同,继续提交将结束之前合同的账户使用权，请谨慎操作</span>';
        }else
        {
            $str='<span style="color:#0F3">该账户可以提交</span>';
        }
        echo $str;
    }

    public function no_list(){
        $NOLIST=R('Refund/no_list');
        echo $NOLIST;

    }
    function keyup_fzrlist(){
        $val=I('post.val');
        if($val=='')
        {
            return ;
        }
        $Customer=M("Users");
        //权限条件

        $list=$Customer->field("id,name")->where(" name like '%$val%' ")->select();

        foreach ($list as $key=>$val)
        {
            $str.="<li><a id='".$val[id]."'>$val[name]</a></li>";
        }
        echo $str;

    }
    public function addru(){

        $Refund=M("Account");
        $list=$Refund->create();
        $Refund->ctime=time();



        if($insertid=$Refund->add()){



            if($insertid==1)
            {
                $result = $Refund->query("select currval('jd_account_id_seq')");
                $insertid=$result[0][currval];
            }

            //添加账户的时候 判断这个账户之前是否被添加过
            $a_users=I('post.a_users');
            $Refund->where("a_users ='$a_users' and id !=$insertid and endtime='4092599349'")->save(array("endtime"=>time()));

            if(I('post.fzrlist')){
                    //循环联系人并且记录
                    foreach (I('post.fzrlist') as $key => $val)
                    {
                        $contact_list[]=array("account_id"=>$insertid,"u_id"=>I('post.fzrlist')[$key]);
                    }
                    //联系人表
                    $contact=M("AccountUsers");
                    foreach($contact_list as $key=>$val)
                    {
                        $contact->add($contact_list[$key]);
                    }
                   // $contact->addAll($contact_list);
            }



            if(I('post.for_contract')!=1)
            {
                $this->success("添加成功",U("index"));
            }else{
                $this->success("添加成功",U("index?contract_id=".I('post.contract_id')));
            }


        }else
        {
            $this->error("添加失败");
        }


    }
//修改操作
    public  function updata(){
        $id=I('get.id');
        $Refund=M("Account");
        $info=$Refund->find($id);
        $this->info=$info;

        //合同编号
        $hetong=M("Contract");
        $hetong_on_name=$hetong->field('a.contract_no,b.advertiser,b.id')->join(" a left join __CUSTOMER__ b on a.advertiser=b.id")->where("a.id =".$info['contract_id'])->find();
        $this->hetong=$hetong_on_name;
        $biaohao=$hetong->field('id,contract_no,advertiser')->where(" isxufei=0 and advertiser=".$hetong_on_name['id'])->select();
        $this->bianhao=$biaohao;
      //  dump($biaohao);
        $Accounttype=M("Accounttype");
        $this->accounttype=$Accounttype->field("id,name")->order("id asc")->select();
        //从合同列表点击过来
        $contract_id=I('get.contract_id');
        if($contract_id!='')
        {
            $this->contract_id=$contract_id;
        }

        //负责人
        $principal=M("AccountUsers");
        $fzridlist=$principal->field('u_id')->where("account_id = $id")->select(false);
        $userslist=M("Users")->field('id,name')->where("id in ($fzridlist)")->select();
        $this->userslist=$userslist;



        $this->display();

    }
    //修改返回
    public function upru(){
        $id=(int)I('post.id');
        $Refund=M("Account");


        $Refund->create();
        $Refund->ctime=$Refund->ctime+1;
        $Refund->endtime='4092599349'; //默认到期时间为无限
        if($Refund->where("id=$id")->save())
        {
            //修改账户的时候 判断这个账户之前是否被添加过 如果有并且不等于当前id数据 则变更之前的账户有效期为当前时间
            $a_users=I('post.a_users');
            $Refund->where("a_users ='$a_users' and id !=$id and endtime='4092599349'")->save(array("endtime"=>time()));

            if(I('post.fzrlist')){
                //循环联系人并且记录
                foreach (I('post.fzrlist') as $key => $val)
                {
                    $contact_list[]=array("account_id"=>$id,"u_id"=>(int)I('post.fzrlist')[$key]);
                }

                //联系人表
                $contact=M("AccountUsers");
                //删除现在有联系人
                $contact->where("account_id=$id")->delete();
                /*
                if(!$contact->addAll($contact_list))
                {
                    echo $contact->_sql();
                    $this->error('添加负责人失败');
                }
                */
                foreach($contact_list as $key=>$val)
                {
                    $contact->add($contact_list[$key]);
                }
            }
            if(I('post.for_contract')!=1)
            {
                $this->success("修改成功",U("index"));
            }else{
                $this->success("修改成功",U("index?contract_id=".I('post.contract_id')));
            }

        }else{
            $this->error('修改失败');
        }


    }


    public function delete(){
        $id=I('get.id');
        $Refund=M("Account");
        if($Refund->delete($id))
        {
            if(I('get.contract_id')=="")
            {
                $this->success("删除成功",U("index"));
            }else{
                $this->success("删除成功",U("index?contract_id=".I('get.contract_id')));
            }
        }else
        {
            $this->error("删除失败");
        }


    }


    //查看合同
    public function show(){
        $id=I('get.id');
        $Refund=M("Account");
        $info=$Refund->find($id);
        $this->info=$info;

        //销售
        $submitusers=users_info($info[submituser]);
        $this->users_info=$submitusers['name'];


        //合同编号
        $hetong=M("Contract");
        $hetong_on_name=$hetong->field('a.contract_no,b.advertiser,b.id')->join(" a left join __CUSTOMER__ b on a.advertiser=b.id")->where("a.id =".$info['contract_id'])->find();
        $this->hetong=$hetong_on_name;

        //负责人
        $principal=M("AccountUsers");
        $fzridlist=$principal->field('u_id')->where("account_id = $id")->select(false);
        $userslist=M("Users")->field('name')->where("id in ($fzridlist)")->select();
        $this->userslist=$userslist;

        $Accounttype=M("Accounttype");
        $this->accounttype=$Accounttype->field("id,name")->order("id asc")->select();
        $this->display();

    }
    function isfzr(){
        $Customer=M("Users");
        $val=I('get.val');
        $count=$Customer->where("name = '$val'")->count();
        if($count<1)
        {
            echo 0;
        }else
        {
            echo 1;
        }

    }


    public function account_xiaohao()
    {
        $date=date("Y-m-d",strtotime("-1 day"));//今日日期
        echo $date;
        exit;
        $account_counsumption=M("AccountConsumption");
        //缓存每个客户具体消费情况 appid ,日期,消费  获取周消费的时候要调用缓存 所以在这里先生存缓存
        $tabledata = M("accountdaily", "baiduapi_", "pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/msdb");
        $account_day_cost = $tabledata->field('appid,date,baidu_cost_total')->select();
        $count=0;
        foreach ($account_day_cost as $key => $val)
        {
            $data['appid']=$val['appid'];
            $data['starttime']=strtotime($val['date']);
            $data['endtime']=strtotime($val['date'] ."23:59:59");
            $data['baidu_cost_total']=$val['baidu_cost_total'];
            if($account_counsumption->add($data))
            {
                $count++;
            }
        }
        echo "成功载入$count 条记录";

    }

}