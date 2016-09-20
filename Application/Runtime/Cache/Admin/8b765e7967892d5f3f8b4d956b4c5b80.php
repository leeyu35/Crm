<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>修改合同</title>

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
			
			$("#advertiser").val($(this).attr("id"));
			$("#submituser").val($(this).attr("title"));
			$("#gongsi").val(($(this).html()));
			$("#adlist").hide();
			//$("#gongsi").html(data);
		})
		
		
		
		//生成合同编号
        $("#product_line").change(function(){
			
			bianhao();
		})
		$("#agent_company").change(function(){
			bianhao();
		})
		
		function bianhao(){
			
		gs=$("#agent_company option:selected").attr("title");
			pr=$("#product_line option:selected").attr("title");
			xhx="_";
			var mydate = new Date();

			 var str = "" + mydate.getFullYear();
			 if(mydate.getMonth()+1 <10)
			 {
				  str += "0"+(mydate.getMonth()+1);
			 }else
			 {
				  str += (mydate.getMonth()+1);
			 }
			  
			   str += mydate.getDate();
			//动态检查是第几个合同
			advertiser=$("#advertiser").val();  //公司ID
			prid=$("#product_line").val(); //产品线ID
			$.get("<?php echo U('Contract_num');?>",{advertiser:advertiser,prid:prid},function(index){
				num=index;	
				$("#contract_no").val(gs+xhx+pr+xhx+str+num);
			})
			//alert(gs+xhx+pr+xhx+str+num);
			//关联字符串
			
				
			
			
		}
		$("input[name='type']").change(function(){
			if($(this).val()=='2')
			{
				$(".bzj").show(200);	
			}else
			{
				$(".bzj").hide(100);	
			}	
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
			alert("合同编号生成失败!\n请按照公司名称->代理公司->产品线顺序添加，我们将自动生成合同编号！");
			return false;	
		}	
		if($("#contract_money").val()=="")
		{
			alert("请填写合同金额");
			$("#contract_money").select();
			return false;	
		}	
		if($("#rebates_proportion").val()=="")
		{
			alert("请填写返点比例");
			$("#rebates_proportion").select();
			return false;	
		}	
		if($("#show_money").val()=="")
		{
			alert("请填写账户显示金额");
			$("#show_money").select();
			return false;	
		}	
		if($("#contract_start").val()=="")
		{
			alert("请选择合同开始时间");
			return false;	
		}	
		if($("#contract_end").val()=="")
		{
			alert("请选择合同结束时间");
			return false;	
		}	
		if($("#payment_time").val()=="")
		{
			alert("请选择付款日期");
			return false;	
		}	
		if($("#fk_money").val()=="")
		{
			alert("请填写付款金额");
			return false;	
		}	
				
	})
    });
</script>
<style type="text/css">
	#adlist{ margin-left:15px;}
	#adlist li a{ cursor:pointer;}
	
</style>

<?php if($info[type] < 2): ?><style type="text/css">.bzj{ display:none;}</style><?php endif; ?>
</head>

<body>
<span id="hjd"></span>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >修改合同<small>Update contract</small></h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="id" id="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="advertiser" id="advertiser" value="<?php echo ($info[advertiser]); ?>">
<input type="hidden" name="submituser" id="submituser" value="<?php echo ($info[submituser]); ?>">
<h4 class="bor-left-bull" >合同基本信息</h4>
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" autocomplete="off" name="gongsi" id="gongsi" value="<?php echo ($gongsi); ?>" placeholder="输入客户名称前几个字我们将自动匹配">
    <ul class="dropdown-menu" id="adlist">

    </ul>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">代理公司</label>
    <div class="col-sm-2">
      <select  class="form-control" name="agent_company" id="agent_company">
      	<option value="凌众时代" title="LZAD" <?php echo ($info[agent_company]=='凌众时代'?'selected':''); ?>>凌众时代</option>
      	<option value="谋士" title="MSWL"  <?php echo ($info[agent_company]=='谋士'?'selected':''); ?>>谋士</option>
      </select>
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-3">
    <select  class="form-control" name="product_line" id="product_line">
      	<option>请选择</option>
      	<?php if(is_array($product_line_list)): $k = 0; $__LIST__ = $product_line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product_line_list): $mod = ($k % 2 );++$k;?><option value="<?php echo ($product_line_list["id"]); ?>" title="<?php echo ($product_line_list["title"]); ?>" <?php echo ($info[product_line]==$product_line_list[id]?'selected':''); ?>><?php echo ($product_line_list["name"]); ?></option><?php endforeach; endif; else: echo "" ;endif; ?>
      </select>
      
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">合同编号</label>
    <div class="col-sm-3">
      <input name="contract_no" type="text" class="form-control" id="contract_no" value="<?php echo ($info[contract_no]); ?>" placeholder="自动生成，无需填写">
    </div>
    </div>
    
    <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">合同类型</label>
    <div class="col-sm-3">
   	    <label class="radio-inline">
   	      <input name="type" type="radio" id="type_0" value="1" <?php echo ($info[type]=='1'?'checked':''); ?> >
   	      普通合同</label>
   	    
   	    <label class="radio-inline">
   	      <input type="radio" name="type" value="2" id="type_1" <?php echo ($info[type]=='2'?'checked':''); ?>>
   	      框架合同</label>
   
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label bzj">保证金</label>
    <div class="col-sm-3 bzj">
      <div class="input-group">
      <input name="margin" type="text" class="form-control" id="margin" value="<?php echo ($info[margin]); ?>">
      <span class="input-group-addon">元</span>
      </div>
    </div>
  
    
