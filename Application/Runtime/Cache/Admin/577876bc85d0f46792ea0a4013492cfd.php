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
<h3 class="bor-left-bull" >添加职位<small>Add position</small></h3>
<hr>
<form action="<?php echo U("addru");?>" method="post" enctype="multipart/form-data" id="addusers">
  <div class="form-group">
    <label for="exampleInputEmail1">职位名称</label>
    <input type="text" class="form-control" id="users" name="users" placeholder="Users" required  >
  </div>
  
  
   <div class="form-group">
    <label for="exampleInputFile">权利</label>
	<select class="form-control" style="width:200px;">
	  <option value="0">超级管理员</option>
	  <option value="1">部门2</option>
	  <option value="1">部门3</option>
	  <option value="1">部门4</option>
    	
    </select>
    
  </div>

	<hr>
  <button type="submit" class="btn btn-primary" style="width:150px;">提交</button>
</form>
</div>

</body>
</html>