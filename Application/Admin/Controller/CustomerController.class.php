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

            $this->type=$type;
            $this->ser_txt=I('get.search_text');

        }
        $count      = $coustomer->where("submituser=".session('u_id')." $where")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$coustomer->field('id,advertiser,industry,website,product_line,ctime,city')->where("submituser=".session('u_id')." $where")->limit($Page->firstRow.','.$Page->listRows)->select();
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
        //获取产品线，并把数组组成字符串
        /*
        $lin=I('post.product_line');
        $product_line=implode(",",$lin);
        */
        $Customer=D("Customer");
        if($Customer->create()) {
            //$Customer->product_line=$product_line;
            $Customer->submituser=session('u_id');
            $Customer->ctime=time();


            if($insertid=$Customer->add()){

                if(I('post.name')){
                    //循环联系人并且记录
                    foreach (I('post.name') as $key => $val)
                    {
                        $contact_list[]=array("name"=>I('post.name')[$key],"qq"=>I('post.qq')[$key],"weixin"=>I('post.weixin')[$key],"email"=>I('post.email')[$key],"position"=>I('post.position')[$key],"tel"=>I('post.tel')[$key],"time"=>time(),"customer_id"=>$insertid);
                    }
                    //联系人表
                    $contact=M("ContactList");

                    $contact->addAll($contact_list);
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
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
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
        $Customer->ctime=I('post.time')+1;
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


        $contact->addAll($contact_list2);

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


}