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
            $data['counsumption'] = number_format($sum,2);
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
            $data['counsumption'] = number_format($sum,2);
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
            $data['counsumption'] = number_format($sum,2);
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
            $data['counsumption'] = number_format($sum,2);
        }
        $this->response($data,'json');
    }
    //根据销售id 获取销售本周|本年|本日|所属客户消耗数据列表
    public function find_market_week_counsumption_statistics(){
        $id=I('get.usersid');
        $date_type=I('get.datetype');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
        }else {
            $account_counsumption=M("AccountConsumption");
            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }

            if ($date_type == 'day') {
                //最近七天
                $j7=date_daye_j7();
                $list=$account_counsumption->field('date,sum(baidu_cost_total) as consumption')->where("$where and starttime>='$j7[start]'  and starttime<'$j7[end]' ")->group('date')->order("date asc")->select();

            } elseif ($date_type == 'week') {
                //最近四周
                $zhouar = teodate_week(4, 'Monday');//本周开始时间和结束时间

                foreach ($zhouar as $key=>$val)
                {
                    $time_start = strtotime($val['start']);
                    $time_end = strtotime($val['end'] . "+1 day");
                    $Consumption=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum('baidu_cost_total');
                    $list[$key]['date']=$val['start'];
                    $list[$key]['consumption']=$Consumption?$Consumption:0;
                }

            } elseif ($date_type == 'month') {
                //最近12个月
                $yuear = teodate_month_12(12);//本月开始时间和结束时间
                foreach ($yuear as $key=>$val)
                {
                    $time_start = strtotime($val['start']);
                    $time_end = strtotime($val['end'] . "+1 day");
                    $Consumption=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end'")->sum('baidu_cost_total');
                    $list[$key]['date']=$val['start'];
                    $list[$key]['consumption']=$Consumption?$Consumption:0;
                }

            }
            /*
            $time_start=strtotime(date("Y-m-d")."-7 day");
            $time_end=strtotime(date("Y-m-d"));

            $sum=$account_counsumption->field('date,sum(baidu_cost_total) as baidu_cost_total')->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->group("date")->order("date asc")->select();

            if(!$sum){$sum="0";}
           */
            $data['code'] = 200;
            $data['counsumption'] = $list;
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

            if(S($id.'_maket_cache_'.I('get.type'))==''){
                $contract=M('Contract');
                $kehu=M("Customer");
                if(I('get.type')!='all')
                {

                    $list=$contract->field('advertiser')->where("market='$id'")->DISTINCT('advertiser')->select();
                }else
                {
                    $list=$contract->field('advertiser')->DISTINCT('advertiser')->select();
                }

                foreach ($list as $key=>$val)
                {
                    $khinfo=$kehu->field('advertiser,id')->find(($val['advertiser']));
                    $kdata[$key]['id']=$khinfo['id'];
                    $kdata[$key]['advertiser']=$khinfo['advertiser'];
                    $kdata[$key]['week_counsumption']=$this->customer_market_week_clientele($id,$val['advertiser'],I('get.type'));
                    $kdata[$key]['month_counsumption']=$this->customer_market_month_clientele($id,$val['advertiser'],I('get.type'));
                }

                S($id.'_maket_cache_'.I('get.type'),$kdata,28800);
            }
                $dddd=S($id.'_maket_cache_'.I('get.type'));


            $data['code'] = 200;
            $data['data'] = $dddd;
        }

        $this->response($data,'json');

    }
    //根据销售ID 和 客户id 获取客户的周消费
    private function customer_market_week_clientele($xsid,$avid,$type=0){
        $account_counsumption=M('AccountConsumption');
        $zhouar=teodate_week(1,'Monday');//本周开始时间和结束时间
        $time_start=strtotime($zhouar[0]['start']);
        $time_end=strtotime($zhouar[0]['end']."+1 day");
        if($type='all')
        {
            $where='';
        }else
        {
            $where="xsid='$xsid' and ";
        }

        $sum+=$account_counsumption->where("$where starttime>='$time_start'  and starttime<'$time_end' and avid='$avid' ")->sum("baidu_cost_total");

        return  $sum?number_format($sum,2):'0';
    }
    //根据销售ID 和 客户id 获取客户的月消费
    private function customer_market_month_clientele($xsid,$avid,$type=0){
        $account_counsumption=M('AccountConsumption');
        $yuear=teodate_month();//本月开始时间和结束时间
        $time_start=strtotime($yuear['start']);
        $time_end=strtotime($yuear['end']);
        if($type='all')
        {
            $where='';
        }else
        {
            $where="xsid='$xsid' and ";
        }
        $sum+=$account_counsumption->where("$where starttime>='$time_start'  and starttime<'$time_end' and  avid='$avid' ")->sum("baidu_cost_total");

        return  $sum?number_format($sum,2):'0';
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

        $strat=strtotime($zhouar[1]['start']);
        $end=strtotime($zhouar[1]['end'] . "+1 day");
        if(I('get.usersid')!='')
        {
            $where=" and market='".I('get.usersid')."'";
        }
        
        $count=$customer->field(1)->where("ctime>$strat and ctime<$end.$where ")->count('id');

        $data['code'] = 200;
        $data['count'] = $count;
        $this->response($data,'json');
    }
    //月新增合同
    public function contract_month(){
        $customer=M('Contract');
        $zhouar=teodate_month();
        $strat=strtotime($zhouar['start']);
        $end=strtotime($zhouar['end'] . "+1 day");
        if(I('get.usersid')!='')
        {
            $where=" and market='".I('get.usersid')."'";
        }
        $count=$customer->field(1)->where("ctime>$strat and ctime<$end.$where")->count('id');
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
        $end=strtotime(date("Y-m-d")."+ 1 day");

        //今日总回款
        $sum_hk=$backmoney->where(" payment_time >='$start' and payment_time<'$end' and is_huikuan=1 and audit_1!=2 and audit_2!=2")->sum('money');
        //今日总付款续费
        $sum_fk=$backmoney->where(" payment_time >='$start' and payment_time<'$end' and (payment_type=1 or payment_type=2) and audit_1!=2 and audit_2!=2 and audit_3!=2")->sum('money');

        //今日总垫付
        $sum_df=$backmoney->where(" payment_time >='$start' and payment_time<'$end' and payment_type=2 and audit_1!=2 and audit_2!=2")->sum('money');
        switch ($type){
            case 'backmoney':

                $data['money']=$sum_hk?number_format($sum_hk,2):'0';
                break;
            case 'fukuan':

                $data['money']=$sum_fk?number_format($sum_fk,2):'0';
                break;
            /*
            case 'diankuan':
                $data['money']=$sum_df?number_format($sum_df,2):'0';
                break;
            */
        }
        $data['code'] = 200;
        $this->response($data,'json');
        //is_huikuan
    }

    //今日款项 根据type 返回 本月 回款 续费  垫款 补款数据  垫款和补款显示是总额
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
        //本月总补款
        $sum_bk=$backmoney->where("payment_type=3 and audit_1!=2 and audit_2!=2")->sum('money');
        switch ($type){
            case 'backmoney':

                $data['money']=$sum_hk?number_format($sum_hk,2):'0';
                break;
            case 'fukuan':

                $data['money']=$sum_fk?number_format($sum_fk,2):'0';
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

                $data['money']=$diankuan?number_format(-$diankuan,2):'0';
                break;
            case 'bukuan':
                $data['money']=$sum_bk?number_format($sum_bk,2):'0';
                break;

        }
        $data['code'] = 200;

        $this->response($data,'json');
        //is_huikuan
    }

    //根据type，date  返回 本日 或者本月 回款 续费  垫款 补款 数据列表
    public function  boss_money_type_list(){
        $backmoney=M("RenewHuikuan");
        $type=I('get.type');
        $date=I('get.date');
        if($date=='day')
        {
            $start=strtotime(date("Y-m-d"));
        }elseif ($date=='month')
        {
            $yuear=teodate_month();
            $start=strtotime($yuear["start"]);
        }
        switch ($type){
            case 'backmoney':
                //本月总回款
                $list=$backmoney->field('id,advertiser,money,payment_time,account,market,ctime')->where(" payment_time >='$start' and is_huikuan=1 and audit_1!=2 and audit_2!=2")->select();
                break;
            case 'fukuan':
                $list=$backmoney->field('id,advertiser,money,payment_time,account,market,ctime')->where(" payment_time >='$start' and (payment_type=1 or payment_type=2) and audit_1!=2 and audit_2!=2")->select();
                break;
            case 'bukuan':
                $list=$backmoney->field('id,advertiser,money,payment_time,account,market,ctime')->where("payment_type=3 and audit_1!=2 and audit_2!=2")->select();
                break;
        }
        foreach ($list as $key=>$val)
        {
            $K=kehu($val['advertiser']);
            $account=M('Account')->field('a_users')->find($val['account']?$val['account']:'');
            $list[$key]['advertisername']=$K['advertiser'];
            $list[$key]['account_name']=$account['a_users'];
            $list[$key]['ctime']=date("Y-m-d",$val['ctime']);
            $list[$key]['payment_time']=date("Y-m-d",$val['payment_time']);
            $list[$key]['money']=number_format($val['money'],2);
        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }


    //欠款公司最高的20条，并取得她的近三次回款记录
    public function diankuan_compare(){
        $customer=M("Customer");//公司
        $backmoney=M("RenewHuikuan");//续费回款表
        $dk_sm=$customer->field('id,advertiser,yu_e,huikuan,huikuan-yu_e as yue')->order("yue asc")->limit('0,20')->select();

        foreach ($dk_sm as $key=>$val)
        {
            $zuijinhk=$backmoney->where("advertiser=$val[id] and is_huikuan=1")->field('payment_time,money')->order("payment_time desc")->limit('0,5')->select();
            foreach ($zuijinhk as $k=>$v)
            {
                $zuijinhk[$k]['payment_time']=date("Y-m-d",$v['payment_time']);
                $zuijinhk[$k]['money']=number_format($v['money'],2);
                //number_format
            }
            $dk_sm[$key]['huikuan_record']=$zuijinhk;
            $dk_sm[$key]['yu_e']=number_format($val['yu_e'],2);
            $dk_sm[$key]['huikuan']=number_format($val['huikuan'],2);
            $dk_sm[$key]['yue']=number_format($val['yue'],2);
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
            $data['counsumption'] = number_format($sum,2);
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
            $zhouar=teodate_week2(1,"Monday");//本周开始时间和结束时间


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
            $data['counsumption'] = number_format($sum,2);
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
            $data['counsumption'] = number_format($sum,2);
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
        $zhouar=teodate_week2(1,"Monday");//本周开始时间和结束时间
        $time_start=strtotime($zhouar[0]['start']);
        $time_end=strtotime($zhouar[0]['end']."+1 day");
        $sum+=$account_counsumption->where("semid=$semid and starttime>='$time_start'  and starttime<'$time_end' and appid='$appid'")->sum("baidu_cost_total");

        return  $sum?number_format($sum,2):'0';
    }
    //根据appid semID 获取账户月消费
    private function sem_month_counsumption($appid,$semid){

        $account_counsumption=M("AccountConsumption");
        $yuear=teodate_month();//本月开始时间和结束时间
        $time_start=strtotime($yuear['start']);
        $time_end=strtotime($yuear['end']);
        $sum+=$account_counsumption->where("semid=$semid and starttime>='$time_start'  and starttime<'$time_end' and appid='$appid'")->sum("baidu_cost_total");

        return  $sum?number_format($sum,2):'0';
    }

    /*
     * 二级页面接口
     *
     * */

    //周新增合同
    public function contract_date_list(){
        $customer=M('Contract');
        $type=I('get.type');
        if($type=='yesterday')
        {
            $zuori = Yesterday();//昨日开始时间和结束时间
            $time_start=strtotime($zuori['start']);
            $time_end=strtotime($zuori['end']."+1 day");
        }elseif ($type=='week')
        {
            $zhouar=teodate_week2(1,'Monday');//本周开始时间和结束时间
            $time_start=strtotime($zhouar[0]['start']);
            $time_end=strtotime($zhouar[0]['end']."+1 day");
        }elseif($type=='month')
        {
            $yuear=teodate_month();//本月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']."+1 day");
        }
        $list=$customer->field('a.id,a.advertiser as aid,a.contract_no,a.users2,a.isguidang,a.iszuofei,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.ctime>'$time_start' and a.ctime<'$time_end' ")->order("ctime desc")->select();

        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
            $list[$key]['ctime']=date("Y-m-d",$val['ctime']);
        }

        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }

    //昨日消耗列表
    public function SpecifyDate_counsumption_list(){
        $type=I('get.type');
        $xiaohao=M("AccountConsumption");
        if($type=='yesterday')
        {
            $zuori = Yesterday();//昨日开始时间和结束时间
            $time_start=strtotime($zuori['start']);
            $time_end=strtotime($zuori['end']."+1 day");
        }elseif ($type=='week')
        {
            $zhouar=teodate_week2(1,'Monday');//本周开始时间和结束时间
            $time_start=strtotime($zhouar[0]['start']);
            $time_end=strtotime($zhouar[0]['end']."+1 day");
        }elseif($type=='month')
        {
            $yuear=teodate_month();//本月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
        }

        $users=M("Users");
        if(I('get.usersid')!='')
        {
            $where=" and xiaohao.xsid=".I('get.usersid');
        }

        $xiaohaolist=$xiaohao->field("sum(xiaohao.baidu_cost_total) as baidu_cost_total,xiaohao.appid,zhanghu.a_users,zhanghu.appname,zhanghu.id as account_id,gongsi.id as avid,gongsi.advertiser,xiaohao.xsid,xiaohao.semid")->join("xiaohao left join jd_account zhanghu on xiaohao.appid=zhanghu.appid left join jd_customer gongsi on xiaohao.avid=gongsi.id")->where("xiaohao.starttime>='$time_start'  and xiaohao.starttime<'$time_end' $where")->group("xiaohao.appid,gongsi.id,gongsi.advertiser,zhanghu.a_users,xiaohao.xsid,xiaohao.semid,zhanghu.appname,zhanghu.id")->order("baidu_cost_total desc")->select();


        foreach ($xiaohaolist as $key=>$val)
        {
            if($val['xsid'])
            {
                $xs=$users->field('name')->find($val['xsid']);
            }else
            {
                $xs['name']='';
            }
            if($val['semid'])
            {
                $sem=$users->field('name')->find($val['semid']);
            }else
            {
                $sem['name']='';
            }

            $xiaohaolist[$key]['a_users']=$val['a_users']?$val['a_users']:'';
            $xiaohaolist[$key]['advertiser']=$val['advertiser']?$val['advertiser']:'';
            $xiaohaolist[$key]['xsid']=$val['xsid']?$val['xsid']:'';
            $xiaohaolist[$key]['semid']=$val['semid']?$val['semid']:'';
            $xiaohaolist[$key]['market']=$xs['name']?$xs['name']:'';
            $xiaohaolist[$key]['sem']=$sem['name']?$sem['name']:'';

            //$xiaohaolist[$key]['baidu_cost_total']=number_format($val['baidu_cost_total']);
        }

        $data['code'] = 200;
        $data['data'] = $xiaohaolist;
        $this->response($data,'json');
    }
    /*
 *
 * 点击公司以后详情页
 *
 *
 * */
    //根据公司id 列出该公司下的所有合同
    public function company_contract_list(){
       $avid=I('get.id');
        if($avid=='')
        {
            $data['code'] = 400;
            $data['mes'] = '参数错误';
            $this->response($data,'json');
            exit;
        }
       $contract=M("Contract");
       $list=$contract->field('id,huikuan,yu_e,contract_no,market')->where("advertiser='$avid' and isxufei=0")->select();
       $xiaohao=M("AccountConsumption");
        $account=M("Account");

       foreach ($list as $key=>$val)
       {
           $aclist=$account->field("a.id,a.a_users,a.appid,a.contract_id,b.u_id")->join("a left join jd_account_users b on a.id=b.account_id")->where('contract_id='.$val['id'])->select();


           foreach ($aclist as $k=>$v)
           {
               if($v['id'])
               {
                   $aclist[$k]['semname']=$this->usersname($v['u_id']);
                   $aclist[$k]['yesterday']=$this->Consumption_account_($v['appid'],$v['contract_id'],"yesterday");
                   $aclist[$k]['week']=$this->Consumption_account_($v['appid'],$v['contract_id'],"week");
                   $aclist[$k]['month']=$this->Consumption_account_($v['appid'],$v['contract_id'],"month");

               }
           }
           $list[$key]['accountlist']=$aclist;
           $list[$key]['market']=$this->usersname($val['market']);;
           //$xiaohao->field('')->where("htid='$val[id]'")->select();
       }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');

    }

    private function usersname($uid)
    {
        if($uid=='')
        {
            return '';
        }
        $users=M('users');
        $usersname=$users->field('name')->find($uid);
        return $usersname['name'];
    }

    private function Consumption_account_($appid,$htid,$type){
        $xiaohao=M("AccountConsumption");
        if($type=='yesterday')
        {

            $zuori = Yesterday();//昨日开始时间和结束时间
            $time_start=strtotime($zuori['start']);
            $time_end=strtotime($zuori['end']."+1 day");
        }elseif ($type=='week')
        {
            $zhouar=teodate_week2(1,'Monday');//本周开始时间和结束时间
            $time_start=strtotime($zhouar[0]['start']);
            $time_end=strtotime($zhouar[0]['end']."+1 day");
        }elseif($type=='month')
        {
            $yuear=teodate_month();//本月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
        }

        $xiaohaolist=$xiaohao->field("baidu_cost_total")->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid' and htid='$htid'")->sum('baidu_cost_total');
        return $xiaohaolist?$xiaohaolist:'';

    }
    //公司的日周月消耗

    public function customer_date_counsumption_line()
    {
        $avid=I('get.id');
        $type = I('get.type');
        $xiaohao = M("AccountConsumption");
        if ($type == 'day') {
            //最近七天
            $j7=date_daye_j7();
            $list=$xiaohao->field('date,sum(baidu_cost_total) as consumption')->where("starttime>='$j7[start]'  and starttime<'$j7[end]' and avid=$avid")->group('date')->order("date asc")->select();

        } elseif ($type == 'week') {
            $zhouar = teodate_week(4, 'Monday');//本周开始时间和结束时间 正序

            foreach ($zhouar as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end'] . "+1 day");
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and avid=$avid")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        } elseif ($type == 'month') {
            $yuear = teodate_month_12(12);//本月开始时间和结束时间
            foreach ($yuear as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end'] . "+1 day");
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and avid=$avid")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }
    //公司详情
    public function customer_info()
    {
        $id=I('get.id');
        if($id=='')
        {
            $data['code'] = 400;
            $data['mes'] = '参数错误';
            $this->response($data,'json');
            exit;
        }
        $customer=M("Customer");
        $info=$customer->field('advertiser,industry,website,appname,city')->find($id);
        $data['code'] = 200;
        $data['data'] = $info;
        $this->response($data,'json');
    }
    //账户详情的折线图
    public function account_date_counsumption_line(){
        $account_id=I('get.id');
        $appid=M("Account")->field('appid')->find($account_id);
        $type = I('get.type');
        $xiaohao = M("AccountConsumption");
        if ($type == 'day') {
            //最近七天
            $j7=date_daye_j7();
            $list=$xiaohao->field('date,sum(baidu_cost_total) as consumption')->where("starttime>='$j7[start]'  and starttime<'$j7[end]' and appid='$appid[appid]'")->group('date')->order("date asc")->select();

        } elseif ($type == 'week') {
            $zhouar = teodate_week(4, 'Monday');//本周开始时间和结束时间
            foreach ($zhouar as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end'] . "+1 day");
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]' ")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        } elseif ($type == 'month') {
            $yuear = teodate_month_12(12);//本月开始时间和结束时间
            foreach ($yuear as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end'] . "+1 day");
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]'")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }

    //账户详情
    public function account_info()
    {
        $id = I('get.id');
        if ($id == '') {
            $data['code'] = 400;
            $data['mes'] = '参数错误';
            $this->response($data, 'json');
            exit;
        }else
        {
            //账户所属合同
            $account_id=I('get.id');
            $info=M("Account")->find($account_id);
            //合同号
            $hetong=M("Contract")->field('contract_no')->find($info['contract_id']);
            //账户负责人
            $fzr=M("Account_users")->field("b.name")->join(" a left join jd_users b on a.u_id = b.id ")->where("account_id=$id")->find();

            //公司名称
            $ad=M("Customer")->field('advertiser')->find($info['advertiser']);
            $info2['appname']=$info['appname'];
            $info2['a_users']=$info['a_users'];
            $info2['contract_no']=$hetong['contract_no'];
            $info2['advertiser']=$ad['advertiser'];
            $info2['sem']=$fzr['name'];
            $info2['appid']=$info['appid'];

        }
        $data['code'] = 200;
        $data['data'] = $info2;
        $this->response($data,'json');
    }
    /*
     * 部门消耗 -2017年1月23日10:17:50
     *
     * */
    //sem 部门
    public function sem_list()
    {
    $users = M("Users");
    $list = $users->field('id,name')->where('groupid=10 and is_delete!=1')->select();
    foreach ($list as $key => $val)
    {
        $day=hjd_curl("http://localhost/Api/find_sem_day_counsumption?usersid=$val[id]");
        $week=hjd_curl("http://localhost/Api/find_sem_week_counsumption?usersid=$val[id]");
        $month=hjd_curl("http://localhost/Api/find_sem_month_counsumption?usersid=$val[id]");
        $list[$key]['day_counsumption']=$day['counsumption'];
        $list[$key]['week_counsumption']=$week['counsumption'];
        $list[$key]['month_counsumption']=$month['counsumption'];

    }

    $data['code'] = 200;
    $data['data'] = $list;
    $this->response($data,'json');
    }
    //某个sem 所有账户三 日周月消耗
    public function sem_account_counsumption_3_line_list(){
        $id=I('get.id');//semid
        $type=I('get.type');//type
        $name=users_info($id);

        $account=M("Account");
        $idin=M("AccountUsers")->field('account_id')->where("u_id=$id")->select(false);
        $list=$account->field('id,a_users')->where("id in($idin)")->select();
        foreach ($list as $key=>$val)
        {
            if($type=='day')
            {
                $list[$key]['counsumption']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=day");
            }elseif($type=='week')
            {
                $list[$key]['counsumption']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=week");
            }else
            {
                $list[$key]['counsumption']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=month");
            }

          //  $list[$key]['week']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=week");
          //  $list[$key]['month']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=month");
        }
        $data['code'] = 200;
        $data['data'] = $list;
        $data['name'] = $name['name'];
        $this->response($data,'json');

    }

    public function sem_account_counsumption_3_line(){
        $account_id=I('get.id');
        $appid=M("Account")->field('appid')->find($account_id);
        $type = I('get.type');
        $xiaohao = M("AccountConsumption");
        if ($type == 'day') {
            //最近七天
            $j7['start']=$time_start=strtotime(date("Y-m-d")."-3 day");
            $j7['end']=$time_end=strtotime(date("Y-m-d"));

            $list=$xiaohao->field('date,sum(baidu_cost_total) as consumption')->where("starttime>='$j7[start]'  and starttime<'$j7[end]' and appid='$appid[appid]'")->group('date')->order("date asc")->select();

        } elseif ($type == 'week') {
            $zhouar = teodate_week(3, 'Monday');//本周开始时间和结束时间

            foreach ($zhouar as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end'] . "+1 day");
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]' ")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        } elseif ($type == 'month') {
            $yuear = teodate_month_12(3);//本月开始时间和结束时间
            foreach ($yuear as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end'] . "+1 day");
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]'")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }

    //销售部门
    public function market_list()
    {
        $users = M("Users");
        $list = $users->field('id,name')->where('groupid in(15,9,2) and is_delete!=1')->select();
        foreach ($list as $key => $val)
        {
            $day=hjd_curl("http://localhost/Api/find_market_day_counsumption?usersid=$val[id]");
            $week=hjd_curl("http://localhost/Api/find_market_week_counsumption?usersid=$val[id]");
            $month=hjd_curl("http://localhost/Api/find_market_month_counsumption?usersid=$val[id]");
            $list[$key]['day_counsumption']=$day['counsumption'];
            $list[$key]['week_counsumption']=$week['counsumption'];
            $list[$key]['month_counsumption']=$month['counsumption'];

        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }




}













