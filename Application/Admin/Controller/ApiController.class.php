<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2017/1/13
 * Time: 10:23
 * public 表示全局，类内部外部子类都可以访问；
 * private表示私有的，只有本类内部可以使用；
 * protected表示受保护的，只有本类或子类或父类中可以访问；
 */
namespace Admin\Controller;
use Think\Controller\RestController;
class ApiController extends RestController{
    public  function  index(){
        echo('<h1>您好,欢迎访问CRM接口</h1>
<p>
        支持资源类型自动检测；<br>
        支持请求类型自动检测；<br>
        RESTFul方法支持；<br>
        可以设置允许的请求类型列表；<br>
        可以设置允许请求和输出的资源类型；<br>
        可以设置默认请求类型和默认资源类型；

</p>');
    }

    // 根据appid 合同id  统计 消耗 条件(开始时间 结束时间)
    private function AccountConsumption($appid,$starttime,$endtime,$account_ht_id){
        $account_counsumption=M("AccountConsumption");
        $time_start=strtotime($starttime);
        // $time_start=strtotime("-1 days",$time_start);

        $time_end=strtotime($endtime);

        //$time_end=strtotime("+1 days",$time_end);
        $sum=$account_counsumption->where("appid='$appid' and starttime>='$time_start' and htid='$account_ht_id'")->sum("baidu_cost_total");
        //echo $account_counsumption->_sql()."<br>";
        return $sum;
    }
    // 根据appid 合同id  统计 展现 条件(开始时间 结束时间)
    private function Account_zhanxian($appid,$starttime,$endtime,$account_ht_id){
        $account_counsumption=M("AccountConsumption");
        $time_start=strtotime($starttime);
        $time_end=strtotime($endtime);
        $sum=$account_counsumption->where("appid='$appid' and starttime>='$time_start' and htid='$account_ht_id'")->sum("zhanxian");
        return $sum;
    }
    // 根据appid 合同id  统计 点击 条件(开始时间 结束时间)
    private function Account_dianji($appid,$starttime,$endtime,$account_ht_id){
        $account_counsumption=M("AccountConsumption");
        $time_start=strtotime($starttime);
        $time_end=strtotime($endtime);
        $sum=$account_counsumption->where("appid='$appid' and starttime>='$time_start' and htid='$account_ht_id'")->sum("dianji");
        return $sum;
    }

    /*
     * 销售接口
    */
    //根据销售id 获取单个销售本周新增客户
    public function find_market_week_clientele($id){
        $data['code']=200;
        $data['count']=$this->market_week_clientele($id);
        $this->response($data,'json');
    }
    //根据销售id 获取单个销售本周新增客户
    public function find_market_month_clientele($id){
        $data['code']=200;
        $data['count']=$this->market_month_clientele($id);
        $this->response($data,'json');
    }
    //根据销售id 获取销售本周新增客户
    private function market_week_clientele($id){
        $customer=M('Customer');
        $zhouar=teodate_week(1,"Monday");
        $strat=strtotime($zhouar[0]['start']);
        $end=strtotime($zhouar[0]['end'] . "+1 day");
        $count=$customer->field(1)->where("ctime>$strat and ctime<$end and submituser in($id)")->count('id');
        return $count;
    }
    //根据销售id 获取销售本月新增客户
    private function market_month_clientele($id){
        $customer=M('Customer');
        $yuear=teodate_month();
        $strat=strtotime($yuear['start']);
        $end=strtotime($yuear['end'] . "+1 day");
        $count=$customer->field(1)->where("ctime>$strat and ctime<$end and submituser in($id)")->count('id');
        return $count;
    }
}