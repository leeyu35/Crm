<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2017/3/7
 * Time: 19:32
 */

require("4.php");
use hjd\Hjd;


class hjd2 extends Hjd{
    function index(){
        echo $this->helloword();
    }
}

$hjd=new hjd2();

$hjd->index();