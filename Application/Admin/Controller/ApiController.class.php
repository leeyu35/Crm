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

    /*
     * 销售接口----------------------------------------------------------------------------------------------------------------------
    */
    //根据销售id 获取单个销售本周新增客户
    public function find_market_week_clientele(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else
        {
            $data['code']=200;
            $data['count']=$this->market_week_clientele($id);
        }

        $this->response($data,'json');
    }
    //根据销售id 获取单个销售本月新增客户
    public function find_market_month_clientele(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $data['code'] = 200;
            $data['count'] = $this->market_month_clientele($id);
        }
        $this->response($data,'json');
    }
    //根据销售id 获取销售所有客户昨日消耗
    public function find_market_day_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            $zuori = Yesterday();//昨日开始时间和结束时间
            $time_start=strtotime($zuori['start']);
            $time_end=strtotime($zuori['end']."+1 day");
            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');

    }
    
    //根据销售id 获取销售所有客户本周消耗
    public function find_market_week_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            $zhouar=teodate_week(1,"Monday");//本周开始时间和结束时间
            $time_start=strtotime($zhouar[0]['start']);
            $time_end=strtotime($zhouar[0]['end']."+1 day");
            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');
    }
    //根据销售id 获取销售所有客户本月消耗
    public function find_market_month_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            $yuear=teodate_month();//本月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');
    }

    //根据销售id 获取销售所有客户上月消耗
    public function find_market_smonth_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            $yuear=teodate_smonth();//本月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');
    }
    //根据销售id 获取销售本周所属客户消耗数据列表
    public function find_market_week_counsumption_statistics(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");

            $time_start=strtotime(date("Y-m-d")."-7 day");
            $time_end=strtotime(date("Y-m-d"));
            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->field('date,sum(baidu_cost_total) as baidu_cost_total')->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->group("date")->order("date asc")->select();
            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');
    }
    //根据销售id 获取该每个客户的周消耗 和 月消耗
    public function find_market_clientele_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $contract=M('Contract');
            $kehu=M("Customer");
            if(I('type')!='all')
            {

                $list=$contract->field('advertiser')->where("market='$id'")->DISTINCT('advertiser')->select();
            }else
            {
                $list=$contract->field('advertiser')->DISTINCT('advertiser')->select();
            }


            foreach ($list as $key=>$val)
            {
                $khinfo=$kehu->field('advertiser')->find(($val['advertiser']));
                $kdata[$key]['advertiser']=$khinfo['advertiser'];
                $kdata[$key]['week_counsumption']=$this->customer_market_week_clientele($id,$val['advertiser']);
                $kdata[$key]['month_counsumption']=$this->customer_market_month_clientele($id,$val['advertiser']);
            }
            $data['code'] = 200;
            $data['data'] = $kdata;
        }
        $this->response($data,'json');

    }
    //根据销售ID 和 客户id 获取客户的周消费
    private function customer_market_week_clientele($xsid,$avid,$type=0){
        $contract=M('Contract');
        $account_counsumption=M("AccountConsumption");
        $list=$contract->field('advertiser,id')->where("market='$xsid' and advertiser='$avid'")->select();

        foreach ($list as $key=>$val)
        {
            $zhouar=teodate_week(1,"Monday");//本周开始时间和结束时间
            $time_start=strtotime($zhouar[0]['start']);
            $time_end=strtotime($zhouar[0]['end']."+1 day");
            $sum+=$account_counsumption->where("xsid=$xsid and starttime>='$time_start'  and starttime<'$time_end' and htid=$val[id] ")->sum("baidu_cost_total");

        }
        return  $sum?$sum:'0';
    }
    //根据销售ID 和 客户id 获取客户的月消费
    private function customer_market_month_clientele($xsid,$avid,$type=0){
        $contract=M('Contract');
        $account_counsumption=M("AccountConsumption");
        $list=$contract->field('advertiser,id')->where("market='$xsid' and advertiser='$avid'")->select();
        foreach ($list as $key=>$val)
        {

            $yuear=teodate_month();//本月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
            $sum+=$account_counsumption->where("xsid=$xsid and starttime>='$time_start'  and starttime<'$time_end' and htid=$val[id] ")->sum("baidu_cost_total");
        }
        return  $sum?$sum:'0';
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
    /*
         * boos接口----------------------------------------------------------------------------------------------------------------------
   */
    //周新增合同
    public function contract_week(){
        $customer=M('Contract');
        $zhouar=teodate_week(1,"Monday");

        $strat=strtotime($zhouar[0]['start']);
        $end=strtotime($zhouar[0]['end'] . "+1 day");
        $count=$customer->field(1)->where("ctime>$strat and ctime<$end ")->count('id');
        $data['code'] = 200;
        $data['count'] = $count;
        $this->response($data,'json');
    }
    //周新增合同
    public function contract_month(){
        $customer=M('Contract');
        $zhouar=teodate_month();
        $strat=strtotime($zhouar['start']);
        $end=strtotime($zhouar['end'] . "+1 day");
        $count=$customer->field(1)->where("ctime>$strat and ctime<$end ")->count('id');
        $data['code'] = 200;
        $data['count'] = $count;
        $this->response($data,'json');
    }
    //今日款项 根据type 返回 本日 回款 续费  垫款数据
    public function today_day_type()
    {
        $type=I('get.type');
        $backmoney=M("RenewHuikuan");
        $start=strtotime(date("Y-m-d"));
        //今日总回款
        $sum_hk=$backmoney->where(" payment_time >='$start' and is_huikuan=1 and audit_1!=2 and audit_2!=2")->sum('money');
        //今日总付款续费
        $sum_fk=$backmoney->where(" payment_time >='$start' and (payment_type=1 or payment_type=2) and audit_1!=2 and audit_2!=2")->sum('money');
        //今日总垫付
        $sum_df=$backmoney->where(" payment_time >='$start' and payment_type=2 and audit_1!=2 and audit_2!=2")->sum('money');
        switch ($type){
            case 'backmoney':

                $data['money']=$sum_hk?$sum_hk:'0';
                break;
            case 'fukuan':

                $data['money']=$sum_fk?$sum_fk:'0';
                break;
            case 'diankuan':
                $data['money']=$sum_df?$sum_df:'0';
                break;
        }
        $data['code'] = 200;

        $this->response($data,'json');
        //is_huikuan
    }

    //今日款项 根据type 返回 本月 回款 续费  垫款数据
    public function today_month_type()
    {
        $type=I('get.type');
        $backmoney=M("RenewHuikuan");
        $yuear=teodate_month();
        $start=strtotime($yuear["start"]);
        //本月总回款
        $sum_hk=$backmoney->where(" payment_time >='$start' and is_huikuan=1 and audit_1!=2 and audit_2!=2")->sum('money');
        //本月总付款续费
        $sum_fk=$backmoney->where(" payment_time >='$start' and (payment_type=1 or payment_type=2) and audit_1!=2 and audit_2!=2")->sum('money');

        switch ($type){
            case 'backmoney':

                $data['money']=$sum_hk?$sum_hk:'0';
                break;
            case 'fukuan':

                $data['money']=$sum_fk?$sum_fk:'0';
                break;
            case 'diankuan':
                $customer=M("Customer");
                $dk_sm=$customer->field('yu_e,huikuan')->select();
                foreach ($dk_sm as $key=>$val)
                {
                    if($val['huikuan']-$val['yu_e']<0){
                       // echo $val['huikuan']-$val['yu_e']."<br>";
                        $diankuan+=$val['huikuan']-$val['yu_e'];
                    }
                }

                $data['money']=$diankuan?-$diankuan:'0';
                break;
        }
        $data['code'] = 200;

        $this->response($data,'json');
        //is_huikuan
    }
    //欠款公司最高的20条，并取得她的近三次回款记录
    public function diankuan_compare(){
        $customer=M("Customer");//公司
        $backmoney=M("RenewHuikuan");//续费回款表
        $dk_sm=$customer->field('id,advertiser,yu_e,huikuan,huikuan-yu_e as yue')->order("yue asc")->limit('0,20')->select();

        foreach ($dk_sm as $key=>$val)
        {
            $zuijinhk=$backmoney->where("advertiser=$val[id] and is_huikuan=1")->field('payment_time,money')->order("payment_time desc")->limit('0,3')->select();
            foreach ($zuijinhk as $k=>$v)
            {
                $zuijinhk[$k]['payment_time']=date("Y-m-d",$v['payment_time']);
            }
            $dk_sm[$key]['huikuan_record']=$zuijinhk;
        }
        $data['code'] = 200;
        $data['diankuan_huikuan_record'] = $dk_sm;
        $this->response($data,'json');
    }

    /*
     * SEM接口
     * */

    //根据SEMid 获取SEM所有客户昨日消耗
    public function find_sem_day_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            $zuori = Yesterday();//昨日开始时间和结束时间
            $time_start=strtotime($zuori['start']);
            $time_end=strtotime($zuori['end']."+1 day");
            if(I('type')!='all')
            {
                $where="semid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');

    }

    //根据SEMid 获取SEM所有客户本周消耗
    public function find_sem_week_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            $zhouar=teodate_week(1,"Monday");//本周开始时间和结束时间
            $time_start=strtotime($zhouar[0]['start']);
            $time_end=strtotime($zhouar[0]['end']."+1 day");
            if(I('type')!='all')
            {
                $where="semid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');
    }
    //根据SEMid 获取SEM所有客户本月消耗
    public function find_sem_month_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            $yuear=teodate_month();//本月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
            if(I('type')!='all')
            {
                $where="semid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");

            if(!$sum){$sum="0";}
            $data['code'] = 200;
            $data['counsumption'] = $sum;
        }
        $this->response($data,'json');
    }
    //根据sem ID 获取 负责账户的周消耗和月消耗
    public function sem_account_counsumption(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account=M("Account");
            if(I('type')!='all')
            {
                $where="u_id=$id";
            }else
            {
                $where="id != 0";
            }
            $sem_acc_id=M("AccountUsers")->field('account_id')->where("$where")->select(false);
            $list=$account->field('appname,a_users,appid')->where("id in($sem_acc_id)")->select();

            foreach ($list as $key=>$val)
            {
                $list[$key]['week_counsumption']=$this->sem_week_counsumption($val['appid'],$id);
                $list[$key]['month_counsumption']=$this->sem_month_counsumption($val['appid'],$id);
            }
            $data['code'] = 200;
            $data['counsumption'] = $list;
        }
        $this->response($data,'json');
    }
    //根据appid semID 获取账户周消费
    private function sem_week_counsumption($appid,$semid){

        $account_counsumption=M("AccountConsumption");
        $zhouar=teodate_week(1,"Monday");//本周开始时间和结束时间
        $time_start=strtotime($zhouar[0]['start']);
        $time_end=strtotime($zhouar[0]['end']."+1 day");
        $sum+=$account_counsumption->where("semid=$semid and starttime>='$time_start'  and starttime<'$time_end' and appid='$appid'")->sum("baidu_cost_total");

        return  $sum?$sum:'0';
    }
    //根据appid semID 获取账户月消费
    private function sem_month_counsumption($appid,$semid){

        $account_counsumption=M("AccountConsumption");
        $yuear=teodate_month();//本月开始时间和结束时间
        $time_start=strtotime($yuear['start']);
        $time_end=strtotime($yuear['end']);
        $sum+=$account_counsumption->where("semid=$semid and starttime>='$time_start'  and starttime<'$time_end' and appid='$appid'")->sum("baidu_cost_total");

        return  $sum?$sum:'0';
    }
}











