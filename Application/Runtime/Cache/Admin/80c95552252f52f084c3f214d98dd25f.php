<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
</head>

<body>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull">登录日志</h3>

<table class="table table-hover">
	<tr>
    	<th>#</th>
        <th>头像</th>
    	<th>登录IP</th>
    	<th>姓名</th>
        <th>职位</th>
        <th>登录时间</th>
        <th>地址</th>
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td><?php echo ($k); ?></td>
        <td><img src="<?php echo ($list[image]); ?>" width="50" height="50" class="img-circle"/></td>
    	<td><?php echo ($list[ip]); ?></td>
    	<td><?php echo ($list[name]); ?></td>
    	<td><?php echo ($list[groupname]); ?></td>
    	<td><?php echo (date("Y-m-d H:i:s",$list[time])); ?></td>
        <td><?php echo ($list[dizhi]); ?></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    
</table>
<?php echo ($page); ?>
</div><br>
<br>
<br>

</body>
</html>