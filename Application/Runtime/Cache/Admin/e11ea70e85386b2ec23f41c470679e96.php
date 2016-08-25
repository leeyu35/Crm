<?php if (!defined('THINK_PATH')) exit();?><!DOCTYPE html>
<html>
<head lang="en">
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
    <link rel="stylesheet" href="/Public/css/reset.css"/>
    <link rel="stylesheet" href="/Public/css/persion.css"/>
    <script src="/Public/js/jquery-3.1.0.min.js"></script>

    <title><?php echo ($web_title); ?></title>
    <style>
        .xiao{
            color: #ffffff;
            background: #0398cb;
        }

    </style>
    <script language="javascript">
	$(document).ready(function(e) {
        $(".liebiao").click(function(){
		$(".liebiao").removeClass("xz");
		iformurl=$(this).attr("id");
		$("#frame").attr("src",iformurl);	
		$(this).addClass("xz");
	})

    });

</script>
</head>
<body>
<div class="title clear">
    <div style="float: left">
        <span style="margin-left: 30px;">
        CRM管理控制台
        </span>
    </div>
    <div style="float:right; margin-right: 10px;">
        <span class="xiaoxi">
                <img src="/Public/images/admin/消息.png" alt="" style="vertical-align: middle;"/>消息
                <span class="shu">
                    10
                </span>
        </span><span class="help">
            <img src="/Public/images/admin/帮助.png" alt="" style="vertical-align: middle;"/>
            <span class="help1">
                帮助
            </span>
        </span><span class="user">
            <img src="/Public/images/admin/用户.png" alt="" style="vertical-align: middle;"/>
            <span class="help1">
               <?php echo (session('u_name')); ?>
            </span>
            <img src="/Public/images/admin/下三角.png" alt="" style="vertical-align: middle;"/>
        </span>
    </div>
    
</div>
<div class="clear" style="height:95%;">
    <div class="left">
        <p class="suo">
            <img src="/Public/images/admin/三个丨.png" alt="" style="vertical-align: middle;"/>
        </p>
        <p class="fuwu-1">
            <img src="/Public/images/admin/下三角.png" alt="" style="vertical-align: middle;"/>
            <span style="margin-right: 20px;margin-left: 10px;" class="yin">服务列表</span>
            <img src="/Public/images/admin/设置.png"  class="yin" alt="" style="vertical-align: middle"/>
        </p>
            <ul class="client">
                <li>
                    <img src="/Public/images/admin/客户管理.png" alt="" style="vertical-align: middle"/>
                    <span style="margin-left:10px;">客户管理</span>
                    <div class="biao">
                        客户管理
                    </div>
                </li>
                <li>
                    <img src="/Public/images/admin/合同管理.png" alt="" style="vertical-align: middle"/>
                    <span style="margin-left:10px;">合同管理</span>
                    <div class="biao">
                        合同管理
                    </div>
                </li>
                <li>
                    <img src="/Public/images/admin/财务管理.png" alt="" style="vertical-align: middle"/>
                    <span style="margin-left:10px;">财务管理</span>
                    <div class="biao">
                        财务管理
                    </div>
                </li>
                <li>
                    <img src="/Public/images/admin/账户管理.png" alt="" style="vertical-align: middle"/>
                    <span style="margin-left:10px;">账户管理</span>
                    <div class="biao">
                        账户管理
                    </div>
                </li>
                <li>
                    <img src="/Public/images/admin/盖章申请.png" alt="" style="vertical-align: middle"/>
                    <span style="margin-left:10px;">盖章管理</span>
                    <div class="biao">
                        盖章管理
                    </div>
                </li>
            </ul>


        <p class="fuwu-1">
            <img src="/Public/images/admin/下三角.png" alt="" style="vertical-align: middle;"/>
            <span style="margin-right: 20px;margin-left: 10px;" class="yin">用户中心</span>
            <img src="/Public/images/admin/设置.png" alt=""  class="yin" style="vertical-align: middle"/>
        </p>
        <ul class="client1">
            <li>
                <img src="/Public/images/admin/账号管理.png" alt="" style="vertical-align: middle"/>
                <span style="margin-left:10px;">账号管理</span>
                <div class="biao">
                    账号管理
                </div>
            </li>
            <li>
                <img src="/Public/images/admin/消息中心.png" alt="" style="vertical-align: middle"/>
                <span style="margin-left:10px;">消息中心</span>
                <div class="biao">
                    消息中心
                </div>
            </li>
            <li>
                <img src="/Public/images/admin/记事本.png" alt="" style="vertical-align: middle"/>
                <span style="margin-left:10px;">记事本&nbsp;&nbsp;&nbsp;</span>
                <div class="biao">
                    记事本
                </div>
            </li>
        </ul>



    </div>
    <div class="toc">
        <div class="li1">
            <img src="/Public/images/admin/收起.png" alt="" class="shou"/>
            <div class="guanli">
               	客户管理
            </div>
            <div class="liebiao" id="<?php echo U('Customer/add');?>">
                <a >新客户意向</a>
            </div>
            <div class="liebiao" id="<?php echo U('Customer/index');?>">
                <a  >客户列表</a>
            </div>
        </div>

        

    </div>
    <div class="right">
        <iframe src="<?php echo U('Public/usermessage');?>" frameborder="0" id="frame"></iframe>
    </div>
