<?php
$input_array = array('a', 'b', 'c', 'd', 'e');
echo "<pre>";
print_r(array_chunk($input_array, 2));
print_r(array_chunk($input_array, 2, true));
/*
$input_array = array("FirSt" => 1, "SecOnd" => 4);
print_r(array_change_key_case($input_array, CASE_LOWER));

/*
echo count($_SERVER);
echo $_SERVER['HTTP_USER_AGENT'];
echo "<pre>";
print_r($_ENV);
/*
$foo = array("bob", "fred", "jussi", "jouni", "egon", "marliese");
end($foo);//最后一个指针
prev($foo);//上一个
echo key($foo).current($foo); //获取key 和 值
/*
$bar = each($foo);
print_r($bar);
/*
$arr=array('琦玉','杰诺斯','饿狼');
list($a,,$c)=$arr;

echo $a;
//echo $b;
echo $c;
/*
function xdw($m,$n){
    $a="a";
    $array=array();
    for ($i=0;$i<$m;$i++)
    {
        $array[$i]=$a;
        //echo $a."<br>";
        $a++;
    }
    $b=0;

   // $num=count($array);
    while(count($array) > 1)
    {

        if($b%$n==0)
        {
            unset($array[$b]);
        }else
        {
            $array[]=$array[$b];
            unset($array[$b]);
        }
        $b++;
        print_r($array);
        echo "<br>";
    }



    return $array;
}

xdw(5,2);







/*
function dome(){
    $a=1;
    $b=2;
    $demo2=function ($str) use ($a,&$b)
    {
        echo $a;
        $b++;
        echo $b;
        echo $str."<br>";
    };
   return $demo2;
}

$fun=dome();
$fun("aaaaa");
$fun("bbbbb");
$fun("ccccc");
dome();
/*
function test($n)
{

    echo $n . "<br>";
    if($n>0) {
       test($n - 1);
        if($n>5)
        {
            return ;
        }
    }
    else
    {
        echo "-----------------------------<br>";
    }
    echo $n."<br>";
}

test(10);
/*
function hjd($a,$n){
        for($i=1;$i<=$a;$i++)
        {
            if(call_user_func_array($n,array($i)))
            {
                continue;
            }else
            {
                echo $i."<br>";
            }

        }

}

function one($i){
    if($i%3==0)
    {
        return true;
    }else
    {
        return false;
    }
}

class hjdcalss{
    public function tow($i){
        if(preg_match("/3/",$i))
        {
            return true;
        }else
        {
            return false;
        }
    }

    static function troee($i){
        if(preg_match('/2/',$i))
        {
            return false;
        }else
        {
            return true;
        }
    }
}

//hjd(100,"one");
//hjd(100,array(new hjdcalss(),"tow"));
hjd(100,array("hjdcalss","troee"));
exit;
/*
function hjd($num,$cabl)
{
    for($i=1;$i<=$num;$i++)
    {
        if($cabl($i)){
            continue;
        }
        echo $i."<br>";
    }

}
function cabll($i){
    if($i==strrev($i))
    {
        return true;
    }else
    {
        return false;
    }

}

hjd(10000,"cabll");


exit;
    $dirpath='./Application';
    function foreach_dir($dirpath)
    {
        //打开资源
        $dir=opendir($dirpath);
        while($file=readdir($dir))
        {
            if($file !='.' and $file !='..')
            {
                $file=$dirpath."/".$file;
                if(is_dir($file))
                {
                    echo "目录：".$file."<br>";
                    foreach_dir($file);
                }else{
                    echo "&nbsp &nbsp 文件：".$file."<br>";
                }
            }

        }
        //关闭资源
        closedir($dir);
    }

    foreach_dir($dirpath);




?>
*/