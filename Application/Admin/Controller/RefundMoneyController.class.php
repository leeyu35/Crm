<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/31
 * Time: 9:16
 */

namespace Admin\Controller;
use Think\Controller;

class RefundMoneyController extends  CommonController
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

        //退款条件
        $where.=" and (a.payment_type = 14 or a.payment_type=15)";

        $renew_huikuan=M('RenewHuikuan');
        $contact_id=I('get.id');
        $count      = $renew_huikuan->field('a.id,a.advertiser,a.money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0 and a.xf_contractid='$contact_id' and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$renew_huikuan->field('a.id,a.advertiser as aid,a.users2,a.rebates_proportion,a.account,a.appname,a.money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0 and a.xf_contractid='$contact_id'  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
            //账户信息
            $account=account($val['account']);
            $list[$key]['a_users']=$account['a_users'];
            $list[$key]['a_id']=$account['id'];
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
                $where.=" and (a.audit_1=0 or a.audit_2=0 or a.audit_3=0) and a.audit_1!=2 and a.audit_2!=2 and a.audit_3!=2";
            }
            if($type2=='1')
            {
                $where.=" and a.audit_1=1 and a.audit_2=1 and a.audit_3=1";
            }
            if($type2=='2')
            {
                $where.=" and (a.audit_1=2 or a.audit_2=2 or a.audit_3=2)";
            }
            $this->type2=$type2;
            $this->ser_txt2=I('get.search_text');

        }

        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");

        //退款条件
        $where.=" and (a.payment_type = 14 or a.payment_type=15)";
        $RenewHuikuan=M('RenewHuikuan');
        $count      = $RenewHuikuan->field('a.id,a.advertiser,a.product_line,a.ctime,a.audit_1,a.audit_2,a.audit_3,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0 and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$RenewHuikuan->field('a.id,a.payment_type,a.advertiser as aid,a.users2,a.xf_contractid,a.submituser,a.rebates_proportion,a.account,a.appname,a.money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.audit_3,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("is_accomplish asc,ctime desc")->select();

        $sum =$RenewHuikuan->field('a.id,a.advertiser,a.product_line,a.ctime,a.audit_1,a.audit_2,a.audit_3,a.money,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0 and ".$q_where.$where)->sum("a.money");// 查询满足要求的总记录数
        $this->sum=$sum;
        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
            //账户信息
            $account=account($val['account']);
            $list[$key]['a_users']=$account['a_users'];
            $list[$key]['a_id']=$account['id'];
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
        $accountlist=$account->field("id,a_users")->where("contract_id =".I('get.id'))->select();


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

        if($postdate['payment_type']==14)
        {
            $hetong->xf_qiane=$postdate['money'];
        }
        if($postdate['payment_type']==15)
        {
            $hetong->backmoney_yue=$postdate['money'];
        }

        if($insid=$hetong->add()){
            //如果续费成功则修改客户出款或者补款余额  I('post.payment_type')
            money_change($postdate['advertiser'],$postdate['xf_contractid'],I('post.payment_type'),$postdate['money']);

            if($insid==1)
            {
                $result = $hetong->query("select currval('jd_renew_huikuan_id_seq')");
                $insid=$result[0][currval];
            }

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

            //续费对应回款
            renew_huikuan();
           
            $this->success("添加成功",U("NewCaiwu/show?id=".$postdate['advertiser']));
        }else
        {
            $this->error("添加失败");
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
        $accountlist=$account->field("id,a_users")->where("contract_id =".I('get.yid'))->select();
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
        $table=M("RenewHuikuan");
        $info=$table->find($id);

        //14 到客户 15 到总账户
        if($info[payment_type]==15)
        {

            //退款合同详细信息
            $contract=M("Contract");
            $coninfo=$contract->field('mht_id')->find($info['xf_contractid']);

            $mht_info=$contract->find($coninfo['mht_id']);

            if(empty($mht_info)) {
                die('非常抱歉，没有媒介合同，无法判断您是否有权限审核该退款，请联系CRM系统管理员！');
                exit();
            }

            if($mht_info['state']==2 or $mht_info['state']==3)
            {

              if(cookie('u_groupid')!=5)
              {
                  $this->error('sorry,退款到总账户并且是拼框套壳的，只能由媒介操作审核。您未拥有该权限!');
                  exit;
              }else
              {
                  if(I('get.ju')!=''){
                      $shenhe=2;
                  }else
                  {
                      $shenhe=1;
                      $data['is_accomplish']=1;
                      $data['accomplish_users']=cookie("u_id");
                  }
                  $data['audit_1']=$shenhe;
                  $data['audit_2']=$shenhe;
                  $data['audit_3']=$shenhe;
                  $data['susers1']=cookie("u_id");
                  $data['susers2']=cookie("u_id");
                  $data['susers3']=cookie("u_id");


              }

            }
            elseif($mht_info['state']==1)
            {

                /*
                echo '退款到总账户 直开 飞';
                exit;
                */
                if($type=='audit_1' and cookie("u_id")==22)
                {
                    if(I('get.ju')!=''){
                        $shenhe=2;
                    }else
                    {
                        $shenhe=1;
                    }
                    $data['audit_1']=$shenhe;
                    $data['susers1']=cookie("u_id");
                }elseif(($type=='audit_2'  or $type=='audit_3') and cookie("u_id")==21)
                {
                    if(I('get.ju')!=''){
                        $shenhe=2;
                    }else
                    {
                        $shenhe=1;
                    }

                    $data['audit_2']=$shenhe;
                    $data['audit_3']=$shenhe;
                    $data['susers2']=cookie("u_id");
                    $data['susers3']=cookie("u_id");
                }else
                {
                    $this->error('sorry,退款到总账户并且是直开的，只能由刘哥（刘亚军）一级审核，望哥（西望）二级审核。您未拥有该权限!');
                    exit();
                }
            }

            if($table->where("id=$id")->save($data))
            {
                if($shenhe==2)
                {
                    $xfinfo=$table->find($id);
                    //advertiser,xf_contractid,payment_type,fk_money
                    money_reduce($xfinfo['advertiser'],$xfinfo['xf_contractid'],$xfinfo['payment_type'],$xfinfo['money']);
                }
                $this->success('审核成功');
            }else
            {
                $this->error('审核失败');
            }
            exit;
        }
        //dump($info);

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
                    money_reduce($xfinfo['advertiser'],$xfinfo['xf_contractid'],$xfinfo['payment_type'],$xfinfo['money']);

                }
                /*
                if($yid!='')
                {
                    $this->success('审核成功',U("index?id=$yid"));
                    //修改审核者

                }else
                {

                }*/
                if($type=='audit_1')
                {
                    $table->where("id=$id")->setField('susers1',cookie('u_id'));
                }
                if($type=='audit_2')
                {
                    $table->where("id=$id")->setField('susers2',cookie('u_id'));
                }
                if($type=='audit_3')
                {
                    $table->where("id=$id")->setField('susers3',cookie('u_id'));
                }
                $this->success('审核成功',U("index2?shenhe=0"));
            }else
            {
                $this->error('审核失败');
            }
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

        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //原合同
        $this->yinfo=$hetong->find(I('get.yid'));

        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];

        //文件
        $file=M("File");
        $filelist=$file->where("type=4 and yid=$id")->select();
        $this->filelist=$filelist;

        //infi
        $avinfo=kehu($info['advertiser']);

        $this->avinfo=$avinfo;

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
            }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');

        }

        //合同类型
        $httype=I('get.httype');
        if($httype!='')
        {
            $where.=" and a.type=2 ";
            $this->httype=$httype;
        }else
        {
            $where.=" and a.type=1 ";
            $this->httype=$httype;
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
        //归档条件
        $type4=I('get.guidang');
        if($type4!='')
        {
            if($type2=='k')
            {
                $where.=" and a.id!='0' ";
            }
            if($type2=='0')
            {
                $where.=" and (a.audit_1=0 or a.audit_2=0 or a.audit_3=0) and a.audit_1!=2 and a.audit_2!=2 and a.audit_3!=2";
            }
            if($type2=='1')
            {
                $where.=" and a.audit_1=1 and a.audit_2=1 and a.audit_3=1";
            }
            if($type2=='2')
            {
                $where.=" and (a.audit_1=2 or a.audit_2=2 or a.audit_3=2)";
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
        //退款条件
        $where.=" and (a.payment_type = 14 or a.payment_type=15)";

        $list=$hetong->field('a.id,a.advertiser as aid,a.money,a.payment_type,a.payment_time,a.account,a.contract_start,a.contract_end,a.type,a.users2,a.appname,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.id!=0 and ".$q_where.$where)->order("a.ctime desc,is_accomplish asc")->select();

        foreach($list as $key => $val)
        {
            $Contract=M("Contract")->field('contract_no')->find($val['xf_contractid']);

            //公司
            $list2[$key]['advertiser']=$val['advertiser'];
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

            //产品线
            $list2[$key]['product_line']=$val['name'];
            //返点
            $list2[$key]['rebates_proportion']=$val['rebates_proportion'];

            //提交时间
            $list2[$key]['ctime']=date("Y-m-d H:i:s",$val['ctime']);
            //代理公司
            $agentcompany=M("AgentCompany");
            $aagentcompany=$agentcompany->field("companyname")->find($val[agent_company]);
            $list2[$key]['daili']=$aagentcompany['companyname'];
            //合同类型
            $list2[$key]['type']=$val['type']==1?'普通合同':'框架合同';
            //保证金
            $list2[$key]['margin']=$val['margin'];

            //付款方式
            if($val['payment_type']==14)
            {
                $list2[$key]['payment_type']='退款到客户';
            }
            if($val['payment_type']==15)
            {
                $list2[$key]['payment_type']='退款到总账户';
            }

            //付款时间
            $list2[$key]['payment_time']=$val['payment_time']?date("Y-m-d",$val['payment_time']):'';


            //销售
            $submitusers=users_info($val[submituser]);
            $list2[$key]['submitusers2']=$submitusers['name'];

            //提交人
            $uindo=users_info($val['users2']);
            $list2[$key]['submituser']=$uindo[name];
        }

        $filename="xufei_excel";
        $headArr=array("公司","合同编号",'APP名称','账户名称','金额','显示百度币','产品线','返点','提交时间','代理公司','合同类型','保证金','付款方式','付款时间','销售','提交人');

        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }


}