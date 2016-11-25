<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/24
 * Time: 16:16
 */

namespace Admin\Controller;
use Think\Controller;

class LinuxTimeController extends Controller
{
    function account_listdata(){
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
            }
            //获取周消费数据
            foreach ($array_slist as $key=>$val)
            {
                $accountsem_one=hjd_curl('http://101.200.130.178:5281/account/getweekcost?appid='.$val['appid']);
                $array_slist[$key]['data']=$accountsem_one['data'];
                // $array_slist[$key]['data']=$accountsem_one['data'];
            }
            //缓存数据
            if(S('account_data',$array_slist)){
                $data['code']=200;
            }else{
                $data['code']=403;
            }
            $this->ajaxReturn($data);
    }
}