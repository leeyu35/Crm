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
<div class="container"  style="width:100%;">
<h3  class="bor-left-bull" >客户列表<small>Customer list</small></h3>
<div class="form-group">
<form class="form-inline" method="get">
  <div class="form-group">
     <select  class="form-control" name="searchtype">
     	<option value="advertiser" <?php echo ($type=='advertiser'?'selected':''); ?> >广告主公司名称</option>
     	<option value="name"  <?php echo ($type=='name'?'selected':''); ?>>联系人</option>
        <option value="tel"  <?php echo ($ser_txt=='tel'?'selected':''); ?>>电话</option>
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
  	<?php if(($ser_txt != '') OR ($time_end != '')): ?><a class="btn btn-info" href="<?php echo U('index');?>">清除搜索条件</a><?php endif; ?>
</form>
</div>
<table class="table table-hover  table-striped">
	<tr>
    	<th>#</th>
    	<th>广告主公司名称</th>
        <th>app名称</th>
    	<th>所属行业</th>
       
        <th>联系人</th>
        <th>联系人电话</th>
        <th>城市</th>
        <th>创建时间</th>
        <th>提交人</th>
        <th>附件</th>	  
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($k); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($list[advertiser]); ?></td>
        <td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($list[appname]); ?></td>
        <td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($list[industry]); ?></td>
    	
    	<td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($list[contact]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($list[tel]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($list[city]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo (date("Y-m-d",$list[ctime])); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]");?>"><?php echo ($list[submituser]); ?></td>
        <td>
        <span><a href="<?php echo U("addim?id=$list[id]");?>" data-toggle="tooltip" data-placement="left" title="新增附件"><span class="glyphicon glyphicon-plus"></span></a></span>&nbsp;|&nbsp;<a href="<?php echo U("showim?id=$list[id]");?>" data-toggle="tooltip" data-placement="left" title="查看附件"><span class="glyphicon glyphicon-zoom-in"></span></a><br>
        </td>
    	<td>
        <a href="<?php echo U("updata?id=$list[id]");?>"  data-toggle="tooltip" data-placement="left" title="修改" ><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;|&nbsp;<a href="<?php echo U("delete?id=$list[id]");?>" <?php echo ($confirm); ?>  data-toggle="tooltip" data-placement="left"  title="删除"><span class="glyphicon glyphicon-trash"></span></a>
        </td>
    </tr><?php endforeach; endif; else: echo "" ;endif; ?>
</table>
<?php echo ($page); ?>

</div>
<script>
   $(function () { $("[data-toggle='tooltip']").tooltip(); });
</script>

</body>
</html>