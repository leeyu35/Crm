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
<form action="#" method="post" class="form-horizontal" id="formid" >

<div class="container" style="width:100%;">
<h4 class="bor-left-bull" >开票基本信息</h4>
<hr>
<div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">发票抬头</label>
    <div class="col-sm-3">

    	<label  class="control-label"><strong><?php echo ($gongsi); ?></strong></label>
    </div>
    
    <label for="contract_money" class="col-sm-1 control-label">开票金额</label>
    <div class="col-sm-2">
        <label  class="control-label"><strong><?php echo ($info[money]); ?>元</strong></label>
    </div>
  </div>
  <div class="form-group">
  	<label for="inputEmail3" class="col-sm-2 control-label">开票主体</label>
    <div class="col-sm-3">
      
        <?php if(is_array($agentcompany)): $k = 0; $__LIST__ = $agentcompany;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agentcompany): $mod = ($k % 2 );++$k;?><label  class="control-label"><strong><?php echo ($info[main_company]==$agentcompany[id]?$agentcompany[companyname]:''); ?></strong></label><?php endforeach; endif; else: echo "" ;endif; ?>

    </div>
  
    	<label for="inputEmail1" class="col-sm-1 control-label">开票类型</label>
    <div class="col-sm-2">
      
      	<?php if(is_array($list)): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$list): $mod = ($i % 2 );++$i;?><label  class="control-label"><strong><?php echo ($info[type]==$list[id]?$list[name]:''); ?></strong></label><?php endforeach; endif; else: echo "" ;endif; ?>
     
    </div>
  
    
    </div>
    
	<div class="form-group">
   	<label for="inputEmail3" class="col-sm-2 control-label">合同编号</label>
    <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($info[contract_no]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">是否回款</label>
    <div class="col-sm-3">
     	<label  class="control-label"><strong>
   	     <?php echo ($info[is_back_money]==1?'已回款':''); ?>
   	     <?php echo ($info[is_back_money]==0?'未回款':''); ?>
         </strong></label>
    </div>
   </div>
   <div class="form-group">
   	<label for="inputEmail3" class="col-sm-2 control-label">发票号</label>
    <div class="col-sm-3">
    	 	<label  class="control-label"><strong><?php echo ($info[fp_on]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票时间</label>
    <div class="col-sm-3">
     	<label  class="control-label"><strong><?php echo (date("Y-m-d",$info[kp_time]?$info[kp_time]:'')); ?></strong></label>
    </div>
   </div>
   
   <div class="form-group">
    <label for="inputEmail3" class="col-sm-2 control-label">APP名称</label>
    <div class="col-sm-3">
     	 <label  class="control-label"><strong><?php echo ($info[appname]); ?></strong></label>
    </div>
   </div>
    
   <h4  class="bor-left-bull" >开票资料 </h4>
  <hr>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">纳税人识别号</label>
    <div class="col-sm-3">
      <label  class="control-label"><strong><?php echo ($kaipinfo[tax_identification]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票地址</label>
    <div class="col-sm-3">      
    	<label  class="control-label"><strong><?php echo ($kaipinfo[ticket_address]); ?></strong></label>
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
    <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($kaipinfo[open_account]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账号</label>
    <div class="col-sm-3">      
    	<label  class="control-label"><strong><?php echo ($kaipinfo[account]); ?></strong></label>
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($kaipinfo[kp_tel]); ?></strong></label>
    </div>
    


  </div>
  <h4 class="bor-left-bull" >开票备注</h4>
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