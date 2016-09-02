<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
</head>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >职位列表<small>Group list</small></h3>

<table class="table table-hover">
	<tr>
    	<th>#</th>
        
    	<th>职位</th>
    	
        
        <th>创建时间</th>
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td><?php echo ($k); ?></td>
    	<td><?php echo ($list[group_name]); ?></td>

    	<td><?php echo (date("Y-m-d",$list[ctime])); ?></td>
    	<td><a href="<?php echo U("updata?id=$list[id]");?>">修改</a> <!--<a href="<?php echo U("delete?id=$list[id]");?>">删除</a>--></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    
</table>

</div>
<body>
</body>
</html>