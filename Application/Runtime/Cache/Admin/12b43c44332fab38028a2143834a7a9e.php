<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script language="javascript">
	$(document).ready(function(e) {
				

		


				
		$("#formid").submit(function(){
	
		if($("#gongsi").val()=="")
		{
			alert("请填写公司名称");
			$("#gongsi").select();
			return false;	
		}	
		if($("#contract_no").val()=="")
		{
			alert("请选择合同编号");
			return false;	
		}	
		if($("#r_money").val()=="")
		{
			alert("请填写退款金额");
			$("#r_money").select();
			return false;	
		}	
		if($("#r_open_account").val()=="")
		{
			alert("请填写退款开户行");
			$("#r_open_account").select();
			return false;	
		}	
		if($("#r_account").val()=="")
		{
			alert("请填写退款开户账户");
			$("#show_money").select();
			return false;	
		}	
		if($("#r_time").val()=="")
		{
			alert("请选择退款日期");
			return false;	
		}	


				
	})
    });
</script>
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
<h3 class="bor-left-bull" >添加账户<small>add Account</small></h3>
<br>
<form action="<?php echo U('addru');?>" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="submituser" id="submituser" value="<?php echo (session('u_id')); ?>">
<h4 class="bor-left-bull" >账户基本信息</h4>
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">APP名称</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" autocomplete="off" name="appname" id="appname">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账户类型</label>
    <div class="col-sm-2">
      <select  class="form-control" name="type" id="type">        
        <?php if(is_array($accounttype)): $k = 0; $__LIST__ = $accounttype;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$accounttype): $mod = ($k % 2 );++$k;?><option value="<?php echo ($accounttype["id"]); ?>" title="<?php echo ($accounttype["name"]); ?>"><?php echo ($accounttype["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

      </select>
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">推广URL</label>
    <div class="col-sm-3">
    	  <input type="text" class="form-control" autocomplete="off" name="promote_url" id="promote_url">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">IP地址</label>
    <div class="col-sm-2">
     	<input type="text" class="form-control" name="ip" id="ip">
    </div>
    </div>
    
   


  
  
  <div class="form-group">
  
    <label for="contract_money" class="col-sm-2 control-label">返点</label>
    <div class="col-sm-3">
      	<div class="input-group">
        <input type="text" class="form-control" name="fandian" id="fandian">
    	<span class="input-group-addon">%</span>
        </div>
    </div>
    
    <label for="rebates_proportion" class="col-sm-1 control-label">屏蔽地域</label>
    <div class="col-sm-3">      
    	
		<input type="text" class="form-control" name="pingbidiyu" id="pingbidiyu">
    	
    </div>
    

    
  </div>

  
  <h4 class="bor-left-bull" >账户账号信息</h4>
  <hr>
	<div class="form-group">
  
    <label for="payment_type" class="col-sm-2 control-label">用户名</label>
    <div class="col-sm-3">
     	<input type="text" name="a_users" class="form-control" id="a_users">
    </div>
    
    <label for="payment_time" class="col-sm-1 control-label">密码</label>
    <div class="col-sm-3">      
		<input type="text" name="a_password" class="form-control" id="a_password">
    </div>

    
  </div>
 
  <h4 class="bor-left-bull" >备注</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">      
		<textarea class="form-control" name="note" id="note" rows="4"></textarea>
   </div>

   </div>
	
    <div class="form-group">
    
       <div class="col-sm-2">
       <button type="submit" class="btn btn-primary">提交申请</button>
       </div>
    </div>

</form>

</div>
<br>
<br>
<br>

</body>
</html>