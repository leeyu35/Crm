<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/30
 * Time: 9:29
 */

namespace Admin\Controller;
use Think\Controller;

class RbacController extends CommonController
{
    public function index(){
        //职位列表
        $users=M('Groupl');
        $this->grouplist=$users->field('id,group_name,ctime')->order('ctime desc')->select();
        //权限表
        $rbac=M('Rbac');
        $list=$rbac->select();
        foreach ($list as $key=>$val)
        {
            if($val['audit_1']){
                $list[$key]['audit_1']=group_name($val['audit_1']);
            }
            if($val['audit_2']){
                $list[$key]['audit_2']=group_name($val['audit_2']);
            }
            if($val['add_']){
                $list[$key]['add_']=group_name($val['add_']);
            }
            if($val['update_']){
                $list[$key]['update_']=group_name($val['update_']);
            }
            if($val['delete_']){
                $list[$key]['delete_']=group_name($val['delete_']);
            }
            if($val['show_']){
                $list[$key]['show_']=group_name($val['show_']);
            }
            if($val['index_show']){
                $list[$key]['index_show']=group_name($val['index_show']);
            }

        }
        $this->list=$list;
        $this->display();

    }

    public function add(){
        $Groupl=M('Groupl');
        $listgroup=$Groupl->field('id,group_name')->select();
        $this->grouplist=$listgroup;
       // echo __CONTROLLER__;

        $this->display();
    }
    public function addru(){
    $Groupl=M('Rbac');
    $data['module']=I('post.module');
    $data['title']=I('post.title');
    $data['audit_1']=implode(',',I('post.audit_1'));
    $data['audit_2']=implode(',',I('post.audit_2'));
    $data['add_']=implode(',',I('post.add_'));
    $data['update_']=implode(',',I('post.update_'));
    $data['delete_']=implode(',',I('post.delete_'));
    $data['show_']=implode(',',I('post.show_'));
    $data['index_show']=implode(',',I('post.index_show'));
    if($Groupl->add($data))
    {
        $this->success("添加成功",U('index'));
    }else
    {
        $this->error("添加失败");
    }

}

    //修改职位
    public function updata(){
        $id=I('get.id');
        $Groupl=M('Groupl');
        $listgroup=$Groupl->field('id,group_name')->select();
        $this->grouplist=$listgroup;

        $group=M("Rbac");
        $this->info=$group->find($id);
        $this->display();

    }
    public function upru(){
        $Groupl=M('Rbac');
        $id=I('post.id');
        $data['module']=I('post.module');
        $data['title']=I('post.title');
        $data['audit_1']=implode(',',I('post.audit_1'));
        $data['audit_2']=implode(',',I('post.audit_2'));
        $data['add_']=implode(',',I('post.add_'));
        $data['update_']=implode(',',I('post.update_'));
        $data['delete_']=implode(',',I('post.delete_'));
        $data['show_']=implode(',',I('post.show_'));
        $data['index_show']=implode(',',I('post.index_show'));
        if($Groupl->where("id=$id")->save($data))
        {
            $this->success("修改成功",U('index'));
        }else
        {
            $this->error("修改失败");
        }

    }

    public function delete(){
        $id=I('get.id');
        $group=M("Rbac");
        if($group->delete($id))
        {
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
        }
    }
}