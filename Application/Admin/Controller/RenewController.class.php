<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/31
 * Time: 9:16
 */

namespace Admin\Controller;
use Think\Controller;

class RenewController extends  CommonController
{
    //属于某合同续费列表
    public function index(){
        //检查该合同是否已经通过审核
        $hetong=M("Contract");
        $info=$hetong->field("a.*,b.advertiser as gongsi,c.name")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.id'))->find();
        /*
        if($info[audit_1]!='1' or $info[audit_2]!='1')
        {
            $this->error("该合同还未审核，请完成审核再进行操作");
            exit();
        }*/
        $this->info=$info;

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
            if($type=='contract_no')
            {
                $where.=" and a.id!='0' and a.contract_no like '%".I('get.search_text')."%'";
            }
            if($type=='appname')
            {
                $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
            }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');

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
            $where.=" and a.ctime > $time_start and a.ctime < $time_end";
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
                $where.=" and (a.audit_1=0 or a.audit_2=0 or a.audit_3=0 or a.audit_4=0)";
            }
            if($type2=='1')
            {
                $where.=" and a.audit_1=1 and a.audit_2=1 and a.audit_3=1  and a.audit_4=1";
            }
            $this->type2=$type2;
            $this->ser_txt2=I('get.search_text');

        }

        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");
        $renew_huikuan=M('RenewHuikuan');

        $contact_id=I('get.id');
        $count      = $renew_huikuan->field('a.id,a.advertiser,a.money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0 and a.payment_type!=14 and a.payment_type!=15 and a.xf_contractid='$contact_id' and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$renew_huikuan->field('a.id,a.advertiser as aid,a.users2,a.rebates_proportion,a.account,a.appname,a.money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0 and a.payment_type!=14 and a.payment_type!=15 and a.xf_contractid='$contact_id'  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
            //账户信息
            $account=account($val['account']);
            $list[$key]['a_users']=$account['a_users'];
            $list[$key]['a_id']=$account['id'];

            //产品线信息
            $list[$key]['name']=product_line_name($account['id']);
        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    //属于某合同续费列表
    public function index2(){
        //检查该合同是否已经通过审核
        $hetong=M("Contract");
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

            if($type=='appname')
            {
                $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
            }
            if($type=='users')
            {
                //销售或商务
                $users=M('Users');
                $zsql=$users->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                $adveritiser=M("Customer")->field('id')->where("submituser in($zsql) or business in ($zsql)")->select(false);
                $where.=" and  a.id!='0' and a.advertiser in($adveritiser) ";
            }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');

        }

        //时间条件
        $time_start=I('get.time_start');
        $time_end=I('get.time_end');
        if($time_start!="" and $time_end!="")
        {
            $time_start=strtotime($time_start);


            $time_end=strtotime($time_end);


            $where.=" and a.ctime > $time_start and a.ctime < $time_end";
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
                $where.=" and (a.audit_1=0 or a.audit_2=0) and a.audit_1!=2 and a.audit_2!=2 ";
            }
            if($type2=='1')
            {
                $where.=" and a.audit_1=1 and a.audit_2=1";
            }
            if($type2=='2')
            {
                $where.="  and (a.audit_1=2 or a.audit_2=2)";
            }
            $this->type2=$type2;
            $this->ser_txt2=I('get.search_text');

        }


        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");
        //部门权限sush4 ：1超级管理员 2销售 3商务 4财务 5媒介 6boss 9销售经理  10优化师 11技术部 12 人事 13运营 14会计 15APP销售 16 设计
        $usinfo=users_info(cookie("u_id"));

        if($usinfo['groupid']=='2'  or $usinfo['groupid']=='3' or $usinfo['groupid']=='15')
        {
            if($usinfo['manager']=='1')
            {
                $this->type4_show=1;

                if($usinfo['groupid']=='2' or $usinfo['groupid']=='15')
                {
                    $userswe=M("Users")->field('id')->where("groupid=$usinfo[groupid]")->select(false);
                    $adveritiser = M("Customer")->field('id')->where("submituser in($userswe)")->select(false);
                    $q_where=' a.id!=0';
                    $where .= " and a.id!='0' and a.advertiser in($adveritiser) ";

                }
                $q_where='a.id!=0';
            }
            if($usinfo['groupid']=='3' and $usinfo['manager']!='1')
            {
                    $adveritiser = M("Customer")->field('id')->where(" business = $usinfo[id]")->select(false);
                    $where .= " and  a.id!='0' and a.advertiser in($adveritiser) ";
            }
            //如果是销售并且不是经理的
            if(($usinfo['groupid']=='2' or $usinfo['groupid']=='15') and $usinfo['manager']=='0')
            {
                $adveritiser = M("Customer")->field('id')->where("submituser = $usinfo[id]")->select(false);
                $q_where=' a.id!=0';
                $where .= " and a.id!='0' and a.advertiser in($adveritiser) ";
            }

        }else
        {
            $this->type4_show=1;
        }



        $RenewHuikuan=M('RenewHuikuan');
        $count      = $RenewHuikuan->field('a.id,a.advertiser,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,b.customer_type,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("(a.payment_type=1 or a.payment_type=2 or a.payment_type=3) and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$RenewHuikuan->field('a.id,a.advertiser as aid,a.users2,a.xf_contractid,a.is_accomplish,a.submituser,a.rebates_proportion,b.customer_type,a.account,a.appname,a.money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.audit_3,a.audit_4,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("(a.payment_type=1 or a.payment_type=2 or a.payment_type=3) and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("is_accomplish asc,ctime desc")->select();


        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
            //账户信息
            $account=account($val['account']);
            $list[$key]['a_users']=$account['a_users'];
            $list[$key]['a_id']=$account['id'];
            //产品线信息
            $list[$key]['name']=product_line_name($account['id']);
            //媒介合同返点
            $htinfo=M("Contract")->field('mht_id')->find($val['xf_contractid']);

            $meihetonginfo=M("Contract")->field('rebates_proportion')->find("$htinfo[mht_id]");

            $list[$key]['mt_fandian']=$meihetonginfo['rebates_proportion'];

        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function add(){
        //要续费合同信息
        $hetong=M("Contract");
        $info=$hetong->field("a.*,b.advertiser as gongsi,c.name,a.contract_start,a.contract_end")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.id'))->find();
        $this->info=$info;
        //产品线列表
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();

        //账户
        $account=M("Account");
        $accountlist=$account->field("a.id,a.a_users,a.prlin_id,b.name")->join("a left join __PRODUCT_LINE__ b on a.prlin_id=b.id")->where("a.contract_id =".I('get.id')." and endtime='4092599349'")->select();
        $this->account=$accountlist;


        $this->display();

    }
    //该合同续费合同编号第几份
    public function Contract_num(){
        $hetong=M("Contract");
        $advertiser=I('get.advertiser');
        $pr=I('get.pr');
        $today = strtotime(date('Y-m-d', time()));//获取当天0点
        $max=$hetong->field('contract_no')->where("isxufei=1 and ctime>$today and product_line=$pr")->order("ctime desc")->find();
        $maxsun=substr($max['contract_no'],-2,2);
        $num=$maxsun+1;
        if($num<10)
        {
            $num="0".$num;
        }

        echo $num;
    }



    public function addru(){
        $hetong=M("RenewHuikuan");
        $postdate=$hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=time();
        $hetong->users2=cookie('u_id');
        if($postdate['money']<0)
        {
            $this->error('不能输入负数');
            exit;
        }
        $contract_mthiid=M("Contract")->field('mht_id')->find(I('post.xf_contractid'));
        if(empty($contract_mthiid['mht_id']))
        {
            $this->error('此续费的合同没有选择媒介合同，故而提交续费失败。');
            exit;
        }
        //客户余额
        $advertiser=kehu($postdate['advertiser']);
        $adyue=($advertiser['huikuan']+$advertiser['bukuan'])-$advertiser['yu_e'];
        if($postdate['payment_type']==1 && I('post.curl')!=1)
        {

            //比较两个高精度的数值
            $c = bccomp($postdate['money'],$adyue, 2);
            if($c==1)
            {
                $this->error("客户余额为$adyue,不足以预付此比续费。请重新提交！");

            }
        }

        $xf_qiane=I('post.money');


        //默认续费欠额为全款
        $hetong->xf_qiane=I('post.money');



        //计算续费成本，从合同id读出该合同所属的媒介合同返点，用续费显示金额 除 媒介返点比例
        $yhtinfo=M("Contract")->field('huikuan,yu_e,mht_id,contract_state')->find(I('post.xf_contractid'));
        $mjhtinfo=M("Contract")->field('rebates_proportion,dl_fandian')->find($yhtinfo['mht_id']);
        $fandian=($mjhtinfo['rebates_proportion']+100)/100; //媒体返点
        $hetong->xf_cost=I('post.show_money')/$fandian; //续费成本
        $kehuinfo=kehu(I('post.advertiser'));//客户信息

        //如果是补款  设置回款余额为金额
        if(I('post.payment_type')=='3')
        {
            $hetong->xf_qiane=null;
            $hetong->xf_cost=null;
            $hetong->backmoney_yue=$postdate['money'];
        }

        if($insid=$hetong->add()){
         
                //如果续费成功则修改客户出款或者补款余额  I('post.payment_type')
                money_change($postdate['advertiser'],$postdate['xf_contractid'],I('post.payment_type'),$postdate['money'],$postdate['account']);
           
            if($insid==1)
            {
                $result = $hetong->query("select currval('jd_renew_huikuan_id_seq')");
                $insid=$result[0][currval];
            }

            //添加已续费回款并且修改续费欠额 和 回款的余额
            renew_huikuan($insid);

            //dump($_FILES["file"]);
            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 2097152;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = '/xufei/'; // 设置附件上传（子）目录
                // 上传文件
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功
                    $dkfile = M("File");//type=2
                    foreach ($info as $file) {
                        $datafile['type'] = 4;
                        $datafile['yid'] = $insid;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }
                }
            }
            if(I('post.curl')==1)
            {
                $data['code']=200;
                $data['msg']='success';
                $this->ajaxReturn($data,'json');
                exit;
            }else {
                $this->success("添加成功", U("NewCaiwu/show?id=" . $postdate['advertiser']));
            }
        }else
        {
            if(I('post.curl')==1)
            {
                $data['code']=500;
                $data['msg']='error info:'.$hetong->_sql();
                $this->ajaxReturn($data,'json');
                exit;
            }else {
                $this->error("添加失败");
            }
        }

    }
    //修改操作
    public  function updata(){
        //要续费合同信息
        $hetong=M("RenewHuikuan");
        $info=$hetong->field("a.*,b.advertiser as gongsi,c.name")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.id'))->find();
        //检查审核状态如果未审核 或者已通过审核则不能修改
        /*
        if(($info['audit_1']=='0' and $info['audit_1']=='0') or ($info['audit_1']=='1' and $info['audit_1']=='1') )
        {
            $this->error("未审核或者已审核项目不可更改！");
        }*/

        $this->info=$info;

        $this->yid=I('get.yid');
        $id=I('get.id');

        //文件
        $file=M("File");
        $filelist=$file->where("type=4 and yid=$id")->select();
        $this->filelist=$filelist;
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //公司名称

        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        //一级审核人
        $submitusers3=users_info($info[susers1]);
        $this->users_info3=$submitusers3['name'];
        //二级审核人
        $submitusers4=users_info($info[susers2]);
        $this->users_info4=$submitusers4['name'];

        //账户
        $account=M("Account");
        $accountlist=$account->field("id,a_users")->where("contract_id =".I('get.yid')." and endtime='4092599349'")->select();
        $this->account=$accountlist;

      //  $this->dinfo=$diankuan->where("contract_id=".I('get.id'))->find();

        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $hetong=M("RenewHuikuan");
        $yid=I('post.yid');

        //检查是否有这个客户

        $postdate=$hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=I('post.time')+1;
        $hetong->users2=cookie('u_id');

        if($hetong->where("id=$id")->save())
        {
            //如果修改续费成功则修改客户出款或者补款余额  I('post.payment_type') 修改只可在审核不通过的情况下
            money_change($postdate['advertiser'],$postdate['xf_contractid'],I('post.payment_type'),$postdate['money']);
            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 2097152;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = '/xufei/'; // 设置附件上传（子）目录
                // 上传文件
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功
                    $dkfile = M("File");//type=2
                    foreach ($info as $file) {
                        $datafile['type'] = 4;
                        $datafile['yid'] = $id;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }

                }
            }

            if($yid!='')
            {

                $this->success('修改成功',U("index?id=$yid"));
                //修改审核者

            }else
            {
                $this->success('修改成功',U("index2?shenhe=0"));
            }
        }else{
            $this->error('修改失败');
        }


    }

    //删除操作
    public function delete(){
        $id=I('get.id');
        $group=M("RenewHuikuan");
        $yid=I('get.yid');
        if($group->delete($id))
        {
            if($yid!='')
            {
                $this->success("删除成功",U("index?id=$yid"));
            }else
            {
                $this->success("删除成功",U("index2"));
            }

        }else
        {
            $this->error("删除失败");
        }
    }
    //审核操作
    public function shenhe(){
        $type=I('get.type');
        $id=I('get.id');
        $yid=I('get.yid');


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
                    money_reduce($xfinfo['advertiser'],$xfinfo['xf_contractid'],$xfinfo['payment_type'],$xfinfo['money'],$xfinfo['account']);

                    //回滚已回款续费 1包含本续费的回款 回滚 2，续费欠额回滚  3，已回款续费删除
                    $yihuikuanxufei=M("Yihuikuanxufei")->where("xf_id=$id")->select();
                    foreach ($yihuikuanxufei as $key=>$val)
                    {
                        //回款回滚
                        M("RenewHuikuan")->where("id=$val[hk_id]")->setInc('backmoney_yue',$val['money']);
                        //续费回滚
                        M("RenewHuikuan")->where("id=$val[xf_id]")->setField('xf_qiane',$xfinfo['money']);
                        //删除已回款续费记录
                        M("Yihuikuanxufei")->delete($val[id]);
                    }
                    //循环该合同回款并且重新对应有欠额的续费
                    /*
                    $contract_list=M("RenewHuikuan")->where("is_huikuan=1 and backmoney_yue>0 and xf_contractid=$xfinfo[xf_contractid]")->select();
                    foreach ($contract_list as $key => $val)
                    {
                        huikuan_xufei_auto($val['id']);
                    }
                    */
                    //续费对应回款
                    renew_huikuan();

                }

                $renew_info=$table->find($id);

                //如果是补款并且审核通过的 则复制一条续费记录
                if($type=='audit_2' && $shenhe==1 && $renew_info[payment_type]==3)
                {
                    $data['advertiser']=$renew_info['advertiser'];
                    $data['submituser']=16;
                    $data['type']=$renew_info['type'];
                    $data['xf_contractid']=$renew_info['xf_contractid'];
                    $data['market']=$renew_info['market'];
                    $data['account']=$renew_info['account'];
                    $data['appname']=$renew_info['appname'];
                    $data['money']=$renew_info['money'];
                    $data['rebates_proportion']=$renew_info['rebates_proportion'];
                    $data['show_money']=$renew_info['show_money'];
                    $data['payment_type']=1;//预付
                    $data['payment_time']=$renew_info['payment_time'];
                    $data['note']='补款审核通过 由系统自动生成的续费记录. 操作人：crm管理员~';
                    $data['contract_start']=$renew_info['contract_start'];
                    $data['contract_end']=$renew_info['contract_end'];
                    $data['ctime']=date("Y-m-d H:i:s");
                    $data['users2']=$renew_info['users2'];
                    $data['xf_qiane']=$renew_info['xf_qiane'];
                    $data['xf_cost']=$renew_info['xf_cost'];
                    $data['audit_1']=1;//1级默认审核通过
                    $data['audit_2']=1;//2级默认审核通过
                    $data['susers1']=16;
                    $data['susers2']=16;
                    $data['users2']=16;
                    $data['curl']=1;
                    $url="http://localhost/Admin/Renew/addru.html";
                    $code=post_curl($url,$data);
                    if($code['code']!=200)
                    {
                        dump($code);
                        die("出现严重错误，补款记录自动生成续费失败。请联系CRM 管理员");
                    }
                }


                if($type=='audit_1')
                {
                    $table->where("id=$id")->setField('susers1',cookie('u_id'));
                }
                if($type=='audit_2')
                {
                    $table->where("id=$id")->setField('susers2',cookie('u_id'));
                    if($shenhe==1){
                        M("RenewHuikuan")->where("id=$id")->setField("accomplish_users",cookie("u_id"));
                        M("RenewHuikuan")->where("id=$id")->setField("is_accomplish",'1');
                    }
                }
                /*
                if($type=='audit_3')
                {
                    $table->where("id=$id")->setField('susers3',cookie('u_id'));
                }
                if($type=='audit_4')
                {

                }
                */
            }else
            {
                $this->error('审核失败');
            }
            $this->success('审核成功',U("index2?shenhe=0"));
        }
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

    //查看合同
    public function show(){
        $id=I('get.id');
        $RenewHuikuan=M("RenewHuikuan");
        $hetong=M("contract");
        $info=$RenewHuikuan->find($id);

        //账户信息
        $account=account($info['account']);
        $info['a_users']=$account['a_users'];
        $info['a_id']=$account['id'];

        //产品线信息
        $this->name=product_line_name($account['id']);
        $this->info=$info;
        $this->yid=I('get.yid');
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
        //三级审核人
        $submitusers5=users_info($info[susers3]);
        $this->users_info5=$submitusers5['name'];
        //四级审核人
        $submitusers6=users_info($info[susers4]);
        $this->users_info6=$submitusers6['name'];
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //原合同
        $yinfo=$hetong->find(I('get.yid'));
        $this->yinfo=$yinfo;
        //媒介合同信息
        $this->mhtinfo=$hetong->find($yinfo['mht_id']);
        //销售
        $market=users_info($yinfo[market]);
        $this->market=$market;
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];

        //文件
        $file=M("File");
        $filelist=$file->where("type=4 and yid=$id")->select();
        $this->filelist=$filelist;

        //完成操作人
        $accomplish_users=users_info($info[accomplish_users]);
        $this->accomplish_users=$accomplish_users['name'];
        $this->display();

    }

    public function excel(){
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
            if($type=='contract_no')
            {
                $where.=" and a.id!='0' and a.contract_no like '%".I('get.search_text')."%'";
            }
            if($type=='appname')
            {
                $where.=" and a.id!='0' and a.appname like '%".I('get.search_text')."%'";
            }if($type=='users')
        {
            //销售或商务
            $users=M('Users');
            $zsql=$users->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
            $adveritiser=M("Customer")->field('id')->where("submituser in($zsql) or business in ($zsql)")->select(false);

            $where.=" and  a.id!='0' and a.advertiser in($adveritiser) ";
        }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');

        }

        //合同类型
        /*
        $httype=I('get.httype');
        if($httype!='')
        {
            $where.=" and a.type=2 ";
            $this->httype=$httype;
        }else
        {
            $where.=" and a.type=1 ";
            $this->httype=$httype;
        }*/
        //时间条件
        $time_start=I('get.time_start');
        $time_end=I('get.time_end');
        if($time_start!="" and $time_end!="")
        {
            $time_start=strtotime($time_start);

            $time_end=strtotime($time_end);


            $where.=" and a.ctime >= $time_start and a.ctime <= $time_end";
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
                $where.=" and (a.audit_1=0 or a.audit_2=0 ) and a.audit_1!=2 and a.audit_2!=2";
            }
            if($type2=='1')
            {
                $where.=" and a.audit_1=1 and a.audit_2=1";
            }
            if($type2=='2')
            {
                $where.="  and (a.audit_1=2 or a.audit_2=2 )";
            }
            $this->type2=$type2;
            $this->ser_txt2=I('get.search_text');

        }
        //归档条件
        $type4=I('get.guidang');
        if($type4!='')
        {
            if($type4=='k')
            {
                $where.=" and a.id!='0' ";
            }
            if($type4=='0')
            {
                $where.=" and a.isguidang=0 ";
            }
            if($type4=='1')
            {
                $where.=" and a.isguidang=1 ";
            }
            $this->type4=$type4;
        }

        $type3=I('get.pr_line');
        if($type3!='')
        {
            $where.="and a.product_line =$type3";
            $this->type3=$type3;
        }
        $hetong=M("RenewHuikuan");
        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");
        //部门权限sush4 ：1超级管理员 2销售 3商务 4财务 5媒介 6boss 9销售经理  10优化师 11技术部 12 人事 13运营 14会计 15APP销售 16 设计
        $usinfo=users_info(cookie("u_id"));

        if($usinfo['groupid']=='2'  or $usinfo['groupid']=='3' or $usinfo['groupid']=='15')
        {
            if($usinfo['manager']=='1')
            {
                $this->type4_show=1;

                if($usinfo['groupid']=='2' or $usinfo['groupid']=='15')
                {
                    $userswe=M("Users")->field('id')->where("groupid=$usinfo[groupid]")->select(false);
                    $where.=" and a.market in($userswe)";
                }
                $q_where='a.id!=0';
            }
            if($usinfo['groupid']=='3' and $usinfo['manager']!='1')
            {
                    $adveritiser = M("Customer")->field('id')->where(" business = $usinfo[id]")->select(false);
                    $where .= " and  a.id!='0' and a.advertiser in($adveritiser) ";
            }

        }else
        {
            $this->type4_show=1;
        }

        $list=$hetong->field('a.id,a.advertiser as aid,a.xf_contractid,a.money,a.payment_type,a.payment_time,a.note,a.account,b.customer_type,a.contract_start,a.contract_end,a.type,a.users2,a.appname,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("(a.payment_type=1 or a.payment_type=2 or a.payment_type=3) and  ".$q_where.$where)->order("a.ctime desc")->select();

        foreach($list as $key => $val)
        {

            $Contract=M("Contract")->field('contract_no')->find($val['xf_contractid']);

            //公司
            $list2[$key]['advertiser']=$val['advertiser'];
            if($val['customer_type']==1)
            {
                $list2[$key]['customer_type']='直客';
            }elseif($val['customer_type']==2)
            {
                $list2[$key]['customer_type']='渠道';
            }elseif($val['customer_type']==2)
            {
                $list2[$key]['customer_type']='媒介';
            }


            //合同编号
            $list2[$key]['contract_no']=$Contract['contract_no'];
            //appname
            $list2[$key]['appname']=$val['appname'];
            //账户信息
            $account=account($val['account']);
            $list2[$key]['account']=$account['a_users'];

            //合同金额
            $list2[$key]['money']=num_format($val['money']);
            //显示百度币
            $list2[$key]['show_money']=num_format($val['show_money']);


            //产品线信息
            $list2[$key]['name']=product_line_name($account['id']);
            //返点
            $list2[$key]['rebates_proportion']=$val['rebates_proportion'];
            //媒介合同返点
            $htinfo=M("Contract")->field('mht_id')->find($val['xf_contractid']);

            $meihetonginfo=M("Contract")->field('rebates_proportion')->find("$htinfo[mht_id]");

            $list2[$key]['mt_fandian']=$meihetonginfo['rebates_proportion'];

            if(cookie('u_groupid')!=4)
            {
                $list2[$key]['mt_fandian']='Without permission';
            }
            //提交时间
            $list2[$key]['ctime']=date("Y-m-d H:i:s",$val['ctime']);
            //代理公司
            $agentcompany=M("AgentCompany");


            $confind=M("Contract")->field('agent_company')->find($val['xf_contractid']);
            $aagentcompany=$agentcompany->field("companyname")->find($confind[agent_company]);
            $list2[$key]['daili']=$aagentcompany['companyname'];
            //合同类型

            $list2[$key]['type']=$val['type']==1?'普通合同':'框架合同';
            //保证金
            $list2[$key]['margin']=$val['margin'];

            //付款方式
            if($val['payment_type']==1)
            {
                $list2[$key]['payment_type']='预付';
            }elseif($val['payment_type']==2)
            {
                $list2[$key]['payment_type']='垫付';
            }elseif($val['payment_type']==3)
            {
                $list2[$key]['payment_type']='补款';
            }

            //付款时间
            $list2[$key]['payment_time']=$val['payment_time']?date("Y-m-d",$val['payment_time']):'';


            //销售
            //原合同
            $yinfo=M("Contract")->find($val['xf_contractid']);

            //销售
            $market=users_info($yinfo['market']);
            $list2[$key]['submitusers2']=$market['name'];

            //提交人
            $uindo=users_info($val['users2']);
            $list2[$key]['submituser']=$uindo[name];

            //备注
            $list2[$key]['note']=$val['note'];
        }

        $filename="xufei_excel";
        $headArr=array("公司","公司类型","合同编号",'APP名称','账户名称','金额','显示百度币','产品线','返点','媒体返点','提交时间','代理公司','合同类型','保证金','付款方式','付款时间','销售','提交人','备注');

        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };

    }

    public function contract_account_fandian(){
        $prlin=M("ContractRelevance");
        $contract_id=I("get.htid");
        $acid=I('get.acid');
        $account_info=M("Account")->field("prlin_id")->find($acid);
        $one=$prlin->where("contract_id=$contract_id and product_line=".$account_info['prlin_id'])->find();

        echo $one['fandian'];
    }

    public function is_accomplish(){
        $id=I('get.id');

        if(I('get.type')=='renew')
        {
            $url=U("Renew/index2");
        }elseif(I('get.type')=='tuikuan')
        {
            $url=U("RefundMoney/index2");
        }

        if(I('get.type')=='renew' && cookie("u_groupid")!=5){
            $this->success('sorry，您不是媒介部门成员，无法对续费进行完成操作~~~!');
            exit;
        }
        if(I('get.type')=='tuikuan' && cookie("u_groupid")!=4){
            $this->success('sorry，您不是财务部门成员，无法对退款进行完成操作~~~!');
            exit;
        }


        if(M("RenewHuikuan")->where("id=$id")->setField("accomplish_users",cookie("u_id"))) {
            M("RenewHuikuan")->where("id=$id")->setField("is_accomplish",'1');
            $this->success('操作成功', $url);
        }else
        {
            $this->success('操作失败');
        }
    }
    public function lo(){

        renew_huikuan();
    }

}