</div>
<script>
	/*
    $(".liebiao").hover(function(){
        $(this).css("color","#666666").css("background","#ffffff")
    },function(){
        $(this).css("color","#333333").css("background",'#e9ebf0')
    })
	*/
    var flag= 1,flag1= 1,toc= 1,li1=1;

    $(".fuwu-1").eq(0).click(function(){
        if(flag==1){
            $(this).find("img").eq(0).attr("src","/Public/images/admin/右三角.png")
            $(".client").slideUp();
            flag=0;
        }else if(flag==0){
            $(this).find("img").eq(0).attr("src","/Public/images/admin/下三角.png")
            $(".client").slideDown();
            flag=1;
        }
    });
    $(".fuwu-1").eq(1).click(function(){
        if(flag1==1){
            $(this).find("img").eq(0).attr("src","/Public/images/admin/右三角.png")
            $(".client1").slideUp();
            flag1=0;
        }else if(flag1==0){
            $(this).find("img").eq(0).attr("src","/Public/images/admin/下三角.png")
            $(".client1").slideDown();
            flag1=1;
        }
    })

    $(".left li").eq(0).hover(function(){
        if(xuan==1){
            $(this).find("img").attr("src",'/Public/images/admin/客户管理1.png')
            $(this).css("color","#ffffff").css('background','#394555')
        }

        if(suojin==0){
            $(".biao").eq(0).show();
        }
    },function(){
        if(xuan==1){
            $(this).find("img").attr("src",'/Public/images/admin/客户管理.png')
            $(this).css("color","#999999").css('background','#3d3f46')
        }
        if(suojin==0){
            $(".biao").eq(0).hide();
        }
    }).click(function(){
        if(li1==1){
//            $(this).unbind();
//            $(this).unbind("mouseenter").unbind("mouseleave");
            $(".toc").show();
            $(this).css("color","#ffffff").css('background','#0398cb')
            $(this).find("img").attr("src",'/Public/images/admin/客户管理1.png')
            xuan=0;
            toc=0;
            if(suojin==1){
                $(".right").width("78%")
            }else{
                $(".right").width("85%")
            }
            li1=0
        }else{
            $(".toc").hide();
            if(suojin==1){
                $(".right").width("88%")
            }else{
                $(".right").width("95%")
            }
            $(this).css("color","#999999").css('background','#3d3f46')
            $(this).find("img").attr("src",'/Public/images/admin/客户管理.png')
            xuan=1;
            toc=1;
            li1=1;
//            $(this).bind("hover");
        }

    });
    $(".shou").eq(0).click(function(){
        $(".left li").eq(0).css("color","#999999").css('background','#3d3f46').find("img").attr("src",'/Public/images/admin/客户管理.png')
        xuan=1;
        $(".toc").hide();
        toc=1;
        li1=1;
        if(suojin==1){
            $(".right").width("88%")
        }else{
            $(".right").width("95%")
        }
//		 $(".right").width("88%");
    })

    $(".left li").eq(1).hover(function(){
        $(this).find("img").attr("src",'/Public/images/admin/合同管理1.png')
        if(suojin==0){
            $(".biao").eq(1).show();
        }
    },function(){
        $(this).find("img").attr("src",'/Public/images/admin/合同管理.png')
        if(suojin==0){
            $(".biao").eq(1).hide();
        }
    })
    $(".left li").eq(2).hover(function(){
        $(this).find("img").attr("src",'/Public/images/admin/财务管理1.png')
        if(suojin==0){
            $(".biao").eq(2).show();
        }
    },function(){
        $(this).find("img").attr("src",'/Public/images/admin/财务管理.png')
        if(suojin==0){
            $(".biao").eq(2).hide();
        }
    })
    $(".left li").eq(3).hover(function(){
        $(this).find("img").attr("src",'/Public/images/admin/账户管理1.png')
        if(suojin==0){
            $(".biao").eq(3).show();
        }
    },function(){
        $(this).find("img").attr("src",'/Public/images/admin/账户管理.png')
        if(suojin==0){
            $(".biao").eq(3).hide();
        }
    })
    $(".left li").eq(4).hover(function(){
        $(this).find("img").attr("src",'/Public/images/admin/盖章申请1.png')
        if(suojin==0){
            $(".biao").eq(4).show();
        }
    },function(){
        $(this).find("img").attr("src",'/Public/images/admin/盖章申请.png')
        if(suojin==0){
            $(".biao").eq(4).hide();
        }
    })
    $(".left li").eq(5).hover(function(){
        $(this).find("img").attr("src",'/Public/images/admin/账号管理1.png')
        if(suojin==0){
            $(".biao").eq(5).show();
        }
    },function(){
        $(this).find("img").attr("src",'/Public/images/admin/账号管理.png')
        if(suojin==0){
            $(".biao").eq(5).hide();
        }
    })
    $(".left li").eq(6).hover(function(){
        $(this).find("img").attr("src",'/Public/images/admin/消息中心1.png')
        if(suojin==0){
            $(".biao").eq(6).show();
        }
    },function(){
        $(this).find("img").attr("src",'/Public/images/admin/消息中心.png')
        if(suojin==0){
            $(".biao").eq(6).hide();
        }
    })
    $(".left li").eq(7).hover(function(){
        $(this).find("img").attr("src",'/Public/images/admin/记事本1.png')
        if(suojin==0){
            $(".biao").eq(7).show();
        }
    },function(){
        $(this).find("img").attr("src",'/Public/images/admin/记事本.png')
        if(suojin==0){
            $(".biao").eq(7).hide();
        }
    })
var suojin=1;

    $(".suo").click(function(){
        if(suojin==1){
            $(".left").width("5%");
            if(toc==0){
                $(".right").width("85%");
            }else{
                $(".right").width("95%");
            }


            $(this).find("img").attr("src","/Public/images/admin/三个横.png")
            $(".yin").hide();
            $(".client span").hide();
            $(".client1 span").hide();
            suojin=0;
        }else if(suojin==0){
            $(".left").width("12%");
            if(toc==0){
                $(".right").width("78%");
            }else{
                $(".right").width("88%");
            }

            $(this).find("img").attr("src","/Public/images/admin/三个丨.png")
            $(".yin").show();
            $(".client span").show();
            $(".client1 span").show();
            suojin=1;
        }
	
    })
</script>
</body>
</html>