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
	
		if($("#bumen").val()=="")
		{
			alert("请填写部门");
			$("#bumen").select();
			return false;	
		}	
		if($("#zhiwu").val()=="")
		{
			alert("请填写职务");
			return false;	
		}	


		if($("#tye").val()=="")
		{
			alert("请选择请假类型");
			return false;	
		}
		
		if($("#contract_start").val()=="")
		{
			alert("请选择开始时间");
			return false;	
		}	
		if($("#contract_end").val()=="")
		{
			alert("请选择结束时间");
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
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >我要请假<small> I leave</small></h3>
<br>
<form action="<?php echo U('addru');?>" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="submituser" id="submituser"  value="<?php echo (cookie('u_id')); ?>">
<h4 class="bor-left-bull" >请假信息</h4>
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">部门</label>
    <div class="col-sm-2">
      <input type="text" class="form-control" autocomplete="off" name="bumen" id="bumen">
    <ul class="dropdown-menu" id="adlist">
	  
    </ul>
    </div>
    
    <label for="contract_money" class="col-sm-1 control-label">职务</label>
    <div class="col-sm-2">
        <input type="text" class="form-control" name="zhiwu" id="zhiwu">        
    </div>
    
      	<label for="inputEmail3" class="col-sm-1 control-label">请假类型</label>
    <div class="col-sm-2">
      <select  class="form-control" name="type" id="type">
      	<option>--请选择--</option>    
        <option value="事假">事假</option>
        <option value="病假">病假</option>
        <option value="婚假">婚假</option>
        <option value="丧假">丧假</option>
        <option value="公假">公假</option>
        <option value="工伤">工伤</option>
        <option value="产假">产假</option>
        <option value="护理假">护理假</option>
        <option value="其他">其他</option>
      </select>
    </div>
  
  </div>
  <div class="form-group">


    
    </div>

    <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">请假时间</label>
    <div class="col-sm-2">
    	<input id="contract_start" class="Wdate form-control" type="text" name="starttime" onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'contract_end\')||\'2020-10-01\'}'})"/> 
    </div>
        <div class="col-sm-2">
<input id="contract_end" class="Wdate form-control" type="text" name="endtime" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'contract_start\')}',maxDate:'2020-10-01'})"/>
    </div>

   
	    <label for="inputEmail3" class="col-sm-1 control-label">说明</label>
    <div class="col-sm-3">
      <input name="shuoming" type="text" class="form-control" id="shuoming" placeholder="如果是请半天请在此说明">
    </div>
    
  </div>

 
  <h4 class="bor-left-bull" >请假事由</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">      
		<textarea class="form-control" name="shiyou" id="note" rows="4"></textarea>
   </div>

   </div>
	<div class="form-group">
    	<div class="col-sm-12">  注：1病假需出具医院证明。2请假必须通过审核才算生效（提交后为未审核状态）。3提交后不可更改</div>
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