<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2017/3/7
 * Time: 18:51
 */
namespace  Foo;
function strlen(){}
const INI_ALL=3;
class Exception{}

$a=\strlen("Hi");
$b=\INI_ALL;
$c=new \Exception('error');

echo \strlen("he");