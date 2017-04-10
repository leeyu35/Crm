<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/16
 * Time: 17:39
 */
namespace Admin\Controller;
use Think\Controller;

class CustomerController extends CommonController
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
            if($type=='users')
            {
                //销售或商务
                $users=M('Users');

                $zsql=$users->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  id!='0' and (submituser in($zsql) or business in ($zsql))";
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

        $count      = $coustomer->where("id!=0 and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出

        $list=$coustomer->field('id,advertiser,business,industry,website,product_line,ctime,city,appName,submituser')->where("id!=0 and ".$q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order('ctime desc')->select();

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
        $this->customer_type=I('get.customer_type');
        $this->product_line_list=$product_line->field("id,name")->order("id asc")->select();
        $this->display();


    }
    //新增客户返回
    public function addru(){
        //获取产品线，并把数组组成字符串
        /*
        $lin=I('post.product_line');
        $product_line=implode(",",$lin);
        */
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


                if(I('post.customer_type')=='2'){

                    $url = "http://sem.yushanapp.com/sem/createsem";

                    $post_data = array (
                        "name"=>I('post.advertiser'),
                        "password" =>md5('123456'),
                        "email" => '123@qq.com',
                        "phone" => '12345678910',
                        "realname"=>I('post.advertiser'),
                        "type"=>'100004',
                    );

                    $yushan_data=hjd_post_curl($url,$post_data);
                    $yushan_id=$yushan_data->data->id;
                    if($yushan_id!='')
                    {
                        $Customer->where('id='.$insertid)->setField('yushan_id',$yushan_id);

                    }else
                    {
                        die('oh~no！添加渠道客户 与 羽扇平台数据同步失败，请联系CRM技术管理人员！！！');
                    }
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
        $wslist = M('Users')->field('id,name')->where("(groupid=2 or groupid=9 or groupid=15) and is_delete!=1")->select();

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
            if(I('get.set_type')=='market')
            {
                $servach='submituser';
            }elseif(I('get.set_type')=='business')
            {
                $servach='business';
            }
            $where.=" and $servach = ".I("get.business");
            $this->business=I('get.business');
        }

        //权限条件
        $q_where=quan_where(__CONTROLLER__);

        $this->set_type='market';
        //筛选有合同的客户
        if(!I('get.set_market'))
        {
            $contact_avid=M("Contract")->field('advertiser')->group('advertiser')->select(false);
            $where.=" and id in($contact_avid)";
            $this->set_type='business';
            $wslist = M('Users')->field('id,name')->where("groupid=3 and is_delete!=1")->select();
        }




        $count      = $coustomer->where("id!=0 and ".$q_where.$where)->count();// 查询满足要求的总记录数

        $Page       = new \Think\Page($count,1000);// 实例化分页类 传入总记录数和每页显示的记录数(25)
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
        $this->wslist = $wslist;
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
    //修改合同所属销售返回
    public function upusers(){
        $hetong=M("Customer");
        if(I('get.type')=='business')
        {
            $ziduan='business';
        }
        if(I('get.type')=='market')
        {
            $ziduan='submituser';
        }
        if($hetong->where("id=".I("get.id"))->setField($ziduan,I("get.users")))
        {
            if(I('get.type')=='market')
            {
                M('Contract')->where('advertiser='.I('get.id'))->save(array("market"=>I('get.users')));
                M('Contract')->where('advertiser='.I('get.id'))->save(array("submituser"=>I('get.users')));
                M('RenewHuikuan')->where('advertiser='.I('get.id'))->save(array("market"=>I('get.users')));
            }
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
            if($type=='users')
            {
                //销售或商务
                $users=M('Users');
                $zsql=$users->field("id")->where(" name like '%".I('get.search_text')."%'")->select(false);
                $where.=" and  id!='0' and (submituser in($zsql) or business in ($zsql))";
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
            if($usinfo['groupid']=='3')
            {
                $where.=' and business='.$usinfo[id];
            }

        }else
        {
            $this->type4_show=1;
        }

        $list=$coustomer->field('id,advertiser,industry,website,ctime,city,appName,submituser,business,customer_type,yu_e,huikuan,bukuan')->where("id!=0 and ".$q_where.$where)->order('ctime desc')->select();

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
            //所属商务
            $uindo=users_info($val['business']);
            $list[$key]['business']=$uindo[name];
            //客户余额
            $list[$key]['yue']=$val['huikuan']-$val['yu_e'];
            //客户创建时间
            $list[$key]['ctime']=Date("Y-m-d",$val['ctime']);
            //客户类型
            $list[$key]['customer_type']==1?$list[$key]['customer_type']='直接客户':$list[$key]['customer_type']='渠道客户';
            //取第一位联系人
            $contact_one=$contact->field('name,tel')->where("customer_id=$val[id]")->find();
            $list[$key]['contact']=$contact_one['name'];
            $list[$key]['tel']=$contact_one['tel'];
        }
        $filename="kehu_excel";
        $headArr=array("id","公司名称",'行业','网址','创建时间','城市','APP名称','提交人','商务','客户类型','总消耗','总回款','总补款','余额','联系人','联系人电话');
        if(!getExcel($filename,$headArr,$list))
        {
            $this->error('没有数据可导出');
        };
    }
}