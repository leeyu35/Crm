<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2017/3/7
 * Time: 18:38
 */

include 'file1.php';
use \Foo\Bar\subnamespace;
const FOO=2;
function foo(){echo 'file2.php';}
class foo{
    static function staticmethod(){echo 'file2 stat';}
}

foo();
foo::staticmethod();
echo FOO;
echo '<br>';
subnamespace\foo();
echo subnamespace\Foo;
subnamespace\foo::staticmethod();
echo '<br>';



