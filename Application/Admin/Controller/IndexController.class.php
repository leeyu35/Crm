<?php
namespace Admin\Controller;
use Think\Controller;
class IndexController extends Controller {
    public function index(){
        $users=M("users");
        $info=$users->find(1);
        echo $info['name'];
    }
    public function hjd(){
        echo "hjd";
        $this->success('hello',U("index"));
    }
}