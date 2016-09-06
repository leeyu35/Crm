<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>查看续费合同</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/js/jquery-3.1.0.min.js"></script>

<style type="text/css">
	#adlist{ margin-left:15px;}
	#adlist li a{ cursor:pointer;}
	
</style>

<?php if($info[type] < 2): ?><style type="text/css">.bzj{ display:none;}</style><?php endif; ?>
</head>

<body>
<span id="hjd"></span>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >查看续费合同<small>Show contract</small></h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="id" id="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="advertiser" id="advertiser" value="<?php echo ($info[advertiser]); ?>">
<input type="hidden" name="submituser" id="submituser" value="<?php echo ($info[submituser]); ?>">
<h4 class="bor-left-bull" >合同基本信息</h4>
<hr>
 
   <div class="form-group">
  
    <label class="col-sm-2 control-label">初始合同编号</label>
    <div class="col-sm-3">
    	<label  class="control-label"><strong> <?php echo ($yinfo[contract_no]); ?></strong></label>
    </div>
    <label for="inputEmail3" class="col-sm-1 control-label">销售</label>
    <div class="col-sm-2">
    <label  class="control-label"><strong><?php echo ($users_info); ?></strong></label>
      
    </div>
 
  </div>
  
  
  <div class="form-group">
    <label class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($gongsi); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">代理公司</label>
    <div class="col-sm-2">
        <?php if(is_array($agentcompany)): $k = 0; $__LIST__ = $agentcompany;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agentcompany): $mod = ($k % 2 );++$k;?><label  class="control-label"><strong><?php echo ($info[agent_company]==$agentcompany[id]?"$agentcompany[companyname]":""); ?></strong></label><?php endforeach; endif; else: echo "" ;endif; ?>      
    </div>
    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-3">
   
      	<?php if(is_array($product_line_list)): $k = 0; $__LIST__ = $product_line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product_line_list): $mod = ($k % 2 );++$k;?><label  class="control-label"><strong><?php echo ($info[product_line]==$product_line_list[id]?"$product_line_list[name]":""); ?></strong></label><?php endforeach; endif; else: echo "" ;endif; ?>

      
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">续费合同编号</label>
    <div class="col-sm-3">
      <label  class="control-label"><strong><?php echo ($info[contract_no]); ?></strong></label>
    </div>
    </div>
    
    <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">合同类型</label>
    <div class="col-sm-3">
   	   <label  class="control-label"><strong> <?php echo ($info[type]=='1'?'普通合同':'框架合同'); ?></strong></label>

   
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label bzj">保证金</label>
    <div class="col-sm-3 bzj">
      
      <label  class="control-label"><strong><?php echo ($info[margin]); ?></strong></label>
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
        <label  class="control-label"><strong><?php echo ($info[contract_money]); ?>元</strong></label>
    </div>
    
    <label for="rebates_proportion" class="col-sm-1 control-label">返点比例</label>
    <div class="col-sm-1">
        <label  class="control-label"><strong><?php echo ($info[rebates_proportion]); ?>%</strong></label>
    </div>
    
    <label for="show_money" class="col-sm-2 control-label">账户显示金额</label>
    <div class="col-sm-2">      
        <label  class="control-label"><strong><?php echo ($info[show_money]); ?>元</strong></label>
    </div>
    
  </div>
  <div class="form-group">
 
    <label for="inputEmail3" class="col-sm-2 control-label">合同有效期</label>
    <div class="col-sm-4">
         <label  class="control-label"><strong><?php echo (date('Y-m-d',$info[contract_start])); ?></strong></label>
         到<label  class="control-label"><strong><?php echo (date('Y-m-d',$info[contract_end])); ?></strong></label>
    </div>
   
   

    
  </div>
  
  <h4 class="bor-left-bull" >付款信息</h4>
  <hr>
	<div class="form-group">
  
    <label for="payment_type" class="col-sm-2 control-label">付款方式</label>
    <div class="col-sm-3">
      <label  class="control-label"><strong>
      	<?php echo ($info[payment_type]=='1'?'预付':''); ?>
      	<?php echo ($info[payment_type]=='2'?'垫付':''); ?>
     </strong></label>
    </div>
    
    <label for="payment_time" class="col-sm-1 control-label">付款日期</label>
    <div class="col-sm-2">      
         <label  class="control-label"><strong><?php echo (date('Y-m-d',$info[payment_time])); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">付款金额</label>
    <div class="col-sm-2">
        <label  class="control-label"><strong><?php echo ($info[fk_money]); ?>元</strong></label>
    </div>
    
  </div>
 
  <h4 class="bor-left-bull" >合同备注</h4>
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