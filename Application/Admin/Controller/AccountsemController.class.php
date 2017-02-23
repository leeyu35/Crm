<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/9/8
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class AccountsemController extends CommonController
{

    public function account_info($acconut_u){
        $account=M("Account");
        $hetong=M("Contract");
        // $data=$account->field('')->where('a_users='.$acconut_u)->select();
        $list=$account->field('a.id,a.appname,a.type,a.contract_id,b.name')->join("a left join __ACCOUNTTYPE__ b on a.type = b.id ")->where("a.a_users like '%".$acconut_u."%'")->order("a.ctime desc")->select();

        if(is_array($list[0])){


        foreach ($list as $key=>$val)
        {
            $ht=$hetong->field('a.contract_no,a.market,b.advertiser')->join(" a left join __CUSTOMER__ b on a.advertiser=b.id")->where("a.id =".$val['contract_id'])->find();
            $list[$key]['advertiser']=$ht['advertiser'];
            $list[$key]['contract_no']=$ht['contract_no'];
            $list[$key]['market']=$ht['market'];
        }}else
        {

            $list[0]['advertiser']='暂无数据';
            $list[0]['contract_no']='暂无数据';
            $list[0]['market']='暂无数据';
        }

        return $list;
    }
    public function index(){
        $dataarr=S('account_data');

        if(I('get.to')!='')
        {
            $to=I('get.to');
        }else
        {
            $to=8;
        }
        $this->to=$to;

        //dump($dataarr);
        //$a=array("a"=>"red","b"=>"green","c"=>"blue","sem"=>"李金茹","data"=>array('123'));


        foreach ($dataarr as $key=>$val)
        {
            //echo $val['account'].'========'.$val["l_app"];
            $list=$this->account_info($val['account']);

            if(!is_array($list))
            {
                $dataarr[$key]['advertiser']='暂无数据';
                $dataarr[$key]['type']='暂无数据';
            }else{
                foreach ($list as $k=>$v)
                {
                    $dataarr[$key]['advertiser']=$v['advertiser']?$v['advertiser']:'暂无数据';
                    $dataarr[$key]['type']=$v['name']?$v['name']:'暂无数据';
                    $dataarr[$key]['market']=$v['market']?$v['market']:'暂无数据';
                }
            }
        }

        //搜索条件
        $type=I('get.searchtype');

        if(!empty($type) and I('get.search_text')!='')
        {
            unset($serarray);
            if($type=='advertiser')
            {
                foreach ($dataarr as $key=>$val)
                {

                    if(strpos($val['l_app'],I('get.search_text'))!==false)
                    {
                        $serarray[]=$dataarr[$key];
                    }
                }
            }
            if ($type=='gongsi') {
                foreach ($dataarr as $key=>$val)
                {

                    if(strpos($val['advertiser'],I('get.search_text'))!==false)
                    {
                        $serarray[]=$dataarr[$key];
                    }
                }
            }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');
            $dataarr=$serarray;
        }
        $srot=I("sort");

        //排序
        if($srot!='')
        {
            uasort($dataarr,function($a,$b){
                $srot=I("sort");
                if($a['data'][$srot]['cost']>$b['data'][$srot]['cost'])
                {
                    return -1;
                }elseif($a['data'][$srot]['cost']<$b['data'][$srot]['cost'])
                {
                    return 1;
                }elseif($a['data'][$srot]['cost']==$b['data'][$srot]['cost'])
                {
                    return 0;
                }


            });


        }


        $this->list=$dataarr;

        $this->display();




    }

    public function  index2(){

        //echo date("Y-m-d","4092599349");
        $account=M("Account");
        $hetong=M("Contract");
        // $data=$account->field('')->where('a_users='.$acconut_u)->select();
        $zhouar=teodate_week(1,"Monday"); //获取周日期的开始时间和结束时间
        $yuear=teodate_month();
        $zuori=Yesterday();

        $list=$account->field('a.id,a.appname,a.a_users,a.type,a.contract_id,b.name,a.appid,a.contract_id')->join("a left join __ACCOUNTTYPE__ b on a.type = b.id ")->where("appid !=''")->order("a.ctime desc")->select();
        //负责人
        $principal=M("AccountUsers");
        foreach ($list as $key=>$val)
        {
            $fzridlist=$principal->field('u_id')->where("account_id = $val[id]")->select(false);
            $userslist=M("Users")->field('name,id as uid')->where("id in ($fzridlist)")->find();

            $list[$key]['sem']=$userslist['name'];//sem姓名
            $list[$key]['semid']=$userslist['uid'];//semid
            $list[$key]['week_counsumption']=$this->AccountConsumption($val[appid],$zhouar[0]['start'],$zhouar[0]['end'],$val['contract_id']);
            $list[$key]['month_counsumption']=$this->AccountConsumption($val[appid],$yuear['start'],$yuear['end'],$val['contract_id']);
            $list[$key]['zuori_counsumption']=$this->AccountConsumption($val[appid],$zuori['start'],$zuori['end'],$val['contract_id']);
            $list[$key]['week_zhanxian']=$this->Account_zhanxian($val[appid],$zhouar[0]['start'],$zhouar[0]['end'],$val['contract_id']);
            $list[$key]['month_zhanxian']=$this->Account_zhanxian($val[appid],$yuear['start'],$yuear['end'],$val['contract_id']);
            $list[$key]['zuori_zhanxian']=$this->Account_zhanxian($val[appid],$zuori['start'],$zuori['end'],$val['contract_id']);
            $list[$key]['week_dianji']=$this->Account_dianji($val[appid],$zhouar[0]['start'],$zhouar[0]['end'],$val['contract_id']);
            $list[$key]['month_dianji']=$this->Account_dianji($val[appid],$yuear['start'],$yuear['end'],$val['contract_id']);
            $list[$key]['zuori_dianji']=$this->Account_dianji($val[appid],$zuori['start'],$zuori['end'],$val['contract_id']);
        }


        dump($list);


    }

    // 根据appid 合同id  统计 消耗 条件(开始时间 结束时间)
    public function AccountConsumption($appid,$starttime,$endtime,$account_ht_id){
        $account_counsumption=M("AccountConsumption");
        $time_start=strtotime($starttime);
       // $time_start=strtotime("-1 days",$time_start);
        $time_end=strtotime($endtime."+1 day");
        //$time_end=strtotime("+1 days",$time_end);
        $sum=$account_counsumption->where("appid='$appid' and starttime>='$time_start'  and starttime<'$time_end' and htid='$account_ht_id'")->sum("baidu_cost_total");
        //echo $account_counsumption->_sql()."<br>";
        return $sum;
    }
    // 根据appid 合同id  统计 展现 条件(开始时间 结束时间)
    public function Account_zhanxian($appid,$starttime,$endtime,$account_ht_id){
        $account_counsumption=M("AccountConsumption");
        $time_start=strtotime($starttime);
        $time_end=strtotime($endtime."+1 day");
        $sum=$account_counsumption->where("appid='$appid' and starttime>='$time_start' and starttime<'$time_end'  and htid='$account_ht_id'")->sum("zhanxian");
        return $sum;
    }
    // 根据appid 合同id  统计 点击 条件(开始时间 结束时间)
    public function Account_dianji($appid,$starttime,$endtime,$account_ht_id){
        $account_counsumption=M("AccountConsumption");
        $time_start=strtotime($starttime);
        $time_end=strtotime($endtime."+1 day");
        $sum=$account_counsumption->where("appid='$appid' and starttime>='$time_start' and starttime<'$time_end'  and htid='$account_ht_id'")->sum("dianji");
        return $sum;
    }


    //匹配账户appid
    public function appid_set(){
        //获取账户列表及APPID
        $accountsem_list=hjd_curl('http://www.yushanapp.com/api/get/customer/c03d80f07c144cdab5e881866b92ad9f');
        $i=0;
        foreach ($accountsem_list['customers'] as $key=>$val)
        {
            $array_slist[$key]['appid']=$val['appid'];
            $array_slist[$key]['account']=$val['api_account'];
            $i++;
        }

        //dump($array_slist);
        $account=M("Account");
        foreach ($array_slist as $key=>$val)
        {
           if($account->where("a_users='$val[account]'")->save(array("appid"=>$val['appid'])))
           {
                echo $account->_sql() . "修改了一条<br>";
           }
        }
    }

    //获取周消耗数据，全部
    public function account_cost_all(){

        $account_counsumption=M("AccountConsumption");
        //缓存每个客户具体消费情况 appid ,日期,消费  获取周消费的时候要调用缓存 所以在这里先生存缓存
        $account_day_cost = account_daili();//消耗数据  百度-神马 合并封装
        if(!$account_day_cost)
        {
            $data['code']=403;
            $data['msg']='远程也羽扇数据库连接失败。来自（read_today_account_consumption_data）';
            return $date;
        }

        $count=0;
        foreach ($account_day_cost as $key => $val)
        {
            $data2['appid']=$val['appid'];
            $data2['starttime']=strtotime($val['date']);
            $data2['endtime']=strtotime($val['date'] ."23:59:59");
            $data2['baidu_cost_total']=$val['cost_total'];
            $data2['zhanxian']=$val['view_total'];
            $data2['dianji']=$val['click_total'];
            $data2['date']=$val['date'];
            $data2['semid']=account_sem_id($val['appid']);
            $data2['xsid']=account_xs_id($val['appid'],'market');
            $data2['htid']=account_xs_id($val['appid'],'id');
            $data2['avid']=account_xs_id($val['appid'],'advertiser');
            if($account_counsumption->add($data2))
            {

                $count++;
            }
          //  dump($data2);
        }


        $data['code']=200;
        $data['msg']='成功添加'.$count."条记录消费记录。来自（read_today_account_consumption_data）";
         $this->ajaxReturn($data);
    }
    public function xiamu(){
        echo round(0.409999999999999999,2);
        exit;
        $tabledata = M("accountdaily", "baiduapi_", "pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/msdb");
        $list=$tabledata->field('appid')->where(" date >= '2017-01-01'")->group("appid")->select();
        $account=M("Account");

        foreach ($list as $key=>$val)
        {
            $appidacc=$account->where("appid='".$val['appid']."'")->find();
            if($appidacc!='')
            {

                echo "'".$val['appid']."',";
            }else
            {


                //echo "'".$val['appid']."',";
            }

            //echo $appidacc."<br>";
        }
    }
}