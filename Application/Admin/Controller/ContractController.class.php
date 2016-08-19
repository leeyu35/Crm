<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/16
 * Time: 10:02
 */
class ContractController extends CommonController
{
    public function index(){
        $this->display();
    }
    public function add(){
        $this->display();
    }
    public function addru(){
        dump($_POST);
    }
}