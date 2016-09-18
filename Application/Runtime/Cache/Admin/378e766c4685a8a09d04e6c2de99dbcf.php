<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script language="javascript">
	$(document).ready(function(e) {
		
		$(".users").click(function(){
			var idlist='';
			if($(this).hasClass("xuanzhong"))
			{
				$(this).removeClass("xuanzhong");
			}else
			{
				$(this).addClass("xuanzhong");
			
			}
			
			$(".xuanzhong").each(function(index) {
				//alert($(this).attr("id"))
            	idlist+=","+$(this).attr("id");    
            });
			liidts=idlist.substr(1,9999);
			$("#users").val(liidts);
		})
		

	});
</script>
</head>

<body>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" ><span class="glyphicon glyphicon-send"></span>&nbsp;写消息给别人</h3>
<hr>
<form action="<?php echo U("addru");?>" method="post" enctype="multipart/form-data" id="addusers">
<input type="hidden" value="" name="users" id="users">
  <div class="form-group">
    <label for="exampleInputEmail1">收件人</label><br>
     <label  class="control-label"><strong>
    <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><span class="users" id="<?php echo ($list[id]); ?>">
        	<img src="<?php echo ($list[image]); ?>" width="40"  class="img-circle"><br>
            <?php echo ($list[name]); ?>
        </span><?php endforeach; endif; else: echo "" ;endif; ?>
    </strong></label>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">标题</label>
    <input type="text" class="form-control" name="title" id="title" placeholder="标题" required>
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">内容</label>
    <textarea class="form-control" name="content" id="content" rows="8"></textarea>  </div>


	<hr>
  <button type="submit" class="btn btn-primary" style="width:150px;">提交</button>
</form>
</div>

</body>
</html>