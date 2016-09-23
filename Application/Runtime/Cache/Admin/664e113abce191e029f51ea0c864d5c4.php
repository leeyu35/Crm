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
<h3 class="bor-left-bull">客户转移<small> Customer transfer</small></h3>
<br>
<div style="width:500px; margin-left:30px;">
<form action="<?php echo U("addru");?>" method="post" enctype="multipart/form-data" id="addusers">
  <div class="form-group">
    <label for="exampleInputEmail1">转移谁的客户</label>
	<select class="form-control" style="width:200px;" name="users1">
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list1): $mod = ($i % 2 );++$i;?><option value="<?php echo ($list1[id]); ?>"><?php echo ($list1[name]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    	
    </select>
    
  </div>
  <div class="form-group">
    <label for="exampleInputPassword1">转移给</label>
	<select class="form-control" style="width:200px;" name="users2">
      <?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$lista): $mod = ($i % 2 );++$i;?><option value="<?php echo ($lista[id]); ?>"><?php echo ($lista[name]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
    	
    </select>

  </div>



        <p class="help-block">此操作会将客户所属者转移给他人，请谨慎操作，一旦转移客户将不可恢复!</p>


	<hr>
  <button type="submit" class="btn btn-primary" style="width:150px;">提交</button>
</form>
</div>

</div>
<br>
<br>

</body>
</html>