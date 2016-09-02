<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script src="/Public/js/layer/layer.js"></script>
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
			
			$("#advertiser").val($(this).attr("id"));
			$("#submituser").val($(this).attr("title"));
			$("#gongsi").val(($(this).html()));
			$("#adlist").hide();
			//$("#gongsi").html(data);
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
		if($("#d_money").val()=="")
		{
			alert("请填写垫款金额");
			$("#d_money").select();
			return false;	
		}	

		if($("#r_time").val()=="")
		{
			alert("请选择垫款日期");
			return false;	
		}	
		if($("#back_money_time").val()=="")
		{
			alert("请选择垫款日期");
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
<h3 class="bor-left-bull" >修改垫款<small>Refund</small></h3>
<br>
<form action="<?php echo U('upru');?>" method="post" enctype="multipart/form-data" class="form-horizontal" id="formid" >
<input type="hidden" name="id" id="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="advertiser" id="advertiser" value="<?php echo ($info[advertiser]); ?>">
<input type="hidden" name="submituser" id="submituser" value="<?php echo ($info[submituser]); ?>">
<input type="hidden" name="time" id="submituser" value="<?php echo ($info[ctime]); ?>">
<h4 class="bor-left-bull" >垫款基本信息</h4>
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" autocomplete="off" name="gongsi" id="gongsi" placeholder="输入客户名称前几个字我们将自动匹配" value="<?php echo ($gongsi); ?>">
    <ul class="dropdown-menu" id="adlist">
	  
    </ul>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">垫款主体</label>
    <div class="col-sm-2">
      <select  class="form-control" name="d_company" id="d_company">        
        <?php if(is_array($agentcompany)): $k = 0; $__LIST__ = $agentcompany;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agentcompany): $mod = ($k % 2 );++$k;?><option value="<?php echo ($agentcompany["id"]); ?>" title="<?php echo ($agentcompany["title"]); ?>" <?php echo ($info[d_company]==$agentcompany[id]?'selected':''); ?> ><?php echo ($agentcompany["companyname"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>

      </select>
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">合同编号</label>
    <div class="col-sm-3">
    	<select  class="form-control" name="contract_no" id="contract_no">
        	 <option value="<?php echo ($info[contract_no]); ?>"><?php echo ($info[contract_no]); ?></option>
    	
        </select>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">是否开票</label>
    <div class="col-sm-3">
     	<label class="radio-inline">
   	      <input name="ispiao" type="radio" id="type_0" value="0" <?php echo ($info[ispiao]==0?'checked':''); ?> >
   	      未开</label>
   	    <label class="radio-inline">
   	      <input type="radio" name="ispiao" value="1" id="type_1" <?php echo ($info[ispiao]==1?'checked':''); ?>>
   	      已开</label>
    </div>
    </div>
    
   


  
  
  <div class="form-group">
  
    <label for="contract_money" class="col-sm-2 control-label">垫款金额</label>
    <div class="col-sm-3">
      	<div class="input-group">
        <input type="text" class="form-control" name="d_money" id="d_money" value="<?php echo ($info[d_money]); ?>">
    	<span class="input-group-addon">元</span>
        </div>
    </div>
    
    <label for="rebates_proportion" class="col-sm-1 control-label">垫款账户名称</label>
    <div class="col-sm-2">      
    	
		<input type="text" class="form-control" name="d_account_name" id="d_account_name" value="<?php echo ($info[d_account_name]); ?>">
    	
    </div>
    
    <label for="show_money" class="col-sm-1 control-label">APP名称</label>
    <div class="col-sm-2">      
        <input type="text" class="form-control" name="appName" id="appName"  value="<?php echo ($info[appname]); ?>">
       
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">垫款日期</label>
    <div class="col-sm-3">
    	<input type="text" name="d_time" class="Wdate form-control" id="d_time" onClick="WdatePicker()" value="<?php echo (date("Y-m-d",$info[d_time])); ?>">
    </div>
   <label for="inputEmail3" class="col-sm-1 control-label">预计回款日期</label>
    <div class="col-sm-2">
    	<input type="text" name="back_money_time" class="Wdate form-control" id="back_money_time" onClick="WdatePicker()"value="<?php echo (date("Y-m-d",$info[back_money_time])); ?>">
    </div>
   


    
  </div>
  
<h4 class="bor-left-bull" >垫款客户确认文件</h4>
  <hr>
	<div class="form-group">
  
    
     <div class="col-sm-12" id="imgshowtime">
    	
      	<?php if(is_array($filelist)): $i = 0; $__LIST__ = $filelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$filelist): $mod = ($i % 2 );++$i;?><div class="col-sm-1" style="padding-right:5px;">
        	<img class="shouim" layer-src="<?php echo ($filelist[file]); ?>" src="<?php echo ($filelist[file]); ?>" width="100" height="100" style="border:1px #ccc solid; ">&nbsp;
				<a href="<?php echo U("defile?id=$filelist[id]");?>">删除</a>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    
	<div class="col-sm-12">
      <br>
<input name="file[]" type="file" multiple>
    </div>
  </div>
 
  <h4 class="bor-left-bull" >垫款备注</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">      
		<textarea class="form-control" name="note" id="note" rows="4"><?php echo ($info[note]); ?></textarea>
   </div>

   </div>
	<h4 class="bor-left-bull" >审核状态</h4>
 	 <hr>
	 <div class="form-group">
    
       <div class="col-sm-12">
       
       <?php echo ($info[audit_1]=='0'?'<span class="shno">一级审核未审核</span>':'<span class="shyes">一级审核已审核</span>'); ?>
       <?php echo ($info[audit_2]=='0'?'<span class="shno">二级审核未审核</span>':'<span class="shyes">二级审核已审核</span>'); ?>  
       </div>
    </div>
    <div class="form-group">
    
       <div class="col-sm-12">
       <button type="submit" class="btn btn-primary">提交</button>
       <a href="<?php echo U("shenhe?type=audit_1&id=$info[id]");?>" class="btn btn-primary"  <?php echo ($info[audit_1]!='0'?'style="display:none"':''); ?> >一级审核通过</a>
        <a href="<?php echo U("shenhe?type=audit_2&id=$info[id]");?>" class="btn btn-primary"  <?php echo ($info[audit_2]!='0'?'style="display:none"':''); ?>>二级审核通过</a>
       </div>
    </div>

</form>

</div>
<br>
<br>
<br>
<script>
;!function(){

//页面一打开就执行，放入ready是为了layer所需配件（css、扩展模块）加载完毕
layer.ready(function(){ 
  //官网欢迎页
  
  //使用相册
  layer.photos({
    photos: '#imgshowtime'
  });
});



}();
</script>
</body>
</html>