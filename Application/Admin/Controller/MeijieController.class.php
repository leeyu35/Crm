<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/16
 * Time: 17:39
 */
namespace Admin\Controller;
use Think\Controller;

class MeijieController extends CommonController
{
    public function index(){

        $coustomer=M('Customer');

        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $where=" and advertiser like '%".I('get.search_text')."%'";
            }
            if($type=='name' or $type=='tel')
            {
                //联系人
                $contact=M('ContactList');
                $se_idlist=$contact->where("$type like '%".I('get.search_text')."%'")->field("customer_id")->select(false);

                $where=" and id in($se_idlist)";
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

            $where.=" and ctime > $time_start and ctime < $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }

        //权限条件
        $q_where=quan_where(__CONTROLLER__);

        $count      = $coustomer->where("customer_type=3 and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出

        $list=$coustomer->field('id,advertiser,business,industry,website,product_line,ctime,city,appName,submituser')->where("customer_type=3 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime desc')->select();

        //echo $coustomer->_sql();
        $contact=M('ContactList');

        foreach($list as $key => $val)
        {
            //产品线
           // $lin=product_line($val['product_line']);
           // $list[$key]['product_lin']=$lin;
            //取第一位联系人
            $contact_one=$contact->field('name,tel')->where("customer_id=$val[id]")->find();
            $list[$key]['contact']=$contact_one['name'];
            $list[$key]['tel']=$contact_one['tel'];
            //提交人
            $uindo=users_info($val['submituser']);
            $list[$key]['submituser']=$uindo[name];
        }

        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    //新增客户信息
    public function add(){
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name")->order("id asc")->select();
        $this->display();


    }
    //新增客户返回
    public function addru(){

        $Customer=D("Customer");
        if($Customer->create()) {
            //$Customer->product_line=$product_line;
            $Customer->submituser=cookie('u_id');
            $Customer->ctime=time();


            if($insertid=$Customer->add()){

                if($insertid==1)
                {
                    $result = $Customer->query("select currval('jd_customer_id_seq')");
                    $insertid=$result[0][currval];
                }

                if(I('post.name')){
                    //循环联系人并且记录
                    foreach (I('post.name') as $key => $val)
                    {
                        $contact_list[]=array("name"=>I('post.name')[$key],"qq"=>I('post.qq')[$key],"weixin"=>I('post.weixin')[$key],"email"=>I('post.email')[$key],"position"=>I('post.position')[$key],"tel"=>I('post.tel')[$key],"time"=>time(),"customer_id"=>$insertid);
                    }
                    //联系人表
                    $contact=M("ContactList");
                    foreach($contact_list as $key=>$val)
                    {
                        $contact->add($contact_list[$key]);
                    }
                    //$contact->addAll($contact_list);
                }

                $this->success("添加成功",U("index"));
            }else
            {
                $this->error("添加失败");
            }

        }else
        {
            $this->error($Customer->getError());
        }


    }
    //添加附件图片
    public function addimg(){
        //如果没有ID或者类型ID则返回错误信息
        $id=I('get.id');
        $type=I('get.type');
        if($id=="" || $type=="")
        {
            header('HTTP/1.1 500');
        }
        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     2097152 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     '/customer/'; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            header('HTTP/1.1 500');
            $this->error($upload->getError());
        }else{// 上传成功
            $file=M('File');
            $image=C('Upload_path').$info['file']['savepath'].$info['file']['savename'];
            $data['type']=$type;
            $data['yid']=$id;
            $data['file']=$image;
            if($file->add($data))
            {
                header('HTTP/1.1 200'); //成功
            }else
            {
                header('HTTP/1.1 500');
            }
        }
       exit();

    }

    //修改客户信息
    public function updata(){
        $id=I('get.id');
        $Customer=M("Customer");
        $this->info=$Customer->find($id);

        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name")->order("id asc")->select();

        //联系人列表
        $contact=M('ContactList');
        $this->contact_list=$contact->field('id,name,qq,weixin,email,position,tel')->where("customer_id=$id")->select();
        $this->display();

    }
    //删除联系人信息 单条
    public function delete_contact(){
        $contact=M('ContactList');
        if($contact->delete(I('get.id')))
        {
           echo '1';
        }else
        {
          echo '2';
        }
    }

    //修改返回
    public function upru(){
        //获取产品线，并把数组组成字符串
        $lin=I('post.product_line');
        $id=I('post.id');
        $product_line=implode(",",$lin);

        $Customer=M("Customer");
        $Customer->create();
        $Customer->product_line=$product_line;
        $Customer->ctime=time();

        //联系人操作
        $contact=M('ContactList');
        //修改
        foreach (I('post.contactid') as $key => $val)
        {
            $contact_list=array("name"=>I('post.name')[$key],"qq"=>I('post.qq')[$key],"weixin"=>I('post.weixin')[$key],"email"=>I('post.email')[$key],"position"=>I('post.position')[$key],"tel"=>I('post.tel')[$key],"time"=>time(),"customer_id"=>$id);
            $contact->where("id=".I('post.contactid')[$key])->save($contact_list);
        }
        //新增
        //循环联系人并且记录
        foreach (I('post.name_n') as $key => $val)
        {
            $contact_list2[]=array("name"=>I('post.name_n')[$key],"qq"=>I('post.qq_n')[$key],"weixin"=>I('post.weixin_n')[$key],"email"=>I('post.email_n')[$key],"position"=>I('post.position_n')[$key],"tel"=>I('post.tel_n')[$key],"time"=>time(),"customer_id"=>$id);
        }

        foreach($contact_list2 as $key=>$val)
        {
            $contact->add($contact_list[$key]);
        }

        //$contact->addAll($contact_list2);

        if($Customer->where("id=$id")->save())
        {

            $this->success('修改成功',U('index'));
        }else{
            $this->error('修改失败');
        }
    }

    //新增图片
    public function addim(){
        $id=I('get.id');
        $Customer=M("Customer");
        $this->info=$Customer->field('advertiser')->find($id);
        $this->id=$id;
        $this->display();
    }
    public function delete(){
        $id=I('get.id');
        $group=M("Customer");
        if($group->delete($id))
        {
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
        }
    }

    //查看客户信息
    public  function show(){
        $id=I('get.id');
        $Customer=M("Customer");
        $info=$Customer->find($id);;
        $this->info=$info;
        //销售
        $submitusers=users_info($info[submituser]);
        $this->users_info=$submitusers['name'];


        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name")->order("id asc")->select();

        //联系人列表
        $contact=M('ContactList');
        $this->contact_list=$contact->field('id,name,qq,weixin,email,position,tel')->where("customer_id=$id")->select();
        $this->display();


    }
    //查看图片
    public  function showim(){
        $id=I('get.id');
        //文件
        $file=M("File");
        $filelist=$file->where("type=1 and yid=$id")->select();
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

    //分配商务
    public  function set_business(){

        $coustomer=M('Customer');

        //只有商务组经理才能执行
        $myusersinfo=users_info(cookie("u_id"));
       // dump($myusersinfo);
        $this->swzs=1;
        $wslist = M('Users')->field('id,name')->where("groupid=3 and is_delete!=1")->select();
        $this->wslist = $wslist;
        if($myusersinfo['groupid']!='1' and $myusersinfo['groupid']!='3')
        {

            $this->error('You have no legal power~');
            $this->swzs=0;
            exit;
        }
        if($myusersinfo['groupid']=='3' and $myusersinfo['manager']==0)
        {
            $this->error('You have no legal power~');
            $this->swzs=0;
            exit;
        }


        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $where=" and advertiser like '%".I('get.search_text')."%'";
            }
            if($type=='name' or $type=='tel')
            {
                //联系人
                $contact=M('ContactList');
                $se_idlist=$contact->where("$type like '%".I('get.search_text')."%'")->field("customer_id")->select(false);

                $where=" and id in($se_idlist)";
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

            $where.=" and ctime > $time_start and ctime < $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }
        //商务条件
        if (I("get.business") != "")
        {
            $where.=" and business = ".I("get.business");
            $this->business=I('get.business');
        }
        //权限条件
        $q_where=quan_where(__CONTROLLER__);

        $count      = $coustomer->where("id!=0 and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出

        $list=$coustomer->field('id,advertiser,industry,website,business,product_line,ctime,city,appName,submituser')->where("id!=0 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime desc')->select();

        //echo $coustomer->_sql();
        $contact=M('ContactList');

        foreach($list as $key => $val)
        {
            //产品线
            // $lin=product_line($val['product_line']);
            // $list[$key]['product_lin']=$lin;
            //取第一位联系人
            $contact_one=$contact->field('name,tel')->where("customer_id=$val[id]")->find();
            $list[$key]['contact']=$contact_one['name'];
            $list[$key]['tel']=$contact_one['tel'];
            //提交人
            $uindo=users_info($val['submituser']);
            $list[$key]['submituser']=$uindo[name];
            //商务
            $uindo=users_info($val['business']);
            $list[$key]['business']=$uindo[name];
        }

        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    //修改合同所属销售
    public function upbusiness(){
        $id=I('get.id');
        if(!is_numeric($id))
        {
            $this->error('参数类型错误');
        }
        $hetong=M("Customer");
        $info=$hetong->find($id);
        $this->info=$info;
        $this->id=$id; //合同id
        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=3 and is_delete!=1")->select();
        $this->display();
    }
    //修改合同所属销售返回
    public function upbusinessru(){
        $hetong=M("Customer");


        if($hetong->where("id=".I("post.id"))->setField('business',I("post.business")))
        {
            echo 1;
        }else
        {
            echo 2;
        }


    }


    public function excel(){

        $coustomer=M('Customer');

        //搜索条件
        $type=I('get.searchtype');
        if($type!='')
        {
            if($type=='advertiser')
            {
                $where=" and advertiser like '%".I('get.search_text')."%'";
            }
            if($type=='name' or $type=='tel')
            {
                //联系人
                $contact=M('ContactList');
                $se_idlist=$contact->where("$type like '%".I('get.search_text')."%'")->field("customer_id")->select(false);

                $where=" and id in($se_idlist)";
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

            $where.=" and ctime > $time_start and ctime < $time_end";
            $this->time_start=I('get.time_start');
            $this->time_end=I('get.time_end');
        }

        //权限条件
        $q_where=quan_where(__CONTROLLER__);


        $list=$coustomer->field('id,advertiser,ctime,submituser')->where("customer_type=3  and ".$q_where.$where)->order('ctime desc')->select();

        //echo $coustomer->_sql();
        $contact=M('ContactList');

        foreach($list as $key => $val)
        {
            //产品线
            // $lin=product_line($val['product_line']);
            // $list[$key]['product_lin']=$lin;

            //提交人
            $uindo=users_info($val['submituser']);
            $list[$key]['submituser']=$uindo[name];

            //客户创建时间
            $list[$key]['ctime']=Date("Y-m-d",$val['ctime']);
            //客户类型

        }
        $filename="kehu_excel";
        $headArr=array("id","公司名称",'创建时间','提交人');
        if(!getExcel($filename,$headArr,$list))
        {
            $this->error('没有数据可导出');
        };
    }

    //媒介合同列表
    public function contract(){
            //产品线
            $product_line=M("ProductLine");
            $product_line_list=$product_line->field("id,name,title")->where("parent_id=0")->order("id asc")->select();
            foreach ($product_line_list as $key=>$val)
            {
                $product_line_list[$key]['erji']=$product_line->field("id,name,title")->where("parent_id=$val[id]")->order("id asc")->select();
            }
            $this->product_line_list=$product_line_list;
            $hetong=M("Contract");


            //搜索条件
            $type=I('get.searchtype');
            if($type!='') {
                if ($type == 'advertiser') {
                    $coustomer = M('Customer');
                    $zsql = $coustomer->field("id")->where(" advertiser like '%" . I('get.search_text') . "%'")->select(false);
                    $where .= " and  a.id!='0' and a.advertiser in($zsql)";

                }
                if ($type == 'contract_no') {
                    $where .= " and a.id!='0' and a.contract_no like '%" . I('get.search_text') . "%'";
                }
                if ($type == 'appname') {
                    $where .= " and a.id!='0' and a.appname like '%" . I('get.search_text') . "%'";
                }
                if ($type == 'submitname')
                {
                    $coustomer=M('Users');
                    $zsql=$coustomer->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                    $where.=" and  a.id!='0' and a.submituser in($zsql)";
                }
                $this->type=$type;
                $this->ser_txt=I('get.search_text');

            }

            //合同类型
            $httype=I('get.httype');
            if($httype!='')
            {
                if($httype!=3)
                {
                    $where.=" and a.type=2  and a.isxufei='0'";
                    $this->httype=$httype;
                }else
                {
                    $where.=" and a.isxufei='0'";
                }

            }else
            {

                $where.=" and a.isxufei='0'";
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
            //产品线条件
            $type3=I('get.pr_line');
            if($type3!='')
            {
                $contract_relevance=M('ContractRelevance');
                $zsql=$contract_relevance->field("contract_id")->where("product_line=$type3")->select(false);
                $where.=" and  a.product_line ='$type3'";

                $this->type3=$type3;
            }
            $where.=" and is_meijie = 1 ";
            //echo $where;
            //权限条件
            $q_where=quan_where(__CONTROLLER__,"a");
            $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where($q_where.$where)->count();// 查询满足要求的总记录数

            $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$hetong->field('a.id,a.advertiser as aid,a.contract_no,a.title,a.parent_id,a.users2,a.isguidang,a.iszuofei,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where($q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();

            foreach($list as $key => $val)
            {
                //提交人
                $uindo=users_info($val['users2']);
                $list[$key]['submituser']=$uindo[name];
                $list[$key]['prduct_line']=contract_prlin($val['id']);
            }

            $this->list=$list;
            $this->assign('page',$show);// 赋值分页输出
            $this->display();

    }

    public function addcontract(){
        //产品线
        $product_line=M("ProductLine");
        $product_line_list=$product_line->field("id,name,title")->where("parent_id=0")->order("id asc")->select();
        foreach ($product_line_list as $key=>$val)
        {
            $product_line_list[$key]['erji']=$product_line->field("id,name,title")->where("parent_id=$val[id]")->order("id asc")->select();
        }
        $this->product_line_list=$product_line_list;
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=2 or groupid=15  or groupid=9")->select();

        //产品线JS字符串
        $jsstr='<select  class="form-control product_line" name="product_line" id="product_line"><option>请选择</option>';
        foreach ($product_line_list as $key=>$value)
        {
            $jsstr.='<option value="'.$value[id].'" title="'.$value[title].'">'.$value['name'].'</option>';

            foreach ($value['erji'] as $k=>$v)
            {

                $jsstr.='<option value="'.$v[id].'" title="'.$v[title].'">&nbsp&nbsp&nbsp&nbsp'.$v['name'].'</option>';

            }
        }

        $jsstr.='</select>';
        $this->jsstr=$jsstr;
        $this->display();
    }

    //输入公司名称
    public function keyup_adlist(){
        $Blog = R('Contract/keyup_adlist',array('3'));
        echo $Blog;
    }

    //添加媒介合同返回
    public function addru_contract(){

        $hetong=M("Contract");
        $postdate=$hetong->create();

        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=time();
        $hetong->users2=cookie('u_id');
        //检查是否有这个客户
        $Customer=M("Customer");

        $co=$Customer->where("id='".I('post.advertiser')."'")->count();

        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        //检查合同编号是否重复
        $biaohaocon=$hetong->where("contract_no='".I('post.contract_no')."'")->count();
        if($biaohaocon>0)
        {
            $this->error("合同编号重复!");
            exit;
        }

        if($hetong->advertiser=='')
        {
            $this->error('提交失败，公司名称不能为空，或您没有按规定操作');
            exit;
        }
        if($insid=$hetong->add()){


            $this->success("添加成功".$success_str,U("contract"));

        }else
        {
            $this->error("添加失败");
        }
    }

    //查看合同
    public function showcontract(){

        $id=I('get.id');
        $hetong=M("Contract");
        $info=$hetong->find($id);
        $this->info=$info;
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

        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();
        //公司名称
        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=2 or groupid=15 or groupid=9")->select();
        //查询是否有关于该合同的子合同
        $zicontract=$hetong->field("id,advertiser,contract_no,appname,market")->where("parent_id=$id")->select();
        foreach($zicontract as $key=>$val)
        {
            $gs=kehu($val[advertiser]);
            $zicontract[$key]['advertiser']=$gs[advertiser];
            //二级审核人
            $makert=users_info($info[market]);
            $zicontract[$key]['market']=$makert['name'];

        }
        $this->zicontract=$zicontract;

        $this->display();

    }
    //修改操作
    public  function updatacontract(){
        $id=I('get.id');
        $hetong=M("Contract");
        $info=$hetong->find($id);
        $this->info=$info;

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
        //显示垫付信息
        $diankuan=M("Diankuan");
        $this->dinfo=$diankuan->find($info['contract_id']);

        //所有销售
        $this->xiaoshou=M('Users')->field('id,name')->where("groupid=2 or groupid=15 or groupid=9")->select();
        $this->display();

    }

    //修改返回
    public function uprucontract(){
        $id=I('post.id');
        $hetong=M("Contract");
        //检查是否有这个客户
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }
        //检查合同编号是否重复
        $biaohaocon=$hetong->where("contract_no='".I('post.contract_no')."'")->count();
        if($biaohaocon>1)
        {
            $this->error("合同编号重复!");
            exit;
        }
        //判断合同归档的时候是否已经审核过
        if(I('post.isguidang')==1)
        {
            //
            $het=$hetong->field('audit_1,audit_2')->find($id);
            if($het['audit_1']!=1 or $het['audit_2']!=1)
            {
                $this->error('此合同还未全部通过审核，无法操作归档');
                exit;
            }
        }

        $postdate=$hetong->create();
        if($hetong->advertiser=='')
        {
            $this->error('提交失败，公司名称不能为空，或您没有按规定操作');
            exit;
        }
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->users2=cookie('u_id');

        if($hetong->where("id=$id")->save())
        {
            $this->success('修改成功'.$success_str,U('contract'));
        }else{
            $this->error('修改失败');
        }


    }

    //添加媒介合同续费活着打款
    public function addrenew($id){
        //续费合同信息
        $contract=M("Contract")->find($id);
        $this->contract=$contract;
        //公司信息
        $this->kehuinfo=kehu($contract['advertiser']);

        //渠道客户列表
        $customer_list=M("customer")->field('id,advertiser,appname')->where("customer_type=2")->select();

        $this->customer_list=$customer_list;
        //代理公司
        $agentcompany=M("AgentCompany");
        $this->agentcompany=$agentcompany->field("id,companyname,title")->order("id asc")->select();

        $this->display();
    }

    public function m_renew_addru(){

        $hetong=M("MrenewHuikuan");
        $postdate=$hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->type=1;
        $hetong->ctime=time();
        $hetong->users2=cookie('u_id');
        if($postdate['money']<0)
        {
            $this->error('不能输入负数');
            exit;
        }
        //默认续费欠额为全款
        $hetong->xf_qiane=I('post.money');

        //计算续费成本，从合同id读出该合同所属的媒介合同返点，用续费显示金额 除 媒介返点比例

        $fandian=($postdate['rebates_proportion']+100)/100; //媒体返点
        $hetong->xf_cost=I('post.show_money')/$fandian; //续费成本
        $kehuinfo=kehu(I('post.advertiser'));//客户信息


        if($insid=$hetong->add()){

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
                        $datafile['type'] = 41;
                        $datafile['yid'] = $insid;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }
                }
            }
            //公司总续费加
            m_money_updata($postdate['advertiser'],I('post.xf_contractid'),$postdate['money'],1,"Inc");
           // M("Customer")->where("id=".$postdate['advertiser'])->setInc("yu_e",$postdate['money']);

            $this->success("添加成功",U("Meijie/meijiexflist?type=1&id=".$postdate['advertiser']));

        }else
        {
            $this->error("添加失败");
        }
    }

    //媒介续费方法
    public function meijiexflist(){
        //检查该合同是否已经通过审核
        $hetong=M("Contract");

        $renew_type=I('get.type');
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


        $where.=" and a.type=$renew_type ";
        $RenewHuikuan=M('MrenewHuikuan');
        $count      = $RenewHuikuan->field('a.id,a.advertiser,a.product_line,a.ctime,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0  and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$RenewHuikuan->field('a.id,a.advertiser as aid,a.users2,a.xf_contractid,a.xf_qiane,a.gongsi,a.qudao,a.is_accomplish,a.submituser,a.rebates_proportion,a.account,a.appname,a.money,a.product_line,a.ctime,a.audit_1,a.audit_2,a.audit_3,a.audit_4,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->where("a.is_huikuan=0 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("is_accomplish asc,ctime desc")->select();


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
            //渠道
            $qudao=kehu($val['qudao']);

            $list[$key]['qudao']=$qudao['advertiser'];

            $list[$key]['mt_fandian']=$meihetonginfo['rebates_proportion'];

        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

    public function renewshow(){

            $id=I('get.id');
            $RenewHuikuan=M("MrenewHuikuan");
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


            //渠道
            $this->qudao=kehu($info['qudao']);
            $this->display();


    }

    public function addrenew_kuan($id){
        $mrenew=M("MrenewHuikuan");
        $info=$mrenew->find($id);
        $qudao=kehu($info[qudao]);
        $info['qudao']=$qudao['advertiser'];
        $this->mrenewinfo=$info;
        $this->display();
    }

    //媒介客户控制台
    public function meijiecustomer(){
        $coustomer = M('Customer');
        $myusersinfo = users_info(cookie("u_id"));
        //搜索条件
        $type = I('get.searchtype');
        if ($type != '') {
            if ($type == 'advertiser') {
                $where = " and advertiser like '%" . I('get.search_text') . "%'";
            }
            if ($type == 'name' or $type == 'tel') {
                //联系人
                $contact = M('ContactList');
                $se_idlist = $contact->where("$type like '%" . I('get.search_text') . "%'")->field("customer_id")->select(false);

                $where = " and id in($se_idlist)";
            }
            if($type=='users')
            {
                //销售或商务
                $users=M('Users');
                $zsql=$users->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  id!='0' and (submituser in($zsql) or business in ($zsql))";
            }
            $this->type = $type;
            $this->ser_txt = I('get.search_text');

        }
        //时间条件
        $time_start = I('get.time_start');
        $time_end = I('get.time_end');
        if ($time_start != "" and $time_end != "") {
            $time_start = strtotime($time_start);
            $time_end = strtotime($time_end);

            $where .= " and ctime > $time_start and ctime < $time_end";
            $this->time_start = I('get.time_start');
            $this->time_end = I('get.time_end');

        }
        //商务条件
        /*
        if (I("get.business") != "")
        {
            $where.=" and business = ".I("get.business");
            $this->business=I('get.business');
        }*/



        //权限条件
        $q_where=quan_where(__CONTROLLER__);
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
                    $where.=" and submituser in($userswe)";
                }
                $q_where='id!=0';
            }
            if($usinfo['groupid']=='3' and $usinfo['manager']!='1')
            {
                $where.=' and business='.$usinfo[id];
            }

        }else
        {
            $this->type4_show=1;
        }

        $count      = $coustomer->where("customer_type=3  and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$coustomer->field('id,advertiser,myu_e,dakuan,undistributed_yu_e,industry,website,product_line,ctime,city,appName,submituser,type,customer_type')->where("customer_type=3  and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime desc')->select();

        $contact=M('ContactList');
        $hetong=M("contract");
        foreach($list as $key => $val)
        {
            //产品线
            // $lin=product_line($val['product_line']);
            // $list[$key]['product_lin']=$lin;
            //取第一位联系人
            $contact_one=$contact->field('name,tel')->where("customer_id=$val[id]")->find();
            $list[$key]['contact']=$contact_one['name'];
            $list[$key]['tel']=$contact_one['tel'];
            $list[$key]['yue']=$val['dakuan']-$val['myu_e'];
            //发票
            $fap=$hetong->field('a.id,a.advertiser as aid,a.audit_1,a.audit_2,a.contract_no,a.market,a.users2,a.isguidang,a.iszuofei,a.appname,a.contract_money,a.product_line,a.ctime,a.rebates_proportion,a.submituser,a.audit_1,a.audit_2,a.show_money,b.advertiser,c.name,a.yu_e,a.huikuan,a.invoice,a.bukuan,a.type')->where("a.advertiser =$val[id] and isxufei=0")->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->order("a.ctime desc")->select();

            $list[$key]['invoice']=$hetong->field('invoice')->where("advertiser =$val[id] ")->sum("invoice");
            //提交人
            $uindo=users_info($val['submituser']);
            $list[$key]['submituser']=$uindo[name];

        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

    }
}