<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/9/2
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class YihuikuanxufeiController extends CommonController
{
        public function index(){
            //dump($_SERVER);
            $Diankuan=M("Yihuikuanxufei");
            //搜索条件
            $type=I('get.searchtype');
            if($type!='')
            {
                if($type=='advertiser')
                {
                    $coustomer=M('Customer');
                    $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                    $where.=" and  a.id!='0' and a.avid in($zsql)";
                }
                $this->ser_txt=I('get.search_text');
                if($type=='appname')
                {
                    $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
                }
                $this->type=$type;
            }
            //销售
            $sql_false=$Diankuan->field('xsid')->group("xsid")->select(false);
            $userslist=M("Users")->field('id,name')->where("id in ($sql_false)")->select();
            $this->userslist=$userslist;


            //时间条件
            $time_start=I('get.time_start');
            $time_end=I('get.time_end');
            if($time_start!="" and $time_end!="")
            {
                $time_start=strtotime($time_start);
                
                $time_end=strtotime($time_end);
                $time_end=strtotime("+1 days",$time_end);

                $where.=" and a.time > $time_start and a.time < $time_end";
                $this->time_start=I('get.time_start');
                $this->time_end=I('get.time_end');
            }
            //审核条件
            $type2=I('get.market');
            if($type2!='')
            {
                if($type2=='k')
                {
                    $where.=" and a.id!='0' ";
                }
                else
                {
                    $where.=" and xsid=$type2";
                }

                $this->type2=$type2;
                $this->ser_txt2=I('get.search_text');

            }

            //款type条件
            $ktype=I('get.ktype');
            $this->ktypedh=$ktype;

            if($ktype!='')
            {

                foreach ($ktype as $key=>$val)
                {
                    if($key==0)
                    {
                        $str.="?ktype[]=".$val;
                    }else
                    {
                        $str.="&ktype[]=".$val;
                    }



                    if($val=='1')
                    {
                        $in1.=' or xf.payment_type=1';
                    }
                    if($val=='2')
                    {
                        $in1.=' or xf.payment_type=2';
                    }
                    if($val=='14')
                    {
                        $in1.=' or xf.payment_type=14';
                    }
                    if($val=='16')
                    {
                        $in1.=' or xf.payment_type=16';
                    }

                    if($val=='4')
                    {
                        $in2.=' or hk.is_huikuan=1';
                    }
                    if($val=='3')
                    {
                        $in2.=' or hk.payment_type=3';
                    }
                    if($val=='15')
                    {
                        $in2.=' or hk.payment_type=15';
                    }

                }
                $this->ktype=$str;
                $in1= substr($in1,3,100);
                $in2= substr($in2,3,100);
                if($in1=='')
                {
                    $in1=' a.id!=0';
                }
                if($in2=='')
                {
                    $in2=' a.id!=0';
                }
               $where.=" and ($in1) and ($in2) ";
            };

            //权限条件
            $q_where=quan_where(__CONTROLLER__,"a");

            $count      = $Diankuan->field('a.*,b.advertiser,xf.payment_type as xf_type,hk.payment_type as hk_type,hk.is_huikuan')->join("a left join __CUSTOMER__ b on a.avid = b.id left join __RENEW_HUIKUAN__ xf on a.xf_id=xf.id left join __RENEW_HUIKUAN__ hk on a.hk_id=hk.id")->where("a.id!='0' and ".$q_where.$where)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$Diankuan->field('a.*,b.advertiser,xf.payment_type as xf_type,hk.payment_type as hk_type,hk.is_huikuan')->join("a left join __CUSTOMER__ b on a.avid = b.id left join __RENEW_HUIKUAN__ xf on a.xf_id=xf.id left join __RENEW_HUIKUAN__ hk on a.hk_id=hk.id")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.time desc")->select();
             //echo $Diankuan->_sql();
            $sum = $Diankuan->field('a.id,a.money,xf.payment_type as xf_type,hk.payment_type as hk_type,hk.is_huikuan')->join("a left join __CUSTOMER__ b on a.avid = b.id left join __RENEW_HUIKUAN__ xf on a.xf_id=xf.id left join __RENEW_HUIKUAN__ hk on a.hk_id=hk.id ")->where("a.id!='0' and ".$q_where.$where)->sum("a.money");// 查询满足要求的总记录数


            $this->sum=number_format($sum,2);
            foreach($list as $key => $val)
            {
                //产品线
                $xfinfo=M("RenewHuikuan")->field('account')->find($val['xf_id']);
                $account=M("Account")->field('prlin_id')->find($xfinfo['account']);
                $prlin=M('ProductLine')->field('name')->find($account['prlin_id']);
                $list[$key]['prlin']=$prlin['name'];
                //提交人
                $uindo=users_info($val['xsid']);
                $list[$key]['market']=$uindo[name];
                $shifu_sum+=$val['shifu_money']; //实付金额总额
                $list[$key]['shifu_money']=number_format($val['shifu_money'],2);
                //个人返点所耗金额
                $grfdxsjr=$val['money']*($val['gr_fandian']/100);

                //提成前利润
                $list[$key]['tcq_lirun']=$val['money']-$val['shifu_money']-$grfdxsjr;
                //销售提成
                $list[$key]['market_tc']=$list[$key]['tcq_lirun']*($val['xs_fandian']/100);
                //毛利润
                $list[$key]['mao_lirun']=$list[$key]['tcq_lirun']-$list[$key]['market_tc'];

                $tcqlr_sum+=$list[$key]['tcq_lirun']; //提成钱利润总额
                $maolr_sum+=$list[$key]['mao_lirun'];//毛利利润总额
                $xstc_sum+=$list[$key]['market_tc'];//销售提成总额
                //保留两位小数点 并加逗号
                $list[$key]['tcq_lirun']=number_format($list[$key]['tcq_lirun'],2);
                $list[$key]['market_tc']=number_format($list[$key]['market_tc'],2);
                $list[$key]['mao_lirun']=number_format($list[$key]['mao_lirun'],2);

            }
            $this->tcqlr_sum=number_format($tcqlr_sum,2);//提成钱利润总额
            $this->shifu_sum=number_format($shifu_sum,2);//实付金额总额
            $this->maolr_sum=number_format($maolr_sum,2);//毛利利润总额
            $this->xstc_sum=number_format($xstc_sum,2);//销售提成总额

            $this->list=$list;
            $this->assign('page',$show);// 赋值分页输出
            $this->display();

    }
    public function add(){
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $this->display();
    }

    public function add2(){
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $contract=M("Contract");
        $contract_info=$contract->field('id,market,appname,mht_id')->find(I('get.contract_id'));
        $xufeilist=M("RenewHuikuan")->field('a.*,b.a_users')->join(" a left join jd_account b on a.account=b.id")->where('a.xf_contractid='.I('get.contract_id').' and (a.payment_type=1 or a.payment_type=2) and a.xf_qiane>0')->select();

        //媒体合同信息
        $mt_contract_info=$contract->field("id,rebates_proportion,dl_fandian")->find($contract_info['mht_id']);
        $this->mt_contract_info=$mt_contract_info;



        $this->xufeilist=$xufeilist;

        $this->contract_info=$contract_info;
        $this->display();
    }

    public function keyup_adlist(){
        $Blog = R('Contract/keyup_adlist');
        echo $Blog;
    }



    public function addru(){

        $Diankuan=M("RenewHuikuan");
        $postdate=$Diankuan->create();
        $Diankuan->payment_time=strtotime($Diankuan->payment_time);
        $Diankuan->ctime=time();
        $Diankuan->is_huikuan=1;
        $Diankuan->users2=cookie('u_id');






        if($Diankuan->advertiser=='')
        {
            $this->error('提交失败，公司名称不能为空，或您没有按规定操作');
            exit;
        }
        if($postdate['money']<0)
        {
            $this->error('不能输入负数');
            exit;
        }

        if($insid=$Diankuan->add()){
            //如果回款成功则修改客户和合同回款总额
            money_change($postdate['advertiser'],$postdate['xf_contractid'],4,$postdate['money']);
            if($insid==1)
            {
                $result = $Diankuan->query("select currval('jd_renew_huikuan_id_seq')");
                $insid=$result[0][currval];
            }

            if(I('post.xf_id')){
                //循环联系人并且记录
                foreach (I('post.xf_id') as $key => $val)
                {
                    //实付金额
                    $shifu=(I('post.pmoney')[$key]*I('post.xf_fandian')[$key])/((I('post.mt_fandian')+100)%100);
                    $yhkxf_list[]=array("xf_id"=>I('post.xf_id')[$key],"hk_id"=>$insid,"money"=>I('post.pmoney')[$key],"mt_fandian"=>I('post.mt_fandian'),"dl_fandian"=>I('post.dl_fandian'),"xf_fandian"=>I('post.xf_fandian')[$key],"shifu_money"=>$shifu,"time"=>time(),"avid"=>I('post.advertiser'),"xsid"=>I('post.market'));
                }
                //联系人表
                $Yihuikuanxufei=M("Yihuikuanxufei");
                foreach($yhkxf_list as $key=>$val)
                {
                    echo $val['money'];
                    if($val['money']!=0)
                    {
                    //如果平款成功
                    if($Yihuikuanxufei->add($yhkxf_list[$key])){
                        M("RenewHuikuan")->where('id='.$val['xf_id'])->setDec('xf_qiane',$val['money']);
                    }
                    }
                }
                //$contact->addAll($contact_list);
            }

            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 2097152;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = '/diankuan/'; // 设置附件上传（子）目录
                // 上传文件
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功
                    $dkfile = M("File");//type=2
                    foreach ($info as $file) {
                        $datafile['type'] = 3;
                        $datafile['yid'] = $insid;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }

                }
            }

            $this->success("提交成功",U("NewCaiwu/show?id=".$postdate['advertiser']));


        }else
        {
            $this->error("提交失败");
        }


    }



    public function excel(){

        $Diankuan=M("Yihuikuanxufei");
        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $coustomer=M('Customer');
                $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='0' and a.avid in($zsql)";
            }
            $this->ser_txt=I('get.search_text');
            if($type=='appname')
            {
                $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
            }
            $this->type=$type;
        }
        //销售
        $sql_false=$Diankuan->field('xsid')->group("xsid")->select(false);
        $userslist=M("Users")->field('id,name')->where("id in ($sql_false)")->select();
        $this->userslist=$userslist;


        //时间条件
        $time_start=I('get.time_start');
        $time_end=I('get.time_end');
        if($time_start!="" and $time_end!="")
        {
            $time_start=strtotime($time_start);

            $time_end=strtotime($time_end);
            $time_end=strtotime("+1 days",$time_end);

            $where.=" and a.time > $time_start and a.time < $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }
        //审核条件
        $type2=I('get.market');
        if($type2!='')
        {
            if($type2=='k')
            {
                $where.=" and a.id!='0' ";
            }
            else
            {
                $where.=" and xsid=$type2";
            }

            $this->type2=$type2;
            $this->ser_txt2=I('get.search_text');

        }
        //款type条件
        $ktype=I('get.ktype');

        if($ktype!='')
        {
            foreach ($ktype as $key=>$val)
            {
                if($val=='1')
                {
                    $in1.=' or xf.payment_type=1';
                }
                if($val=='2')
                {
                    $in1.=' or xf.payment_type=2';
                }
                if($val=='14')
                {
                    $in1.=' or xf.payment_type=14';
                }
                if($val=='16')
                {
                    $in1.=' or xf.payment_type=16';
                }

                if($val=='4')
                {
                    $in2.=' or hk.is_huikuan=1';
                }
                if($val=='3')
                {
                    $in2.=' or hk.payment_type=3';
                }
                if($val=='15')
                {
                    $in2.=' or hk.payment_type=15';
                }

            }

            $in1= substr($in1,3,100);
            $in2= substr($in2,3,100);
            if($in1=='')
            {
                $in1=' a.id!=0';
            }
            if($in2=='')
            {
                $in2=' a.id!=0';
            }
            $where.=" and ($in1) and ($in2) ";
        };
        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");

        //$list=$Diankuan->field('a.*,b.advertiser')->join("a left join __CUSTOMER__ b on a.avid = b.id ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.time desc")->select();
        $list=$Diankuan->field('a.*,b.advertiser,xf.payment_type as xf_type,hk.payment_type as hk_type,hk.is_huikuan')->join("a left join __CUSTOMER__ b on a.avid = b.id left join __RENEW_HUIKUAN__ xf on a.xf_id=xf.id left join __RENEW_HUIKUAN__ hk on a.hk_id=hk.id")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.time desc")->select();

        //主体公司
        $agentcompany=M("AgentCompany");

        foreach($list as $key => $val)
        {
            $uindo=users_info($val['xsid']);
            //公司
            $list2[$key]['advertiser']=$val['advertiser'];
            //产品线
            $xfinfo=M("RenewHuikuan")->field('account')->find($val['xf_id']);
            $account=M("Account")->field('prlin_id')->find($xfinfo['account']);
            $prlin=M('ProductLine')->field('name')->find($account['prlin_id']);
            $list2[$key]['prlin']=$prlin['name'];
            $list2[$key]['money']=num_format($val['money']);
            $list2[$key]['mt_fandian']=$val['mt_fandian'];
            $list2[$key]['xf_fandian']=$val['xf_fandian'];
            $list2[$key]['shifu_money']=number_format($val['shifu_money'],2);
            $list2[$key]['dl_fandian']=$val['dl_fandian'];
            $list2[$key]['gr_fandian']=$val['gr_fandian'];
            //个人返点所耗金额
            $grfdxsjr=$val['money']*($val['gr_fandian']/100);
            //提成前利润
            $list[$key]['tcq_lirun']=$val['money']-$val['shifu_money']-$grfdxsjr;
            $list2[$key]['tcq_lirun']=number_format($list[$key]['tcq_lirun'],2);
            $list2[$key]['market']=$uindo[name];
           // $list2[$key]['xs_fandian']=$val['xs_fandian'];
            //销售提成
            $list[$key]['market_tc']=$list[$key]['tcq_lirun']*($val['xs_fandian']/100);
           // $list2[$key]['market_tc']=number_format($list[$key]['market_tc'],2);
            $list2[$key]['time']=date("Y-m-d",$val['time']);
            //毛利润
            $list[$key]['mao_lirun']=$list[$key]['tcq_lirun']-$list[$key]['market_tc'];
            //$list2[$key]['mao_lirun']=number_format($list[$key]['mao_lirun'],2);

        }

        $filename="huikuan_excel";
       // $headArr=array("公司",'产品线','回款金额',"媒体返点",'续费返点','实付金额','代理返点','个人返点','提成前利润','销售','销售返点','销售提成','时间','毛利润');
        $headArr=array("公司",'产品线','回款金额',"媒体返点",'续费返点','实付金额','代理返点','个人返点','提成前利润','销售','时间');
        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }

    //修改合同所属销售
    public function upfandian(){
        $id=I('get.id');
        if(!is_numeric($id))
        {
            $this->error('参数类型错误');
        }
        $hetong=M("Yihuikuanxufei");
        $info=$hetong->find($id);
        $this->info=$info;
        $this->id=$id; //合同id

        $this->display();
    }
    //修改合同所属销售返回
    public function upfandianru(){
        $hetong=M("Yihuikuanxufei");

        $postdate=$hetong->create();
        if($hetong->where("id=".I('post.id'))->setField('gr_fandian',I("post.fandian")))
        {
            echo 1;
        }else
        {
            echo 2;
        }


    }

}