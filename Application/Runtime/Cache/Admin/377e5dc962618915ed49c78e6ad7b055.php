<?php if (!defined('THINK_PATH')) exit(); if(C('LAYOUT_ON')) { echo ''; } ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>跳转提示</title>
 <link rel="stylesheet" href="/Public/css/reset.css"/>
 <link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<style type="text/css">
*{ padding: 0; margin: 0; }
body{ background: #fff; font-family: '微软雅黑'; color: #333; font-size: 16px; }
.system-message{ padding: 24px 48px; }
.system-message h1{ font-size: 100px; font-weight: normal; line-height: 120px; margin-bottom: 12px; }
.system-message .jump{ padding-top: 10px}
.system-message .jump a{ color: #333;}
.system-message .success,.system-message .error{ line-height: 1.8em; font-size: 22px }
.system-message .detail{ font-size: 12px; line-height: 20px; margin-top: 12px; display:none}
.people{
            margin-top: 5%;
			margin-left:5%;
            width: 90%;
            height: 240px;
            background: #f5f5f5;
            /*border: 1px solid #999999;*/
            box-shadow: 0px 2px 10px #999999;
			border-top:3px #328dcc solid;
			border-bottom:3px #328dcc solid;
			padding-top:1%;
			padding-bottom:5%;
			
			height:90%;
        }
        .touxiang,.dai{
            float: left;
        }
        .touxiang{
            margin-left: 50px;
        }
        .dai{
            color: #333333;
            font-size: 12px;
            margin-left: 80px;
            margin-top:30px;
        }
        .message{
            color: #ff4e3e;
        }
</style>
</head>
<body>
<div class="people" style="overflow:hidden;" >
<div class="system-message">
<?php if(isset($message)) {?>
<!--<h1>:)</h1>-->
<img src="/Public/images/images/y.gif" width="100" />
<p class="success"><img src="/Public/images/images/1.png" />&nbsp;<?php echo($message); ?></p>
<?php }else{?>
<!--<h1>:(</h1>-->
<img src="/Public/images/images/n.gif" width="100" />
<p class="error"><img src="/Public/images/images/0.png" alt="" />&nbsp;<?php echo($error); ?></p>
<?php }?>
<p class="detail"></p>
<p class="jump">
页面自动 <a id="href" href="<?php echo($jumpUrl); ?>">跳转</a> 等待时间： <b id="wait"><?php echo($waitSecond); ?></b>
</p>
</div>
</div>
<script type="text/javascript">
(function(){
var wait = document.getElementById('wait'),href = document.getElementById('href').href;
var interval = setInterval(function(){
	var time = --wait.innerHTML;
	if(time <= 0) {
		location.href = href;
		clearInterval(interval);
	};
}, 1000);
})();
</script>
</body>
</html>