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
<h3 class="bor-left-bull" >添加模块权限<small>Add power</small></h3>
<hr>
<form action="<?php echo U("addru");?>" method="post" enctype="multipart/form-data" id="addru">
  <div class="form-group">
    <label for="exampleInputEmail1">模块名</label>
    <input type="text" class="form-control" id="module" name="module" required  >
  </div>
  <div class="form-group">
    <label for="title">描述</label>
    <input type="text" class="form-control" id="title" name="title" required  >
  </div>
  <div class="form-group">
    <label>一级权限审核人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist1): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="audit_1[]" value="<?php echo ($grouplist1["id"]); ?>"><?php echo ($grouplist1["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
    
  	 	
  </div>
  <div class="form-group">
    <label>二级权限审核人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist2): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="audit_2[]" value="<?php echo ($grouplist2["id"]); ?>"><?php echo ($grouplist2["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>

  </div>
  <div class="form-group">
    <label>新增权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist3): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="add_[]" value="<?php echo ($grouplist3["id"]); ?>"><?php echo ($grouplist3["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>
  <div class="form-group">
    <label>修改权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist4): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="update_[]" value="<?php echo ($grouplist4["id"]); ?>"><?php echo ($grouplist4["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>

  </div>
  <div class="form-group">
    <label>删除权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist5): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="delete_[]" value="<?php echo ($grouplist5["id"]); ?>"><?php echo ($grouplist5["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>
  <div class="form-group">
    <label>查看权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist6): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="show_[]" value="<?php echo ($grouplist6["id"]); ?>"><?php echo ($grouplist6["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>
  <div class="form-group">
    <label>是否可以看他人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist7): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="index_show[]" value="<?php echo ($grouplist7["id"]); ?>"><?php echo ($grouplist7["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
    （不勾选的职位只能看到自己提交的信息，看不到他人提交的信息）
  </div>

	<hr>
  <button type="submit" class="btn btn-primary" style="width:150px;">提交</button>
</form>
</div>

</body>
</html>