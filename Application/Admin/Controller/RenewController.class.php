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
        if($info[audit_1]!='1' or $info[audit_2]!='1')
        {
            $this->error("该合同还未审核，请完成审核再进行操作");
            exit();
        }
        $this->info=$info;

        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $coustomer=M('Customer');
                $zsql=$coustomer->field("id")->where(" advertiser like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  a.id!='hjd2' and a.advertiser in($zsql)";

            }
            if($type=='contract_no')
            {
                $where.=" and a.id!='hjd2' and a.contract_no like '%".I('get.search_text')."%'";
            }
            if($type=='appname')
            {
                $where.=" and a.id!='hjd3' and a.appname like '%".I('get.search_text')."%'";
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
                $where.=" and a.id!='hjd3' ";
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
        $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.xf_hetonghao='$info[contract_no]' and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$hetong->field('a.id,a.advertiser as aid,a.users2,a.rebates_proportion,a.contract_no,a.account,a.appname,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.xf_hetonghao='$info[contract_no]'  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
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
                $where.=" and  a.id!='hjd2' and a.advertiser in($zsql)";

            }
            if($type=='contract_no')
            {
                $where.=" and a.id!='hjd2' and a.contract_no like '%".I('get.search_text')."%'";
            }
            if($type=='appname')
            {
                $where.=" and a.id!='hjd3' and a.appname like '%".I('get.search_text')."%'";
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
                $where.=" and a.id!='hjd3' ";
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

        $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.isxufei=1 and ".$q_where.$where)->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$hetong->field('a.id,a.advertiser as aid,a.users2,a.xf_hetonghao,a.submituser,a.rebates_proportion,a.contract_no,a.account,a.appname,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.isxufei=1  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        //echo $hetong->_sql();

        foreach($list as $key => $val)
        {
            //提交人
            $uindo=users_info($val['users2']);
            $list[$key]['submituser']=$uindo[name];
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
        $this->display();

    }
    //该合同续费合同编号第几份
    public function Contract_num(){
        $hetong=M("Contract");
        $advertiser=I('get.advertiser');
        $on=I('get.on');
        $num=$hetong->where("advertiser=$advertiser and xf_hetonghao='$on'")->count();

        $num=$num+1;
        if($num<10)
        {
            $num="0".$num;
        }

        echo $num;
    }

    public function addru(){
        $hetong=M("Contract");
        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=time();
        $hetong->users2=cookie('u_id');

        if($insid=$hetong->add()){
            dump($_FILES["file"]);
            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 3145728;// 设置附件上传大小
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

            $this->success("添加成功",U("index?id=".I('post.htid')));

        }else
        {
            $this->error("添加失败");
        }

    }
    //修改操作
    public  function updata(){
        //要续费合同信息
        $hetong=M("Contract");
        $info=$hetong->field("a.*,b.advertiser as gongsi,c.name")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.id'))->find();
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
        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $hetong=M("Contract");
        //检查是否有这个客户

        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=I('post.time')+1;
        $hetong->users2=cookie('u_id');
        if($hetong->where("id=$id")->save())
        {
            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 3145728;// 设置附件上传大小
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
        $group=M("Contract");
        $yid=I('get.yid');
        if($group->delete($id))
        {
            $this->success("删除成功",U("index?id=$yid"));
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
            $table=M("Contract");
            if(I('get.ju')!=''){
                $shenhe=2;
            }else
            {
                $shenhe=1;
            }
            if($table->where("id=$id")->setField($type,$shenhe))
            {
                if($yid!='')
                {
                    $this->success('审核成功',U("index?id=$yid"));
                    //修改审核者

                }else
                {
                    $this->success('审核成功',U("index2?shenhe=0"));
                }
                if($type=='audit_1')
                {
                    $table->where("id=$id")->setField('susers1',cookie('u_id'));
                }
                if($type=='audit_2')
                {
                    $table->where("id=$id")->setField('susers2',cookie('u_id'));
                }
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
        $hetong=M("Contract");
        $info=$hetong->find($id);
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
        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //原合同
        $this->yinfo=$hetong->find(I('get.yid'));

        if(I('get.yid2')){
            $this->yinfo=array("contract_no"=>I('get.yid2'));

        }
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];

        //文件
        $file=M("File");
        $filelist=$file->where("type=4 and yid=$id")->select();
        $this->filelist=$filelist;
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
                $where.=" and  a.id!='hjd2' and a.advertiser in($zsql)";

            }
            if($type=='contract_no')
            {
                $where.=" and a.id!='hjd2' and a.contract_no like '%".I('get.search_text')."%'";
            }
            if($type=='appname')
            {
                $where.=" and a.id!='hjd3' and a.appname like '%".I('get.search_text')."%'";
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
                $where.=" and a.id!='hjd3' ";
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
        //归档条件
        $type4=I('get.guidang');
        if($type4!='')
        {
            if($type4=='k')
            {
                $where.=" and a.id!='hjd4' ";
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
        $hetong=M("Contract");
        //权限条件
        $q_where=quan_where(__CONTROLLER__,"a");

        if(I('get.yid')=='')
        {
            $where1='a.isxufei=1';
        }else
        {
            $info=$hetong->field("a.*,b.advertiser as gongsi,c.name")->join("a left join jd_customer b on a.advertiser=b.id left join jd_product_line c on a.product_line = c.id")->where("a.id=".I('get.yid'))->find();
            $where1="a.xf_hetonghao='$info[contract_no]'";
        }
        $list=$hetong->field('a.id,a.advertiser as aid,a.fk_money,a.agent_company,a.payment_time,a.account,a.contract_no,a.contract_start,a.contract_end,a.type,a.users2,a.isguidang,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("$where1 and ".$q_where.$where)->order("a.ctime desc")->select();

        foreach($list as $key => $val)
        {
            //公司
            $list2[$key]['advertiser']=$val['advertiser'];
            //合同编号
            $list2[$key]['contract_no']=$val['contract_no'];
            //appname
            $list2[$key]['appname']=$val['appname'];
            //账户名称
            $list2[$key]['account']=$val['account'];
            //合同金额
            $list2[$key]['contract_money']=num_format($val['contract_money']);
            //显示百度币
            $list2[$key]['show_money']=num_format($val['show_money']);
            //付款金额
            $list2[$key]['fk_money']=num_format($val['fk_money']);
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
            //合同开始时间
            $list2[$key]['contract_start']=date("Y-m-d",$val['contract_start']);
            //合同结束时间
            $list2[$key]['contract_end']=date("Y-m-d",$val['contract_end']);
            //付款方式
            $list2[$key]['payment_type']=$val['payment_type']==1?'预付':'垫付';
            //付款时间
            $list2[$key]['payment_time']=$val['payment_time']?date("Y-m-d",$val['payment_time']):'';
            //是否归档
            $list2[$key]['isguidang']=$val['isguidang']==0?'未归档':'已归档';

            //销售
            $submitusers=users_info($val[submituser]);
            $list2[$key]['submitusers2']=$submitusers['name'];

            //提交人
            $uindo=users_info($val['users2']);
            $list2[$key]['submituser']=$uindo[name];
        }

        $filename="xufei_excel";
        $headArr=array("公司","合同编号",'APP名称','账户名称','合同金额','显示百度币','付款金额','产品线','返点','提交时间','代理公司','合同类型','保证金','合同开始时间','合同结束时间','付款方式','付款时间','是否归档','销售','提交人');

        if(!getExcel($filename,$headArr,$list2))
        {
            $this->error('没有数据可导出');
        };
    }


}