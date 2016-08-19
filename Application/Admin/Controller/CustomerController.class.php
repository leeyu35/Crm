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
        /*


        */
    }
    public function add(){
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name")->order("id asc")->select();
        $this->display();


    }
    public function addru(){
        //获取产品线，并把数组组成字符串
        $lin=I('post.product_line');
        $product_line=implode(",",$lin);

        $Customer=M("Customer");
        $Customer->create();
        $Customer->product_line=$product_line;




        if($insertid=$Customer->add()){
           echo $insertid;
            //循环联系人并且记录
            foreach (I('post.name') as $key => $val)
            {
                $contact_list[]=array("name"=>I('post.name')[$key],"qq"=>I('post.qq')[$key],"weixin"=>I('post.weixin')[$key],"email"=>I('post.email')[$key],"position"=>I('post.position')[$key],"tel"=>I('post.tel')[$key],"time"=>time(),"customer_id"=>$insertid);
            }
            //联系人表
            $contact=M("ContactList");

            $contact->addAll($contact_list);

            // $this->success("添加成功");
        }else
        {
            $this->error("添加失败");
        }

    }

    public function addimg(){

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './Uploads/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $this->error($upload->getError());
        }else{// 上传成功
            dump($info);
        }
       exit();

    }
}