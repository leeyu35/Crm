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

//根据账户id 返回产品线名称
function product_line_name($id)
{
    $account=M("Account")->field('prlin_id')->find($id);
    $product_line=M('ProductLine')->field('name')->find($account['prlin_id']);
    return $product_line['name'];
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

    $one=$rbac->where("module = '$module'")->find();

    if($one!="") {
            $array=explode(",",$one['index_show']);
            if(in_array(cookie('u_groupid'),$array))
            {
                if($join==""){
                    $where="id!='0'";
                }else
                {
                    $where=$join.".id!='0'";
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
                    $array_s1=explode(",",$one['audit_1']);
                    $array_s2=explode(",",$one['audit_2']);
                    $array_s3=explode(",",$one['audit_3']);
                    $array_s4=explode(",",$one['audit_4']);
                   // echo cookie('u_groupid');
                   // dump($array_s1);
                    if(in_array(cookie('u_groupid'),$array_s2) and !in_array(cookie('u_groupid'),$array_s1))
                    {
                        if($join==""){
                            $where.=" and audit_1=1 ";
                        }else
                        {
                            $where.=" and ".$join.".audit_1=1 ";
                        }
                    }
                    if(in_array(cookie('u_groupid'),$array_s3) and !in_array(cookie('u_groupid'),$array_s1)  and !in_array(cookie('u_groupid'),$array_s2))
                    {
                        if($join==""){
                            $where.=" and audit_1=1  and audit_2=1 ";
                        }else
                        {
                            $where.=" and ".$join.".audit_1=1 and ".$join.".audit_2=1 ";
                        }
                    }
                    if(in_array(cookie('u_groupid'),$array_s4) and !in_array(cookie('u_groupid'),$array_s1)  and !in_array(cookie('u_groupid'),$array_s2)  and !in_array(cookie('u_groupid'),$array_s3))
                    {
                        if($join==""){
                            $where.=" and audit_1=1  and audit_2=1 and audit_3=1 ";
                        }else
                        {
                            $where.=" and ".$join.".audit_1=1 and ".$join.".audit_2=1  and ".$join.".audit_3=1 ";
                        }
                    }
                }
            }
    }
    //echo $where;
    return $where;
}
//用户管理权限
function quan_users_where($module,$join=""){

    $rbac=M("Rbac");
    $one=$rbac->where("module = '$module'")->find();
    if($one!="") {
        $array=explode(",",$one['index_show']);
        if(in_array(cookie('u_groupid'),$array))
        {
                $where=$join."id!='0'";
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
    $one=$rbac->where("module = '$module'")->find();
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
    $rest=0;
    //组
    $group=M('Groupl')->field('group_name')->find(cookie('u_groupid'));
    $group=$group;
    $group_name=$group['group_name'];
    //垫款
    /*
    $Diankuan=M("Diankuan");
    switch ($group_name)
    {
        case "商务":
            $qitian=strtotime("+1 week");
            $time=time();
            $Diankuanlst=$Diankuan->field('id')->where(" audit_1=1 and audit_2=1 and state=0 and back_money_time>$time and back_money_time <$qitian")->count();
            $rest=$Diankuanlst;


            break;
        case "超级管理员" :
            echo "";
            break;

    }*/
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
    $xufeihuikuan=M("RenewHuikuan");
    //续费待审核
    $raac_xhetong=$rbac->field('audit_1,audit_2,audit_3')->where("module = '/Admin/Renew'")->find();

    //一级审核
    $array=explode(",",$raac_xhetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$xufeihuikuan->where("is_huikuan=0 and payment_type!=14 and payment_type!=15  and audit_1 =0")->count();
        $rest+=$ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_xhetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {

        $ht_s2=$xufeihuikuan->where("is_huikuan=0 and payment_type!=14 and payment_type!=15 and audit_2 =0  and audit_1=1")->count();

        $rest+=$ht_s2;
    }
    //三级审核
    $array2=explode(",",$raac_xhetong['audit_3']);
    if(in_array(cookie('u_groupid'),$array2))
    {

        $ht_s3=$xufeihuikuan->where("is_huikuan=0 and payment_type!=14 and payment_type!=15 and audit_2 =1  and audit_1=1 and audit_3=0")->count();

        $rest+=$ht_s3;
    }

    //四级审核
    $array3=explode(",",$raac_xhetong['audit_4']);
    if(in_array(cookie('u_groupid'),$array3))
    {

        $ht_s4=$xufeihuikuan->where("is_huikuan=0 and payment_type!=14 and payment_type!=15 and audit_2 =1  and audit_1=1 and audit_3=1 and audit_4=0")->count();

        $rest+=$ht_s4;
    }

    /*
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
       */
    //退款待审核

    $raac_hetong=$rbac->field('audit_1,audit_2')->where("module = '/Admin/RefundMoney'")->find();
    //一级审核
    $array=explode(",",$raac_hetong['audit_1']);
    if(in_array(cookie('u_groupid'),$array))
    {
        $ht_s1=$xufeihuikuan->where("(audit_1 =0) and (payment_type=14 or payment_type=15) ")->count();
        $rest+=$ht_s1;
        //echo $ht_s1;
    }
    //二级审核
    $array1=explode(",",$raac_hetong['audit_2']);
    if(in_array(cookie('u_groupid'),$array1))
    {
        $ht_s2=$xufeihuikuan->where("audit_2 =0  and audit_1=1  and (payment_type=14 or payment_type=15)")->count();
        $rest+=$ht_s2;

    }
    //二级审核
    $array1=explode(",",$raac_hetong['audit_3']);
    if(in_array(cookie('u_groupid'),$array1))
    {
        $ht_s2=$xufeihuikuan->where("audit_3 =0  and audit_1=1  and audit_2=1  and (payment_type=14 or payment_type=15)")->count();
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

//账户信息
    function account($id){
        $account=M("Account");
        $find=$account->field('id,a_users')->find($id);
        return $find;


    }


function num_format($num){
    if(!is_numeric($num)){
        return false;
    }
    if($num<1)
    {
        return round($num,2);
    }
    if($num==0)
    {
        return 0;
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
function msubstr($str, $start=0, $length, $charset="utf-8", $suffix=true)
{
    if(function_exists("mb_substr"))
    {
        if($suffix)
            return mb_substr($str, $start, $length, $charset)."...";
        else
            return mb_substr($str, $start, $length, $charset);
    }
    elseif(function_exists('iconv_substr'))
    {
        if($suffix)
            return iconv_substr($str,$start,$length,$charset)."...";
        else
            return iconv_substr($str,$start,$length,$charset);
    }
    $re['utf-8']   = "/[x01-x7f]|[xc2-xdf][x80-xbf]|[xe0-xef]  
    [x80-xbf]{2}|[xf0-xff][x80-xbf]{3}/";
    $re['gb2312'] = "/[x01-x7f]|[xb0-xf7][xa0-xfe]/";
    $re['gbk']    = "/[x01-x7f]|[x81-xfe][x40-xfe]/";
    $re['big5']   = "/[x01-x7f]|[x81-xfe]([x40-x7e]|xa1-xfe])/";
    preg_match_all($re[$charset], $str, $match);
    $slice = join("",array_slice($match[0], $start, $length));
    if($suffix) return $slice."…";
    return $slice;
}

function getExcel($fileName,$headArr,$data){
    //对数据进行检验
    if(empty($data) || !is_array($data)){
        return false;
        exit();
    }
    //检查文件名
    if(empty($fileName)){
        exit;
    }
    //导入PHPExcel类库，因为PHPExcel没有用命名空间，只能inport导入
     import("Library.Org.Util.PHPExcel",THINK_PATH,".php");

     import("Library.Org.Util.PHPExcel.Writer.Excel2007",THINK_PATH,".php");
     import("Library.Org.Util.PHPExcel.IOFactory",THINK_PATH,".php");

    $date = date("Y_m_d",time());
    $fileName .= "_{$date}.xls";

    //创建PHPExcel对象，注意，不能少了\
    $objPHPExcel = new \PHPExcel();

    $objProps = $objPHPExcel->getProperties();

     $objActSheet = $objPHPExcel->getActiveSheet();

    //设置表头
    $key = ord("A");

    foreach($headArr as $v){
        $colum = chr($key);
        $objPHPExcel->setActiveSheetIndex(0) ->setCellValue($colum.'1', $v);
        //设置列宽
        $objActSheet->getColumnDimension($colum.'1')->setAutoSize(true);
        //设置字体
        $objActSheet->getStyle($colum.'1')->getFont()->setName('微软雅黑');
        //设置加粗
        $objActSheet->getStyle($colum.'1')->getFont()->setBold(true);
        //设置字号
        $objPHPExcel->getActiveSheet()->getStyle($colum.'1')->getFont()->setSize(10);
        $key += 1;
    }



     $column = 2;
   // $objActSheet = $objPHPExcel->getActiveSheet();

    foreach($data as $key => $rows){ //行写入
        $span = ord("A");
        foreach($rows as $keyName=>$value){// 列写入
            $j = chr($span);

            //设置列宽
           // $objActSheet->getColumnDimension($j.$column)->setAutoSize(true);
            //设置字体
            $objActSheet->getStyle($j.$column)->getFont()->setName('微软雅黑');
            //设置字号
            $objPHPExcel->getActiveSheet()->getStyle($j.$column)->getFont()->setSize(10);

            $objActSheet->setCellValue($j.$column, $value);
            $span++;
        }
        $column++;
    }

    $fileName = iconv("utf-8", "gb2312", $fileName);
     header("Pragma: public");
     header("Expires: 0");
     header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
     header("Content-Type:application/force-download");
     header("Content-Type:application/vnd.ms-execl");
     header("Content-Type:application/octet-stream");
     header("Content-Type:application/download");;
     header('Content-Disposition:attachment;filename="'.$fileName.'"');
     header("Content-Transfer-Encoding:binary");
     $objWriter = new \PHPExcel_Writer_Excel5($objPHPExcel);
     $objWriter->save('php://output');

     exit;
}
//读取excel
function excet_d($file,$sheet=0){
    import("Library.Org.Util.PHPExcel",THINK_PATH,".php");

    import("Library.Org.Util.PHPExcel.Writer.Excel2007",THINK_PATH,".php");
    import("Library.Org.Util.PHPExcel.IOFactory",THINK_PATH,".php");

    $objReader =  \PHPExcel_IOFactory::createReader('Excel2007');//use excel2007 for 2007 format

    $PHPExcel = $objReader->load($file);
    $currentSheet = $PHPExcel->getSheet($sheet);        //**读取excel文件中的指定工作表*/
    $allColumn = $currentSheet->getHighestColumn();        //**取得最大的列号*/
    $allRow = $currentSheet->getHighestRow();        //**取得一共有多少行*/
    $data = array();
    for($rowIndex=1;$rowIndex<=$allRow;$rowIndex++){        //循环读取每个单元格的内容。注意行从1开始，列从A开始
        for($colIndex='A';$colIndex<=$allColumn;$colIndex++){
            $addr = $colIndex.$rowIndex;
            $cell = $currentSheet->getCell($addr)->getValue();
            if($cell instanceof PHPExcel_RichText){ //富文本转换字符串
                $cell = $cell->__toString();
            }
            $data[$rowIndex][$colIndex] = $cell;
        }
    }
    return($data);
}

//公司和合同余额变动 1公司id,2合同id，3类型:1续费预付，2续费垫付,3续费补款，4回款，5发票。 4 变动值
function money_change($advertisers_id,$contract_id,$type,$value,$accountid='')
{
    $kehu=kehu($advertisers_id);
    if($kehu['customer_type']==3)
    {
        return;
    }

//类型:1续费预付，2续费垫付,3续费补款，4回款，5发票 14 退款  15 转款
    $advertisers = M("Customer");
    $contract = M("Contract");
    //如果是续费操作 则在客户出款字段yu_e上执行加操作
    if ($type == '1' or $type == '2') {
        $update1=$advertisers->where("id=$advertisers_id")->setInc('yu_e', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setInc('yu_e', $value);//更新合同出款值
        if($update1!=1)
        {
            die('广告续费总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.' 账户ID是'.$accountid.' 的续费操作，该公司总消耗加'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同续费总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            $str=cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.' 账户ID是'.$accountid.' 的续费操作，该合同总消耗加'.$value;
            crm_record($str);
          // money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }

    } elseif ($type == '3') {
        //补款
        $update1=$advertisers->where("id=$advertisers_id")->setInc('bukuan', $value);//更新公司补款值
        $update2=$contract->where("id=$contract_id")->setInc('bukuan', $value);//更新合同补款值
        if($update1!=1)
        {
            die('广告补款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的补款操作，该公司总补款加'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同补款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的补款操作，该合同总补款加'.$value);
        }
    } elseif ($type == '4') {
        //回款
        //$update1=$advertisers->where("id=$advertisers_id")->setInc('huikuan', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setInc('huikuan', $value);//跟新合同补款值

        if($update2!=1)
        {
            die('合同回款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的回款操作，该合同总回款加'.$value);
        }
    } elseif ($type == '5')
    {
        //发票
        $update1=$contract->where("id=$contract_id")->setInc('invoice', $value);//更新发票总金额值

        if($update1!=1)
        {
            die('合同发票总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的发票操作，该公司总发票加'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
    }elseif ($type == '6') {
        //公司回款
        $update1=$advertisers->where("id=$advertisers_id")->setInc('huikuan', $value);//更新公司出款值

        if($update1!=1)
        {
            die('公司回款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            $str=cookie('u_name').'操作了 公司ID是'.$advertisers_id.'的回款操作，该公司总回款加'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
    }elseif($type=='14')
    {
        //退款到客户  总收款减
        $update1=$advertisers->where("id=$advertisers_id")->setDec('huikuan', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setDec('huikuan', $value);//跟新合同补款值
        if($update1!=1)
        {
            die('广告退款到客户 操作回款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到客户操作，该公司总回款减'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同退款到客户 操作回款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到客户操作，该合同总回款减'.$value);
        }
    }elseif($type=='15')
    {
        //退款到总账户 总消耗减
        $update1=$advertisers->where("id=$advertisers_id")->setDec('yu_e', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setDec('yu_e', $value);//跟新合同补款值
        if($update1!=1)
        {
            die('广告退款到总账户 操作回款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到总账户操作，该公司总消耗减'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同退款到总账户 操作回款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'操作了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到总账户操作，该合同总消耗减'.$value);
        }
    }

}

function money_reduce($advertisers_id,$contract_id,$type,$value,$accountid='')
{
    $kehu=kehu($advertisers_id);
    if($kehu['customer_type']==3)
    {
        return;
    }
    $advertisers = M("Customer");
    $contract = M("Contract");

    //如果是续费操作 则在客户出款字段yu_e上执行加操作
    if ($type == '1' or $type == '2') {

        $update1=$advertisers->where("id=$advertisers_id")->setDec('yu_e', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setDec('yu_e', $value);//更新合同出款值
        if($update1!=1)
        {
            die('广告续费总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.' 账户ID是'.$accountid.' 的续费操作，该公司总消耗减'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同续费总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.' 账户ID是'.$accountid.' 的续费操作，该合同总消耗减'.$value);
        }
    } elseif ($type == '3') {
        //补款
        $update1=$advertisers->where("id=$advertisers_id")->setDec('bukuan', $value);//更新公司补款值
        $update2=$contract->where("id=$contract_id")->setDec('bukuan', $value);//更新合同补款值
        if($update1!=1)
        {
            die('广告补款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的补款操作，该公司总补款减'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同补款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的补款操作，该合同总补款减'.$value);
        }
    } elseif ($type == '4') {
        //回款
        $update1=$advertisers->where("id=$advertisers_id")->setDec('huikuan', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setDec('huikuan', $value);//跟新合同补款值
        if($update1!=1)
        {
            die('广告回款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的回款操作，该公司总回款减'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同回款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的回款操作，该合同总回款减'.$value);
        }
    } elseif ($type == '5')
    {
        //发票
        $update1=$contract->where("id=$contract_id")->setDec('invoice', $value);//更新发票总金额值

        if($update1!=1)
        {
            die('合同发票总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的发票操作，该公司总发票减'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
    }elseif($type=='14')
    {
        //退款到客户  总收款加
        $update1=$advertisers->where("id=$advertisers_id")->setInc('huikuan', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setInc('huikuan', $value);//跟新合同补款值
        if($update1!=1)
        {
            die('广告退款到客户 操作回款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {
            $str=cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到客户操作，该公司总回款加'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同退款到客户 操作回款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到客户操作，该合同总回款加'.$value);
        }
    }elseif($type=='15')
    {
        //退款到总账户 总消耗加
        $update1=$advertisers->where("id=$advertisers_id")->setInc('yu_e', $value);//更新公司出款值
        $update2=$contract->where("id=$contract_id")->setInc('yu_e', $value);//跟新合同补款值
        if($update1!=1)
        {
            die('广告退款到总账户 操作回款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
        }else
        {

            $str=cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到总账户操作，该公司总消耗加'.$value;
            crm_record($str);
            money_record($contract_id,$advertisers_id,$type,$str,$value,1);
        }
        if($update2!=1)
        {
            die('合同退款到总账户 操作回款总额回滚失败，请尽快联系CRM系统管理员<br>sql:'.$contract->_sql());
        }else
        {
            crm_record(cookie('u_name').'驳回了 公司ID是'.$advertisers_id.'合同ID是'.$contract_id.'的退款到总账户操作，该合同总消耗加'.$value);
        }
    }
}

function money_record($contract_id,$advertisers_id,$type,$str,$cmoney,$jiaorjian){
    $money_hisory=M("MoneyHistory");
    $data['htid']=$contract_id;
    $data['adid']=$advertisers_id;
    $data['mes']=$str;
    $data['ctime']=time();
    $data['type']=$type;
    $data['cmoney']=$cmoney;
    $data['date']=date("Y-m-d H:i:s");
    $adinfo=M('Customer')->field('yu_e,huikuan,bukuan')->find($advertisers_id);
    if($type=='1' or $type=='2' or $type == '4' or $type == '14' or $type == '6' or $type == '15')
    {
        //客户总余额
        $data['balance']=$adinfo['huikuan']-$adinfo['yu_e'];
    }
    if($type=='3')
    {
        //客户总余额
        $data['bukuan']=$adinfo['bukuan'];
    }if($type=='5')
    {
        $invoice=M("Invoice");
        $sum=$invoice->where("invoice_head=$advertisers_id and audit_1 != 2 and audit_2 !=2")->sum("money");

        //客户总余额
        $data['invoice']=$sum;
    }


    $money_hisory->add($data);


}

//获取周的开始时间和结束时间参数1 得到几周数据  参数2 从上周几开始计算，周期  参数3 指定开始时间 没有则默认今天
function teodate_week($to,$zhouji,$strdate=''){
    //如果没有指定日期则默认当前日期
    if($strdate=='')
    {
        $strdate=date('Y-m-d');
    }
    $a=strtotime($strdate."+1 day"); //因为是获取的上周几 所以给她加一天，比如 今天周一，要获取上周一，则 改认为今天是周二这样去读
    //获取几周日期
    /*
    for($i=0;$i<$to;$i++)
    {

    }
    */
    $bb=0;
    for($i=$to;$i>0;$i--)
    {
        $start=strtotime("this $zhouji -$i week",$a);//起始时间;
        $array[$bb]['start']=date('Y-m-d',$start);
        $enddate=date("Y-m-d",strtotime("+1 week ",$start));
        if($enddate > date("Y-m-d"))
        {
            //$enddate=date("Yq-m-d",strtotime("-1 day"));
            //$enddate=date("Y-m-d");
        }
        $array[$bb]['end']=$enddate;//结束日期
        $bb++;
    }

    //echo $a;

    return $array;
}
function teodate_week2($to,$zhouji,$strdate=''){
    //如果没有指定日期则默认当前日期
    if($strdate=='')
    {
        $strdate=date('Y-m-d');
    }
    $a=strtotime($strdate."+1 day"); //因为是获取的上周几 所以给她加一天，比如 今天周一，要获取上周一，则 改认为今天是周二这样去读
    //获取几周日期

    for($i=0;$i<$to;$i++)
    {
        $start=strtotime("last $zhouji -$i week",$a);//起始时间;
        $array[$i]['start']=date('Y-m-d',$start);
        $enddate=date("Y-m-d",strtotime("+1 week ",$start));
        if($enddate > date("Y-m-d"))
        {
            //$enddate=date("Yq-m-d",strtotime("-1 day"));
            //  $enddate=date("Y-m-d");
        }
        $array[$i]['end']=$enddate;//结束日期
    }


    //echo $a;

    return $array;
}
function shangzhou(){
    $array['start']=mktime(0,0,0,date('m'),date('d')-date('w')+1-7,date('Y'));
    $array['end']=mktime(23,59,59,date('m'),date('d')-date('w')+7-7,date('Y'));
    return $array;
}
function date_daye_j7(){
    $array['start']=$time_start=strtotime(date("Y-m-d")."-7 day");
    $array['end']=$time_end=strtotime(date("Y-m-d"));
    return $array;
}
//获取月的开始时间和结束时间
function teodate_month(){
    $array['start']=date("Y-m-d",mktime(0,0,0,date('m'),1,date('Y')));
    $enddate=date("Y-m-d");
    $array['end']=$enddate;//结束日期
    //echo $a;
    return $array;
}
//获取上月的开始时间和结束时间
function teodate_smonth(){
    $array['start']=date("Y-m-d",mktime(0,0,0,date('m')-1,1,date('Y')));

    $enddate=date("Y-m-d",strtotime("+1 month ",strtotime($array['start'])));
    $array['end']=$enddate;//结束日期
    //echo $a;
    return $array;
}
//获取昨日开始时间和结束时间
function Yesterday(){
    $array['start']=date("Y-m-d",mktime(0,0,0,date('m'),date('d')-1,date('Y')));
    $array['end']=date("Y-m-d",mktime(0,0,0,date('m'),date('d'),date('Y')));
    //echo $a;
    return $array;
}
//获取前日开始时间和结束时间
function Qianday(){
    $array['start']=date("Y-m-d",mktime(0,0,0,date('m'),date('d')-2,date('Y')));
    $array['end']=date("Y-m-d",mktime(0,0,0,date('m'),date('d')-1,date('Y')));
    //echo $a;
    return $array;
}
//获取12个月的开始时间和结束时间参数1 得到几月数据  参数2 从上周几开始计算，周期  参数3 指定开始时间 没有则默认今天
function teodate_month_12($to,$strdate=''){
    //如果没有指定日期则默认当前日期
    if($strdate=='')
    {
        $strdate=date('Y-01-01');
    }


    $a=strtotime($strdate);

    //获取几周日期
    for($i=0;$i<$to;$i++)
    {
        $start=strtotime(" +$i month",$a);//起始时间;
        $array[$i]['start']=date('Y-m-d',$start);
        $enddate=date("Y-m-d",strtotime("+1 month ",$start));
        if($enddate > date("Y-m-d"))
        {
            //$enddate=date("Y-m-d",strtotime("-1 day"));
            //  $enddate=date("Y-m-d");
        }
        $array[$i]['end']=$enddate;//结束日期
    }
    //echo $a;
    return $array;
}


//根据appid 获取 相关账户负责sem id
function account_sem_id($appid){
    $account=M("Account");
    $accountinfo=$account->field('id')->where("appid = '$appid' and endtime='4092599349'")->find();

    if($accountinfo[id]!='')
    {
        //负责人
        $principal=M("AccountUsers");
        $fzridlist=$principal->field('u_id')->where("account_id = $accountinfo[id]")->select(false);
        $userslist=M("Users")->field('name,id as uid')->where("id in ($fzridlist)")->find();
        return $userslist['uid'];
    }else
    {
        return ;
    }

}



//根据appID 获取相关账户销售id或者合同id 或者媒介返点
function  account_xs_id($appid,$field){

    $account=M("Account");
    $accountinfo=$account->field('id,contract_id')->where("appid = '$appid' and endtime='4092599349'")->find();

    if($accountinfo[id]!='') {
        $hetong = M("Contract");
        $hetonginfo = $hetong->field('id,market,advertiser,mht_id')->find($accountinfo['contract_id']);
        if($field=='mt_fandian')
        {
            $mjinfo=$hetong->field('rebates_proportion')->find($hetonginfo['mht_id']);
            if(empty($mjinfo['rebates_proportion']))
            {
                $mjinfo['rebates_proportion']=0;
            }
            return $mjinfo['rebates_proportion']/100;
        }else{
            return $hetonginfo[$field];
        }

    }
    else
    {
        return ;
    }
}



  function nianjia($uid){
    $users=users_info($uid);
    //首先先算我是不是正式员工


    $m=date("m");

    if($users['istrue']==1)
    {

        //算我的工龄
        $a=date("Y-m-d",$users['jobtime']);//工作时间
        $c=date("Y-m-d",$users['intime']);//入职时间
        $b=date("Y-m-d");

        //入职是否满一年
        if(strtotime($a."+1 year") < strtotime($b))
        {




            $ca3=date_diff(date_create($c),date_create($b));//入职时间和现在时间差值
            $nianjia=$nianjia+5+$ca3->y;

            //echo $gongling."<br>";
            $nianjiazhouqi=12/round($nianjia,1);//年假周期  12个月 除以 工龄

            $nianjiazhouqi=$nianjiazhouqi/2; //0.5天的周期


            //$nianjia=$m/round($nianjiazhouqi,1);
            $nianjia=$m/$nianjiazhouqi;
            $nianjia=floor($nianjia);

            $snianjia=floor(date("m",$users['njuptime'])/round($nianjiazhouqi,1));

            if($nianjia-$snianjia>0)
            {
                $nianjiazjts=($nianjia-$snianjia)*0.5;
            }

            $User = M("Users"); // 实例化User对象
            if($nianjiazjts>0)
            {
                $User = M("Users"); // 实例化User对象
                $User->where("id=$uid")->setInc('nianjia',$nianjiazjts); // 年假加0.5
                $User->where("id=$uid")->setField('njuptime',time());
            }
            $md=date("m-d");
            if($md=='01-01')
            {
                $b=strtotime(date("Y-m-d")." -1 years");

                $yjjj=floor(1/$nianjiazhouqi)*0.5;//1月应有年假

                //算出去年应有年假
                if(strtotime($a."+1 year") < $b)
                {
                    $ca3=date_diff(date_create($c),date_create(date("Y-m-d",$b)));//入职时间和现在时间差值
                    $nianjia=5+$ca3->y;
                    if($nianjia<$users['nianjia'])
                    {
                        $User->where("id=$uid")->setField('nianjia',$nianjia+$yjjj); //
                    }else{
                        $User->where("id=$uid")->setField('nianjia',$users['nianjia']+$yjjj); //
                    }

                }
                $User->where("id=$uid")->setField('njuptime',time());
            }

        }else
        {
            //入职时间不满一年
            $nianjia=0;

        }

        /*
 $ca3=date_diff(date_create($c),date_create($b));//入职时间和现在时间差值

        $niangjia1=((floor($nianjia)+1)-0.5); //年假整天+1天 - 0.5 天
        $niangjia2=round($nianjia,1);//产生的年假天数     取一位小数点
        if($niangjia2>=$niangjia1)//如果产生的年假天数 大于年假的0.5天
        {

            $nianjia=$niangjia1;
        }else
        {

            $nianjia=floor($nianjia);
        }


        $nianjia=$nianjia+$ca3->y;
        */



        $ca2=date_diff(date_create($c),date_create($b));//入职时间和现在时间差值
        //echo $gongling."<br>";
        $nianjiazhouqi=12/round($nianjia,1);//年假周期  12个月 除以 工龄


        $nianjia=$m/round($nianjiazhouqi,1);


    }else
    {
        $nianjia= 0;
    }

    return $nianjia;
}
//根据合同id获取合同关联表
function contract_prlin($contrct_id){
    //合同关联表
    $contract_relevance=M("ContractRelevance");
    $conlin_list=$contract_relevance->field("a.*,b.name")->join(" a left join __PRODUCT_LINE__ b on a.product_line=b.id")->where("a.contract_id=$contrct_id")->select();
    return $conlin_list;
}

//获取传入时间倒15天日期 开始时间和结束时间
function day_15($start_time){
    //如果没有指定日期则默认当前日期
    if($strdate=='')
    {
        $strdate=date('Y-m-d');
    }
    //加一天
    $strdate=date("Y-m-d",strtotime($strdate." -16 day"));

    for ($i=0;$i<=15;$i++)
    {
        $array[$i]['start']=date("Y-m-d",strtotime($strdate." +$i day"));
        $array[$i]['end']=date("Y-m-d",strtotime($array[$i]['start']." +1 day"));
    }

    $b=strtotime($strdate." -14 day");
    return $array;

}

//续费对回款消耗
function renew_huikuan($xf_insid=0){

    //循环续费欠额大于0的数据 并且对应该续费合同的回款
    $renew=M("RenewHuikuan");
    $list=$renew->field('id')->where("(payment_type=1 or payment_type=2 or payment_type=14 or payment_type=16) and xf_qiane>0 and audit_2!=2 and audit_1!=2 ")->order("payment_time asc")->select();

    foreach ($list as $key=>$val)
    {
        renew_auto_huikuan($val[id]);
    }

}

//续费回款自动对应统一方法（）
function renew_auto_huikuan($xf_insid)
{
    $huikuan=M("RenewHuikuan");
    //续费信息
    $xufei_info=$huikuan->find($xf_insid);

    //此合同回款列表
    $backmoney=$huikuan->where("(is_huikuan=1 or payment_type=3 or payment_type=15)  and xf_contractid='$xufei_info[xf_contractid]' and audit_2!=2 and audit_1!=2 and backmoney_yue >0")->order("payment_time asc")->select();


    //计算续费成本
    //要续费合同
    $yhtinfo=M("Contract")->field('huikuan,yu_e,mht_id,contract_state')->find($xufei_info[xf_contractid]);
    //媒介合同信息
    $mjhtinfo=M("Contract")->field('rebates_proportion,dl_fandian')->find($yhtinfo['mht_id']);
    //销售提成
    if($yhtinfo['contract_state']=='1')
    {
        $xs_fandian=20;
    }elseif($yhtinfo['contract_state']=='2')
    {
        $xs_fandian=5;
    }elseif($yhtinfo['contract_state']=='3')
    {
        $xs_fandian=10;
    }
    if(cookie("u_id")==114 or cookie("u_id")==115 or cookie("u_id")==113)
    {
        $xs_fandian=0;
    }
    $yixufeihuikuan_date['mt_fandian']=$mjhtinfo['rebates_proportion'];
    $yixufeihuikuan_date['dl_fandian']=$mjhtinfo['dl_fandian'];
    $yixufeihuikuan_date['xf_fandian']=$xufei_info['rebates_proportion'];
    $yixufeihuikuan_date['gr_fandian']=0;
    $yixufeihuikuan_date['xs_fandian']=$xs_fandian;
    $yixufeihuikuan_date['avid']=$xufei_info['advertiser'];
    //销售
    $kehu=kehu($xufei_info['advertiser']);

    $yixufeihuikuan_date['xsid']=$kehu['submituser'];
    $yixufeihuikuan_date['xf_id']=$xf_insid;
    $yixufeihuikuan_date['xs_tc']=0;
    $yixufeihuikuan_date['ht_id']=$xufei_info[xf_contractid];
    $xf_fd=($xufei_info[rebates_proportion]+100)/100;

    foreach ($backmoney as $key=>$value)
    {
        //续费信息
        $xufei_info=$huikuan->find($xf_insid);
        //如果续费欠额为0则跳出本次循环
        if($xufei_info['xf_qiane']==0)
        {
            break ;
        }
        //如果回款金额 大于 续费金额 （余额大于续费金额）停止循环并返回续费欠额为0
        if($value['backmoney_yue']-$xufei_info['xf_qiane']>0) {
            //设置回款余额
            $huikuan->where("id=$value[id]")->setField('backmoney_yue', $value['backmoney_yue'] - $xufei_info['xf_qiane']);
            //增加已回款续费记录
            $yixufeihuikuan_date['hk_id'] = $value['id'];
            //谁大取谁
            if ($value['payment_time'] >= $xufei_info['payment_time']) {
                $yixufeihuikuan_date['time'] = $value['payment_time'];
            } else
            {
                $yixufeihuikuan_date['time'] = $xufei_info['payment_time'];
            }

            $yixufeihuikuan_date['money']=$xufei_info['xf_qiane'];
            $shifu=($yixufeihuikuan_date['money']*$xf_fd)/(($mjhtinfo['rebates_proportion']+100)/100);
            $yixufeihuikuan_date['shifu_money']=$shifu;

            M("Yihuikuanxufei")->add($yixufeihuikuan_date);

            $xf_money=0;
            //修改续费欠额
            M("RenewHuikuan")->where("id=$xf_insid")->setField('xf_qiane',$xf_money);
            //到此结束
            return $xf_money;
            exit;
        }else
        {

            //如果回款余额 小于 续费金额  （此回款余额不足以抹平此续费）则继续执行循环 并把此回款的余额为0


            //增加已回款续费记录
            $yixufeihuikuan_date['hk_id']=$value['id'];
            //谁大取谁
            if ($value['payment_time'] >= $xufei_info['payment_time']) {
                $yixufeihuikuan_date['time'] = $value['payment_time'];
            } else
            {
                $yixufeihuikuan_date['time'] = $xufei_info['payment_time'];
            }
            $yixufeihuikuan_date['money']=$value['backmoney_yue'];
            $shifu=($yixufeihuikuan_date['money']*$xf_fd)/(($mjhtinfo['rebates_proportion']+100)/100);
            $yixufeihuikuan_date['shifu_money']=$shifu;
            M("Yihuikuanxufei")->add($yixufeihuikuan_date);

            //设置回款余额
            $huikuan->where("id=$value[id]")->setField('backmoney_yue','0');
            //修改续费欠额
            M("RenewHuikuan")->where("id=$xf_insid")->setField('xf_qiane',$xufei_info['xf_qiane']-$value['backmoney_yue']);
            //$xf_money=$value['money']-$xf_money;
        }
    }
}



//回款自动对应续费
function huikuan_xufei_auto($hk_id){
    $huikuan=M("RenewHuikuan");
    $hk_info=$huikuan->find($hk_id);
    //要续费合同
    $yhtinfo=M("Contract")->field('huikuan,yu_e,mht_id,contract_state')->find($hk_info['xf_contractid']);
    //媒介合同信息
    $mjhtinfo=M("Contract")->field('rebates_proportion,dl_fandian')->find($yhtinfo['mht_id']);
    //销售提成
    if($yhtinfo['contract_state']=='1')
    {
        $xs_fandian=20;
    }elseif($yhtinfo['contract_state']=='2')
    {
        $xs_fandian=5;
    }elseif($yhtinfo['contract_state']=='3')
    {
        $xs_fandian=10;
    }
    if(cookie("u_id")==114 or cookie("u_id")==115 or cookie("u_id")==113)
    {
        $xs_fandian=0;
    }
    $yixufeihuikuan_date['mt_fandian']=$mjhtinfo['rebates_proportion'];
    $yixufeihuikuan_date['dl_fandian']=$mjhtinfo['dl_fandian'];

    $yixufeihuikuan_date['gr_fandian']=0;
    $yixufeihuikuan_date['xs_fandian']=$xs_fandian;
    $yixufeihuikuan_date['avid']=$hk_info[advertiser];
    $yixufeihuikuan_date['xsid']=$hk_info[market];;
    $yixufeihuikuan_date['hk_id']=$hk_id;
    $yixufeihuikuan_date['xs_tc']=0;
    $yixufeihuikuan_date['ht_id']=$hk_info['xf_contractid'];
    $xf_fd=(I('post.rebates_proportion')+100)/100;




    //此合同续费列表
    $xflist=$huikuan->where("is_huikuan=0 and xf_contractid='$hk_info[xf_contractid]' and (payment_type=1 or payment_type=2 or payment_type=14  or payment_type=16) and audit_2!=2 and audit_1!=2 and audit_3!=2  and audit_4!=2 and xf_qiane>0")->order("payment_time asc")->select();
    foreach ($xflist as $key=>$value)
    {
        //回款信息
        $hk_info=$huikuan->find($hk_id);

        //已经把回款消耗完毕 跳出循环
        if($hk_info['backmoney_yue']<0)
        {
            break;
        }

        //如果回款金额 小于 续费欠额 （余额小于续费金额）停止循环并返回回款余额为0
        if($hk_info['backmoney_yue']-$value['xf_qiane']<0)
        {


            //增加已回款续费记录
            $yixufeihuikuan_date['xf_fandian']=$value['rebates_proportion'];
            $yixufeihuikuan_date['xf_id']=$value['id'];
            //谁大取谁
            if ($value['payment_time'] >= $hk_info['payment_time']) {
                $yixufeihuikuan_date['time'] = $value['payment_time'];
            } else
            {
                $yixufeihuikuan_date['time'] = $hk_info['payment_time'];
            }
            $yixufeihuikuan_date['time']=time();
            $yixufeihuikuan_date['money']=$hk_info['backmoney_yue'];
            $shifu=($yixufeihuikuan_date['money']*$xf_fd)/(($mjhtinfo['rebates_proportion']+100)/100);
            $yixufeihuikuan_date['shifu_money']=$shifu;
            M("Yihuikuanxufei")->add($yixufeihuikuan_date);

            //设置回款余额
            $huikuan->where("id=$hk_id")->setField('backmoney_yue','0');
            //修改续费欠额
            M("RenewHuikuan")->where("id=$value[id]")->setField('xf_qiane',$value['xf_qiane']-$hk_info['backmoney_yue']);
            //$xf_money=$value['money']-$xf_money;
            //已经把回款消耗完毕
            break;

        }else
        {

            //如果回款金额 大于 续费金额  （此回款余额可以抹平此续费）则继续执行循环 直到回款余额为0

            //设置回款余额
            $huikuan->where("id=$hk_id")->setField('backmoney_yue',$hk_info['backmoney_yue']-$value['xf_qiane']);
            //增加已回款续费记录
            $yixufeihuikuan_date['xf_fandian']=$value['rebates_proportion'];
            $yixufeihuikuan_date['xf_id']=$value['id'];
            //谁大取谁
            if ($value['payment_time'] >= $hk_info['payment_time']) {
                $yixufeihuikuan_date['time'] = $value['payment_time'];
            } else
            {
                $yixufeihuikuan_date['time'] = $hk_info['payment_time'];
            }
            $yixufeihuikuan_date['money']=$value['xf_qiane'];
            $shifu=($yixufeihuikuan_date['money']*$xf_fd)/(($mjhtinfo['rebates_proportion']+100)/100);
            $yixufeihuikuan_date['shifu_money']=$shifu;
           
            M("Yihuikuanxufei")->add($yixufeihuikuan_date);


            //修改续费欠额
            M("RenewHuikuan")->where("id=$value[id]")->setField('xf_qiane',0);
        }
    }

}

function post_curl($url,$data){
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_COOKIE, "u_id=".cookie('u_id').";u_groupid=".cookie('u_groupid').";u_name=".cookie("u_name"));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // post数据
    curl_setopt($ch, CURLOPT_POST, 1);
    // post的变量
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

    $output = curl_exec($ch);
    curl_close($ch);
    $array=json_decode($output,true);
    return $array;
}

//媒介合同金额变更方法 //参数1公司id  2合同id 3金额 4增加或者减少
function m_money_updata($avid,$cid,$money,$payment_type,$type){
    //$payment_type 1=续费  4=打款
    $customer=M("Customer");
    $mcontract=M("Contract");
    if($type=='Inc')
    {
        $fun="setInc";
    }
    if($type=='Dec')
    {
        $fun="setDec";
    }
    //更新公司续费值 和 媒介合同续费值
    if($payment_type=='1')
    {
        if(!$customer->where("id=".$avid)->$fun("myu_e",$money)){die('增加公司续费失败！');}
        if(!$mcontract->where("id=".$avid)->$fun("yu_e",$money)){die('增加合同续费失败！');}

    }
    if($payment_type=='4')
    {
        if(!$customer->where("id=".$avid)->$fun("dakuan",$money)){die('增加公司打款失败！');}
    }

    if($payment_type=='41')
    {
        if(!$customer->where("id=".$avid)->$fun("huikuan",$money)){die('增加公司打款失败！');}
    }
}