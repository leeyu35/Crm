<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>
<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
</head>

<body>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >修改职位<small>update position</small></h3>
<hr>
<form action="<?php echo U("upru");?>" method="post" enctype="multipart/form-data" id="upru">
 <input type="hidden" name="id" value="<?php echo ($info[id]); ?>">
  <div class="form-group">
    <label for="exampleInputEmail1">职位名称</label>
    <input type="text" class="form-control" id="group_name" name="group_name" placeholder="职位名称" required value="<?php echo ($info[group_name]); ?>"  >
  </div>
  
  


	<hr>
  <button type="submit" class="btn btn-primary" style="width:150px;">提交</button>
</form>
</div>

</body>
</html>