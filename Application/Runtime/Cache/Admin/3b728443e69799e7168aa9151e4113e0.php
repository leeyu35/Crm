<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">

<style type="text/css">
	#adlist{ margin-left:15px;}
	#adlist li a{ cursor:pointer;}
	.bzj{ display:none;}
</style>
</head>

<body>
<?php echo ($hjd); ?>
<span id="hjd"></span>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >查看账户</h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal" id="formid" >

<h4 class="bor-left-bull" >账户基本信息</h4>
<hr>
<div class="form-group">
   	 <label class="col-sm-2 control-label">提交人</label>
     <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($users_info); ?></strong></label>
     </div>
   </div>
   <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">APP名称</label>
    <div class="col-sm-3">
    	 <label  class="control-label"><strong><?php echo ($info[appname]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账户类型</label>
    <div class="col-sm-2">
        <?php if(is_array($accounttype)): $k = 0; $__LIST__ = $accounttype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$accounttype): $mod = ($k % 2 );++$k;?><label  class="control-label"><strong><?php echo ($info[type]==$accounttype[id]?$accounttype[name]:''); ?></strong></label><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">推广URL</label>
    <div class="col-sm-3">

      		<label  class="control-label"><strong><?php echo ($info[promote_url]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">IP地址</label>
    <div class="col-sm-2">
          	<label  class="control-label"><strong><?php echo ($info[ip]); ?></strong></label>
    </div>
    </div>
    
   <div class="form-group">
  
    <label for="contract_money" class="col-sm-2 control-label">返点</label>
    <div class="col-sm-3">
          	<label  class="control-label"><strong><?php echo ($info[fandian]); ?></strong></label>
    </div>
    
    <label for="rebates_proportion" class="col-sm-1 control-label">屏蔽地域</label>
    <div class="col-sm-3">      
          	<label  class="control-label"><strong><?php echo ($info[pingbidiyu]); ?></strong></label>
    </div>
    

    
  </div> 
  
     <div class="form-group">
  
    <label for="contract_money" class="col-sm-2 control-label">主手机号</label>
    <div class="col-sm-3">
       <label  class="control-label"><strong><?php echo ($info[tel]); ?></strong></label>
    </div>
    


    
  </div>
  
  <h4 class="bor-left-bull" >账户账号信息</h4>
  <hr>
	<div class="form-group">
  
    <label for="payment_type" class="col-sm-2 control-label">用户名</label>
    <div class="col-sm-3">
        <label  class="control-label"><strong><?php echo ($info[a_users]); ?></strong></label>
    </div>
    
    <label for="payment_time" class="col-sm-1 control-label">密码</label>
    <div class="col-sm-3">    
        <label  class="control-label"><strong><?php echo ($info[a_password]); ?></strong></label>
    </div>
    
  </div>
  <h4 class="bor-left-bull" >备注</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">
   		<label  class="control-label"><strong><?php echo ($info[note]); ?></strong></label>
   </div>

   </div>
    <div class="form-group">
    
       <div class="col-sm-12">
       <button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">返回</button>
       </div>
    </div>

</form>

</div>
<br>
<br>
<br>

</body>
</html>