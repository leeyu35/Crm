<?php
namespace Admin\Controller;
use Think\Controller;
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/16
 * Time: 10:02
 */
class ContractController extends CommonController
{
    public function index(){
        $hetong=M("Contract");
        $count      = $hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$hetong->field('a.id,a.advertiser,a.contract_no,a.contract_money,a.product_line,a.ctime,a.show_money,b.advertiser,c.name')->join("a left join __CUSTOMER__ b on a.advertiser = b.id left join jd_product_line c on a.product_line =c.id")->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function add(){
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        $this->display();
    }
    public function addru(){
        $hetong=M("Contract");
        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        $hetong->ctime=time();
        //检查是否有这个客户
        $Customer=M("Customer");
        $co=$Customer->where("advertiser='".I('post.gongsi')."'")->count();
        if($co==0)
        {
            $this->error("没有这个公司!");
            exit;
        }

         if($hetong->add()){
             $this->success("添加成功",U("index"));

         }else
         {
             $this->error("添加失败");
         }

    }
    public function keyup_adlist(){
        $val=I('post.val');
        $Customer=M("Customer");
        $list=$Customer->field("id,advertiser,submituser")->where("advertiser like '%$val%' and submituser='".session("u_id")."'")->select();

        foreach ($list as $key=>$val)
        {
            $str.="<li><a id='".$val[id]."' title='".$val[submituser]."'>$val[advertiser]</a></li>";
        }
        echo $str;
      //  echo "<li><a id="">$val</a></li>";


    }

    public function Contract_num(){
        $hetong=M("Contract");
        $advertiser=I('get.advertiser');
        $prid=I('get.prid');
        $num=$hetong->where("advertiser=$advertiser and product_line=$prid")->count();

        $num=$num+1;


        echo $num;
    }
    //修改操作
    public  function update(){
        $id=I('get.id');
        $hetong=M("Contract");
        $info=$hetong->find($id);
        $this->info=$info;

        //产品线
        $product_line=M("ProductLine");
        $this->product_line_list=$product_line->field("id,name,title")->order("id asc")->select();
        //公司名称

        $gs=kehu($info[advertiser]);
        $this->gongsi=$gs[advertiser];
        $this->display();

    }
    //修改返回
    public function upru(){
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
        $hetong->create();
        $hetong->contract_start=strtotime($hetong->contract_start);
        $hetong->contract_end=strtotime($hetong->contract_end);
        $hetong->payment_time=strtotime($hetong->payment_time);
        if($hetong->where("id=$id")->save())
        {
            $this->success('修改成功',U('index'));
        }else{
            $this->error('修改失败');
        }


    }
}