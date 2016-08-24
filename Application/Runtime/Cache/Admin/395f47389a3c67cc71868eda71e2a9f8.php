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
<script language="javascript">
$(document).ready(function(e) {
    $("#new_contact").click(function(){
		$("#contactmain").append('<div class="form-group"><div class="col-sm-1"><input type="text" class="form-control" name="name_n[]" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="qq_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="weixin_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="email_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="position_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="tel_n[]" class="form-control" id="inputEmail3"></div></div>')
	})
	$(".de_contact").click(function(){
		deid=$(this).attr("id");
		
		
		$.get("<?php echo U("delete_contact");?>",{id:deid,stime:+Math.random()},function(index){
			if(index=='1')
			{
				
				$("#contact_list_id_"+deid).remove();
			}else
			{
				alert('删除失败');	
			}
			
		})
		
	})
	
	
});
</script>
</head>

<body>
<div class="container" >
<h3>修改客户<small>update Customer</small></h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal">
<input type="hidden" name="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="time" value="<?php echo ($info[ctime]); ?>">
<h4>客户基本信息</h4>
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
      <input name="advertiser" type="text" class="form-control" value="<?php echo ($info['advertiser']); ?>" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">客户类型</label>
    <div class="col-sm-3">
      <select name="type"  class="form-control" id="type">
      	<option value="1" <?php echo ($info['type']=='1'?'selected':''); ?> >公司</option>
      	<option value="2" <?php echo ($info['type']=='2'?'selected':''); ?>>个人</option>
      </select>
    </div> 

  </div>


  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-10 text-left">
    	
       
       <?php if(is_array($product_line_list)): $k = 0; $__LIST__ = $product_line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product_line_list): $mod = ($k % 2 );++$k;?><label class="checkbox-inline"  <?php echo ($k=='1'?'style="margin-left:10px"':''); ?>><input  name="product_line[]" type="checkbox" id="inlineCheckbox1" value="<?php echo ($product_line_list["id"]); ?>" <?php if(in_array(($product_line_list["id"]), is_array($info[product_line])?$info[product_line]:explode(',',$info[product_line]))): ?>checked<?php endif; ?>><?php echo ($product_line_list["name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
      
    </div>
    


  </div>
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">所属行业</label>
    <div class="col-sm-3">
      <input name="industry" type="text" class="form-control" value="<?php echo ($info['industry']); ?>" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">公司官网</label>
    <div class="col-sm-3">      
		<input name="website" type="text" value="<?php echo ($info['website']); ?>" class="form-control" id="inputEmail3">
    </div>

  </div>
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">是否有APP</label>
    <div class="col-sm-3">
    <label class="radio-inline">
    	<input name="isapp" type="radio" id="inlineRadio1" value="1" <?php echo ($info['isapp']=='1'?'checked':''); ?> > 有
    </label>
    <label class="radio-inline">
    	<input type="radio" name="isapp" id="inlineRadio2" value="0" <?php echo ($info['isapp']=='0'?'checked':''); ?> > 没有
    </label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">APP名称</label>
    <div class="col-sm-3">      
		<input name="appName" type="text" class="form-control" value="<?php echo ($info['appname']); ?>" id="inputEmail3">
    </div>

  </div>
  
  
  <h4>联系人<small>&nbsp;&nbsp;<a  id="new_contact" style="cursor:pointer;"><span class="glyphicon glyphicon-plus"></span>新增联系人</a></small></h4>
  <hr>
  <div id="contactmain">
  <?php if(is_array($contact_list)): $k = 0; $__LIST__ = $contact_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$contact_list): $mod = ($k % 2 );++$k;?><div class="form-group" id="contact_list_id_<?php echo ($contact_list[id]); ?>">
    <input type="hidden" name="contactid[]" value="<?php echo ($contact_list[id]); ?>">
    <div class="col-sm-1">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?>  class="control-label">联系人</label>
      	<input type="text" class="form-control" name="name[]" value="<?php echo ($contact_list[name]); ?>" id="inputEmail3">
    </div>
    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">QQ</label>
      	<input type="text" class="form-control" name="qq[]" value="<?php echo ($contact_list[qq]); ?>" id="inputEmail3">
    </div>
    
    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">微信</label>
      	<input type="text" class="form-control" name="weixin[]"  value="<?php echo ($contact_list[weixin]); ?>" id="inputEmail3">
    </div>

    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">邮箱</label>
      	<input type="text" class="form-control" name="email[]"  value="<?php echo ($contact_list[email]); ?>" id="inputEmail3">
    </div>
    
   
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">职位</label>
      	<input type="text" class="form-control" name="position[]"  value="<?php echo ($contact_list[position]); ?>" id="inputEmail3">
    </div>
    
    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">电话</label>
      	<input type="text" class="form-control" name="tel[]" value="<?php echo ($contact_list[tel]); ?>" id="inputEmail3">
    </div>
    
    <div class="col-sm-1">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label" style="color:#fff;">操作 </label>
    	<input type="button" value="删" id="<?php echo ($contact_list[id]); ?>" class="btn btn-danger de_contact">
    </div>

  </div><?php endforeach; endif; else: echo "" ;endif; ?>
 </div>
   <h4>开票资料</h4>
  <hr>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">纳税人识别号</label>
    <div class="col-sm-3">
      <input name="tax_identification" value="<?php echo ($info['tax_identification']); ?>" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票地址</label>
    <div class="col-sm-3">      
		<input name="ticket_address" value="<?php echo ($info['ticket_address']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
    <div class="col-sm-3">
      <input name="open_account" value="<?php echo ($info['open_account']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账号</label>
    <div class="col-sm-3">      
		<input name="account" value="<?php echo ($info['account']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-3">
      <input name="kp_tel" value="<?php echo ($info['kp_tel']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>
    


  </div>
   <br>
   <hr>
	<div class="form-group">
  
   <div class="col-sm-2">
   <button type="submit" class="btn btn-primary" style="width:150px;" >提交</button>
   </div>
   </div>

</form>

</div>

</body>
</html>