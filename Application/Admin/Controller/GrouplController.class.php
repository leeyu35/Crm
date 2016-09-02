<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/8/29
 * Time: 15:14
 */
namespace Admin\Controller;
use Think\Controller;
class GrouplController extends CommonController
{
    public function index(){
        $users=M('Groupl');
        $this->list=$users->field('id,group_name,ctime')->order('ctime desc')->select();
        $this->display();
    }
    public function add(){

        $this->display();
    }
    public function addru(){
        $group=M("Groupl");
        $data['group_name']=I('post.group_name');
        $data['ctime']=time();
        if($group->add($data))
        {
            $this->success("添加成功");
        }else
        {
            $this->error("添加失败");
        }

    }

    //修改职位
    public function updata(){
        $id=I('get.id');
        $group=M("Groupl");
        $this->info=$group->find($id);
        $this->display();

    }
    //修改职位返回
    public function upru(){
        $id=I('post.id');
        $group=M("Groupl");
        $group->create();
        if($group->save())
        {
            $this->success("修改成功",U('index'));
        }else
        {
            $this->error("修改失败");
        }

    }

    public function delete(){
        $id=I('get.id');
        $group=M("Groupl");
        if($group->delete($id))
        {
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
        }
    }
}




?>