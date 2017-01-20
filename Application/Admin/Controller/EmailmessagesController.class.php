<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/15
 * Time: 14:10
 */
namespace Admin\Controller;
use Think\Controller;
class EmailmessagesController extends CommonController
{
    public function index(){
        $users=M('Users');
        //搜索条件
        $type=I('get.searchtype');
        if($type!='') {
            if ($type == 'name') {
                $where .= " and name like '%" . I('get.search_text') . "%'";
            }
            $this->type=$type;
            $this->ser_txt=I('get.search_text');
        }

        //权限条件
        //$q_where=quan_users_where(__CONTROLLER__);
        $count      =$users->field('id,name,users,image,groupid,ctime,is_delete,phone,email')->where("id !=0 and is_delete=0 $where")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出 ->limit($Page->firstRow.','.$Page->listRows)
        $list=$users->field('id,name,users,image,groupid,manager,ctime,manager,is_delete,phone,email')->order('ctime desc')->where("id!=0  and is_delete=0 $where")->select();


        foreach ($list as $key=>$val)
        {
            $list[$key]['groupname']=group_name($val[groupid]);
        }
        $this->list=$list;
       // $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    public function index2(){
        $users=M('Email');

        $count      =$users->field('id,title,users,s_users,time')->where('users='.cookie('u_id')." and f_show=0")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出
        $list=$users->field('id,title,users,s_users,state,time')->where('users='.cookie('u_id')."  and f_show=0 ")->order('time desc')->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list  as $key=>$val) {
            $info=users_info($val['s_users']);
            $list[$key]['s_users']=$info['name'];
            $list[$key]['s_images']=$info['image'];
        }

        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }
    function keyup_fzrlist(){
        $val=I('post.val');
        if($val=='')
        {
            return ;
        }
        $Customer=M("Users");
        //权限条件

        $list=$Customer->field("id,name,image,email")->where(" name like '%$val%' and is_delete!=1")->select();
        if($list){
            foreach ($list as $key=>$val)
            {
                $val[email]="&lt;$val[email]&gt;";
                $str.="<li class='xzusers'><img class='img-circle' src='".$val[image]."' height='20' width='20'><a id='".$val[id]."' >$val[name]</a> - $val[email]</li>";
            }
        }else
        {
            $str="";
        }

        echo $str;

    }
    function isfzr(){
        $Customer=M("Users");
        $val=I('get.val');
        $count=$Customer->where("name = '$val'")->count();
        if($count<1)
        {
            echo 0;
        }else
        {
            echo 1;
        }

    }
    public function add(){
        $Groupl=M('Groupl');
        $listgroup=$Groupl->field('id,group_name')->select();
		$users=M('Users');
		$this->list=$users->where(" id != ".cookie("u_id")." and is_delete!=1")->field('id,name,image')->select();
		$this->id=I('get.id');
        $this->grouplist=$listgroup;
        //我的分组
        $this->my_info=$users=M('Users')->find(cookie('u_id'));

        $this->display();
    }
    //发送短信
    public function add2(){
        $Groupl=M('Groupl');
        $listgroup=$Groupl->field('id,group_name')->select();
        //我的分组
        $my_inf=$users=M('Users')->find(cookie('u_id'));
        $this->my_info=$my_inf;
        if($my_inf['groupid'] !='1' and $my_inf['groupid'] !='6' and $my_inf['groupid'] !='12')
        {
            $this->error('您没有权限发送短信');
            exit;
        }
        $users=M('Users');
        $this->list=$users->where(" id != ".cookie("u_id")." and is_delete!=1")->field('id,name,image')->select();
        $this->id=I('get.id');
        $this->grouplist=$listgroup;


        $this->display();
    }
    //发送信息
    public function addru2(){
        $emailmes=M("EmailMes");
        $s_users=I('post.fzrlist');
        $q_users=implode(",",$s_users);

        $users=M("Users");

        if(I('post.type')=='users')
        {
            $userslist=$users->field('phone')->where("id in($q_users)")->select();
        }elseif(I('post.type')=='group')
        {
            $userslist=$users->field('phone')->where("groupid = ".cookie('u_groupid')." and is_delete!=1")->select();
        }elseif(I('post.type')=='all')
        {
            $userslist=$users->field('phone')->where(" is_delete!=1")->select();
        }


        foreach ($userslist as $key=>$val)
        {
            if($val['phone']!='')
            {
                $email.=$val['phone'].',';
            }
        }
        $email=substr($email,0,-1);
        //dump($_POST);


        $url = "http://123.57.136.122:12007/api/msg/sms";
        $content= str_replace('"', "'",$_POST['content']);
        $post_data = array (
            "param"=>array($content),
            "templateId" => "146980",
            "title" => I('post.title'),
            "to"=>$email,
        );
        dump($post_data);

        $data_string=json_encode($post_data);

           //$data_string='{"content":"<p>\u65e0\u60c5\u4e8c\u4e03\u989d<br><\/p>","template":"default","title":"123\u9a71\u868a\u5668\u6587","to":"2885430949@qq.com"}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string))
        );
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();

        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($return_code==200)
        {
            $emailmes->create();

            $emailmes->ctime=time();
            $emailmes->submitusers=cookie('u_id');
            $emailmes->s_users=$q_users;
            $emailmes->title= I('post.title');
            $emailmes->content= I('post.content');
            $emailmes->add();
            $this->success('发送成功');
        }else
        {
            $this->error('发送失败');
        }

    }
    //发送邮件
    public function addru(){
        $emailmes=M("EmailMes");
        $s_users=I('post.fzrlist');
        $q_users=implode(",",$s_users);

        $users=M("Users");

        if(I('post.type')=='users')
        {
            $userslist=$users->field('email')->where("id in($q_users)")->select();
        }elseif(I('post.type')=='group')
        {
            $userslist=$users->field('email')->where("groupid = ".cookie('u_groupid')." and is_delete!=1")->select();
        }elseif(I('post.type')=='all')
        {
            $userslist=$users->field('email')->where(" is_delete!=1")->select();
        }


        foreach ($userslist as $key=>$val)
        {
            if($val['email']!='')
            {
                $email.=$val['email'].';';
            }
        }
        $email=substr($email,0,-1);
        //dump($_POST);


        $url = "http://123.57.136.122:12007/api/msg/email";
        $content= str_replace('"', "'",$_POST['content']);
        $post_data = array (
            "params"=>array("content" => $content),
            "template" => "default",
            "title" => I('post.title'),
            "to"=>$email,
        );

        $data_string=json_encode($post_data);
        $data_string= str_replace("\\/", "/",  $data_string);

        //$data_string='{"content":"<p>\u65e0\u60c5\u4e8c\u4e03\u989d<br><\/p>","template":"default","title":"123\u9a71\u868a\u5668\u6587","to":"2885430949@qq.com"}';
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json; charset=utf-8',
                'Content-Length: ' . strlen($data_string))
        );
        ob_start();
        curl_exec($ch);
        $return_content = ob_get_contents();
        ob_end_clean();

        $return_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        if($return_code==200)
        {
            $emailmes->create();

            $emailmes->ctime=time();
            $emailmes->submitusers=cookie('u_id');
            $emailmes->s_users=$q_users;
            $emailmes->title= I('post.title');
            $emailmes->content= I('post.content');
            $emailmes->add();
            $this->success('发送成功');
        }else
        {
            $this->error('发送失败');
        }

    }

    //删除用户
    public function delete(){
        $users=M("Email");
        $id=I("get.id");
        $type=I("get.type");

        if($users->where("id=$id")->setField($type,1))
        {
            $this->success('删除成功');
        }else
        {
            $this->error('删除失败');
        }


    }

    //修改用户
    public function show(){
        $id=I('get.id');
        $users=M("Email");
        $info=$users->find($id);
        $info2=users_info($info['s_users']);
        $this->info=$users->find($id);
        $this->info2=$info2;
        $this->display();
    }
    public function show2(){
        $id=I('get.id');
        $users=M("Email");
        $info=$users->find($id);
        $info2=users_info($info['users']);
        $this->info=$users->find($id);
        $this->info2=$info2;
        //修改消息状态

        $users->where("id=$id")->setField('state',1);

        $this->display();
    }

    public  function message(){
        $email=M("Email");
        $count      =$email->where("s_users=".cookie('u_id')."  and s_show=0 ")->count();// 查询满足要求的总记录数
        $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
        $show       = $Page->show();// 分页显示输出


        $list=$email->where("s_users=".cookie('u_id')."  and s_show=0 ")->order("time desc")->limit($Page->firstRow.','.$Page->listRows)->select();
        foreach ($list  as $key=>$val) {
            $info=users_info($val['users']);
            $list[$key]['users']=$info['name'];
            $list[$key]['f_images']=$info['image'];
        }
        $this->list=$list;
        $this->assign('page',$show);// 赋值分页输出
        $this->display();
    }

}