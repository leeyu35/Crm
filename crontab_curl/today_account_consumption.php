<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/22
 * Time: 10:41

 */
ini_set('max_execution_time', '0');
include_once ("function.php");
//初始化
$ch=curl_init();
curl_setopt($ch,CURLOPT_URL,'http://localhost/Admin/LinuxTime/read_today_account_consumption_data');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_HEADER, 0);
// 3. 执行一个cURL会话并且获取相关回复
$response = curl_exec($ch);

$response=json_decode($response,true);

if ($response  === FALSE ){
    json_record('Error:访问页面失败 导致记录账户每日消耗失败!');
    //echo "cURL 具体出错信息: " . curl_error($ch);

 }elseif($response['code']==200){
    json_record($response['msg']);
    echo 'success';
}elseif($response['code']==403){
    json_record($response['msg']);
}

var_dump($response);
$curl_info= curl_getinfo($ch);
// 4. 释放cURL句柄,关闭一个cURL会话
curl_close($ch);


