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
				
		//动态加载公司名称
		$("#gongsi").keyup(function(){
			//$("#hjd").html($("#hjd").html()+"1");
			val=$(this).val();
			$.post("<?php echo U('keyup_adlist');?>",{val:val},function(data){
					$("#adlist").html(data);
			})
			$("#adlist").show();
		})
		
		$("#adlist").on("click","a",function(){
			
			$.post("<?php echo U('no_list');?>",{id:$(this).attr("id")},function(data){
					$("#contract_no").html(data);
			})
			
			$.getJSON("<?php echo U('fp_info');?>",{id:$(this).attr("id")},function(data){
				
				$("#tax_identification").val(data.tax_identification);
				$("#ticket_address").val(data.ticket_address);
				$("#open_account").val(data.open_account);
				$("#account").val(data.account);
				$("#kp_tel").val(data.kp_tel);

			})
			$("#advertiser").val($(this).attr("id"));
			$("#submituser").val($(this).attr("title"));
			$("#gongsi").val(($(this).html()));
			$("#adlist").hide();
			//$("#gongsi").html(data);
		})
		
		$("#main_company").change(function(){
			$.post("<?php echo U('fptype');?>",{id:$(this).val()},function(data){
					$("#type").html(data);
			})
		})


				
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
		if($("#money").val()=="")
		{
			alert("请填写发票金额");
			$("#money").select();
			return false;	
		}	
		if($("#r_open_account").val()=="")
		{
			alert("请填写退款开户行");
			$("#r_open_account").select();
			return false;	
		}	
		if($("#main_company").val()=="")
		{
			alert("请选择开票主体");
			return false;	
		}	
		if($("#type").val()=="")
		{
			alert("请选择发票类型");
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
<h3 class="bor-left-bull" >申请发票<small>New Invoice</small></h3>
<br>
<form action="<?php echo U('addru');?>" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="invoice_head" id="advertiser">
<input type="hidden" name="submituser" id="submituser">
<h4 class="bor-left-bull" >开票基本信息</h4>
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">发票抬头</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" autocomplete="off" name="gongsi" id="gongsi" placeholder="输入客户名称前几个字我们将自动匹配">
    <ul class="dropdown-menu" id="adlist">
	  
    </ul>
    </div>
    
    <label for="contract_money" class="col-sm-1 control-label">开票金额</label>
    <div class="col-sm-2">
      	<div class="input-group">
        <input type="text" class="form-control" name="money" id="money">
    	<span class="input-group-addon">元</span>
        </div>
    </div>
  </div>
  <div class="form-group">
  	<label for="inputEmail3" class="col-sm-2 control-label">开票主体</label>
    <div class="col-sm-3">
      <select  class="form-control" name="main_company" id="main_company">
      	<option>--请选择--</option>    
        <?php if(is_array($agentcompany)): $k = 0; $__LIST__ = $agentcompany;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agentcompany): $mod = ($k % 2 );++$k;?><option value="<?php echo ($agentcompany["id"]); ?>" title="<?php echo ($agentcompany["title"]); ?>"><?php echo ($agentcompany["companyname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

      </select>
    </div>
  
    	<label for="inputEmail1" class="col-sm-1 control-label">税目</label>
    <div class="col-sm-2">
      <select  class="form-control" name="type" id="type">        
        <option>--请先选择开票主体--</option>
      </select>
    </div>
  
    
    </div>
    
   <div class="form-group">
   	<label for="inputEmail3" class="col-sm-2 control-label">合同编号</label>
    <div class="col-sm-3">
    	<select  class="form-control" name="contract_no" id="contract_no">
        	 
    	
        </select>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">是否回款</label>
    <div class="col-sm-3">
     	<label class="radio-inline">
   	      <input name="is_back_money" type="radio" id="type_0" value="1" checked>
   	      已回款</label>
   	    
   	    <label class="radio-inline">
   	      <input type="radio" name="is_back_money" value="0" id="type_1">
   	      未回款</label>
    </div>
   </div>
<div class="form-group">
   	<label for="inputEmail3" class="col-sm-2 control-label">开票类型</label>
    <div class="col-sm-3">
    	<label class="radio-inline">
   	      <input name="type2" type="radio" id="type_0" value="1" checked>
   	      专票</label>
   	    
   	    <label class="radio-inline">
   	      <input type="radio" name="type2" value="2" id="type_1">
   	      普票</label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">APP名称</label>
    <div class="col-sm-3">
     	 <input type="text" class="form-control" name="appname" id="appname">
    </div>
   </div>

  
     <h4  class="bor-left-bull" >开票资料 </h4>
  <hr>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">纳税人识别号</label>
    <div class="col-sm-3">
      <input name="tax_identification" type="text" class="form-control" id="tax_identification">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票地址</label>
    <div class="col-sm-3">      
		<input name="ticket_address" type="text" class="form-control" id="ticket_address">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
    <div class="col-sm-3">
      <input name="open_account" type="text" class="form-control" id="open_account">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账号</label>
    <div class="col-sm-3">      
		<input name="account" type="text" class="form-control" id="account">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-3">
      <input name="kp_tel" type="text" class="form-control" id="kp_tel">
    </div>
    


  </div>

  

 
  <h4 class="bor-left-bull" >发票备注</h4>
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