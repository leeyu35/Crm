<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <meta name="renderer" content="webkit|ie-comp|ie-stand">
    <link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Public/css/reset.css"/>
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<link rel="stylesheet" type="text/css" href="/Public/css/animate.min.css">
    
<link rel="stylesheet" type="text/css" href="../../../Public/css/stylesheets/simple-calendar.css">
<script type="text/javascript" src="../../../Public/js/javascripts/simple-calendar.js"></script>
    <title></title>
    <style>
        .people{
            margin-top: 20px;
            margin-left: 40px;
            width: 90%;
            height: 260px;
            background: #f5f5f5;
            /*border: 1px solid #999999;*/
            box-shadow: 0px 2px 10px #999999;
        }
        .touxiang,.dai{
            float: left;
        }
        .touxiang{
			width:250px;
			text-align:center;
			padding-top:35px;
        }
		.touxiang img{ margin:0 auto;}
        .dai{
            color: #333333;
            font-size: 12px;
            margin-left: 80px;
            margin-top:30px;
        }
        .message{
            color: #ff4e3e;
        }
		.index_right{
			float:left;
			border-left:1px #ccc solid;
			padding-left:20px;
		}
		.ring_2{width:36%; float:right;  margin-top:50px; margin-right:6.5%; }
		.r2_t1{ background: #f5f5f5; box-shadow: 0px 2px 10px #999999; margin-bottom:20px; overflow:hidden;}
    </style>
</head>
<body>

<div class="people" style="overflow:hidden;" >
    <div class="clear" style="margin-top:25px;">
        <div class="touxiang">
            <p>
                <img src="<?php echo (cookie('u_image')); ?>" width="100" height="100" class="img-circle animated flipInY" />
            </p>
            <p style="text-align: center;font-size: 12px;color: #333333; padding-top:10px;">
               <a href="<?php echo U("users/updata?id=$sessionuid");?>"><?php echo (cookie('u_name')); ?></a>【<?php echo ($group[group_name]); ?>】
            </p>
            <a href="<?php echo U("Email/message");?>">
            <span style="color: #999999;font-size: 12px; padding-top:10px; display:block;">
                    <img src="/Public/images/admin/我的消息.png" alt=""/>
                    我的消息(
                    <span class="message"><?php echo ($messagecount); ?></span>
                    )

                </span>
            </a>
        </div>
        <div class="index_right">
         	<span style=" color:#333333;font-size: 12px;">
                    <span class="glyphicon glyphicon-bell" style="font-size:18px;"></span>
                    <span style="font-size: 18px;" class="num">
                       <?php echo ($daiban); ?>
                    </span>
                    待办<br><br>
                    <a href="<?php echo U("Contract/index?shenhe=0");?>" class="btn btn-success" style="background:#693; color:#fff;">
                    待审核合同 <?php if($hetong != 0): ?><span class="badge"><?php echo ($hetong?$hetong:'0'); ?></span><?php endif; ?>
                    </a>
                    <a  href="<?php echo U("Renew/index2?shenhe=0");?>"  class="btn btn-primary" >
                    待审核续费 <?php if($xufei != 0): ?><span class="badge"><?php echo ($xufei?$xufei:'0'); ?></span><?php endif; ?>
                    </a>
                    <a  href="<?php echo U("Diankuan/index?shenhe=0");?>" class="btn btn-success">
                    待审核垫款 <?php if($diankuan != 0): ?><span class="badge"><?php echo ($diankuan?$diankuan:'0'); ?></span><?php endif; ?>
                    </a><br><br>
                    <a  href="<?php echo U("Refund/index?shenhe=0");?>" class="btn btn-info">
                    待审核退款 <?php if($tuikuan != 0): ?><span class="badge"><?php echo ($tuikuan?$tuikuan:'0'); ?></span><?php endif; ?>
                    </a>
                    <a href="<?php echo U("Invoice/index?shenhe=0");?>" class="btn btn-warning">
                    待审核发票 <?php if($fapiao != 0): ?><span class="badge"><?php echo ($fapiao?$fapiao:'0'); ?></span><?php endif; ?>
                    </a>
                    <a  href="<?php echo U("RefundInvoice/index?shenhe=0");?>"  class="btn btn-danger">
                    待审核退票 <?php if($tuipiao != 0): ?><span class="badge"><?php echo ($tuipiao?$tuipiao:'0'); ?></span><?php endif; ?>
                    </a><br>
<br>
					<?php if($huikuan != ''): ?><div class="alert alert-danger" role="alert">近七天到期应回款：<a href="<?php echo U("Diankuan/index?shenhe=1");?>"><?php echo ($huikuan); ?></a></div>
                    <?php else: ?>
                     <div class="alert alert-success" role="alert">欢迎使用CRM，祝您拥有美好的一天 !</div><?php endif; ?>

          </span>
        </div>
    </div>
    <div class="clear"></div>
    <div style="margin-left:40px;margin-top: 30px; ">
                
    </div>
    
    

</div>

    <div style="margin-bottom:50px;">
    <div id='container' style="width:50%; margin-left:40px; margin-bottom:50px; float:left; clear:both; margin-top:50px;"></div>
	<script>
        var myCalendar = new SimpleCalendar('#container');
    </script>
    <div class="ring_2">
    	
    	<div class="r2_t1" style="padding:10px;">
            <img src="/Public/images/admin/凌众logo.png" width="30%" style="float:left;" class="animated bounceInLeft ">
        	<img src="/Public/images/admin/谋士LOGO.png" width="30%" style="float:right;" class="animated bounceInRight ">
        </div>
        <div class="r2_t1" style="padding:10px;"><h2><?php echo ($lizhi); ?></h2></div>
    </div>
    </div>
    
    <div style="clear:both; height:50px;"></div>

</body>
</html>