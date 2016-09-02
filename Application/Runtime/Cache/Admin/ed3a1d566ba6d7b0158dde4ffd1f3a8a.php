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
<form action="<?php echo U("upru");?>" method="post" enctype="multipart/form-data" id="upru">
<input type="hidden" name="id" value="<?php echo ($info[id]); ?>">
  <div class="form-group">
    <label for="exampleInputEmail1">模块名</label>
    <input type="text" class="form-control" id="module" name="module" value="<?php echo ($info[module]); ?>" required  >
  </div>
  <div class="form-group">
    <label for="title">描述</label>
    <input type="text" class="form-control" id="title" name="title" value="<?php echo ($info[title]); ?>" required  >
  </div>

  <div class="form-group">
    <label>一级权限审核人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist1): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="audit_1[]" value="<?php echo ($grouplist1["id"]); ?>" <?php if(in_array(($grouplist1["id"]), is_array($info[audit_1])?$info[audit_1]:explode(',',$info[audit_1]))): ?>checked<?php endif; ?>><?php echo ($grouplist1["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
    
  	 	
  </div>
  <div class="form-group">
    <label>二级权限审核人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist2): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="audit_2[]" value="<?php echo ($grouplist2["id"]); ?>" <?php if(in_array(($grouplist2["id"]), is_array($info[audit_2])?$info[audit_2]:explode(',',$info[audit_2]))): ?>checked<?php endif; ?>><?php echo ($grouplist2["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>

  </div>
  <div class="form-group">
    <label>新增权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist3): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="add_[]" value="<?php echo ($grouplist3["id"]); ?>" <?php if(in_array(($grouplist3["id"]), is_array($info[add_])?$info[add_]:explode(',',$info[add_]))): ?>checked<?php endif; ?>><?php echo ($grouplist3["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>
  <div class="form-group">
    <label>修改权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist4): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="update_[]" value="<?php echo ($grouplist4["id"]); ?>" <?php if(in_array(($grouplist4["id"]), is_array($info[update_])?$info[update_]:explode(',',$info[update_]))): ?>checked<?php endif; ?>><?php echo ($grouplist4["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>

  </div>
  <div class="form-group">
    <label>删除权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist5): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="delete_[]" value="<?php echo ($grouplist5["id"]); ?>" <?php if(in_array(($grouplist5["id"]), is_array($info[delete_])?$info[delete_]:explode(',',$info[delete_]))): ?>checked<?php endif; ?>><?php echo ($grouplist5["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>
  <div class="form-group">
    <label>查看权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist6): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="show_[]" value="<?php echo ($grouplist6["id"]); ?>" <?php if(in_array(($grouplist6["id"]), is_array($info[show_])?$info[show_]:explode(',',$info[show_]))): ?>checked<?php endif; ?>><?php echo ($grouplist6["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>  
  <div class="form-group">
    <label>查看权限持有人：</label>
    <?php if(is_array($grouplist)): $i = 0; $__LIST__ = $grouplist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$grouplist7): $mod = ($i % 2 );++$i;?><label class="checkbox-inline">
    	<input type="checkbox" id="inlineCheckbox1" name="index_show[]" value="<?php echo ($grouplist7["id"]); ?>" <?php if(in_array(($grouplist7["id"]), is_array($info[index_show])?$info[index_show]:explode(',',$info[index_show]))): ?>checked<?php endif; ?>><?php echo ($grouplist7["group_name"]); ?>
    </label><?php endforeach; endif; else: echo "" ;endif; ?>
  </div>

	<hr>
  <button type="submit" class="btn btn-primary" style="width:150px;">提交</button>
</form>
</div>

</body>
</html>