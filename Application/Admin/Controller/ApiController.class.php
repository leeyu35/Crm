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

            $qianri=Qianday();
            $time_start2=strtotime($qianri['start']);
            $time_end2=strtotime($qianri['end']);


            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            $qsum=$account_counsumption->where("$where and starttime>='$time_start2'  and starttime<'$time_end2' ")->sum("baidu_cost_total");

            if(!$sum){$sum="0";}
            if(!$qsum){$qsum="0";}
            $data['code'] = 200;
            $data['counsumption'] = number_format($sum,2);
            $data['qounsumption'] = number_format($qsum,2);
            $data['percentage']=number_format($sum/$qsum,2);
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
            $time_end=strtotime($zhouar[0]['end']);

            $shangzhou=shangzhou();

            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            $ssum=$account_counsumption->where("$where and starttime>='$shangzhou[start]'  and starttime<'$shangzhou[end]' ")->sum("baidu_cost_total");

            if(!$sum){$sum="0";}
            if(!$ssum){$ssum="0";}
            $data['code'] = 200;
            $data['counsumption'] = number_format($sum,2);
            $data['qounsumption'] = number_format($ssum,2);
            $data['percentage']=number_format($sum/$ssum,2);
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

            $yuear2=teodate_smonth();//本月开始时间和结束时间
            $time_start2=strtotime($yuear2['start']);
            $time_end2=strtotime($yuear2['end']);
            if(I('type')!='all')
            {
                $where="xsid=$id";
            }else
            {
                $where="id != 0";
            }
            $sum=$account_counsumption->where("$where and starttime>='$time_start'  and starttime<'$time_end' ")->sum("baidu_cost_total");
            $ssum=$account_counsumption->where("$where and starttime>='$time_start2'  and starttime<'$time_end2' ")->sum("baidu_cost_total");
            if(!$sum){$sum="0";}
            if(!$ssum){$ssum="0";}
            $data['code'] = 200;
            $data['counsumption'] = number_format($sum,2);
            $data['qounsumption'] = number_format($ssum,2);
            $data['percentage']=number_format($sum/$ssum,2);
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
                    $time_end = strtotime($val['end']);
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
                    $time_end = strtotime($val['end']);
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
        $time_end=strtotime($zhouar[0]['end']);

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

        $strat=strtotime($zhouar[0]['start']);
        $end=strtotime($zhouar[0]['end']);
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
    //日新增合同
    public function contract_day(){
        $customer=M('Contract');
        $zhouar=Yesterday();

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
                $list=$backmoney->field('id,advertiser,money,payment_time,account,market,ctime,appname,users2')->where(" payment_time >='$start' and is_huikuan=1 and audit_1!=2 and audit_2!=2")->select();
                break;
            case 'fukuan':
                $list=$backmoney->field('id,advertiser,money,payment_time,account,market,ctime,appname,users2')->where(" payment_time >='$start' and (payment_type=1 or payment_type=2) and audit_1!=2 and audit_2!=2")->select();
                break;
            case 'bukuan':
                $list=$backmoney->field('id,advertiser,money,payment_time,account,market,ctime,appname,users2,note')->where("payment_type=3 and audit_1!=2 and audit_2!=2")->select();
                break;
        }
        foreach ($list as $key=>$val)
        {
            $K=kehu($val['advertiser']);//公司名称
            $market=users_info($val['market']);//销售名称
            $shangwu=users_info($val['users2']);//销售名称
            $account=M('Account')->field('a_users')->find($val['account']?$val['account']:'');
            $list[$key]['advertisername']=$K['advertiser'];
            $list[$key]['account_name']=$account['a_users'];
            $list[$key]['ctime']=date("Y-m-d",$val['ctime']);
            $list[$key]['payment_time']=date("Y-m-d",$val['payment_time']);
            $list[$key]['money']=number_format($val['money'],2);
            $list[$key]['market']=$market['name'];
            $list[$key]['business']=$shangwu['name'];
            $list[$key]['business']=$val['note']?$val['note']:'';
        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }


    //欠款公司最高的20条，并取得她的近三次回款记录
    public function diankuan_compare(){
        $customer=M("Customer");//公司
        $backmoney=M("RenewHuikuan");//续费回款表
        //$dk_sm=$customer->field('id,advertiser,yu_e,huikuan,huikuan-yu_e as yue')->order("yue asc")->select();
        $dk_sm=$customer->query("select a.* from (SELECT id,advertiser,submituser,yu_e,huikuan,huikuan-yu_e as yue FROM jd_customer) a where a.yue<0 order by a.yue asc");
        foreach ($dk_sm as $key=>$val)
        {
            $zuijinhk=$backmoney->where("advertiser=$val[id] and is_huikuan=1")->field('payment_time,money')->order("payment_time desc")->limit('0,5')->select();

            foreach ($zuijinhk as $k=>$v)
            {
                $zuijinhk[$k]['payment_time']=date("Y-m-d",$v['payment_time']);
                $zuijinhk[$k]['money']=number_format($v['money'],2);
                //number_format
            }

            usort($zuijinhk,function($a,$b){
                if($a>$b)
                {
                    return 1;
                }elseif($a<$b)
                {
                    return -1;
                }elseif($a==$b)
                {
                    return 0;
                }
            });

            $dk_sm[$key]['huikuan_record']=$zuijinhk;
            $dk_sm[$key]['yu_e']=number_format($val['yu_e'],2);
            $dk_sm[$key]['huikuan']=number_format($val['huikuan'],2);
            $dk_sm[$key]['yue']=number_format($val['yue'],2);
            //公司负责销售
            $u=users_info($val['submituser']);
            $dk_sm[$key]['market']=$u['name'];

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
            $time_end=strtotime($zhouar[0]['end']);
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
        $time_end=strtotime($zhouar[0]['end']);
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
            $time_end=strtotime($zhouar[0]['end']);
        }elseif($type=='month')
        {
            $yuear=teodate_month();//本月开始时间和结束时间

            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']."+1 day");
        }
        $list=$customer->field('a.id,a.advertiser as aid,a.contract_no,a.users2,a.isguidang,a.iszuofei,a.appname,a.market,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.ctime>'$time_start' and a.ctime<'$time_end' ")->order("ctime desc")->select();

        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $market=users_info($val['market']);
            $list[$key]['submituser']=$uindo['name'];
            $list[$key]['marketname']=$market['name'];
            $list[$key]['ctime']=date("Y-m-d",$val['ctime']);
        }

        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }

    //根据时间段展示消耗列表
    public function SpecifyDate_counsumption_list(){
        $type=I('get.type');
        $xiaohao=M("AccountConsumption");
        if($type=='yesterday')
        {
            $zuori = Yesterday();//昨日开始时间和结束时间
            $time_start=strtotime($zuori['start']);
            $time_end=strtotime($zuori['end']);
        }elseif ($type=='week')
        {
            $zhouar=teodate_week2(1,'Monday');//本周开始时间和结束时间
            $time_start=strtotime($zhouar[0]['start']);
            $time_end=strtotime($zhouar[0]['end']);
        }elseif($type=='month')
        {
            $yuear=teodate_month();//本月开始时间和结束时间

            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
        }elseif($type=='smonth')
        {
            $yuear=teodate_smonth();//上月开始时间和结束时间
            $time_start=strtotime($yuear['start']);
            $time_end=strtotime($yuear['end']);
        }elseif ($type=='custom')
        {
            $time_start=strtotime(I('get.start'));
            $time_end=strtotime(I('get.end'));
        }

        $users=M("Users");
        if(I('get.usersid')!='')
        {
            $where=" and xiaohao.xsid=".I('get.usersid');
        }

        //对比日期
        $a=date("Y-m-d",$time_start);
        $b=date("Y-m-d",$time_end);
        $diff=date_diff(date_create($a),date_create($b));
        $bidui_start_time=strtotime(date("Y-m-d",$time_start)." -{$diff->d} day");

        $xiaohaolist=$xiaohao->field("sum(xiaohao.baidu_cost_total) as baidu_cost_total,xiaohao.appid,xiaohao.htid,zhanghu.a_users,zhanghu.appname,zhanghu.id as account_id,gongsi.id as avid,gongsi.advertiser,xiaohao.xsid,xiaohao.semid")->join("xiaohao left join jd_account zhanghu on xiaohao.appid=zhanghu.appid left join jd_customer gongsi on xiaohao.avid=gongsi.id")->where("xiaohao.starttime>='$time_start'  and xiaohao.starttime<'$time_end' $where")->group("xiaohao.appid,gongsi.id,xiaohao.htid,gongsi.advertiser,zhanghu.a_users,xiaohao.xsid,xiaohao.semid,zhanghu.appname,zhanghu.id")->order("baidu_cost_total desc")->select();

        $xiaohaolist_db=$xiaohao->field("sum(xiaohao.baidu_cost_total) as baidu_cost_total,xiaohao.appid,xiaohao.htid,zhanghu.a_users,zhanghu.appname,zhanghu.id as account_id,gongsi.id as avid,gongsi.advertiser,xiaohao.xsid,xiaohao.semid")->join("xiaohao left join jd_account zhanghu on xiaohao.appid=zhanghu.appid left join jd_customer gongsi on xiaohao.avid=gongsi.id")->where("xiaohao.starttime>='$bidui_start_time'  and xiaohao.starttime<'$time_start' $where")->group("xiaohao.appid,gongsi.id,xiaohao.htid,gongsi.advertiser,zhanghu.a_users,xiaohao.xsid,xiaohao.semid,zhanghu.appname,zhanghu.id")->order("baidu_cost_total desc")->select();

        /*
        */

        foreach ($xiaohaolist as $key=>$val) {
            
            foreach ($xiaohaolist_db as $k => $v)
            {
                if($v['appid']==$val['appid'])
                {
                    if($diff->d > 15)
                    {
                        $xiaohaolist[$key]['contrast']='';
                    }
                    else{
                        $xiaohaolist[$key]['contrast']=$v['baidu_cost_total'];
                    }
                }

            }
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
            //主体
            $htinfo=M('Contract')->field('agent_company')->find($val['htid']);
            $zhuti=M("AgentCompany")->field('companyname')->find($htinfo['agent_company']);
            $xiaohaolist[$key]['zt_company']=$zhuti['companyname']?$zhuti['companyname']:'';




            //$xiaohaolist[$key]['baidu_cost_total']=number_format($val['baidu_cost_total']);
        }
        //表格导出
        if(I('get.excel')=='1')
        {
            foreach ($xiaohaolist as $key=>$val)
            {
                $excel[$key]['a_users']=$val['a_users'];
                $excel[$key]['appname']=$val['appname'];
                $excel[$key]['advertiser']=$val['advertiser'];
                $excel[$key]['baidu_cost_total']=$val['baidu_cost_total'];
                $excel[$key]['sem']=$val['sem'];
                $excel[$key]['market']=$val['market'];
                $excel[$key]['zt_company']=$val['zt_company'];
            }

            $filename="xiaohao_excel";
            $headArr=array("账户","APP名称",'公司名称','消耗','SEM','销售','代理公司');

            if(!getExcel($filename,$headArr,$excel))
            {
                $this->error('没有数据可导出');
            };
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
            $time_end=strtotime($zhouar[0]['end']);
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
                $time_end = strtotime($val['end']);
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and avid=$avid")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        } elseif ($type == 'month') {
            $yuear = teodate_month_12(12);//本月开始时间和结束时间

            foreach ($yuear as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end']);
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
                $time_end = strtotime($val['end']);
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]' ")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        } elseif ($type == 'month') {
            $yuear = teodate_month_12(12);//本月开始时间和结束时间

            foreach ($yuear as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end']);
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]'")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }
    //账户充值记录
    public function account_chongzhi_recode(){
        $id = I('get.id');
        if ($id == '') {
            $data['code'] = 400;
            $data['mes'] = '参数错误';
            $this->response($data, 'json');
            exit;
        }else
        {
            $renew_hk=M("RenewHuikuan");
            $list=$renew_hk->field('id,money,payment_time')->where("account=$id")->order("payment_time desc")->select();
            foreach ($list as $key=>$val)
            {
                $list[$key]['payment_time']=date("Y-m-d",$val['payment_time']);
            }
            $data['code'] = 200;
            $data['data'] = $list;
            $this->response($data,'json');
        }
    }
    //账户的list
    public function account_list_acinfo(){

        $uid=I('get.usersid');
        $uinfo=users_info($uid);
        dump($uinfo);
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
            $hetong=M("Contract")->field('contract_no,advertiser')->find($info['contract_id']);
            //账户负责人
            $fzr=M("Account_users")->field("b.name")->join(" a left join jd_users b on a.u_id = b.id ")->where("account_id=$id")->find();

            //公司名称
            $ad=M("Customer")->field('advertiser')->find($hetong['advertiser']);
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
    $type=I('get.type');
    $users = M("Users");
    $list = $users->field('id,name')->where('groupid=10 and is_delete!=1')->select();
    foreach ($list as $key => $val)
    {
        if($type=='day')
        {
            $day=hjd_curl("http://localhost/Api/find_sem_day_counsumption?usersid=$val[id]");
            $list[$key]['counsumption']=$day['counsumption'];
        }elseif($type=='week')
        {
            $week=hjd_curl("http://localhost/Api/find_sem_week_counsumption?usersid=$val[id]");
            $list[$key]['counsumption']=$week['counsumption'];
        }elseif($type=='month')
        {
            $month=hjd_curl("http://localhost/Api/find_sem_month_counsumption?usersid=$val[id]");
            $list[$key]['counsumption']=$month['counsumption'];
        }



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
        $list=$account->field('id,a_users,appname')->where("id in($idin)")->select();

        foreach ($list as $key=>$val)
        {
            if($type=='day')
            {
                $list[$key]['counsumption']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=day&semid=$id");
            }elseif($type=='week')
            {
                $list[$key]['counsumption']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=week&semid=$id");
            }else
            {
                $list[$key]['counsumption']=hjd_curl("http://localhost/Api/sem_account_counsumption_3_line?id=$val[id]&type=month&semid=$id");
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
        $semid=I('get.semid');
        $xiaohao = M("AccountConsumption");
        if ($type == 'day') {
            //最近七天
            $j7['start']=$time_start=strtotime(date("Y-m-d")."-3 day");
            $j7['end']=$time_end=strtotime(date("Y-m-d"));
            $list=$xiaohao->field('date,sum(baidu_cost_total) as consumption')->where("starttime>='$j7[start]'  and starttime<'$j7[end]' and appid='$appid[appid]' and semid='$semid'")->group('date')->order("date asc")->select();

        } elseif ($type == 'week') {
            $zhouar = teodate_week(3, 'Monday');//本周开始时间和结束时间

            foreach ($zhouar as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end']);
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]' and semid='$semid'")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        } elseif ($type == 'month') {
            $yuear = teodate_month_12(3);//本月开始时间和结束时间

            foreach ($yuear as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end']);
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and appid='$appid[appid]' and semid='$semid'")->sum('baidu_cost_total');
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
        $type=I('get.type');
        foreach ($list as $key => $val)
        {
            if($type=='day')
            {
                //日消耗和日新增合同
                $day=hjd_curl("http://localhost/Api/find_market_day_counsumption?usersid=$val[id]");
                $c_day=hjd_curl("http://localhost/Api/contract_day?usersid=$val[id]");
                $list[$key]['counsumption']=$day['counsumption'];
                $list[$key]['contract']=$c_day['count'];
            }elseif($type=='week')
            {
                $week=hjd_curl("http://localhost/Api/find_market_week_counsumption?usersid=$val[id]");
                $c_week=hjd_curl("http://localhost/Api/contract_week?usersid=$val[id]");
                $list[$key]['counsumption']=$week['counsumption'];
                $list[$key]['contract']=$c_week['count'];
            }elseif($type=='month')
            {
                $month=hjd_curl("http://localhost/Api/find_market_month_counsumption?usersid=$val[id]");
                $c_month=hjd_curl("http://localhost/Api/contract_month?usersid=$val[id]");
                $list[$key]['counsumption']=$month['counsumption'];
                $list[$key]['contract']=$c_month['count'];
            }





        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }

    //点击销售进入的销售客户列表页面
    public function find_marker_contract_counsumption_list(){
        $id=I('get.usersid');
        if($id=='')
        {
            $data['code']=400;
            $data['mes']='缺少参数';
            $this->response($data,'json');
        }else {
            $contract=M("Contract");
            $consumption=M("AccountConsumption");
            $yesday=strtotime(date("Y-m-d")." -1 day");

            $list=$contract->field('id,contract_no,advertiser,ctime')->where("market='$id'")->select();
            $name=users_info($id);
            foreach ($list as $key=>$val)
            {
                $gongsi=kehu($val['advertiser']);
                $list[$key]['advertiser']=$gongsi['advertiser'];
                $list[$key]['ctime']=date("Y-m-d",$val['ctime']);

                //合同昨日消耗
                $sum2=$consumption->where("starttime>='$yesday' and htid=$val[id]")->sum("baidu_cost_total");

                $list[$key]['consumption']=$sum2;

            }

            $data['code'] = 200;
            $data['data'] = $list;
            $data['name'] = $name['name'];
            $this->response($data,'json');
        }

    }
    //根据合同id 获取合同的日周月消耗
    public function contract_date_counsumption_line()
    {
        $contractid=I('get.id');
        $type = I('get.type');
        $xiaohao = M("AccountConsumption");
        if ($type == 'day') {
            //最近七天
            $j7=date_daye_j7();

            $list=$xiaohao->field('date,sum(baidu_cost_total) as consumption')->where("starttime>='$j7[start]'  and starttime<'$j7[end]' and htid=$contractid")->group('date')->order("date asc")->select();

        } elseif ($type == 'week') {
            $zhouar = teodate_week(4, 'Monday');//本周开始时间和结束时间 正序

            foreach ($zhouar as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end']);
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and htid=$contractid")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        } elseif ($type == 'month') {
            $yuear = teodate_month_12(12);//本月开始时间和结束时间
           
            foreach ($yuear as $key=>$val)
            {
                $time_start = strtotime($val['start']);
                $time_end = strtotime($val['end']);
                $Consumption=$xiaohao->where("starttime>='$time_start'  and starttime<'$time_end' and htid=$contractid")->sum('baidu_cost_total');
                $list[$key]['date']=$val['start'];
                $list[$key]['consumption']=$Consumption?$Consumption:0;
            }

        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');
    }

    public function diankuan_excel(){
        $customer=M("Customer");
        $dk_sm=$customer->query("select a.* from (SELECT id,advertiser,submituser,yu_e,huikuan,huikuan-yu_e as yue FROM jd_customer) a where a.yue<0 order by a.yue asc");

        foreach ($dk_sm as $key=>$value)
        {
            $list2[$key]["advertiser"]=$value["advertiser"];
            $list2[$key]["yue"]=-$value["yue"];
            //公司负责销售
            $u=users_info($val['submituser']);
            $list2[$key]['market']=$u['name'];
        }
        $filename="diankuan_excel";
        $headArr=array("公司名称","垫款总额","销售");

        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }

    //手动添加数据
    public  function consumption_manual(){

        $appid=I('get.appid');//APPID
        $date=I('get.dates');//日期

        $account_counsumption=M("AccountConsumption");
        $account_counsumption->where("appid ='$appid' and date in ($date)")->delete();

        $where="and appid='$appid' and date in ($date)";
        $account_day_cost = account_daili($where);//消耗数据  百度-神马 合并封装
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
            $data2['xf_fandian']=$val['fandian'];
            $data2['mt_fandian']=account_xs_id($val['appid'],'mt_fandian');
            if($account_counsumption->add($data2))
            {
                $count++;
            }
        }
        $data['code']=200;
        $data['msg']='成功添加'.$count."条记录消费记录。来自（read_today_account_consumption_data）";
        $this->ajaxReturn($data);


    }

    //已回款续费根据客户ID 获取客户的已回款续费列表
    public function customer_yihuikuanxufei(){
        $id=I('get.id');
        $Yihuikuanxufei=M("Yihuikuanxufei");
        $list=$Yihuikuanxufei->field('a.money,a.time,a.xf_id,b.advertiser')->join("a left join __CUSTOMER__ b on a.avid = b.id ")->where("a.avid=$id")->order("a.time desc")->select();
        foreach($list as $key => $val)
        {
            //产品线
            $xfinfo=M("RenewHuikuan")->field('account')->find($val['xf_id']);
            $account=M("Account")->field('prlin_id')->find($xfinfo['account']);
            $prlin=M('ProductLine')->field('name')->find($account['prlin_id']);
            $list[$key]['prlin']=$prlin['name'];
            $list[$key]['time']=date("Y-m-d",$val['time']);
        }
        $data['code'] = 200;
        $data['data'] = $list;
        $this->response($data,'json');

    }
    //修改合同所属销售返回
    public function upusers()
    {
        $hetong = M("Customer");
        if (I('get.type') == 'business') {
            $ziduan = 'business';
        }
        if (I('get.type') == 'market') {
            $ziduan = 'submituser';
        }
        if ($hetong->where("id=" . I("get.id"))->setField($ziduan, I("get.users"))) {
            if (I('get.type') == 'market') {
                M('Contract')->where('advertiser=' . I('get.id'))->save(array("market" => I('get.users')));
            }
            $data['code'] = 200;
            $data['mes'] = 'success';
        }else
        {
            $data['code'] = 400;
            $data['mes'] = 'false';
        }
        $this->response($data, 'json');
    }


    /*
     * 账户服务类型
     * 130001 页面+优化
     * 130002 页面
     * 130003 优化
     * 130004 无服务
     *
     * */
    public function account_server_type(){
        $account=M('Account');
        $zi=$account->field('appid')->where("appid!=''")->select(false);
        $tabledata = M("third_account", "tb_", "pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/yushan");//百度消耗
        $list=$tabledata->where("service_type!=0 and appid in ($zi)")->select();
        echo $tabledata->_sql();
        dump($list);
    }

    /*
     * 消耗列表 日：今天和15日比对  周：传入日期和倒数7周数据  月:本年度月份消耗
     * 2017年3月17日15:00:31   --hjd
     *
     * */
    public function consume_list_to_date(){
        $type=I('get.type');//type
        $view_type=I('get.view_type');//显示类型 1百度币 2客户消耗 3公司消耗
        $time_start=strtotime(I('get.start'));//开始时间
        $time_end=strtotime(I('get.end'));//结束时间
        $xiaohao=M("AccountConsumption");
        $users=M("Users");
        if($type=='day')
        {
            $datear=day_15();
        }elseif($type=='week')
        {
            $datear=teodate_week(8,"Monday",I('get.start')); //获取周日期的开始时间和结束时间
        }elseif($type=='month')
        {
            $datear=teodate_month_12(date("m"));//获取本年月份
        }
        if(I('get.usersid')!='')
        {
            $where=" and xiaohao.xsid=".I('get.usersid');
        }


        foreach ($datear as $k=>$v)
        {
            $zhouqi[$k]=$xiaohao->field("sum(xiaohao.baidu_cost_total) as baidu_cost_total,SUM (xiaohao.baidu_cost_total/(1+xiaohao.xf_fandian)) AS kehu_xiaohao,SUM ((xiaohao.baidu_cost_total/(1+xiaohao.xf_fandian))/(1+xiaohao.mt_fandian)) AS chengben_xiaohao,xiaohao.appid,zhanghu.a_users,zhanghu.endtime,zhanghu.appname,gongsi.submituser as marketid,zhanghu.id as account_id,gongsi.id as avid,gongsi.advertiser")->join("xiaohao left join jd_account zhanghu on xiaohao.appid=zhanghu.appid left join jd_customer gongsi on xiaohao.avid=gongsi.id")->where("xiaohao.starttime>='".strtotime($v[start])."'  and xiaohao.starttime<'".strtotime($v[end])."' $where")->group("xiaohao.appid,gongsi.id,xiaohao.htid,gongsi.advertiser,zhanghu.a_users,zhanghu.appname,zhanghu.id")->order("baidu_cost_total desc")->select();
            $date_top[]=$v['start']."到".$v['end'];
            //$xiaohaolist=$xiaohao->field("sum(xiaohao.baidu_cost_total) as baidu_cost_total,xiaohao.appid,xiaohao.htid,zhanghu.a_users,zhanghu.appname,zhanghu.id as account_id,gongsi.id as avid,gongsi.advertiser,xiaohao.xsid,xiaohao.semid")->join("xiaohao left join jd_account zhanghu on xiaohao.appid=zhanghu.appid left join jd_customer gongsi on xiaohao.avid=gongsi.id")->where("xiaohao.starttime>='$time_start'  and xiaohao.starttime<'$time_end' $where")->group("xiaohao.appid,gongsi.id,xiaohao.htid,gongsi.advertiser,zhanghu.a_users,xiaohao.xsid,xiaohao.semid,zhanghu.appname,zhanghu.id")->order("baidu_cost_total desc")->select();

        }


        foreach ($datear as $dakey=>$daval)
        {



        foreach ($zhouqi[$dakey] as $key=>$val)
        {


            if($val['marketid'])
            {
                $xs=$users->field('name')->find($val['marketid']);
            }else
            {
                $xs['name']='';
            }
            /*
            if($val['semid'])
            {
                $sem=$users->field('name')->find($val['semid']);
            }else
            {
                $sem['name']='';
            }*/

            $zhouqi[$dakey][$key]['a_users']=$val['a_users']?$val['a_users']:'';
            $zhouqi[$dakey][$key]['advertiser']=$val['advertiser']?$val['advertiser']:'';
            $zhouqi[$dakey][$key]['xsid']=$val['xsid']?$val['xsid']:'';
           // $zhouqi[$dakey][$key]['semid']=$val['semid']?$val['semid']:'';
            $zhouqi[$dakey][$key]['market']=$xs['name']?$xs['name']:'';
            $zhouqi[$dakey][$key]['date_to']=$date_top;
           // $zhouqi[$dakey][$key]['sem']=$sem['name']?$sem['name']:'';

            //主体
            /*
            $htinfo=M('Contract')->field('agent_company')->find($val['htid']);
            $zhuti=M("AgentCompany")->field('companyname')->find($htinfo['agent_company']);
            $xiaohaolist[$key]['zt_company']=$zhuti['companyname']?$zhuti['companyname']:'';
            */
            foreach ($datear as $k=>$v)
            {
                foreach ($zhouqi[$k] as $k1=>$v1)
                {
                    if($val['appid']==$v1['appid'] and $val['xsid']==$v1['xsid'])
                    {
                        if($view_type==1)
                        {
                            $dateview='baidu_cost_total';
                        }elseif($view_type==2)
                        {
                            $dateview='kehu_xiaohao';
                        }elseif($view_type==3)
                        {
                            $dateview='chengben_xiaohao';
                        }
                        $zhouqi[$dakey][$key]['xiaohao_date'][$v[start]."到".$v[end]]=round($v1[$dateview],2);
                    }
                }
            }

        }

        }
        /*
        for ($i=count($zhouqi)-1;$i>=0;$i--)
        {
            foreach ($zhouqi[$i] as $k1=>$v1)
            {
               // $zhouqi[$i][$k1]['xiaohao_date'][$daval[start]."到".$daval[end]]=$this->serach($zhouqi);
            }
        }*/
        $date=$zhouqi[count($datear)-1];

        //表格导出
        if(I('get.excel')=='1')
        {
            foreach ($date as $key=>$val)
            {
                $excel[$key]['a_users']=$val['a_users'];
                $excel[$key]['appname']=$val['appname'];
                $excel[$key]['advertiser']=$val['advertiser'];
                $excel[$key]['market']=$val['market'];
                foreach ($val['date_to'] as $k=>$v)
                {
                    $excel[$key][$v]=0; //默认全部为零
                    foreach ($val['xiaohao_date'] as $k2=>$v2)
                    {
                        if($v==$k2)
                        {
                            $excel[$key][$v]=$v2; //修改相对应的值
                        }
                    }
                }

            }

            $filename="消耗_excel";
            $riqi_tablelalab=$date[0]['date_to'];
            $headArr=array("账户","APP名称",'公司名称','销售');

            $headArr=array_merge($headArr,$riqi_tablelalab);


            if(!getExcel($filename,$headArr,$excel))
            {
                $this->error('没有数据可导出');
            };
        }

        $data['code'] = 200;

        $as=$this->array_unset($zhouqi[count($datear)-1],'appid');
        $data['data']=$as;

        $this->response($data,'json');


    }
//二维数组去除特定键的重复项
    public function array_unset($arr,$key){   //$arr->传入数组   $key->判断的key值
        //建立一个目标数组
        $res = array();
        foreach ($arr as $value) {
            //查看有没有重复项

            if(isset($res[$value[$key]]) and $value['endtime']!='4092599349'){

                //有：销毁
                unset($value[$key]);

            }
            else{
                $res[$value[$key]] = $value;
            }
        }

        foreach ($res as $k1=>$v1)
        {
            $ar[]=$v1;
        }

        return $ar;
    }
    //分配优化师同步
    public function set_account_users(){
        $appid=I('get.appid');
        $users_name=I('get.name');
        $account=M("Account");
        $users=M("Users");
        $sem=$users->where("name='$users_name'")->find();
        $account_find=$account->where("appid='$appid' and endtime='4092599349'")->find();

        if($sem['id']=='' or $account_find['id']=='')
        {

            $data['code'] = 500;
            $data['msg'] = 'Because I couldn\'t find the user or account , So the synchronization failure';
            $this->response($data,'json');
            exie;
        }
        $account_sem_set=M("AccountUsers");
        $select=$account_sem_set->where("account_id=$account_find[id]")->count();
        if($select<1) {
            $date['account_id'] = $account_find[id];
            $date['u_id'] = $sem['id'];
            if ($account_sem_set->add($date))
            {
                $data['code'] = 200;
                $data['msg'] = 'success';
                $this->response($data,'json');
            }else
            {
                $data['code'] = 500;
                $data['msg'] = 'Unknown reason! Synchronization failure! account add error! ';
                $this->response($data,'json');
                exie;
            }
        }else{

            if($account_sem_set->where("account_id=$account_find[id]")->setField('u_id',$sem['id']))
            {
                $data['code'] = 200;
                $data['msg'] = 'success';
                $this->response($data,'json');
            }else
            {
                $data['code'] = 500;
                $data['msg'] = 'Unknown reason! Synchronization failure! ';
                $this->response($data,'json');
                exie;
            }

        }


    }

    //根据appid 获取所属销售
    public function get_appid_markert(){
        $appid=I("post.appid");
        $appid=str_replace(",","','",$appid);

        $account=M("Account");
        $account_info=$account->field('a.appid,a.contract_id,b.advertiser,c.submituser,d.name')->join("a left join __CONTRACT__ b  on b.id=a.contract_id left join __CUSTOMER__ c  on c.id=b.advertiser left join __USERS__ d on d.id=c.submituser")->where("a.appid in ('$appid') and a.endtime='4092599349'")->select();
        $data['code'] = 200;
        $data['msg'] = 'success!';
        $data['date'] = $account_info;
        $this->response($data,'json');
    }
}













