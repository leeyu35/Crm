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
<h3 class="bor-left-bull">回款列表</h3>
<div class="form-group">
<form class="form-inline" method="get">
  <div class="form-group">
     <select  class="form-control" name="searchtype">
     	<option value="advertiser" <?php echo ($type=='advertiser'?'selected':''); ?> >广告主公司名称</option>
     </select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="search_text" value="<?php echo ($ser_txt); ?>" id="exampleInputEmail2" placeholder="输入关键字">
  </div>
  

  <div class="form-group">
    	<input id="contract_start" class="Wdate form-control" type="text" name="time_start" placeholder="开始时间" value="<?php echo ($time_start); ?>" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'contract_end\')||\'2020-10-01\'}'})"/> 
  </div>
  <div class="form-group">
<input id="contract_end" class="Wdate form-control" type="text" name="time_end"  placeholder="结束时间" value="<?php echo ($time_end); ?>"  onFocus="WdatePicker({minDate:'#F{$dp.$D(\'contract_start\')}',maxDate:'2020-10-01'})"/>
    </div>
    
  <button type="submit" class="btn btn-primary">搜索</button>
  	<?php if(($ser_txt != '') OR ($time_end != '')): ?><a class="btn btn-info" href="<?php echo U("index?id=$info[id]");?>">清除搜索条件</a><?php endif; ?>
   <a class="btn btn-primary pull-right" href="<?php echo U("add");?>">添加回款</a>
</form>
</div>
<table class="table table-hover">
	<tr>
    	<th>#</th>
    	<th>广告主公司名称</th>
    	
        <th>回款金额</th>

        <th>回款日期</th>
        <th>创建时间</th>
        <!--<th>财务管理</th>-->	  
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($k); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[advertiser]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[b_money]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo (date("y-m-d",$list[b_time])); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo (date("y-m-d",$list[ctime])); ?></td>
       <!-- <td>
        <span style="width:20px; display:inline-block"><a href="" title="垫款">垫</a></span>|&nbsp;&nbsp;<a href="" title="垫款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="回款">回</a></span>|&nbsp;&nbsp;<a href="" title="回款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="退款">退</a></span>|&nbsp;&nbsp;<a href="" title="退款列表">列</a><br>  
        </td>-->

        <td>
        <a href="<?php echo U("updata?id=$list[id]&yid=$info[id]");?>">修改</a>&nbsp;&nbsp;|&nbsp;&nbsp;<a href="<?php echo U("delete?id=$list[id]&yid=$info[id]");?>">删除</a>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php echo ($page); ?>
</div>
<body>
</body>
</html>