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
<h3 class="bor-left-bull">请假列表</h3>
<div class="form-group">
<form class="form-inline" method="get">
  <div class="form-group">
     <select  class="form-control" name="searchtype">
     	<option value="advertiser" <?php echo ($type=='advertiser'?'selected':''); ?> >提交人</option>
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
  <select  class="form-control" name="shenhe">
     	<option value="k"   <?php echo ($type2=='k'?'selected':''); ?> >审核状态</option>
     	<option value="0"  <?php echo ($type2=='0'?'selected':''); ?>>未审核</option>
     	<option value="1"  <?php echo ($type2=='1'?'selected':''); ?>>已审核</option>
  </select>

  <button type="submit" class="btn btn-primary">搜索</button>
  	<?php if(($ser_txt != '') OR ($time_end != '')): ?><a class="btn btn-info" href="<?php echo U("index?id=$info[id]");?>">清除搜索条件</a><?php endif; ?>
   <a class="btn btn-primary pull-right" href="<?php echo U("add?id=$info[id]");?>">我要请假</a>
</form>
</div>
<table class="table table-hover  table-striped">
	<tr>
    	<th>#</th>
    	<th>提交人</th>
    	<th>部门</th>
        <th>职务</th>
        <th>类型</th>
        <th>请假时间</th>

        <th>申请时间</th>
        <th>状态</th>
        <!--<th>财务管理</th>-->	  
        <th>操作</th>	  
    </tr>
    <?php if(is_array($list)): $k = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($k % 2 );++$k;?><tr>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($k); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[name]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[bumen]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[zhiwu]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo ($list[type]); ?></td>
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo (date("Y-m-d",$list[starttime])); ?>到<?php echo (date("y-m-d",$list[endtime])); ?></td>

    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>"><?php echo (date("Y-m-d",$list[ctime])); ?></td>
       <!-- <td>
        <span style="width:20px; display:inline-block"><a href="" title="垫款">垫</a></span>|&nbsp;&nbsp;<a href="" title="垫款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="回款">回</a></span>|&nbsp;&nbsp;<a href="" title="回款列表">列</a><br>
        <span style="width:20px; display:inline-block"><a href="" title="退款">退</a></span>|&nbsp;&nbsp;<a href="" title="退款列表">列</a><br>  
        </td>-->
    	<td class="htshow" id="<?php echo U("show?id=$list[id]&yid=$info[id]");?>">
                <?php if($list[audit_1] == 1): ?><span data-toggle="tooltip" data-placement="left" title="一级审核已审核" class="glyphicon glyphicon-ok" ></span><span class="shyes"></span>
        <?php elseif($list[audit_1] == 2): ?>
        	<span data-toggle="tooltip" data-placement="left" title="一级审核审核未通过" class="glyphicon glyphicon-remove" ></span>
        <?php elseif($list[audit_1] == 0): ?>
        	<span data-toggle="tooltip" data-placement="left"  title="一级审核未审核"  class="glyphicon glyphicon-hourglass"></span><span class="shno"></span><?php endif; ?>
        <?php if($list[audit_2] == 1): ?><span data-toggle="tooltip" data-placement="left" title="二级审核已审核"  class="glyphicon glyphicon-ok" ></span><span class="shyes"></span>
        <?php elseif($list[audit_2] == 2): ?>
            <span data-toggle="tooltip" data-placement="left" title="二级审核审核未通过" class="glyphicon glyphicon-remove" ></span>
        <?php elseif($list[audit_2] == 0): ?>
        	<span data-toggle="tooltip" data-placement="left" title="二级审核未审核"  class="glyphicon glyphicon-hourglass"></span><span class="shno"></span><?php endif; ?>
        </td>
        <td>
        <a href="<?php echo U("delete?id=$list[id]");?>" <?php echo ($confirm); ?>  data-toggle="tooltip" data-placement="left"  title="删除"><span class="glyphicon glyphicon-trash"></span></a>
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