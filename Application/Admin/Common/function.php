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

//返回用户组名称
function group_name($idstr){

    $product_line=M('Groupl');
    $list= $product_line->field('id,group_name')->where("id in ($idstr)")->select();

    foreach ($list as $key=>$val)
    {
        $string.='<span class="lin_pr">'.$val['group_name'].'</span>';
    }
    $string=substr($string,0,-1);
    return $string;

}

//权限条件反馈
/*传递模块名称，根据模块名称返回index方法 所包含的权限
 *
 * */
function quan_where($module,$join="",$setype=""){

    $rbac=M("Rbac");
    $one=$rbac->where("module='$module'")->find();

    if($one!="") {
            $array=explode(",",$one['index_show']);
            if(in_array(cookie('u_groupid'),$array))
            {
                if($join==""){
                    $where="id!='hjd'";
                }else
                {
                    $where=$join.".id!='hjd'";
                }

            }else
            {
                if($join==""){
                    $where="submituser=".cookie('u_id');
                }else
                {
                    $where=$join.".submituser=".cookie('u_id');
                }

            }
            if($setype=='')
            {


            //如果是二级审核 则一级审核通过才显示
            //组
            $group=M("Groupl")->find(cookie("u_groupid"));
                $group=$group;
                $group_name=$group['group_name'];

                if($group_name!='超级管理员' )
                {
                    $array_s2=explode(",",$one['audit_2']);

                    if(in_array(cookie('u_groupid'),$array_s2))
                    {
                        //echo '1';
                        if($join==""){
                            $where.=" and audit_1=1 ";
                        }else
                        {
                            $where.=" and ".$join.".audit_1=1 ";
                        }

                    }
                }
            }
    }

    return $where;
}
//用户管理权限
function quan_users_where($module,$join=""){

    $rbac=M("Rbac");
    $one=$rbac->where("module='$module'")->find();
    if($one!="") {
        $array=explode(",",$one['index_show']);
        if(in_array(cookie('u_groupid'),$array))
        {
                $where=$join."id!='hjd'";
        }else
        {
                $where=$join."id=".cookie('u_id');
        }
    }
    return $where;
}

//s审核权限

function shenhe($module,$type){
    $rbac=M("Rbac");
    $one=$rbac->where("module='$module'")->find();
    if($one!="") {
        $array=explode(",",$one[$type]);
        if(in_array(cookie('u_groupid'),$array))
        {
            $code='200';
        }else
        {
            $code='503';
        }
    }
    return $code;

}

//获取用户信息
function users_info($id){
    $users=M("Users");
    $info=$users->find($id);
    return $info;

}


function hjd(){

    echo "侯建东";
}
//获取ip
function getIPLoc_QQ($queryIP){
    $url = 'http://ip.qq.com/cgi-bin/searchip?searchip1='.$queryIP;

    $ch = curl_init($url);
    curl_setopt($ch,CURLOPT_ENCODING ,'gb2312');
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true) ; // 获取数据返回
    $result = curl_exec($ch);
    $result = mb_convert_encoding($result, "utf-8", "gb2312"); // 编码转换，否则乱码
    curl_close($ch);
    preg_match("@<span>(.*)</span></p>@iU",$result,$ipArray);
    $loc = $ipArray[1];
    return $loc;
}
//获取代办数目
function daiban(){
    //组
    $group=M('Groupl')->field('group_name')->find(cookie('u_groupid'));
    $group=$group;
    $group_name=$group['group_name'];
    //垫款
    $Diankuan=M("Diankuan");
    switch ($group_name)
    {
        case "商务":
            $qitian=strtotime("+1 week");
            $time=time();
            $Diankuanlst=$Diankuan->field('id')->where(" audit_1=1 and audit_2 and state=0 and back_money_time>$time and back_money_time <$qitian")->count();
            $rest=$Diankuanlst;


            break;
        case "超级管理员" :
            echo "";
            break;

    }
    //权限
    $rbac=M("Rbac");
    //获取合同审核权限组并且判断是否有消息
    //合同待审核
    $hetong=M("Contract");
    $raac_hetong=$rbac->field('audit_1,audit_2')->where("module = '/Admin/Contract'")->find();
    //一级审核
    $array=explode(",",$raac_hetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$hetong->field('id')->where("audit_1 =0 and isxufei=0")->count();
        $rest+=$ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_hetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {
        $ht_s2=$hetong->field('id')->where("audit_2 =0  and isxufei=0  and audit_1=1")->count();
        $rest+=$ht_s2;
    }

    //续费待审核
    $raac_xhetong=$rbac->field('audit_1,audit_2')->where("module = '/Admin/Renew'")->find();

    //一级审核
    $array=explode(",",$raac_xhetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$hetong->field('id')->where("audit_1 =0 and isxufei=1")->count();
        $rest+=$ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_xhetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {

        $ht_s2=$hetong->field('id')->where("audit_2 =0  and isxufei=1  and audit_1=1")->count();

        $rest+=$ht_s2;
    }

    //垫款待审核
    $hetong=M("Diankuan");
    $raac_hetong=$rbac->field('audit_1,audit_2')->where("module = '/Admin/Diankuan'")->find();
    //一级审核
    $array=explode(",",$raac_hetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$hetong->field('id')->where("audit_1 =0 ")->count();
        $rest+=$ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_hetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {
        $ht_s2=$hetong->field('id')->where("audit_2 =0  and audit_1=1")->count();
        $rest+=$ht_s2;
    }

    //退款待审核
    $hetong=M("Refund");
    $raac_hetong=$rbac->field('audit_1,audit_2')->where("module = '/Admin/Refund'")->find();
    //一级审核
    $array=explode(",",$raac_hetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$hetong->field('id')->where("audit_1 =0 ")->count();
        $rest+=$ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_hetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {
        $ht_s2=$hetong->field('id')->where("audit_2 =0  and audit_1=1")->count();
        $rest+=$ht_s2;
    }
    //发票待审核
    $hetong=M("Invoice");
    $raac_hetong=$rbac->field('audit_1,audit_2')->where("module = '/Admin/Invoice'")->find();
    //一级审核
    $array=explode(",",$raac_hetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$hetong->field('id')->where("audit_1 =0 ")->count();
        $rest+=$ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_hetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {
        $ht_s2=$hetong->field('id')->where("audit_2 =0  and audit_1=1")->count();
        $rest+=$ht_s2;
    }

    //退票待审核
    $hetong=M("RefundInvoice");
    $raac_hetong=$rbac->field('audit_1,audit_2')->where("module = '/Admin/RefundInvoice'")->find();
    //一级审核
    $array=explode(",",$raac_hetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$hetong->field('id')->where("audit_1 =0 ")->count();
        $rest+=$ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_hetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {
        $ht_s2=$hetong->field('id')->where("audit_2 =0  and audit_1=1")->count();
        $rest+=$ht_s2;
    }
    if($rest==0 or $rest=='')
    {
        $rest=0;
    }
    return $rest;


}