<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2017/3/14
 * Time: 18:19
 */
namespace Admin\Controller;
use Think\Controller;
class AnnouncementController extends CommonController
{
    public function index(){
        $Diankuan=M("gonggao");
        //搜索条件


        //权限条件
        $q_where=quan_where(__CONTROLLER__);

        $count      = $Diankuan->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,cookie('page_sum')?cookie('page_sum'):50);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$Diankuan->where($q_where.$where)->limit($Page->firstRow.','.$Page->listRows)->order("ctime desc")->select();

        foreach ($list as $key=>$value)
        {
            //文件
            $file=M("File");
            $filelist=$file->where("type=5 and yid=$value[id]")->find();
            $list[$key]['file']=$filelist['file'];
        }

        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();

    }
    public function add(){

        $this->display();
    }




    public function addru(){

        $Diankuan=M("gonggao");
        $postdate=$Diankuan->create();
        $Diankuan->ctime=date("Y-m-d H:i:s");

        $Diankuan->submitusers=cookie('u_id');



        if($insid=$Diankuan->add()){
            if($insid==1)
            {
                $result = $Diankuan->query("select currval('jd_gonggao_id_seq')");
                $insid=$result[0][currval];
            }

            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 20971520;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg','pdf','doc');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = '/gonggao/'; // 设置附件上传（子）目录
                // 上传文件
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功
                    $dkfile = M("File");//type=2
                    foreach ($info as $file) {
                        $datafile['type'] = 5;
                        $datafile['yid'] = $insid;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }

                }
            }

            $this->success("提交成功",U("index"));


        }else
        {
            $this->error("提交失败");
        }


    }
    //修改操作
    public  function updata(){
        $id=I('get.id');
        $Diankuan=M("gonggao");
        $info=$Diankuan->find($id);
        $this->info=$info;



        //文件
        $file=M("File");
        $filelist=$file->where("type=5 and yid=$id")->select();
        $this->filelist=$filelist;


        $this->display();

    }
    //修改返回
    public function upru(){
        $id=I('post.id');
        $Diankuan=M("gonggao");

        $Diankuan->create();


        if($Diankuan->where("id=$id")->save())
        {
            if($_FILES["file"]['name'][0]!="") {
                $upload = new \Think\Upload();// 实例化上传类
                $upload->maxSize = 20971520;// 设置附件上传大小
                $upload->exts = array('jpg', 'gif', 'png', 'jpeg','pdf','doc');// 设置附件上传类型
                $upload->rootPath = './Uploads/'; // 设置附件上传根目录
                $upload->savePath = '/gonggao/'; // 设置附件上传（子）目录
                // 上传文件
                $info = $upload->upload();
                if (!$info) {// 上传错误提示错误信息
                    $this->error($upload->getError());
                } else {// 上传成功
                    $dkfile = M("File");//type=2
                    foreach ($info as $file) {
                        $datafile['type'] = 5;
                        $datafile['yid'] = $id;
                        $datafile['file'] = C('Upload_path') . $file['savepath'] . $file['savename'];
                        $dkfile->add($datafile);
                        //echo $dkfile->_sql();
                    }

                }
            }

            $this->success('修改成功',U('index'));
        }else{
            $this->error('修改失败');
        }


    }


    public function delete(){
        $id=I('get.id');
        $Diankuan=M("gonggao");
        if($Diankuan->delete($id))
        {
            $file=M("File");
            $info=$file->where("yid=$id")->find();
            if($file->delete($info[id]))
            {
                unlink(".".$info["File"]);
            }
            $this->success("删除成功",U('index'));
        }else
        {
            $this->error("删除失败");
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

}