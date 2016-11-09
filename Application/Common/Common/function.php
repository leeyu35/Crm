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
