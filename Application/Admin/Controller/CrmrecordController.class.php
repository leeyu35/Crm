<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/3
 * Time: 10:19
 */

namespace Admin\Controller;
use Think\Controller;


class CrmrecordController extends Controller
{

    public function index(){
        if(cookie("u_id")=='')
        {
            $this->error('您还没有登录',U("/login"));
            exit;
        }
        
        if(I('post.date'))
        {
            $date=I('post.date');
        }else
        {
            $date=date("Y_m_d");
        }
        $this->date=$date;

        $str="./Crm_Record/$date.txt";
        $content=file_get_contents($str);
        $log=str_replace("\n","..<br>",$content);
        $this->log=$log;
        $this->display();
    }
}