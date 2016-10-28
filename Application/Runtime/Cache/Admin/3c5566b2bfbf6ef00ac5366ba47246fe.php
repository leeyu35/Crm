<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <link rel="stylesheet" href="/Public/css/reset.css"/>
    <link rel="stylesheet" href="/Public/css/login.css"/>
<title>CRM管理平台</title>
<link rel="shortcut icon" href="/favicon.ico" /> 
</head>

<body>

<div class="total">
    <div class="clear top">
    <form id="form1" name="form1" method="post" action="<?php echo U('loginrn');?>">

        <div class="left">
            <p class="wel">
                欢迎使用CRM管理平台
            </p>
            <p class="wel-2">
                <img src="/Public/images/admin/账号.png" alt=""/>
                <input type="text" name="users" placeholder="请输入您的账号"/>
            </p>
            <p class="wel-3">
                <img src="/Public/images/admin/密码.png" alt=""/>
                <input type="password" name="password" placeholder="请输入您的密码"/>
            </p>
            <p class="wel-4">
                <input type="submit" value="登陆" class="btn"/>
            </p>
        </div>
        </form>
        <div class="right">
            <p style="margin-top: 10px;">
                <img src="/Public/images/admin/凌众.png" alt="" />
            </p>
            <p style="margin-top: 30px;">
                <img src="/Public/images/admin/谋士.png" alt=""/>
            </p>
        </div>
    </div>
    <div class="banben">
        <p>北京凌众时代广告有限公司/北京谋士网络科技有限公司版权所有</p>
        <p>Copyright &copy 2016</p>
    </div>
</div>
<script>
    var bg=document.getElementsByClassName('total')[0];
    var a=Math.floor(Math.random()*7);
//    console.log("login=bg="+a);
    bg.style.backgroundImage="url(/Public/images/admin/BG"+a+".jpg)";
</script>



</body>
</html>