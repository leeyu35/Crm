<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script src="/Public/js/My97DatePicker/WdatePicker.js"></script>
<script language="javascript">
$(document).ready(function(e) {
    $(".htshow").click(function(){
		url=$(this).attr("id");
		window.location.href=url;
	})
});
</script>
</head>
<div class="container"  style="width:100%;">
<h3 class="bor-left-bull"><?php echo ($info[gongsi]); ?><span class="glyphicon glyphicon-share-alt"></span><?php echo ($info[name]); ?><span class="glyphicon glyphicon-share-alt"></span>续费列表</h3>
<div class="form-group">
<form class="form-inline" method="get">
  <div class="form-group">
     <select  class="form-control" name="searchtype">
     	<option value="contract_no"  <?php echo ($type=='contract_no'?'selected':''); ?>>续费合同编号</option>
     </select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="search_text" value="<?php echo ($ser_txt); ?>" id="exampleInputEmail2" >
  </div>
  <div class="form-group">
    	<input id="contract_start" class="Wdate form-control" type="text" name="time_start" placeholder="开始时间" value="<?php echo ($time_start); ?>" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'contract_end\')||\'2020-10-01\'}'})"/> 
  </div>
  <div class="form-group">
<input id="contract_end" class="Wdate form-control" type="text" name="time_end"  placeholder="结束时间" value="<?php echo ($time_end); ?>"  onFocus="WdatePicker({minDate:'#F{$dp.$D(\'contract_start\')}',maxDate:'2020-10-01'})"/>
    </div>
  <select  class="form-control" name="shenhe">
     	<option value="k"   <?php echo ($type2=='k'?'selected':''); ?> >审核状态</option>
     	<option value="0"  <?php echo ($type2=='0'?'selected':''); ?>>未审核</option>
     	<option value="1"  <?php echo ($type2=='1'?'selected':''); ?>>已审核</option>
  </select>

  <button type="submit" class="btn btn-primary">搜索</button>
  	<?php if(($ser_txt != '') OR ($time_end != '')): ?><a class="btn btn-info" href="<?php echo U("index?id=$info[id]");?>">清除搜索条件</a><?php endif; ?>
   <a class="btn btn-primary pull-right" href="<?php echo U("add?id=$info[id]");?>">新增续费</a>
</form>
</div>
<table class="table table-hover">
	<tr>
    	<th>#</th>
    	<th>广告主公司名称</th>
    	<th>续费合同编号</th>
        <th>金额</th>
        <th>账户</th>
        <!--<th>付款金额</th>-->
        <th>产品线</th>
        <th>创建时间</th>
        <th>状态</th>
        <!--<th>财务管理</th>-->	  
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($k); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><a href="<?php echo U("Customer/show?id=$list[aid]");?>"><?php echo ($list[advertiser]); ?></a></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[contract_no]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[contract_money]); ?></td>
    	<td><?php echo ($list[account]); ?></td>
    	<!--<td>2016年8月15日</td>-->
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[name]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo (date("y-m-d",$list[ctime])); ?></td>
       <!-- <td>
        <span style="width:20px; display:inline-block"><a href="" title="垫款">垫</a></span>|&nbsp;&nbsp;<a href="" title="垫款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="回款">回</a></span>|&nbsp;&nbsp;<a href="" title="回款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="退款">退</a></span>|&nbsp;&nbsp;<a href="" title="退款列表">列</a><br>  
        </td>-->
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>">
        <?php if(($list[audit_1] == 1) AND ($list[audit_2] == 1) ): ?><span class="glyphicon glyphicon-ok"></span><span class="shyes">已审核</span>
        <?php else: ?>
        	<span class="glyphicon glyphicon-hourglass"></span><span class="shno">待审核</span><?php endif; ?>
        </td>
        <td>
        <!--<a href="#">发票</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="#">财务</a><br>-->
        <a href="<?php echo U("updata?id=$list[id]&yid=$info[id]");?>" title="修改"><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U("delete?id=$list[id]&yid=$info[id]");?>" title="删除"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php echo ($page); ?>
</div>
<body>
</body>
</html>