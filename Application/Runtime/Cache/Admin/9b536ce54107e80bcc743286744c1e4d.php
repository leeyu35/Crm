<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<script src="/Public/js/layer/layer.js"></script>

</head>

<body>

<span id="hjd"></span>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >查看附件<small>File</small></h3>
<br>
	<div class="form-group" style=" overflow:hidden;">
   <div class="col-sm-12" id="imgshowtime">
    	
      	<?php if(is_array($filelist)): $i = 0; $__LIST__ = $filelist;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$filelist): $mod = ($i % 2 );++$i;?><div class="col-sm-2">
        	<img class="shouim" layer-src="<?php echo ($filelist[file]); ?>" src="<?php echo ($filelist[file]); ?>" width="100" height="100" style="border:1px #ccc solid; ">&nbsp;<br>
				<a href="<?php echo U("defile?id=$filelist[id]");?>">删除</a>
            </div><?php endforeach; endif; else: echo "" ;endif; ?>
    </div>
    
  </div>

	<hr>
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