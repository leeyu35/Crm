<?php
/**
 * Created by PhpStorm.
 * User: hjd
 * Date: 2016/8/22
 * Time: 16:15
 */

namespace Admin\Controller;
use Think\Controller;

class PublicController extends Controller
{
        //登录
        public function login(){

            $this->display();
        }
        public function loginrn(){
            $users=M("Users");
            $u=I('post.users');
            $p=md5(I('post.password'));
            $vo=$users->where("users='$u' and password='$p'")->find();
            if($vo)
            {
                session(array('name'=>'session_id','expire'=>3600*12*24));
                session("u_id",$vo['id']);  //用户ID
                session("u_name",$vo['name']); //用户姓名
                session("u_image",$vo['image']);//用户图片
                session("u_groupid",$vo['groupid']); //用户组id
                //记录登录日志
                $log=M("Log");
                $data[ip]=$_SERVER["REMOTE_ADDR"];
                $data[users]=$vo['id'];
                $data[dizhi]=getIPLoc_QQ($_SERVER["REMOTE_ADDR"])?getIPLoc_QQ($_SERVER["REMOTE_ADDR"]):'暂无地址信息';
                $data[time]=time();
                $log->add($data);

                $this->success('登录成功',U("/adminIndex"));
            }else
            {
                $this->error("用户名或密码错误");
            }
        }
        //登录成功欢迎页面
        public  function index(){
            if(session("u_id")=='')
            {
                $this->error('您还没有登录',U("/login"));
                exit;
            }
            $this->web_title=C('WEB_NAME');
            $this->sessionuid=session("u_id");
            $this->daiban=daiban();
            $this->display();
        }

