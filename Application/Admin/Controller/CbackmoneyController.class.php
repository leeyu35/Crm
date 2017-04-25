<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/9/2
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class CbackmoneyController extends CommonController
{
        public function index(){
            $Diankuan=M("BackMoney");
            //搜索条件
            $type=I('get.searchtype');
            if($type!='')
            {
                if($type=='advertiser')
                {
                    $coustomer=M('Customer');
                    $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                    $where.=" and  a.id!='0' and a.advertiser in($zsql)";
                }
                $this->ser_txt=I('get.search_text');
                if($type=='appname')
                {
                    $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
                }
                $this->type=$type;
            }



            //时间条件
            $time_start=I('get.time_start');
            $time_end=I('get.time_end');
            if($time_start!="" and $time_end!="")
            {
                $time_start=strtotime($time_start);
                $time_end=strtotime($time_end);
                $time_end=strtotime("+1 days",$time_end);

                $where.=" and a.b_time > $time_start and a.b_time < $time_end";
                $this->time_start=I('get.time_start');
                $this->time_end=I('get.time_end');
            }
            //审核条件
            $type2=I('get.shenhe');
            if($type2!='')
            {
                if($type2=='k')
                {
                    $where.=" and a.id!='0' ";
                }
                if($type2=='0')
                {
                    $where.=" and (a.audit_1=0 or a.audit_2=0) and a.audit_1!=2 and a.audit_2!=2";
                }
                if($type2=='1')
                {
                    $where.=" and a.audit_1=1 and a.audit_2=1";
                }
                if($type2=='2')
                {
                    $where.=" and (a.audit_1=2 or a.audit_2=2)";
                }
                $this->type2=$type2;
                $this->ser_txt2=I('get.search_text');

            }

            //权限条件
            $q_where=quan_where(__CONTROLLER__,"a");

            $count      = $Diankuan->field('a.id,a.advertiser,a.b_money,a.b_time,a.ctime,b.advertiser,a.audit_1,a.audit_2')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0' and ".$q_where.$where)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$Diankuan->field('a.id,a.advertiser as aid,a.appname,a.advertiser,a.huikuanren,a.b_money,a.b_time,a.ctime,a.submituser,b.advertiser,a.audit_1,a.audit_2')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0'  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();
            $sum = $Diankuan->field('a.id,a.advertiser,a.b_money,a.b_time,a.ctime,b.advertiser,a.audit_1,a.audit_2')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0' and ".$q_where.$where)->sum("a.b_money");// 查询满足要求的总记录数
            $this->sum=$sum;
            foreach($list as $key => $val)
            {
                //提交人
                $uindo=users_info($val['submituser']);
                $list[$key]['submituser']=$uindo[name];
            }
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


    public function keyup_adlist(){
        $Blog = R('Contract/keyup_adlist');
        echo $Blog;
    }



    public function addru(){

        $Diankuan=M("BackMoney");
        $postdate=$Diankuan->create();

        $Diankuan->b_time=strtotime($Diankuan->b_time);
        $Diankuan->ctime=time();


        if($Diankuan->advertiser=='')
        {
            $this->error('提交失败，公司名称不能为空，或您没有按规定操作');
            exit;
        }
        if($postdate['b_money']<0)
        {
            $this->error('不能输入负数');
            exit;
         }

        if($insid=$Diankuan->add()){

            if($insid==1)
            {
                $result = $Diankuan->query("select currval('jd_back_money_id_seq')");
                $insid=$result[0][currval];
            }



            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 2097152;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = '/gongsihuikuan/'; // 设置附件上传（子）目录
                // 上传文件
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功
                    $dkfile = M("File");//type=2
                    foreach ($info as $file) {
                        $datafile['type'] = 31;
                        $datafile['yid'] = $insid;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }

                }
            }


            $this->success("提交成功",U("index"));


        }else
        {
            $this->error("提交失败");
        }


    }
    //修改操作
    public  function updata(){
        $id=I('get.id');
        $Diankuan=M("RenewHuikuan");
        $info=$Diankuan->find($id);
        $this->info=$info;


        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //公司名称
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        //文件
        $file=M("File");
        $filelist=$file->where("type=3 and yid=$id")->select();
        $this->filelist=$filelist;


        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $Diankuan=M("RenewHuikuan");

        $Diankuan->create();


        $Diankuan->payment_time=strtotime($Diankuan->payment_time);

        $Diankuan->ctime=I('post.time')+1;
        $Diankuan->users2=cookie('u_id');
        if($Diankuan->where("id=$id")->save())
        {
            money_reduce($postdate['advertiser'],$postdate['xf_contractid'],4,$postdate['money']);
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
                        $datafile['yid'] = $id;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }

                }
            }

            $this->success('修改成功',U('index'));
        }else{
            $this->error('修改失败');
        }


    }


    public function delete(){
        $id=I('get.id');
        $Diankuan=M("RenewHuikuan");
        if($Diankuan->delete($id))
        {
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
        }
    }
    //审核操作
    public function shenhe(){
        $type=I('get.type');
        $id=I('get.id');


        //检查是否有权限执行审核操作
        $ispw=shenhe(__CONTROLLER__,$type);
        if($ispw!='200')
        {
            $this->error("您没有权限执行审核操作哦");
        }else
        {
            $table=M("BackMoney");

            if(I('get.ju')!=''){
                $shenhe=2;
            }else
            {
                $shenhe=1;
            }
            if($table->where("id=$id")->setField($type,$shenhe))
            {
                $table->where("id=$id")->setField('audit_1',$shenhe);
                $table->where("id=$id")->setField('audit_2',$shenhe);
                //如果是审核不通过的话则减去客户总额
                if($shenhe==2)
                {
                    /*
                    $postdate=$table->find($id);
                    //如果回款bu成功则修改客户未分配余额
                    M("Customer")->where("id=".$postdate['advertiser'])->setDec('undistributed_yu_e',$postdate['b_money']);
                    M("Customer")->where("id=".$postdate['advertiser'])->setDec('huikuan',$postdate['b_money']);
                    */
                }else
                {
                    $postdate=$table->find($id);
                    //如果回款成功则修改客户未分配余额
                    M("Customer")->where("id=".$postdate['advertiser'])->setInc('undistributed_yu_e',$postdate['b_money']);
                    $update1=M("Customer")->where("id=".$postdate['advertiser'])->setInc('huikuan', $postdate['b_money']);//更新公司出款值
                    if($update1!=1)
                    {
                        die('广告回款总额变更失败，请尽快联系CRM系统管理员<br>sql:'.$advertisers->_sql());
                    }else
                    {
                        $str=cookie('u_name').'操作了 公司ID是'.$postdate['advertiser'].'的回款操作，该公司总回款加'. $postdate['b_money'];

                        money_record(0,$postdate['advertiser'],$type,$str,$postdate['b_money'],1);

                    }
                }




                if($type=='audit_2')
                {
                    //写入审核人员
                    $table->where("id=$id")->setField('susers1',cookie('u_id'));
                    $table->where("id=$id")->setField('susers2',cookie('u_id'));
                }

                $this->success('审核成功',U("index"));
            }else
            {
                $this->error('审核失败');
            }
        }
    }

    //查看合同
    public function show(){
        $id=I('get.id');
        $Diankuan=M("BackMoney");
        $info=$Diankuan->find($id);

        $this->info=$info;

        //回款主体
        $agentcompany=M("AgentCompany");
        $hetong=M('Contract')->field('agent_company')->find($info['xf_contractid']);

        $zhuti=$agentcompany->field("id,companyname,title")->find($info['b_company']);

        $this->zhuti=$zhuti[id];
        //销售
        $submitusers=users_info($info[submituser]);
        $this->users_info=$submitusers['name'];
        //提交人
        $submitusers2=users_info($info[users2]);
        $this->users_info2=$submitusers2['name'];
        //一级审核人
        $submitusers3=users_info($info[susers1]);
        $this->users_info3=$submitusers3['name'];
        //二级审核人
        $submitusers4=users_info($info[susers2]);
        $this->users_info4=$submitusers4['name'];

        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();

        //公司名称
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        $this->avinfo=$gs;
        //文件
        $file=M("File");
        $filelist=$file->where("type=31 and yid=$id")->select();
        $this->filelist=$filelist;
        $this->display();
    }

    //删除图片
    public function defile(){
        $id=I('get.id');

        $file=M("File");
        $info=$file->find($id);
        if($file->delete($id))
        {
            $this->success("删除图片成功");
             unlink(".".$info["File"]);
        }else
        {
            $this->error("删除失败");
        }
    }

    public function excel(){
        $Diankuan=M("RenewHuikuan");
        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {

            if($type=='advertiser')
            {
                $coustomer=M('Customer');
                $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='0' and a.advertiser in($zsql)";

            }

            $this->ser_txt=I('get.search_text');
            if($type=='appname')
            {
                $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
            }
            $this->type=$type;
        }

        //时间条件
        $time_start=I('get.time_start');
        $time_end=I('get.time_end');
        if($time_start!="" and $time_end!="")
        {
            $time_start=strtotime($time_start);
            $time_start=strtotime("-1 days",$time_start);
            $time_end=strtotime($time_end);
            $time_end=strtotime("+1 days",$time_end);

            $where.=" and a.b_time >= $time_start and a.b_time < $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }
        //回款主体

        //审核条件
        $type2=I('get.shenhe');
        if($type2!='')
        {
            if($type2=='k')
            {
                $where.=" and a.id!='0' ";
            }
            if($type2=='0')
            {
                $where.=" and (a.audit_1=0 or a.audit_2=0) and a.audit_1!=2 and a.audit_2!=2";
            }
            if($type2=='1')
            {
                $where.=" and a.audit_1=1 and a.audit_2=1";
            }
            if($type2=='2')
            {
                $where.=" and (a.audit_1=2 or a.audit_2=2)";
            }
            $this->type2=$type2;
            $this->ser_txt2=I('get.search_text');

        }

        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");
        $list=$Diankuan->field('a.id,a.advertiser as aid,a.appname,a.advertiser,a.money,a.payment_time,a.ctime,a.submituser,b.advertiser,a.xf_contractid')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.is_huikuan=1 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();

        //主体公司
        $agentcompany=M("AgentCompany");
        foreach($list as $key => $val)
        {
            //公司
            $list2[$key]['advertiser']=$val['advertiser'];
            //回款主体
            $hetong=M('Contract')->field('agent_company')->find($val['xf_contractid']);
            $zhuti=$agentcompany->field("id,companyname,title")->find($hetong['agent_company']);
            $list2[$key]['b_company']=$zhuti['companyname'];



            //回款金额
            $list2[$key]['b_money']=num_format($val['money']);
            //appname
            $list2[$key]['appname']=$val['appname'];
            //回款日期
            $list2[$key]['b_time']=date("Y-m-d",$val['payment_time']);
            //提交时间
            $list2[$key]['ctime']=date("Y-m-d H:i:s",$val['ctime']);

            //提交人
            $submitusers=users_info($val[submituser]);
            $list2[$key]['submitusers2']=$submitusers['name'];

        }

        $filename="huikuan_excel";
        $headArr=array("公司",'回款主体',"回款金额",'APP名称','回款日期','提交时间','提交人');
        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }

    //分配合同回款
    public function fp_huikuan($id){
        $this->kehuinfo=kehu($id);
        $contract=M("Contract");

        $contract_list=$contract->where("advertiser=$id and iszuofei=0 and isxufei=0")->select();
        foreach ($contract_list as $key=>$value)
        {
            //产品线
            $product_line=contract_prlin($value['id']);
            $contract_list[$key]['product_line']=$product_line;
        }

        $this->contract_list=$contract_list;
        $this->display();
        //dump($contract_list);
        // $xufeilist=M("RenewHuikuan")->field('a.*,b.a_users')->join(" a left join jd_account b on a.account=b.id")->where('a.xf_contractid='.I('get.contract_id').' and (a.payment_type=1 or a.payment_type=2) and a.xf_qiane>0 and a.audit_1!=2 and a.audit_2!=2 and a.audit_3!=2  and a.audit_4!=2')->select();

    }

    public function fpaddru(){

        //客户信息
        $kehuinfo=M("Customer")->find(I('post.advertiser'));
        foreach (I('post.pmoney') as $key => $val)
        {
            $money+=I('post.pmoney')[$key];
        }
        if($money>$kehuinfo['undistributed_yu_e'])
        {
            $this->error('可用分配余额不足！');

        }

        $data['submituser']=cookie('u_id');
        $data['market']=I('post.market');
        $data['advertiser']=I('post.advertiser');
        if(I('post.ht_id')){
            //循环联系人并且记录
            foreach (I('post.ht_id') as $key => $val)
            {
                if (I('post.pmoney')[$key]==0)
                {
                    continue;
                }
                //合同信息
                $contractinfo=M("Contract")->find(I('post.ht_id')[$key]);
                //媒介合同信息
                $meijiecontract=M("Contract")->find($contractinfo['mht_id']);
                //媒体返点 和 代理返点
                $data['mt_fandian']=$meijiecontract['rebates_proportion'];
                $data['dl_fandian']=$meijiecontract['dl_fandian'];
                $data['payment_time']=date("Y-m-d h:i:s");
                $data['xf_contractid']=I('post.ht_id')[$key];
                $data['money']=I('post.pmoney')[$key];
                $data['huikuanren']='';
                $data['note']='系统分配回款,分配人：'.cookie('u_name');
                $data['fphk']='1';
               // $a=hjd_post_curl("http://localhost/Admin/Backmoney/addru.html",$data);

                $ch = curl_init();

                curl_setopt($ch, CURLOPT_URL, "http://localhost/Admin/Backmoney/addru.html");
                curl_setopt($ch, CURLOPT_COOKIE, "u_id=".cookie('u_id').";u_groupid=".cookie('u_groupid').";u_name=".cookie("u_name"));
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                // post数据
                curl_setopt($ch, CURLOPT_POST, 1);
                // post的变量
                curl_setopt($ch, CURLOPT_POSTFIELDS, $data);

                $output = curl_exec($ch);
                curl_close($ch);
                $array=json_decode($output,true);
                //打印获得的数据


                if($array[code]==200)
                {
                    $successcount+=1;
                    //减去客户分配余额
                    M("Customer")->where("id=$kehuinfo[id]")->setDec('undistributed_yu_e',I('post.pmoney')[$key]);
                }else
                {
                    $errorcount+=1;
                }
              }
              if(!$errorcount){$errorcount=0;}
                $str="添加合同回款成功：".$successcount." 失败:".$errorcount;


            $this->success($str);
            /*
            foreach($yhkxf_list as $key=>$val)
            {
                if($val['money']!=0) {
                    //如果平款成功
                    if ($Yihuikuanxufei->add($yhkxf_list[$key])) {
                        M("RenewHuikuan")->where('id=' . $val['xf_id'])->setDec('xf_qiane', $val['money']);
                    }
                }
            }*/
            //$contact->addAll($contact_list);
        }

    }
}