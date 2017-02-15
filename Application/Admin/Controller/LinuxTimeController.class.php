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

            //缓存每个客户具体消费情况 appid ,日期,消费  获取周消费的时候要调用缓存 所以在这里先生存缓存
            $tabledata = M ("accountdaily","baiduapi_","pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/msdb");
            $account_day_cost=$tabledata->field('appid,date,baidu_cost_total')->where("device='all'")->select();
            if(!isset($account_day_cost))
            {

                $data['code']=404;
                $this->ajaxReturn($data);
                exit;
            }else {
                //缓存周消费数据
                S('account_day_cost', $account_day_cost);
            }



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

                 $array_slist[$key]['data']=$accountsem_one['data'];
                // $array_slist[$key]['data']=$accountsem_one['data'];
            }

        //缓存数据具体
        if(S('account_data',$array_slist)){
            $data['code']=200;
        }else{
            $data['code']=403;
        }

            $this->ajaxReturn($data);
    }

    //获取日消耗数据，如果没有指定日期则默认前一天日期
    function read_today_account_consumption_data($date=''){
        if($date=='')
        {
            $date=date("Y-m-d",strtotime("-1 day"));//昨日日期
        }else
        {
            $date=$date;
        }



        $account_counsumption=M("AccountConsumption");
        $account_counsumption->where("date='$date'")->delete();

        //缓存每个客户具体消费情况 appid ,日期,消费  获取周消费的时候要调用缓存 所以在这里先生存缓存
        $tabledata = M("accountdaily", "baiduapi_", "pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/msdb");

        $account_day_cost = $tabledata->field('appid,date,baidu_cost_total,baidu_view_total,baidu_click_total')->where("date='$date' and device='all'")->group('appid,date,baidu_cost_total,baidu_view_total,baidu_click_total')->select();
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
            $data2['baidu_cost_total']=$val['baidu_cost_total'];
            $data2['zhanxian']=$val['baidu_view_total'];
            $data2['dianji']=$val['baidu_click_total'];
            $data2['date']=$val['date'];
            $data2['semid']=account_sem_id($val['appid']);
            $data2['xsid']=account_xs_id($val['appid'],'market');
            $data2['htid']=account_xs_id($val['appid'],'id');
            $data2['avid']=account_xs_id($val['appid'],'advertiser');
            if($account_counsumption->add($data2))
            {

                $count++;
            }
        }
        $data['code']=200;
        $data['msg']='成功添加'.$count."条记录消费记录。来自（read_today_account_consumption_data）";
        $this->ajaxReturn($data);
    }


    function nianjia()
    {
        $users=M("Users");
        $list=$users->field('id,name,nianjia')->where("is_delete!=1")->select();


        foreach ($list as $key=>$val)
        {
             nianjia($val['id']);
            /*
            $benniannianjiatianshu=round(nianjia($val['id'],date("Y-m-d")),1);

            if($benniannianjiatianshu>floor($benniannianjiatianshu)+1-0.5)
            {
                $bnianjia=floor($benniannianjiatianshu)+1-0.5;
            }else
            {
                $bnianjia=floor($benniannianjiatianshu);
            }

            $qnianjia=nianjia($val['id'],date("Y-m-d",strtotime(date('Y-m-d')."-1 day")));

            //echo strtotime(date('Y-m-d')."-1 day");


            $jnhqn=$bnianjia+$qnianjia;


            //今年请年假的天数
            $qstart=strtotime(date("Y")."-01-01"."-1 year");
            $jstart=strtotime(date("Y")."-01-01");
            $Holiday=M("Holiday");
            //去年请的年假天数
            $qqingjia_nianjia=$Holiday->where("starttime>$qstart and starttime<$jstart and audit_1!=2 and audit_2!=2 and type='年假' and submituser=$val[id]")->sum("day");
            //今年请的年假天数
            $jqingjia_nianjia=$Holiday->where("starttime>$jstart and audit_1!=2 and audit_2!=2 and type='年假' and submituser=$val[id]")->sum("day");
            $jqnqjts=$qqingjia_nianjia+$jqingjia_nianjia;
                echo $jqnqjts;
            //剩余年假
            $shengyunianjia=$jnhqn-$jqnqjts;
            //判断是否为1月1号 如果是 则清零前年的年假

            /*
            if(date("m-d")=='01-01')
            {
                echo $jnhqn.'-----------'.$val['nianjia'];
                if($jnhqn<$val['nianjia'])
                {
                    echo 1;
                    $shengyunianjia=$qnianjia; //如果计算年假小于数据库 年假 则年假天数等于去年年假天数；
                }else
                {
                    echo 2;
                    $shengyunianjia=$val['nianjia'];
                }
            }
            */

           // echo $val[name].$shengyunianjia."<br>";


           // $users->where("id=$val[id]")->setField('nianjia',$shengyunianjia);
        }


    }

}