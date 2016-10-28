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
<script src="/Public/js/jquery-3.1.0.min.js"></script>
 <script src="/Public/js/echarts.js"></script>
<script src="/Public/js/china.js"></script>

    <title></title>
    <style>
        .people{
            margin-top: 20px;
            margin-left: 40px;
            width: 90%;
			padding-top:5px;
			padding-bottom:5px;
           	
            background: #f5f5f5;
            /*border: 1px solid #999999;*/
            box-shadow: 0px 2px 10px #999999;
        }
        .touxiang,.dai{
            float: left;
        }
        .touxiang{
			
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
			border-left:1px #ccc solid;
			padding-left:20px;
		}
		.ring_2{width:36%; float:right;  margin-top:50px; margin-right:6.5%; }
		.r2_t1{ background: #f5f5f5; box-shadow: 0px 2px 10px #999999; margin-bottom:20px; overflow:hidden;}
    </style>
    <script>
	$(document).ready(function(e) {
        Notification.requestPermission(function(permission){
				
				if(permission=='granted')
				{
					$("#button").html('<span class="glyphicon glyphicon-comment"></span> 您已开启消息通知')
				}
			});
    });
document.addEventListener('DOMContentLoaded',function(){
		document.getElementById('button').addEventListener('click',function(){
			
			if(! ('Notification' in window) ){
				alert('Sorry bro, your browser is not good enough to display notification');
				return;
			}	
			
			Notification.requestPermission(function(permission){
				
				if(permission=='granted')
				{
					alert('您已开启消息通知!');	
				}
			});
			
		});
	});

</script>

</head>
<body>

<div class="people" >
    <div class="row clear" style="margin-top:25px;">
        <div class="col-md-3 touxiang">
            <p>
                <img src="<?php echo (cookie('u_image')); ?>" width="100" height="100" class="img-circle animated flipInY" />
            </p>
            <p style="text-align: center;font-size: 12px;color: #333333; padding-top:10px;">
               <a href="<?php echo U("users/updata?id=$sessionuid");?>"><?php echo (cookie('u_name')); ?></a>【<?php echo ($group[group_name]); ?>】
            </p>
            <a href="<?php echo U("Email/message");?>">
            <span style="color: #999999;font-size: 12px; padding-top:10px; display:block;">
                    <img src="/Public/images/admin/我的消息.png" alt=""/>
                    我的消息(<span class="message"><?php echo ($messagecount); ?></span>)
            </span>
            </a>
        </div>
        <div class="col-md-5 index_right">
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
					<?php if($huikuan != ''): ?><div class="alert alert-danger width96" role="alert">近七天到期应回款：<a href="<?php echo U("Diankuan/index?shenhe=1");?>"><?php echo ($huikuan); ?></a></div>
                    <?php else: ?>
                     <div class="alert alert-success width96" role="alert">欢迎使用CRM，祝您拥有美好的一天 !</div><?php endif; ?>

          </span>
        
        </div>
        <div class="col-md-4">
          <span>
          	 <span class="glyphicon glyphicon-cog" style="font-size:18px;"></span>
             <span style="font-size: 18px;" class="num"></span>
             设置<br><br>
			  <a class="btn btn-success" id="button"><span class="glyphicon glyphicon-comment"></span>
              开启消息通知（推荐开启）</a><br>
          </span><br><br>

           <span class="glyphicon glyphicon-bookmark"></span>
提示：点击待办或者按F5可以返回本页哦
        </div>
    </div>
    <div class="clear"></div>
    <div style="margin-left:40px;margin-top: 30px; ">
                
    </div>
    
    

</div>

</div>
    <div style="margin-bottom:50px;">
    <div id='container' style="width:50%; margin-left:40px; margin-bottom:50px; float:left; clear:both; margin-top:50px; border:none;">
        <div id="main23" style=" height:600px;"></div>

    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main23'));
        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption({

    title: {
        text: '客户分布图',
        subtext: '根据客户资料省份信息',
        left: 'center'
    },
    tooltip: {
        trigger: 'item'
    },
    legend: {
        orient: 'vertical',
        left: 'left',
        data:['客户']
    },
    visualMap: {
        min: 0,
        max: 200,
        left: 'left',
        top: 'bottom',
        text: ['高','低'],           // 文本，默认为数值文本
        calculable: true
    },
    toolbox: {
        show: true,
        orient: 'vertical',
        left: 'right',
        top: 'center',
        feature: {
            dataView: {readOnly: false},
            restore: {},
            saveAsImage: {}
        }
    },
    series: [
        {
            name: '客户',
            type: 'map',
            mapType: 'china',
            roam: false,
            label: {
                normal: {
                    show: true
                },
                emphasis: {
                    show: true
                }
            },
            data:[
				<?php if(is_array($kehucity)): $i = 0; $__LIST__ = $kehucity;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$kehucity): $mod = ($i % 2 );++$i;?>{name: '<?php echo (msubstr($kehucity[city],0,-1,'utf-8',false)); ?>',value: <?php echo ($kehucity[citysum]); ?>},<?php endforeach; endif; else: echo "" ;endif; ?>

            ]
        },
        
    ]


		});
    </script>

    </div>
	
    <div class="ring_2">
    	
    	<div class="r2_t1" style="padding:10px;">
            <img src="/Public/images/admin/凌众logo.png" width="30%" style="float:left;" class="animated bounceInLeft ">
        	<img src="/Public/images/admin/谋士LOGO.png" width="30%" style="float:right;" class="animated bounceInRight ">
        </div>
        <div class="r2_t1" style="padding:10px;"><h2><?php echo ($lizhi); ?></h2></div>
        <div id="main" style="height:500px;"></div>
         <!-- 为 ECharts 准备一个具备大小（宽高）的 DOM -->
    <script type="text/javascript">
        // 基于准备好的dom，初始化echarts实例
        var myChart = echarts.init(document.getElementById('main'));

        // 使用刚指定的配置项和数据显示图表。
        myChart.setOption({
			 title: {
				text: '产品线分布',
				left: 'center',
				subtext: '根据合同统计',
				top: 20,
				textStyle: {
					color: '#575757'
				}
			},
			tooltip : {
        		trigger: 'item',
        		formatter: "{a} <br/>{b} : {c} ({d}%)"
   			 },
			 series : [
				{
					name: '产品线',
					type: 'pie',
					radius: '55%',
					data:[
					 <?php if(is_array($cpxfb)): $i = 0; $__LIST__ = $cpxfb;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$cpxfb): $mod = ($i % 2 );++$i;?>{value:<?php echo ($cpxfb[prsum]); ?>, name:'<?php echo ($cpxfb[name]); ?>'},<?php endforeach; endif; else: echo "" ;endif; ?>

					],
					//roseType: 'angle'
				}
				]
			});
    </script>

    </div>
    </div>
    
    <div style="clear:both; height:50px;"></div>
     <div class="container" >
     
	</div>

</body>
</html>