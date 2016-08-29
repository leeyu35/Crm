<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/17
 * Time: 14:29
 */
namespace Admin\Model;
use Think\Model;
class CustomerModel extends Model
{
    protected $_validate=array(
        array('advertiser','require','请填写广告主公司名称'),
        array('city','require','请选择用户所属省份'),
        array('advertiser','','这个用户已经被添加过了哦！',0,'unique',1),

    );
}