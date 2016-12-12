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
		//x选择了公司
		$("#adlist").on("click","a",function(){
			
			$.post("<?php echo U('no_list');?>",{id:$(this).attr("id")},function(data){
					$("#contract_no").html(data);
			})
			$("#gongsi").val(($(this).html()));
			$("#adlist").hide();
		})
		//选择了负责人
		$("#fzrlist").on("click","a",function(){
			$("#fuzeren").attr("title",$(this).attr("id"))
			$("#fuzeren").val($(this).html());
			$("#fzrlist").hide();
		})
		//点击新增负责人
		$("#add_fzr").on("click",function(){
			if($("#fuzeren").val()=="")
			{
				alert('负责人姓名不能为空');
				$("#fuzeren").select();
				return false;	
			}else
			{
				$.get("<?php echo U("isfzr");?>",{val:$("#fuzeren").val()},function(data){
						if(data==0)
						{
							alert("没有这个人员哦~");
							$("#fuzeren").select();
							return false;
						}else
						{
							name=$("#fuzeren").val();
							id=$("#fuzeren").attr("title");
							$(".fzrlist").append('<span class="btn btn-danger">'+name+" x</span> ");
							$("#fzrlsitwz").append('<input type="hidden" class="fzrlist" name="fzrlist[]" value="'+id+'">');
							
						}
				})	
			
			}
			
		})
		//删除已选负责人
		$(".fzrlist").on("click","span",function(){
			$(".fzrlist").eq($(this).index()).remove();
			$(this).remove();
		})
		
		$("#contract_no").on("change",function(){
			a=$("#contract_no").find("option:selected").attr("id");
			//alert($(this).val())
			
			$("#contract_id").val(a);
			//alert($("#contract_id").val());
			//contract_id
		})
		
		//动态加载员工姓名
		$("#fuzeren").keyup(function(){
			//$("#hjd").html($("#hjd").html()+"1");
			val=$(this).val();
			$.post("<?php echo U('keyup_fzrlist');?>",{val:val},function(data){
					if(data!='')
					{
						$("#fzrlist").html(data);
					}
			})
			$("#fzrlist").show();
		})

		
		$("#gongsi").on("blur",function(){
			setTimeout(function () {
				$("#adlist").hide();
            }, 300);
		})
	
		$("#formid").submit(function(){
	
		if($("#a_users").val()=="")
		{
			alert("请填写用户名");
			$("#a_users").select();
			return false;	
		}	

				
	})
    });
</script>
<style type="text/css">
	#adlist{ margin-left:15px;}
	#adlist li a,#fzrlist li a{ cursor:pointer;}
	.bzj{ display:none;}
</style>
</head>

<body>

<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >添加账户<small>add Account</small></h3>
<br>
<form action="<?php echo U('addru');?>" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="submituser" id="submituser" value="<?php echo (cookie('u_id')); ?>">
<input type="hidden" name="contract_id" id="contract_id" value="<?php echo ($contract_id); ?>">
<?php echo ($contract_id?'<input type="hidden" name="for_contract" id="for_contract" value="1">':''); ?>
<span id="fzrlsitwz"></span>
<h4 class="bor-left-bull" >账户基本信息</h4>
<h r>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
      <input type="text" class="form-control" autocomplete="off" name="gongsi" id="gongsi" placeholder="输入客户名称前几个字我们将自动匹配" value="<?php echo ($hetong[advertiser]); ?>">
    <ul class="dropdown-menu" id="adlist">
	  
    </ul>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">合同编号</label>
    <div class="col-sm-3">
    	<select  class="form-control" name="contract_no" id="contract_no">
        	 <option><?php echo ($hetong[contract_no]?$hetong[contract_no]:'--选择广告主公司名称--'); ?></option>
        </select>
    </div>
    
    
  </div>


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
  
    <label for="contract_money" class="col-sm-2 control-label">主手机号</label>
    <div class="col-sm-3">
        <input type="text" class="form-control" name="tel" id="tel">
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

  <h4 class="bor-left-bull" >账户负责人信息</h4>
  <hr>
    <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">负责人</label>

	<div class="col-sm-3">
     <div class="input-group">
    <input type="text" class="form-control" autocomplete="off" name="fuzeren" id="fuzeren" placeholder="输入姓氏我们将自动匹配" >
    <ul class="dropdown-menu" id="fzrlist">
	  
    </ul>
      <span class="input-group-btn">
        <button class="btn btn-success" type="button" id="add_fzr">新增</button>
      </span>
      </div>
   </div>
   <div class="col-sm-7">
   		 <div class="input-group">
   		<span class="btn btn-danger">已选负责人</span> <span class="fzrlist"></span>
        </div>
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