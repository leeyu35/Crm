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
<h3 class="bor-left-bull" >查看退款<small>Refund</small></h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="id" id="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="advertiser" id="advertiser" value="<?php echo ($info[advertiser]); ?>">
<input type="hidden" name="submituser" id="submituser" value="<?php echo ($info[submituser]); ?>">
<h4 class="bor-left-bull" >退款基本信息</h4>
<hr>
<div class="form-group">
   	 <label class="col-sm-2 control-label">销售</label>
     <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($users_info); ?></strong></label>
     </div>
   </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">

    <label  class="control-label"><strong><?php echo ($gongsi); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">退款主体</label>
    <div class="col-sm-2">
       <?php if(is_array($agentcompany)): $k = 0; $__LIST__ = $agentcompany;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agentcompany): $mod = ($k % 2 );++$k;?><label  class="control-label"><strong><?php echo ($info[r_company]==$agentcompany[id]?"$agentcompany[companyname]":''); ?></strong></label><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">合同编号</label>
    <div class="col-sm-3">
        <label  class="control-label"><strong><?php echo ($info[contract_no]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">是否开票</label>
    <div class="col-sm-3">
     	  <label  class="control-label"><strong>
   	     <?php echo ($info[ispiao]==0?'未开':'已开'); ?>
   	     
          </strong></label>
    </div>
    </div>
    
   


  
  
  <div class="form-group">
  
    <label for="contract_money" class="col-sm-2 control-label">退款金额</label>
    <div class="col-sm-3">
        <label  class="control-label"><strong><?php echo ($info[r_money]); ?>元</strong></label>
    </div>
    
    <label for="rebates_proportion" class="col-sm-1 control-label">百度账户</label>
    <div class="col-sm-2">      
    	<label  class="control-label"><strong><?php echo ($info[baiduhu]); ?></strong></label>
    </div>
    
    <label for="show_money" class="col-sm-1 control-label">百度币</label>
    <div class="col-sm-2">      
        <label  class="control-label"><strong><?php echo ($info[baidubi]); ?></strong></label>
       
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">退款日期</label>
    <div class="col-sm-3">
                <label  class="control-label"><strong><?php echo (date("Y-m-d",$info[r_time])); ?></strong></label>

    </div>
   


    
  </div>
  
  <h4 class="bor-left-bull" >退款银行信息</h4>
  <hr>
	<div class="form-group">
  
    <label for="payment_type" class="col-sm-2 control-label">退款开户行</label>
    <div class="col-sm-3">
        <label  class="control-label"><strong><?php echo ($info[r_open_account]); ?></strong></label>
    </div>
    
    <label for="payment_time" class="col-sm-1 control-label">退款开户账户</label>
    <div class="col-sm-2">      
        <label  class="control-label"><strong><?php echo ($info[r_account]); ?></strong></label>
    </div>

    
  </div>
 
  <h4 class="bor-left-bull" >退款备注</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">
   		<label  class="control-label"><strong><?php echo ($info[note]); ?></strong></label>
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
       <button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">返回</button>
       <a href="<?php echo U("shenhe?type=audit_1&id=$info[id]");?>" class="btn btn-primary"  <?php echo ($info[audit_1]!='0'?'style="display:none"':''); ?> >一级审核通过</a>
        <a href="<?php echo U("shenhe?type=audit_2&id=$info[id]");?>" class="btn btn-primary"  <?php echo ($info[audit_2]!='0'?'style="display:none"':''); ?>>二级审核通过</a>
       </div>
    </div>

</form>

</div>
<br>
<br>
<br>

</body>
</html>