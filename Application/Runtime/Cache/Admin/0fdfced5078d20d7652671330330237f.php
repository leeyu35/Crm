<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<!--引入CSS-->
<link rel="stylesheet" type="text/css" href="/Public/js/dist/webuploader.css">

<!--引入JS-->
<script src="/Public/js/dist/webuploader.js"></script>
<script language="javascript">
$(document).ready(function(e) {
    $("#new_contact").click(function(){
		
		$("#contactmain").append('<div class="form-group"><div class="col-sm-2"><input type="text" class="form-control" name="name[]" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="qq[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="weixin[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="email[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="position[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="tel[]" class="form-control" id="inputEmail3"></div></div>')})
	
	
});
</script>
</head>

<body>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >新增客户<small>New Customer</small></h3>
<form action="<?php echo U('addru');?>" method="post" class="form-horizontal" >
<!--<h4>客户基本信息</h4>-->
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
      <input name="advertiser" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">客户类型</label>
    <div class="col-sm-3">
      <select name="type"  class="form-control" id="type">
      	<option value="1">公司</option>
      	<option value="2">个人</option>
      </select>
    </div> 

  </div>


  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-10 text-left">
       <?php if(is_array($product_line_list)): $k = 0; $__LIST__ = $product_line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product_line_list): $mod = ($k % 2 );++$k;?><label class="checkbox-inline"  <?php echo ($k=='1'?'style="margin-left:10px"':''); ?>><input name="product_line[]" type="checkbox" id="inlineCheckbox1" value="<?php echo ($product_line_list["id"]); ?>"><?php echo ($product_line_list["name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
      
    </div>
    


  </div>
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">所属行业</label>
    <div class="col-sm-3">
      <input name="industry" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">公司官网</label>
    <div class="col-sm-3">      
		<input name="website" type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">是否有APP</label>
    <div class="col-sm-3">
    <label class="radio-inline">
    	<input name="isapp" type="radio" id="inlineRadio1" value="1" checked> 有
    </label>
    <label class="radio-inline">
    	<input type="radio" name="isapp" id="inlineRadio2" value="0"> 没有
    </label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">APP名称</label>
    <div class="col-sm-3">      
		<input name="appName" type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  
  
  <h4  class="bor-left-bull" >联系人<small>&nbsp;&nbsp;<a  id="new_contact" style="cursor:pointer;"><span class="glyphicon glyphicon-plus"></span>新增联系人</a></small></h4>
  <hr>
  <div id="contactmain">
	<div class="form-group">
  
    
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">联系人</label>
      	<input type="text" class="form-control" name="name[]" id="inputEmail3">
    </div>
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">QQ</label>
      	<input type="text" class="form-control" name="qq[]" id="inputEmail3">
    </div>
    
    
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">微信</label>
      	<input type="text" class="form-control" name="weixin[]" id="inputEmail3">
    </div>

    
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">邮箱</label>
      	<input type="text" class="form-control" name="email[]" id="inputEmail3">
    </div>
    
   
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">职位</label>
      	<input type="text" class="form-control" name="position[]" id="inputEmail3">
    </div>
    
    
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">电话</label>
      	<input type="text" class="form-control" name="tel[]" id="inputEmail3">
    </div>



  </div>
 </div>
   <h4  class="bor-left-bull" >开票资料</h4>
  <hr>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">纳税人识别号</label>
    <div class="col-sm-3">
      <input name="tax_identification" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票地址</label>
    <div class="col-sm-3">      
		<input name="ticket_address" type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
    <div class="col-sm-3">
      <input name="open_account" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账号</label>
    <div class="col-sm-3">      
		<input name="account" type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-3">
      <input name="kp_tel" type="text" class="form-control" id="inputEmail3">
    </div>
    


  </div

  ><br>
   <hr>
	<div class="form-group">
  
   <div class="col-sm-2">
   <button type="submit" class="btn btn-primary" style="width:150px;" >提交</button>
   </div>
   </div>

</form>

</div>
<br>
<br>

</body>
</html>