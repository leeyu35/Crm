<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/25
 * Time: 16:27
 */

namespace Admin\Controller;
use Think\Controller;

class DataController extends Controller
{
    public function index($appid,$to){


        if($appid=='')
        {
            return 403;
        }
        $table=S("account_day_cost");//读取缓存文件

        //循环搜索数据
        foreach ($table as $key=>$val)
        {
            //查找等于appid 的数据 并形成新的数组
            if(in_array($appid,$val)){
               $data[]=$table[$key];
            }
        }

       // $tabdate=$conn->where("appid='$appid'")->select();

       $zhouar=$this->teodate($to); //获取日期
        foreach($zhouar as $key=>$val)
        {
            $cost=0;
           // $list[$key]=$conn->field('sum(baidu_cost_total) as cost')->where("appid='$appid' and date>='$val[start]' and date<='$val[end]'")->group('appid')->select();
            foreach ($data as $dakey=>$daval)
            {

                 if($daval['date'] >=$val['start'] and $daval['date']<=$val['end'])
                 {

                     $cost=$daval['baidu_cost_total']+$cost;
                 }

                 $list[$key]['cost']=$cost;

            }
            unset($cost);
            $list[$key]['date']=$val[start]."至".$val[end];
        }
        $array['appid']=$appid;
        $array['data']=$list;
        $this->ajaxReturn($array);

       //dump($zhouar);
    }


    public function teodate($to,$strdate=''){
        //如果没有指定日期则默认当前日期
        if($strdate=='')
        {
            $strdate=date('Y-m-d');
        }

        $a=strtotime($strdate);
        //获取几周日期
        for($i=0;$i<$to;$i++)
        {

            $start=strtotime("last Monday -$i week",$a);//起始时间;

            $array[$i]['start']=date('Y-m-d',$start);

            $enddate=date("Y-m-d",strtotime("+1 week -1 day",$start));
            if($enddate > date("Y-m-d"))
            {
                $enddate=date("Y-m-d",strtotime("-1 day"));
            }
            $array[$i]['end']=$enddate;//结束日期


        }

        //echo $a;
        return $array;


    }

}