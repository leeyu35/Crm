<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script src="/Public/js/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/js/bootstrap.min.js"></script>
<script language="javascript">
$(document).ready(function(e) {
    $(".htshow").click(function(){
		url=$(this).attr("id");
		window.location.href=url;
	})
});
</script>
</head>

<body>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull">消息列表</h3>

<table class="table table-hover  table-striped">
	<tr>
    	<th>#</th>
        <th>标题</th>
    	<th>发收人</th>
    	<th>状态</th>
        <th>发送时间</th>
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td  class="htshow" id="<?php echo U("show2?id=$list[id]&yid=$info[id]");?>"><?php echo ($k); ?></td>
        <td  class="htshow" id="<?php echo U("show2?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[title]); ?></td>
    	<td  class="htshow" id="<?php echo U("show2?id=$list[id]&yid=$info[id]");?>">
     <!--   <img src="<?php echo ($list[f_images]); ?>" width="30" class="img-circle"><br>--><?php echo ($list[users]); ?></td>
        <td  class="htshow" id="<?php echo U("show2?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[state]==0?'未读':'已读'); ?></td>
    	<td  class="htshow" id="<?php echo U("show2?id=$list[id]&yid=$info[id]");?>"><?php echo (date("Y-m-d H:i:s",$list[time])); ?></td>
    	<td><a href="<?php echo U("delete?id=$list[id]&type=s_show");?>" <?php echo ($confirm); ?>  data-toggle="tooltip" data-placement="left"  title="删除"><span class="glyphicon glyphicon-trash"></span></a></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    
</table>
<?php echo ($page); ?>
</div>
<br>
<br>

</body>
</html>