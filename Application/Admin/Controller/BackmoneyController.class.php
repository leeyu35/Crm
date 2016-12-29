<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/9/2
 * Time: 9:45
 */

namespace Admin\Controller;
use Think\Controller;

class BackmoneyController extends CommonController
{
        public function index(){
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

                $where.=" and a.payment_time > $time_start and a.payment_time < $time_end";
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
                    $where.=" and (a.audit_1=0 or a.audit_2=0)";
                }
                if($type2=='1')
                {
                    $where.=" and a.audit_1=1 and a.audit_2=1";
                }
                $this->type2=$type2;
                $this->ser_txt2=I('get.search_text');

            }

            //权限条件
            $q_where=quan_where(__CONTROLLER__,"a");

            $count      = $Diankuan->field('a.id,a.advertiser,a.money,a.payment_time,a.ctime,b.advertiser,a.audit_1,a.audit_2')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0' and is_huikuan=1 and ".$q_where.$where)->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$Diankuan->field('a.id,a.advertiser as aid,a.appname,a.advertiser,a.money,a.payment_time,a.ctime,a.submituser,b.advertiser,a.audit_1,a.audit_2')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0'  and is_huikuan=1 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();
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

    public function add2(){
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $contract=M("Contract");
        $contract_info=$contract->field('id,market,appname')->find(I('get.contract_id'));
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
        $hetong->users2=cookie('u_id');
        /*
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        */
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
            $table=M("RenewHuikuan");

            if(I('get.ju')!=''){
                $shenhe=2;
            }else
            {
                $shenhe=1;
            }
            if($table->where("id=$id")->setField($type,$shenhe))
            {
                //如果是审核不通过的话则减去客户总额
                if($shenhe==2)
                {
                    $xfinfo=$table->find($id);
                    //advertiser,xf_contractid,payment_type,fk_money
                    money_reduce($xfinfo['advertiser'],$xfinfo['xf_contractid'],4,$xfinfo['money']);

                }



                //写入审核人员
                if($type=='audit_1')
                {
                    $table->where("id=$id")->setField('susers1',cookie('u_id'));
                }
                if($type=='audit_2')
                {
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
        $Diankuan=M("RenewHuikuan");
        $info=$Diankuan->find($id);
        $this->info=$info;

        //回款主体
        $agentcompany=M("AgentCompany");
        $hetong=M('Contract')->field('agent_company')->find($info['xf_contractid']);

        $zhuti=$agentcompany->field("id,companyname,title")->find($hetong['agent_company']);

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

        //文件
        $file=M("File");
        $filelist=$file->where("type=3 and yid=$id")->select();
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

            $where.=" and a.ctime >= $time_start and a.ctime <= $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }
        //回款主体



        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");
        $list=$Diankuan->field('a.id,a.advertiser as aid,a.appname,a.advertiser,a.money,a.payment_time,a.ctime,a.submituser,b.advertiser,a.xf_contractid')->join("a left join __CUSTOMER__ b on a.advertiser = b.id ")->where("a.id!='0' and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("a.ctime desc")->select();

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
}