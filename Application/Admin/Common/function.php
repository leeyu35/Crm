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
//返回用户组名称d单个
function group_name_find($id){

    $product_line=M('Groupl');
    $list= $product_line->field('id,group_name')->where("id = $id")->find();

    $string=$list[group_name];
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
    if($id=='')
    {
        return ;
    }
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
function num_format($num){
    if(!is_numeric($num)){
        return false;
    }
    $rvalue='';
    $num = explode('.',$num);//把整数和小数分开
    $rl = !isset($num['1']) ? '' : $num['1'];//小数部分的值
    $j = strlen($num[0]) % 3;//整数有多少位
    $sl = substr($num[0], 0, $j);//前面不满三位的数取出来
    $sr = substr($num[0], $j);//后面的满三位的数取出来
    $i = 0;
    while($i <= strlen($sr)){
        $rvalue = $rvalue.','.substr($sr, $i, 3);//三位三位取出再合并，按逗号隔开
        $i = $i + 3;
    }
    $rvalue = $sl.$rvalue;
    $rvalue = substr($rvalue,0,strlen($rvalue)-1);//去掉最后一个逗号
    $rvalue = explode(',',$rvalue);//分解成数组
    if($rvalue[0]==0){
        array_shift($rvalue);//如果第一个元素为0，删除第一个元素
    }
    $rv = $rvalue[0];//前面不满三位的数
    for($i = 1; $i < count($rvalue); $i++){
        $rv = $rv.','.$rvalue[$i];
    }
    if(!empty($rl)){
        $rvalue = $rv.'.'.$rl;//小数不为空，整数和小数合并
    }else{
        $rvalue = $rv;//小数为空，只有整数
    }
    return $rvalue;
}


function excel(){
    import("Org.Util.PHPExcel");
    import("Org.Util.PHPExcel.Writer.Excel2007");
//include 'Classes/PHPExcel/Writer/Excel2007.php';
    // 创建一个处理对象实例

    $objExcel=new  \Org\Util\PHPExcel();
// 创建文件格式写入对象实例, uncomment

    $objWriter=new \PHPExcel_Reader_Excel5($objExcel);
//*************************************
//设置当前的sheet索引，用于后续的内容操作。
//一般只有在使用多个sheet的时候才需要显示调用。
//缺省情况下，PHPExcel会自动创建第一个sheet被设置SheetIndex=0
    $objExcel->setActiveSheetIndex(0);
    $objActSheet = $objExcel->getActiveSheet();

//设置当前活动sheet的名称
    $objActSheet->setTitle('月增减变动报表');
//设置单元格的值
    $objActSheet->setCellValue('A1', '章贡区医疗保险局职工月增减变动报表');
//合并单元格
    $objActSheet->mergeCells('A1:N1');
    $objActSheet->setCellValue('A2', '现所在单位');
    $objActSheet->setCellValue('B2', '姓名');
    $objActSheet->setCellValue('C2', '性别');
    $objActSheet->setCellValue('D2', '身份证号码');
    $objActSheet->setCellValue('E2', '参保时间');
    $objActSheet->setCellValue('F2', '增减原因');
    $objActSheet->setCellValue('G2', '原所在单位');
    $objActSheet->setCellValue('H2', '增减时间');
    $objActSheet->setCellValue('I2', '退休时间');
    $objActSheet->setCellValue('J2', '原工资');
    $objActSheet->setCellValue('K2', '现工资');
    $objActSheet->setCellValue('L2', '定点医院');
    $objActSheet->setCellValue('M2', '操作人');
    $objActSheet->setCellValue('N2', '备注');

    header("Pragma: public");
    header("Expires: 0");
    header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
    header("Content-Type:application/force-download");
    header("Content-Type:application/vnd.ms-execl");
    header("Content-Type:application/octet-stream");
    header("Content-Type:application/download");;
    header('Content-Disposition:attachment;filename="resume.xls"');
    header("Content-Transfer-Encoding:binary");
    $objWriter->save('php://output');

}