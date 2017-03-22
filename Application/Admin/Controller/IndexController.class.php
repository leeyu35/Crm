<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        if(cookie("u_id")=='')
        {
            //重定向到New模块的Category操作
            $this->redirect('/login');
        }else
        {
            $this->redirect('/adminIndex');
        }


        $users=M("users");
        $info=$users->find(1);
        echo $info['name'];
    }
    public function usermessage(){

        $this->display();
    }
    public function hjd(){

        nianjia(25);
    }

}