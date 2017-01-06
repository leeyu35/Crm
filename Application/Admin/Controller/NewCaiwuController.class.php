<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/12/14
 * Time: 13:51
 */
namespace Admin\Controller;
use Think\Controller;



class NewCaiwuController extends CommonController
{
    public function index(){
        $coustomer=M('Customer');
        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $where=" and advertiser like '%".I('get.search_text')."%'";
            }
            if($type=='name' or $type=='tel')
            {
                //联系人
                $contact=M('ContactList');
                $se_idlist=$contact->where("$type like '%".I('get.search_text')."%'")->field("customer_id")->select(false);

                $where=" and id in($se_idlist)";
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

            $where.=" and ctime > $time_start and ctime < $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }
        //权限条件
        $q_where=quan_where(__CONTROLLER__);

        $count      = $coustomer->where("id!=0 and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$coustomer->field('id,advertiser,yu_e,huikuan,industry,website,product_line,ctime,city,appName,submituser,type')->where("id!=0 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime desc')->select();

        $contact=M('ContactList');

        foreach($list as $key => $val)
        {
            //产品线
            // $lin=product_line($val['product_line']);
            // $list[$key]['product_lin']=$lin;
            //取第一位联系人
            $contact_one=$contact->field('name,tel')->where("customer_id=$val[id]")->find();
            $list[$key]['contact']=$contact_one['name'];
            $list[$key]['tel']=$contact_one['tel'];
            $list[$key]['yue']=$val['huikuan']-$val['yu_e'];
            //提交人
            $uindo=users_info($val['submituser']);
            $list[$key]['submituser']=$uindo[name];
        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function show()
    {
       $id=I('get.id');
       $hetong=M("contract");
       // $list=$hetong->where("advertiser =$id and isxufei=0")->select();
        $list=$hetong->field('a.id,a.advertiser as aid,a.audit_1,a.audit_2,a.contract_no,a.market,a.users2,a.isguidang,a.iszuofei,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name,a.yu_e,a.huikuan,a.invoice,a.bukuan,a.type')->where("a.advertiser =$id and isxufei=0")->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->order("a.ctime desc")->select();
        foreach ($list as $key=>$val)
        {
            //$zong+=$this->yue($val[id]);
            $list[$key]['yue']=$val['huikuan']-$val['yu_e'];
            //总发票
            $zongfapiao+=$val['invoice'];
            if($val[market]!='')
            {
                $userslist=M("Users")->field('name')->find($val[market]);
            }



            $list[$key]['xiaoshou']=$userslist['name'];
        }

        $this->list=$list;


        //客户详细信息
        $customer_info=kehu($id);
        $this->customer_info=$customer_info;
        $this->zong=$customer_info[huikuan]-$customer_info[yu_e];
        $this->zongfapiao=$zongfapiao;
        $this->display();
       // dump($list);
    }

    public function history()
    {
        $contract_id = I('get.contract_id');
        $ht_on = M("Contract")->field("contract_no,yu_e,bukuan,huikuan,invoice,id,advertiser")->find($contract_id);
        $type = I('get.type');

        if($type=='renew')
        {
            $renewwhere=' and (payment_type !=14 and payment_type !=15)';
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

            $xf_where.=" and payment_time > $time_start and payment_time < $time_end";
            $fp_where.=" and ctime > $time_start and ctime < $time_end";



            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }

        //续费的记录 1预付 2垫付
        $hetong=M("RenewHuikuan");
        $xflist=$hetong->field('money,payment_time,payment_type,account,audit_1,audit_2,audit_3,type,users2')->where("xf_contractid=$contract_id and is_huikuan=0 $renewwhere $xf_where")->order("payment_time asc,id desc")->select();

        $yue=0;
        $bukuan=0;
        $account=M("Account");

        foreach ($xflist as $key=>$val)
        {
            //账户名称
            $account_name=$account->field('a_users')->find($val['account']);
            $account_str="<p>账户名称：$account_name[a_users]</p>";
            //审核状态
            if(($val[audit_1]==0 or $val[audit_1]==1) and ($val[audit_2]==0 or $val[audit_2]==1))
            {
                $yue_xf+=$val['money'];
            }

            if($val[payment_type]==1)
            {
                //续费预付
                $history_xf[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"续费 付款".num_format($val['money']).$account_str,"yue"=>$yue-=$val['money'],"bukuan"=>$bukuan+=0,"audit_1"=>$val['audit_1'],"audit_2"=>$val['audit_2'],"type"=>'续费',"submitusers"=>$val['users2'],"money"=>"-".$val['money'],"audit_3"=>$val['audit_3']);
            }elseif($val[payment_type]==2)
            {
                //续费垫付
                $history_xf[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"续费 垫款".num_format($val['money']).$account_str,"yue"=>$yue-=$val['money'],"bukuan"=>$bukuan+=0,"audit_1"=>$val['audit_1'],"audit_2"=>$val['audit_2'],"type"=>'续费',"submitusers"=>$val['users2'],"money"=>"-".$val['money'],"audit_3"=>$val['audit_3']);
            }elseif($val[payment_type]==3)
            {
                ////续费补款
                $history_xf[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"续费 补款".num_format($val['money']).$account_str,"yue"=>$yue-=0,"bukuan"=>$bukuan+=$val['money'],"audit_1"=>$val['audit_1'],"audit_2"=>$val['audit_2'],"type"=>'补款',"submitusers"=>$val['users2'],"money"=>$val['money'],"audit_3"=>$val['audit_3']);

            }elseif($val[payment_type]==14)
            {
                //续费 退款
                $history_xf[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"退款到客户".num_format($val['money']).$account_str,"yue"=>$yue-=0,"bukuan"=>$bukuan+=0,"audit_1"=>$val['audit_1'],"audit_2"=>$val['audit_2'],"type"=>'退款',"submitusers"=>$val['users2'],"money"=>"-".$val['money']);
            }elseif($val[payment_type]==15)
            {
                //续费 转款
                $history_xf[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"退款到总账户".num_format($val['money']).$account_str,"yue"=>$yue-=0,"bukuan"=>$bukuan+=0,"audit_1"=>$val['audit_1'],"audit_2"=>$val['audit_2'],"type"=>'退款',"submitusers"=>$val['users2'],"money"=>"+".$val['money']);
            }
        }

        //回款
        $backmoney=M("RenewHuikuan");
        $bkm_list=$hetong->field('money,payment_time,payment_type,account,audit_1,audit_2,users2')->where("xf_contractid=$contract_id and is_huikuan=1 $xf_where")->order("payment_time asc,id desc")->select();

        foreach ($bkm_list as $key=>$val)
        {
            if(($val[audit_1]==0 or $val[audit_1]==1) and ($val[audit_2]==0 or $val[audit_2]==1))
            {
                $yue_hk+=$val['money'];
            }
            $history_hk[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"回款".num_format($val['money'])."<p></p>","yue"=>$yue,"audit_1"=>$val['audit_1'],"audit_2"=>$val['audit_2'],"type"=>'回款',"submitusers"=>$val['users2'],"money"=>"+".$val['money']);
        }

        //发票
        $Invoice=M("Invoice");
        $fplist=$Invoice->field('kp_time,money,audit_1,audit_2,ctime,fp_on,users2')->where("contract_no='".$ht_on['contract_no']."' $fp_where")->order("ctime desc")->select();

        //echo $Invoice->_sql();
        foreach ($fplist as $key=>$val)
        {
            if(($val[audit_1]==0 or $val[audit_1]==1) and ($val[audit_2]==0 or $val[audit_2]==1))
            {
                $yue_fp+=$val['money'];
            }
            $kptime=$val[kp_time]!=''?date("Y-m-d",$val[kp_time]):'暂无';
            $kptime.=$val[fp_on]!=''?'&nbsp&nbsp&nbsp&nbsp发票号:'.$val[fp_on]:'&nbsp&nbsp&nbsp&nbsp发票号：暂无';
            $history_fp[]=array("date"=>date("Y-m-d",$val[ctime]),"mes"=>"开票".num_format($val['money'])."<p>开票时间：".$kptime."</p>","yue"=>$yue+=0,"audit_1"=>$val['audit_1'],"audit_2"=>$val['audit_2'],"type"=>'发票',"submitusers"=>$val['users2'],"money"=>$val['money']);
        }

        //根据type给出筛选数据
        if ($type == 'renew')
        {
            $history=$history_xf;
            $this->sxyue=$yue_xf;
        }elseif($type=='huikuan')
        {
            $history=$history_hk;
            $this->sxyue=$yue_hk;
        }elseif($type=='invoice')
        {
            $history=$history_fp;
            $this->sxyue=$yue_fp;
        }
        else{
            if(empty($history_xf)){$history_xf=array();}
            if(empty($history_hk)){$history_hk=array();}
            if(empty($history_fp)){$history_fp=array();}
            $history=array_merge($history_xf,$history_hk,$history_fp);

        }
        foreach($history as $key => $val) {
            //提交人
            $uindo = users_info($val['submitusers']);
            $history[$key]['submituser'] = $uindo[name];
        }

        uasort($history,function ($a,$b){
            if($a['date']>$b['date'])
            {
                return -1;
            }elseif($a['date']<$b['date'])
            {
                return 1;
            }elseif($a['date']==$b['date'])
            {
                return 0;
            }
        });


        $this->yue=$ht_on['huikuan']-$ht_on['yu_e'];
        $this->bukuan=$ht_on['bukuan'];
        $this->invoice=$ht_on['invoice'];
        $this->contract_id=$ht_on['id'];
        $this->history=$history;

        //客户详细信息
        $customer_info=kehu($ht_on['advertiser']);
        $this->customer_info=$customer_info;

       $this->display();
    }

    //参数 合同ID
    public function yue($id){
        $contract_id=$id;
        //续费的记录 1预付 2垫付
        $hetong=M("contract");
        $xflist=$hetong->field('fk_money,payment_time,payment_type')->where("xf_contractid=$contract_id")->order("payment_time asc,id desc")->select();
        $yue=0;
        foreach ($xflist as $key=>$val)
        {
            if($val[payment_type]==1)
            {
                $yue-=$val['fk_money'];
                //$history[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"续费 付款".num_format($val['fk_money']),"yue"=>$yue+=$val['fk_money']);
            }elseif($val[payment_type]==2)
            {
                $yue-=$val['fk_money'];
                //$history[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"续费 垫款".num_format($val['fk_money']),"yue"=>$yue-=$val['fk_money']);
            }
        }

        //回款
        $backmoney=M("Back_money");
        $bkm_list=$backmoney->field('b_money,b_time')->where("contract_id=$contract_id")->select();
        foreach ($bkm_list as $key=>$val)
        {
            $yue+=$val['b_money'];
            //$history[]=array("date"=>date("Y-m-d",$val[b_time]),"mes"=>"回款".num_format($val['b_money']),"yue"=>$yue+=$val['b_money']);
        }
        return $yue;
    }
}