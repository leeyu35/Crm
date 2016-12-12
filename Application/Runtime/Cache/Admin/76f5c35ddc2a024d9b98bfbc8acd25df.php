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
<style type="text/css">
.control-label{ text-align:left !important;}
</style>
</head>

<body>
<div class="container" style="width:100%;" >
<h3 class="bor-left-bull" >查看客户<small>show Customer</small></h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal">
<input type="hidden" name="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="time" value="<?php echo ($info[ctime]); ?>">
<h4  class="bor-left-bull" >客户基本信息</h4>
<hr>

   <div class="form-group">
   	 <label class="col-sm-2 control-label text-left">销售</label>
     <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($users_info); ?></strong></label>
     </div>
   </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label text-left">广告主公司名称</label>
    <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($info['advertiser']); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label text-left">客户类型</label>
    <div class="col-sm-3">
      <label  class="control-label"><strong>
         <?php echo ($info['type']=='1'?'公司':''); ?>
         <?php echo ($info['type']=='2'?'个人':''); ?>
     </strong></label>
    </div> 

  </div>


 <!-- <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-10 text-left">
    	
       
       <?php if(is_array($product_line_list)): $k = 0; $__LIST__ = $product_line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product_line_list): $mod = ($k % 2 );++$k;?><label class="checkbox-inline"  <?php echo ($k=='1'?'style="margin-left:10px"':''); ?>><input  name="product_line[]" type="checkbox" id="inlineCheckbox1" value="<?php echo ($product_line_list["id"]); ?>" <?php if(in_array(($product_line_list["id"]), is_array($info[product_line])?$info[product_line]:explode(',',$info[product_line]))): ?>checked<?php endif; ?>><?php echo ($product_line_list["name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
      
    </div>
    

</div>
  -->
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label text-left">所属行业</label>
    <div class="col-sm-3">
         	<label  class="control-label"><strong><?php echo ($info['industry']); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label text-left">公司官网</label>
    <div class="col-sm-3">   
         	<label  class="control-label"><strong><a href="<?php echo ($info['website']); ?>" target="_blank"><?php echo ($info['website']); ?></a></strong></label>
    </div>

  </div>
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label text-left">是否有APP</label>
    <div class="col-sm-3">
             	<label  class="control-label"><strong><?php echo ($info['isapp']=='1'?'有':''); ?> <?php echo ($info['isapp']=='0'?'没有':''); ?></strong></label>

    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label text-left">APP名称</label>
    <div class="col-sm-3">      
         	<label  class="control-label"><strong><?php echo ($info['appname']); ?></strong></label>
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail8" class="col-sm-2 control-label">客户所属省</label>
    <div class="col-sm-3">
             	<label  class="control-label"><strong><?php echo ($info['city']); ?></strong></label>
    </div>
  </div>

  <h4 class="bor-left-bull" >联系人</h4>
  <hr>
  <div id="contactmain">
  <?php if(is_array($contact_list)): $k = 0; $__LIST__ = $contact_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$contact_list): $mod = ($k % 2 );++$k;?><div class="form-group" id="contact_list_id_<?php echo ($contact_list[id]); ?>">
    <input type="hidden" name="contactid[]" value="<?php echo ($contact_list[id]); ?>">
    
    	<label  class="control-label col-sm-1" style="text-align:right !important;">联系人</label>
      <div class="col-sm-3">
        <label  class="control-label"><strong><?php echo ($contact_list[name]); ?></strong></label>

    </div>
    	<label class="control-label  col-sm-1"  style="text-align:right !important;">电话</label>
    <div class="col-sm-3">
    	
         <label  class="control-label"><strong><?php echo ($contact_list[tel]); ?></strong></label>
    </div>
    
    	<label class="control-label  col-sm-1"  style="text-align:right !important;">微信</label>
    <div class="col-sm-3">
         <label  class="control-label"><strong><?php echo ($contact_list[weixin]); ?></strong></label>
    </div>
    </div>
    <div class="form-group">	
    <label class="control-label  col-sm-1"  style="text-align:right !important;">邮箱</label>
    <div class="col-sm-3">
         <label  class="control-label"><strong><?php echo ($contact_list[email]); ?></strong></label>
    </div>
    
    <label class="control-label col-sm-1"  style="text-align:right !important;">职位</label>
    <div class="col-sm-3">
         <label  class="control-label"><strong><?php echo ($contact_list[position]); ?></strong></label>
    </div>
    
    <label class="control-label col-sm-1"  style="text-align:right !important;">QQ</label>
    <div class="col-sm-3">
         <label  class="control-label"><strong><?php echo ($contact_list[qq]); ?></strong></label>
    </div>
    


  </div><?php endforeach; endif; else: echo "" ;endif; ?>
 </div>

   <h4 class="bor-left-bull" >开票资料</h4>
  <hr>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">纳税人识别号</label>
    <div class="col-sm-3">
         <label  class="control-label" style="text-align:left !important;"><strong><?php echo ($info['tax_identification']); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票地址</label>
    <div class="col-sm-4">      
         <label  class="control-label"><strong><?php echo ($info['ticket_address']); ?></strong></label>
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
    <div class="col-sm-3">
         <label  class="control-label"><strong><?php echo ($info['open_account']); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账号</label>
    <div class="col-sm-3">     
         <label  class="control-label"><strong><?php echo ($info['account']); ?></strong></label>
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-3">
         <label  class="control-label"><strong><?php echo ($info['kp_tel']); ?></strong></label>
    </div>
    


  </div>
   <br>
   <hr>
	<div class="form-group">
  
   <div class="col-sm-2">
       <button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">返回</button>
   </div>
   </div>

</form>

</div>
<br>
<br>

</body>
</html>