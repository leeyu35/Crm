<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>查看续费合同</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/My97DatePicker/WdatePicker.js"></script>
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script src="/Public/js/layer/layer.js"></script>

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
    <label for="inputEmail3" class="col-sm-1 control-label">客户所属</label>
    <div class="col-sm-2">
    <label  class="control-label"><strong><?php echo ($users_info); ?></strong></label>
      
    </div>
     <label for="inputEmail3" class="col-sm-1 control-label">提交人</label>
    <div class="col-sm-2">
    <label  class="control-label"><strong><?php echo ($users_info2); ?></strong></label>
      
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
    
    <label for="inputEmail3" class="col-sm-1 control-label">续费编号</label>
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
      
      <label  class="control-label"><strong><?php echo (num_format($info[margin])); ?></strong></label>
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
        <label  class="control-label"><strong><?php echo (num_format($info[contract_money])); ?>元</strong></label>
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
         到 <label  class="control-label"><?php echo ($info[contract_end]=='0'?'消费结束':''); ?><strong><?php echo (date('Y-m-d',$info[contract_end]!='0'?$info[contract_end]:'')); ?></strong></label>
    </div>
   
   

    
  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">账户名称</label>
    <div class="col-sm-3">
      <label  class="control-label"><strong><?php echo ($info[a_users]); ?></strong>  <a href="<?php echo U("Account/show?id=$info[a_id]");?>">查看账户详细信息</a></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">APP名称</label>
    <div class="col-sm-2">
      <label  class="control-label"><strong><?php echo ($info[appname]); ?></strong></label>


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
         <label  class="control-label"><strong><?php echo (date('Y-m-d',$info[payment_time]!='0'?$info[payment_time]:'')); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">付款金额</label>
    <div class="col-sm-2">
        <label  class="control-label"><strong><?php echo (num_format($info[fk_money])); ?>元</strong></label>
    </div>
    
  </div>
  <h4 class="bor-left-bull" >附件</h4>
  <hr>
	<div class="form-group">
   <div class="col-sm-12" id="imgshowtime">
    	
      	<?php if(is_array($filelist)): $i = 0; $__LIST__ = $filelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$filelist): $mod = ($i % 2 );++$i;?><div class="col-sm-1" style="padding-right:5px;">
        	<img class="shouim" layer-src="<?php echo ($filelist[file]); ?>" src="<?php echo ($filelist[file]); ?>" width="100" height="100" style="border:1px #ccc solid; ">&nbsp;
				<a href="<?php echo U("defile?id=$filelist[id]");?>">删除</a>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
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
       <?php if($info[audit_1] == 0): ?><span class="shno">一级审核未审核</span>
        	<?php elseif($info[audit_1] == 1): ?>
             <span class="shyes">一级审核已审核(审核人：<?php echo ($users_info3?$users_info3:'默认'); ?>)</span>
            <?php elseif($info[audit_1] == 2): ?>
            <span class="shno1">一级审核未通过(审核人：<?php echo ($users_info3?$users_info3:'默认'); ?>)</span><?php endif; ?>
       &nbsp;&nbsp;&nbsp;
       <?php if($info[audit_2] == 0): ?><span class="shno">二级审核未审核</span>
            <?php elseif($info[audit_2] == 1): ?>
            <span class="shyes">二级审核已审核(审核人：<?php echo ($users_info4?$users_info4:'默认'); ?>)</span>
            <?php elseif($info[audit_2] == 2): ?>
            <span class="shno1">二级审核未通过(审核人：<?php echo ($users_info4?$users_info4:'默认'); ?>)</span><?php endif; ?>

       </div>
    </div>

    <div class="form-group">
    
       <div class="col-sm-12">
       
        <button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">返回</button>
       	<?php if(($info[audit_1] != 2) and ($info[audit_2] != 2)): ?>&nbsp;&nbsp;
    	<a href="<?php echo U("shenhe?type=audit_1&id=$info[id]&yid=$yid");?>" class="btn btn-primary"  <?php echo ($info[audit_1]!='0'?'style="display:none"':''); ?> >一级审核通过</a>
    	<a href="<?php echo U("shenhe?type=audit_1&id=$info[id]&ju=1&yid=$yid");?>" class="btn btn-danger"  <?php echo ($info[audit_1]!='0'?'style="display:none"':''); ?> >一级审核不通过</a>
        &nbsp;&nbsp;
        <a href="<?php echo U("shenhe?type=audit_2&id=$info[id]&yid=$yid");?>" class="btn btn-primary"  <?php echo ($info[audit_2]!='0'?'style="display:none"':''); ?>>二级审核通过</a>
      	<a href="<?php echo U("shenhe?type=audit_2&id=$info[id]&ju=1&yid=$yid");?>" class="btn btn-danger"  <?php echo ($info[audit_2]!='0'?'style="display:none"':''); ?> >二级审核不通过</a><?php endif; ?>

       
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