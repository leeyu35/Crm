<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
</head>
<div class="container">
<h3>合同列表<small>Contract list</small></h3>

<table class="table table-hover">
	<tr>
    	<th>#</th>
    	<th>广告主公司名称</th>
    	<th>所属行业</th>
        <th>公司官网</th>
        <th>联系人</th>
        <th>联系人电话</th>
        <th>产品线</th>
        <th>创建时间</th>
        <th>附件</th>	  
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><tr>
    	<td><?php echo ($list[id]); ?></td>
    	<td><?php echo ($list[advertiser]); ?></td>
        <td><?php echo ($list[industry]); ?></td>
    	<td><?php echo ($list[website]); ?></td>
    	<td><?php echo ($list[contact]); ?></td>
    	<td><?php echo ($list[tel]); ?></td>
    	<td><?php echo ($list[product_lin]); ?></td>
    	<td><?php echo (date("Y-m-d",$list[ctime])); ?></td>
    	
        <td>
        <span style="width:40px; display:inline-block"><a href="<?php echo U("addim?id=$list[id]");?>" title="新增附件">新增</a></span>|&nbsp;&nbsp;<a href="" title="查看附件">查看</a><br>
        </td>
    	<td>
        <a href="<?php echo U("updata?id=$list[id]");?>">修改</a> <a href="#">删除</a>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>

</div>
<body>
</body>
</html>