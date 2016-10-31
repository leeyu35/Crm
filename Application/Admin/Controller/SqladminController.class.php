<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/10/31
 * Time: 16:44
 */

namespace Admin\Controller;


class SqladminController extends CommonController
{
    public function index(){

        $this->display();
    }

    //修改已归档合同为未归档状态
    public function up_contract_guidang(){
        $id=I('post.contract_id');
        if($id=='')
        {
            $this->error('参数错误!');
        }
        $query=M("contract")->where("id =$id")->save(array("isguidang"=>0));
        $sqltext="Sql：".M("contract")->_sql();
        if($query){

            $this->success("$sqltext <br>Return：Success",U("index"),10);

        }else
        {
            $this->error("$sqltext <br>Return：Oh~no , Error",U("index"),10);
        }


    }

    //直接执行sql
    public  function sql(){
         $sqlstr=I('post.sqlstr');
         if($sqlstr=='')
         {
             $this->error('参数错误!');
         }
         $Model = new \Think\Model(); // 实例化一个model对象 没有对应任何数据表
         $nume=$Model->execute($sqlstr);
         $num="受影响数：".$nume;
         $sqltext="Sql：".$Model->_sql();
        if($nume){

            $this->success("$sqltext <br> Return：Success<br>$num",U("index"),10);

        }else
        {
            $this->error("$sqltext <br> Return：Oh~no , Error<br>$num",U("index"),10);
        }



    }

}