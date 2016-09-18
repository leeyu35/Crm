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
<h3 class="bor-left-bull" >信息详情</h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal" id="formid" >
<div class="form-group">
   	 <label class="col-sm-2 control-label">发件人</label>
     <div class="col-sm-3">
    	<label  class="control-label"><strong class="text-center">
        <img src="<?php echo ($info2[image]); ?>" width="30" class="img-circle"><br>
        <?php echo ($info2[name]); ?></strong></label>
     </div>
   </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">标题</label>
    <div class="col-sm-3">

    <label  class="control-label"><strong><?php echo ($info[title]); ?></strong></label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">发送时间</label>
    <div class="col-sm-2">
     
     	 <label  class="control-label"><strong><?php echo (date("Y-m-d H:i:s",$info[time])); ?></strong></label>
       
    </div>
    
  </div>

   


  
  

 
  <h4 class="bor-left-bull" >信息内容</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12">
   		<label  class="control-label"><strong><?php echo ($info[content]); ?></strong></label>
   </div>

   </div>
	<hr />

    <div class="form-group">
    
       <div class="col-sm-12">
       <!--<button type="button" class="btn btn-primary" onClick="javascript:history.go(-1)">返回</button>-->
		<a class="btn btn-primary" href="<?php echo U("message");?>">返回</a>
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