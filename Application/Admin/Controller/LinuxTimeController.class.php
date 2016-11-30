<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/24
 * Time: 16:16
 */

namespace Admin\Controller;
use Think\Controller;
ini_set('max_execution_time', '0');
class LinuxTimeController extends Controller
{
    function account_listdata($to){
            //获取账户列表及APPID
            $accountsem_list=hjd_curl('http://www.yushanapp.com/api/get/customer/c03d80f07c144cdab5e881866b92ad9f');
            //$accountsem_list['customers']=array_slice($accountsem_list['customers'], 10,10);
            //dump($accountsem_list);
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
            //获取周消费数据
            foreach ($array_slist as $key=>$val)
            {

              //  $accountsem_one=hjd_curl('http://101.200.130.178:5281/account/getweekcost?appid='.$val['appid']);
                  $accountsem_one=hjd_curl("http://".$_SERVER['HTTP_HOST'].U("Data/index?appid=$val[appid]&to=$to"));
                //  dump($accountsem_one);
                 $array_slist[$key]['data']=$accountsem_one['data'];
                // $array_slist[$key]['data']=$accountsem_one['data'];
            }


            $tabledata = M ("accountdaily","baiduapi_","pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/msdb");


            $account_day_cost=$tabledata->field('appid,date,baidu_cost_total')->select();

            S('account_day_cost',$account_day_cost);
            //缓存数据
            if(S('account_data',$array_slist)){
                $data['code']=200;
            }else{
                $data['code']=403;
            }
            $this->ajaxReturn($data);
    }
}