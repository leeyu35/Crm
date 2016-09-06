<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script src="/Public/js/layer/layer.js"></script>
<style type="text/css">
	#adlist{ margin-left:15px;}
	#adlist li a{ cursor:pointer;}
	.bzj{ display:none;}
</style>
</head>

<body>

<span id="hjd"></span>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >查看垫款<small>Refund</small></h3>
<br>
<form action="#" method="post" class="form-horizontal" id="formid" >
<input type="hidden" name="id" id="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="advertiser" id="advertiser" value="<?php echo ($info[advertiser]); ?>">
<input type="hidden" name="submituser" id="submituser" value="<?php echo ($info[submituser]); ?>">
<h4 class="bor-left-bull" >垫款基本信息</h4>
<hr>
<div class="form-group">
   	 <label class="col-sm-2 control-label">提交人</label>
     <div class="col-sm-3">
    	<label  class="control-label"><strong><?php echo ($users_info); ?></strong></label>
     </div>
   </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">

    <label  class="control-label"><strong><?php echo ($gongsi); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">垫款主体</label>
    <div class="col-sm-2">
       <?php if(is_array($agentcompany)): $k = 0; $__LIST__ = $agentcompany;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$agentcompany): $mod = ($k % 2 );++$k;?><label  class="control-label"><strong><?php echo ($info[b_company]==$agentcompany[id]?"$agentcompany[companyname]":''); ?></strong></label><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    
  </div>
  
    
   


  
  
  <div class="form-group">
  
    <label for="contract_money" class="col-sm-2 control-label">回款金额</label>
    <div class="col-sm-3">
        <label  class="control-label"><strong><?php echo ($info[b_money]); ?>元</strong></label>
    </div>
    
     <label for="inputEmail3" class="col-sm-1 control-label">回款日期</label>
     <div class="col-sm-2">
         <label  class="control-label"><strong><?php echo (date("Y-m-d",$info[b_time])); ?></strong></label>
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
 
  <h4 class="bor-left-bull" >垫款备注</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">
   		<label  class="control-label"><strong><?php echo ($info[note]); ?></strong></label>
   </div>

   </div>
	
    <div class="form-group">
    
       <div class="col-sm-12">
       <button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">返回</button>
       
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