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
        $account=M("Account");
        $hetong=M("Contract");
        // $data=$account->field('')->where('a_users='.$acconut_u)->select();
        $list=$account->field('a.id,a.appname,a.type,a.contract_id,b.name,a.appid')->join("a left join __ACCOUNTTYPE__ b on a.type = b.id ")->where("appid !=''")->order("a.ctime desc")->select();
        //负责人
        $principal=M("AccountUsers");
        foreach ($list as $key=>$val)
        {
            $fzridlist=$principal->field('u_id')->where("account_id = $val[id]")->select(false);
            $userslist=M("Users")->field('name,id as uid')->where("id in ($fzridlist)")->find();

            $list[$key]['sem']=$userslist['name'];
            $list[$key]['semid']=$userslist['uid'];
        }

        $zhouar=teodate_week(1,"Thursday"); //获取周日期的开始时间和结束时间
        dump($list);
        dump($zhouar);
        exit;
        foreach($zhouar as $key=>$val)
        {
            $cost=0;
            // $list[$key]=$conn->field('sum(baidu_cost_total) as cost')->where("appid='$appid' and date>='$val[start]' and date<='$val[end]'")->group('appid')->select();
            foreach ($list as $dakey=>$daval)
            {
                $daval['date']=date("Y-m-d",$daval['date']);
                if($daval['date'] >=$val['start'] and $daval['date']<=$val['end'])
                {
                    $cost=$daval['baidu_cost_total']+$cost;
                }
                $list[$key]['cost']=$cost;
            }
            unset($cost);
            $list[$key]['date']=$val[start]."至".$val[end];
        }


        dump($list);
    }

}