<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
</head>
<div class="container">
<h3>用户列表<small>Users list</small></h3>

<table class="table table-hover">
	<tr>
    	<th>#</th>
        <th>头像</th>
    	<th>用户名</th>
    	<th>姓名</th>
        <th>所属职位</th>
        <th>创建时间</th>
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td><?php echo ($k); ?></td>
        <td><img src="<?php echo ($list[image]); ?>" width="50" height="50" class="img-circle"/></td>
    	<td><?php echo ($list[users]); ?></td>
    	<td><?php echo ($list[name]); ?></td>
    	<td><?php echo ($list[groupid]); ?></td>
    	<td><?php echo (date("Y-m-d",$list[ctime])); ?></td>
    	<td><a href="<?php echo U("updata?id=$list[id]");?>">修改</a> <a href="<?php echo U("delete?id=$list[id]");?>">删除</a></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    
</table>

</div>
<body>
</body>
</html>