</div>

<h4 class="bor-left-bull" >购买产品信息</h4>
<hr>

  <!--<div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-3">
      <select  class="form-control">
      	<option>凌众时代</option>
      	<option>谋士</option>
      </select>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">推广URL</label>
    <div class="col-sm-5">
      <input type="text" class="form-control" id="inputEmail3">
    </div> 

  </div>-->
  
  <div class="form-group">
  
    <label for="contract_money" class="col-sm-2 control-label">合同金额</label>
    <div class="col-sm-3">
      	<div class="input-group">
        <input type="text" class="form-control" name="contract_money" id="contract_money"  value="<?php echo ($info[contract_money]); ?>">
    	<span class="input-group-addon">元</span>
        </div>
    </div>
    
    <label for="rebates_proportion" class="col-sm-1 control-label">返点比例</label>
    <div class="col-sm-1">      
    	<div class="input-group">
		<input type="text" class="form-control" name="rebates_proportion" style=" width:105px;" id="rebates_proportion"  value="<?php echo ($info[rebates_proportion]); ?>">
    	<span class="input-group-addon">%</span>
        </div>
    </div>
    
    <label for="show_money" class="col-sm-2 control-label">账户显示金额</label>
    <div class="col-sm-2">      
		<div class="input-group">
        <input type="text" class="form-control" name="show_money" id="show_money" value="<?php echo ($info[show_money]); ?>">
        <span class="input-group-addon">元</span>
        </div>
    </div>
    
  </div>
  <div class="form-group">
 
    <label for="inputEmail3" class="col-sm-2 control-label">合同有效期</label>
    <div class="col-sm-2">
    	<input id="contract_start" class="Wdate form-control" type="text" value="<?php echo (date('Y-m-d',$info[contract_start])); ?>" name="contract_start"  onFocus="WdatePicker({maxDate:'#F{$dp.$D(\'contract_start\')||\'2020-10-01\'}'})" /> 
    </div>
        <div class="col-sm-2">
<input id="contract_end" class="Wdate form-control" type="text" value="<?php echo (date('Y-m-d',$info[contract_end])); ?>"  name="contract_end" onFocus="WdatePicker({minDate:'#F{$dp.$D(\'contract_end\')}',maxDate:'2020-10-01'})" />
    </div>

   

    
  </div>
  
  <h4 class="bor-left-bull" >付款信息</h4>
  <hr>
	<div class="form-group">
  
    <label for="payment_type" class="col-sm-2 control-label">付款方式</label>
    <div class="col-sm-3">
      <select  class="form-control" name="payment_type" id="payment_type">
      	<option value="1" <?php echo ($info[payment_type]=='1'?'selected':''); ?>>预付</option>
      	<option value="2" <?php echo ($info[payment_type]=='2'?'selected':''); ?>>垫付</option>
      </select>
    </div>
    
    <label for="payment_time" class="col-sm-1 control-label">付款日期</label>
    <div class="col-sm-2">      
		<input type="text" name="payment_time" value="<?php echo (date('Y-m-d',$info[payment_time])); ?>" class="Wdate form-control" id="payment_time" onClick="WdatePicker()">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">付款金额</label>
    <div class="col-sm-2"> 
    	<div class="input-group">     
		<input type="text" name="fk_money" class="form-control" value="<?php echo ($info[fk_money]); ?>" id="fk_money">
        <span class="input-group-addon">元</span>
        </div>
    </div>
    
  </div>
 
  <h4 class="bor-left-bull" >合同备注</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">      
		<textarea class="form-control" name="note" id="note" rows="4"><?php echo ($info[note]); ?></textarea>
   </div>

   </div>
	
    <div class="form-group">
    
       <div class="col-sm-12">
       <button type="submit" class="btn btn-primary">提交合同</button>
       <a href="#" class="btn btn-primary">一级审核通过</a>
       <a href="#" class="btn btn-primary">二级审核通过</a>  
       </div>
    </div>

</form>

</div>
<br>
<br>
<br>

</body>
</html>