        public function usermessage(){
            //组
            $group=M("Groupl")->find(session("u_groupid"));
            $this->group=$group;
            $group_name=$group['group_name'];
            $this->daiban=daiban();
            if($group_name=='商务')
            {
                //垫款
                $Diankuan=M("Diankuan");
                $qitian=strtotime("+1 week");
                $time=time();
                $Diankuanlst=$Diankuan->where(" audit_1=1 and audit_2 and state=0 and back_money_time>$time and back_money_time <$qitian")->count();
                $this->huikuan=$Diankuanlst;
            }


            //权限
            $rbac=M("Rbac");
            //获取合同审核权限组并且判断是否有消息
            //合同待审核
            $hetong=M("Contract");
            $raac_hetong=$rbac->where("module = '/Admin/Contract'")->find();
            //一级审核
            $array=explode(",",$raac_hetong['audit_1']);
            if(in_array(session('u_groupid'),$array))
            {
                $ht_s1=$hetong->where("audit_1 =0 and isxufei=0")->count();
                $rest+=$ht_s1;
            }
            //二级审核
            $array1=explode(",",$raac_hetong['audit_2']);
            if(in_array(session('u_groupid'),$array1))
            {
                $ht_s2=$hetong->where("audit_2 =0  and isxufei=0")->count();
                $rest+=$ht_s2;
            }
            $this->hetong=$rest;

            //续费待审核

            //一级审核
            $array=explode(",",$raac_hetong['audit_1']);
            if(in_array(session('u_groupid'),$array))
            {
                $ht_s1=$hetong->where("audit_1 =0 and isxufei=1")->count();
                $rest2+=$ht_s1;
            }
            //二级审核

            if(in_array(session('u_groupid'),$array1))
            {
                $ht_s2=$hetong->where("audit_2 =0  and isxufei=1")->count();
                $rest2+=$ht_s2;
            }
            $this->xufei=$rest2;
            //垫款待审核
            $hetong=M("Diankuan");
            $raac_hetong=$rbac->where("module = '/Admin/Diankuan'")->find();
            //一级审核
            $array=explode(",",$raac_hetong['audit_1']);
            if(in_array(session('u_groupid'),$array))
            {
                $ht_s1=$hetong->where("audit_1 =0 ")->count();
                $rest3+=$ht_s1;
            }
            //二级审核
            $array1=explode(",",$raac_hetong['audit_2']);
            if(in_array(session('u_groupid'),$array1))
            {
                $ht_s2=$hetong->where("audit_2 =0 ")->count();
                $rest3+=$ht_s2;
            }
            $this->diankuan=$rest3;
            //退款待审核
            $hetong=M("Refund");
            $raac_hetong=$rbac->where("module = '/Admin/Refund'")->find();
            //一级审核
            $array=explode(",",$raac_hetong['audit_1']);
            if(in_array(session('u_groupid'),$array))
            {
                $ht_s1=$hetong->where("audit_1 =0 ")->count();
                $rest4+=$ht_s1;
            }
            //二级审核
            $array1=explode(",",$raac_hetong['audit_2']);
            if(in_array(session('u_groupid'),$array1))
            {
                $ht_s2=$hetong->where("audit_2 =0 ")->count();
                $rest4+=$ht_s2;
            }
            $this->tuikuan=$rest4;

            //发票待审核
            $hetong=M("Invoice");
            $raac_hetong=$rbac->where("module = '/Admin/Invoice'")->find();
            //一级审核
            $array=explode(",",$raac_hetong['audit_1']);
            if(in_array(session('u_groupid'),$array))
            {
                $ht_s1=$hetong->where("audit_1 =0 ")->count();
                $rest5+=$ht_s1;
            }
            //二级审核
            $array1=explode(",",$raac_hetong['audit_2']);
            if(in_array(session('u_groupid'),$array1))
            {
                $ht_s2=$hetong->where("audit_2 =0 ")->count();
                $rest5+=$ht_s2;
            }
            $this->fapiao=$rest5;
            //退票待审核
            $hetong=M("RefundInvoice");
            $raac_hetong=$rbac->where("module = '/Admin/RefundInvoice'")->find();
            //一级审核
            $array=explode(",",$raac_hetong['audit_1']);
            if(in_array(session('u_groupid'),$array))
            {
                $ht_s1=$hetong->where("audit_1 =0 ")->count();
                $rest6+=$ht_s1;
            }
            //二级审核
            $array1=explode(",",$raac_hetong['audit_2']);
            if(in_array(session('u_groupid'),$array1))
            {
                $ht_s2=$hetong->where("audit_2 =0 ")->count();
                $rest6+=$ht_s2;
            }
            $this->tuipiao=$rest6;

            $this->sessionuid=session("u_id");
            $this->url=$_SERVER['SERVER_NAME'];
            //随机励志语录
            $lizhi=array('将来的你会感谢 现在勇敢拼搏的你',
                '我自信，故我成功，我行，我一定能行。',
                '静下来，铸我实力;拼上去，亮我风采。',
                '会当凌绝顶 一览众山小','博观而约取，厚积而薄发。',
                '满招损，谦受益。',
                '笨鸟先飞早入林，笨人勤学早成材',
                '良药苦于口而利于病，忠言逆于耳而利于行。',
                '三人行，必有我师焉，择其善者而从之，其不善者而改之。',
                '天行健，君子以自强不息'

            );
            $this->lizhi=$lizhi[rand(0,8)];
            $this->messagecount =M("Email")->where("s_users=".session('u_id')." and state =0 and s_show=0 ")->order("time desc")->count();// 查询满足要求的总记录数

            $this->display();
        }
        //退出
        public function login_out(){
            session("u_id",null);  //用户ID
            session("u_name",null); //用户姓名
            session("u_image",null);//用户图片
            session("u_groupid",null); //用户组id
            $this->success('退出成功',U('/login'));
        }
        //日志
        public function log(){
            $log=M("Log");
            $users=M('Users');

            $count      = $log->field('a.id,a.ip,a.dizhi,a.time,b.name,b.image,b.groupid')->join("a left join jd_users b on a.users=b.id")->where("b.id !=''")->count();// 查询满足要求的总记录数
            $Page       = new \Think\Page($count,10);// 实例化分页类 传入总记录数和每页显示的记录数(25)
            $show       = $Page->show();// 分页显示输出
            $list=$log->field('a.id,a.ip,a.dizhi,a.time,b.name,b.image,b.groupid')->join("a left join jd_users b on a.users=b.id")->where("b.id !=''")->limit($Page->firstRow.','.$Page->listRows)->order("a.time desc")->select();

            foreach ($list as $key=>$val)
            {
                if($val[groupid])
                {
                    $list[$key]['groupname']=group_name($val[groupid]);
                }
               //$list[$key]['groupname']=group_name($val[groupid])?group_name($val[groupid]):'用户不存在';
            }
            $this->list=$list;
            $this->assign('page',$show);// 赋值分页输出
            $this->display();
        }

}