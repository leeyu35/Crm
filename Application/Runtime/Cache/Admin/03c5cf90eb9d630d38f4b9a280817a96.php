<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
</head>
<div class="container"  style="width:100%;">
<h3 class="bor-left-bull">合同列表<small>Contract list</small></h3>

<table class="table table-hover">
	<tr>
    	<th>#</th>
    	<th>广告主公司名称</th>
    	<th>合同编号</th>
        <th>合同金额</th>
        <!--<th>账户显示金额</th>
        <th>付款金额</th>-->
        <th>产品线</th>
        <th>创建时间</th>
        <th>状态</th>
        <!--<th>财务管理</th>-->	  
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td><?php echo ($k); ?></td>
    	<td><?php echo ($list[advertiser]); ?></td>
    	<td><?php echo ($list[contract_no]); ?></td>
    	<td><?php echo ($list[contract_money]); ?></td>
    	<!--<td><?php echo ($list[show_money]); ?></td>
    	<td>2016年8月15日</td>-->
    	<td><?php echo ($list[name]); ?></td>
    	<td><?php echo (date("y-m-d",$list[ctime])); ?></td>
       <!-- <td>
        <span style="width:20px; display:inline-block"><a href="" title="垫款">垫</a></span>|&nbsp;&nbsp;<a href="" title="垫款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="回款">回</a></span>|&nbsp;&nbsp;<a href="" title="回款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="退款">退</a></span>|&nbsp;&nbsp;<a href="" title="退款列表">列</a><br>  
        </td>-->
    	<td>未审核</td>
        <td>
        <a href="#">续费</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">发票</a><br>
        <a href="#">开户</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">财务</a><br>
        <a href="<?php echo U("update?id=$list[id]");?>">修改</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">删除</a>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php echo ($page); ?>
</div>
<body>
</body>
</html>