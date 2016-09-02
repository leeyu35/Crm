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
<h3 class="bor-left-bull">权限列表<small>power list</small></h3>
<nav class="navbar navbar-default navbar-fixed-top text-left" role="navigation" style=" padding-left:20px; padding-top:10px;">
 
   <table class="table" style="margin-top:9px;">
       <tr>
    	<th align="left">#</th>
        <th align="left">模块/描述</th>
    	<th align="left">一级审核权限</th>
    	<th align="left">二级审核权限</th>
        <th align="left">添加</th>
        <th align="left">修改</th>
        <th align="left">删除</th>
        <th align="left">查看</th>
        <th align="left">可查看他人</th>
        <th align="left">操作</th>	  

      </tr>
    </table>

  </nav>
<table class="table table-hover">
	<tr >
    	<th>#</th>
        <th>模块/描述</th>
    	<th>一级审核权限</th>
    	<th>二级审核权限</th>
        <th>添加</th>
        <th>修改</th>
        <th>删除</th>
        <th>查看</th>
        <th>可查看他人</th>
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td><?php echo ($k); ?></td>
        <td><?php echo ($list[module]); ?><br>(<?php echo ($list[title]); ?>)</td>
        <td><?php echo ($list[audit_1]); ?></td>
    	<td><?php echo ($list[audit_2]); ?></td>
    	<td><?php echo ($list[add_]); ?></td>
    	<td><?php echo ($list[update_]); ?></td>
    	<td><?php echo ($list[delete_]); ?></td>
    	<td><?php echo ($list[show_]); ?></td>
    	<td><?php echo ($list[index_show]); ?></td>
    	<td><a href="<?php echo U("updata?id=$list[id]");?>">修改</a> <a href="<?php echo U("delete?id=$list[id]");?>">删除</a></td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
    
</table>

</div>
<br>
<br>

</body>
</html>