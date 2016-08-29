<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/8/23
 * Time: 11:18
 */

//根据多个产品线ID返回多个产品线名称，
function product_line($idstr){

    $product_line=M('ProductLine');
    $list= $product_line->field('id,name')->where("id in ($idstr)")->select();

    foreach ($list as $key=>$val)
    {

        $string.='<span class="lin_pr">'.$val['name'].'</span>';
    }
    $string=substr($string,0,-1);
    return $string;

}

//根据客户ID 返回客户信息
function kehu($id){
    $kehu=M("Customer");
    $info=$kehu->find(($id));

    return $info;


}