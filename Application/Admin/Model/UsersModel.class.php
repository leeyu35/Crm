<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/22
 * Time: 14:04
 */
namespace Admin\Model;
use Think\Model;

class UsersModel extends  Model
{
    protected $_validate=array(
        array('users','require','请填写用户名'),
        array('fpassword','password','确认密码不正确',0,'confirm'),
        array('name','require','请填写姓名'),
        array('name','','已经有这个用户名了哦！',0,'unique',1),

        );
    protected  $_auto=array(
        array('password','md5',3,'function'),
        array('ctime','time',1,'function'),
    );
}