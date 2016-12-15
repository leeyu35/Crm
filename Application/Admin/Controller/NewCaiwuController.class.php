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
        $list=$coustomer->field('id,advertiser,industry,website,product_line,ctime,city,appName,submituser')->where("id!=0 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime desc')->select();

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
        $list=$hetong->field('a.id,a.advertiser as aid,a.contract_no,a.users2,a.isguidang,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->where("a.advertiser =$id and isxufei=0")->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->order("a.ctime desc")->select();
        $this->list=$list;

        $this->display();
        dump($list);
    }

    public function history(){
        $contract_id=I('get.contract_id');
        //续费的记录 1预付 2垫付
        $hetong=M("contract");
        $xflist=$hetong->field('fk_money,payment_time,payment_type')->where("xf_contractid=$contract_id")->order("payment_time asc,id desc")->select();
        $yue=0;
        foreach ($xflist as $key=>$val)
        {
            if($val[payment_type]==1)
            {
                $history[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"付款".$val['fk_money'],"yue"=>$yue+=$val['fk_money']);

            }elseif($val[payment_type]==2)
            {
                $history[]=array("date"=>date("Y-m-d",$val[payment_time]),"mes"=>"垫款".$val['fk_money'],"yue"=>$yue-=$val['fk_money']);

            }
        }

        //回款
        $backmoney=M("Back_money");
        $bkm_list=$backmoney->field('b_money,b_time')->where("contract_id=$contract_id")->select();
        foreach ($bkm_list as $key=>$val)
        {
            $history[]=array("date"=>date("Y-m-d",$val[b_time]),"mes"=>"回款".$val['b_money'],"yue"=>$yue+=$val['b_money']);
        }

        uasort($history,function ($a,$b){
            if($a['date']>$b['date'])
            {
                return -1;
            }elseif($a['date']<$b['date'])
            {
                return 1;
            }elseif($a['date']=$b['date'])
            {
                return 0;
         });
        // dump($history);
        $this->yue=$yue;
        $this->history=$history;
       $this->display();
    }
}