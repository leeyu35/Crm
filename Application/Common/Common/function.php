<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/2
 * Time: 14:33
 */
//记录CRM操作
function crm_record($str){
    //记录日志
    $date=date("Y_m_d");
    $txt=fopen("./Crm_Record/{$date}.txt",'a+');
    fwrite($txt,"[".date("Y-m-d H:i:s")." *{$_SERVER['REMOTE_ADDR']}*] $str \n");
    fclose($txt);

}

//getcul获取数据 并将json 转换成数组 s
function hjd_curl($url){
    //初始化
    $ch=curl_init();
    curl_setopt($ch,CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HEADER, 0);
// 3. 执行一个cURL会话并且获取相关回复
    $response = curl_exec($ch);
    $response=json_decode($response,true);
    if ($response  === FALSE) {
        echo "cURL 具体出错信息: " . curl_error($ch);
        exit;
    }
    $curl_info= curl_getinfo($ch);

// 4. 释放cURL句柄,关闭一个cURL会话
    curl_close($ch);
    return $response;
}

function account_daili($where=""){
    //如果有条件则先删除CRM消耗  所符合条件的数据


    $tabledata = M("accountdaily", "baiduapi_", "pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/msdb");//百度消耗
    $tabledata1=M("accountdaily", "tb_shenma_", "pgsql://rdspg:anmeng@rds455ekt1422z8sh7e2o.pg.rds.aliyuncs.com:3432/yushan");//百度消耗
    $account_day_cost = $tabledata->field('appid,date,baidu_cost_total as cost_total,baidu_view_total as view_total ,baidu_click_total as click_total')->where("date>='2017-01-01' and device='all' $where")->group('appid,date,baidu_cost_total,baidu_view_total,baidu_click_total')->select();
    $account_day_cost1= $tabledata1->field('appid,date,cost_total,view_total,click_total')->where("date>='2017-01-01' and device='all' $where")->group('appid,date,cost_total,view_total,click_total')->select();
    $result=array_merge($account_day_cost,$account_day_cost1);
    return $result;
}

