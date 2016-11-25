<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/25
 * Time: 9:55
 */
//记录curl 接口 操作
function json_record($str){
    //记录日志
    $date=date("Y_m_d");
    $txt=fopen("./log/{$date}.txt",'a+');
    fwrite($txt,"[".date("Y-m-d H:i:s")." *{$_SERVER['REMOTE_ADDR']}*] $str \n");
    fclose($txt);

}