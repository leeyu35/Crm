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
<h3 class="bor-left-bull"><?php echo ($hetong[advertiser]?"$hetong[advertiser]的":''); ?>账户列表</h3>
<div class="form-group">
<form class="form-inline" method="get">
  <div class="form-group">
     <select  class="form-control" name="searchtype">
     	<option value="advertiser" <?php echo ($type=='advertiser'?'selected':''); ?> >APP名称</option>
     	<option value="gongsi" <?php echo ($type=='gongsi'?'selected':''); ?> >公司名称</option>
     </select>
  </div>
  <div class="form-group">
    <input type="text" class="form-control" name="search_text" value="<?php echo ($ser_txt); ?>" id="exampleInputEmail2" placeholder="输入关键字" >
  </div>
    <div class="form-group">
    	<input id="contract_start" class="Wdate form-control" type="text" name="time_start" placeholder="开始时间" value="<?php echo ($time_start); ?>" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'contract_end\')||\'2020-10-01\'}'})"/> 
  </div>
  <div class="form-group">
<input id="contract_end" class="Wdate form-control" type="text" name="time_end"  placeholder="结束时间" value="<?php echo ($time_end); ?>"  onFocus="WdatePicker({minDate:'#F{$dp.$D(\'contract_start\')}',maxDate:'2020-10-01'})"/>
    </div>


  <button type="submit" class="btn btn-primary">搜索</button>
  	<?php if(($ser_txt != '') OR ($time_end != '')): ?><a class="btn btn-info" href="<?php echo U("index?id=$info[id]");?>">清除搜索条件</a><?php endif; ?>
   <a class="btn btn-primary pull-right" href="<?php echo U("add?contract_id=$contract_id");?>">添加账户</a>
</form>
</div>
<table class="table table-hover  table-striped">
	<tr>
    	<th>#</th>
    	<th>公司名称</th>
    	<!--<th>合同编号</th>-->
    	<th>APP名称</th>
    	<th>账户类型</th>
        <!--<th>推广URL</th>
        <th>账户显示金额</th>
        <th>付款金额</th>
        <th>IP</th>-->
        <th>账户用户名</th>
        <th>账户密码</th>
        <th>主手机号</th>
        <!--<th>财务管理</th>-->	  
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($k); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[advertiser]); ?></td>
    	<!--<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[contract_no]); ?></td>-->
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[appname]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[name]); ?></td>
    	<!--<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[promote_url]); ?></td>
    	<td><?php echo ($list[show_money]); ?></td>
    	<td>2016年8月15日</td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[ip]); ?></td>-->
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[a_users]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[a_password]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[tel]); ?></td>
       <!-- <td>
        <span style="width:20px; display:inline-block"><a href="" title="垫款">垫</a></span>|&nbsp;&nbsp;<a href="" title="垫款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="回款">回</a></span>|&nbsp;&nbsp;<a href="" title="回款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="退款">退</a></span>|&nbsp;&nbsp;<a href="" title="退款列表">列</a><br>  
        </td>-->

        <td>
        <a href="<?php echo U("updata?id=$list[id]&contract_id=$contract_id");?>"  data-toggle="tooltip" data-placement="left" title="修改" ><span class="glyphicon glyphicon-pencil"></span></a>&nbsp;|&nbsp;<a href="<?php echo U("delete?id=$list[id]&contract_id=$contract_id");?>" <?php echo ($confirm); ?>  data-toggle="tooltip" data-placement="left"  title="删除"><span class="glyphicon glyphicon-trash"></span></a>
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