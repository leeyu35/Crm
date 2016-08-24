<?php if (!defined('THINK_PATH')) exit();?><!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>无标题文档</title>

<link rel="stylesheet" type="text/css" href="/Public/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="/Public/css/admin.css">
<script src="/Public/js/jquery-3.1.0.min.js"></script>
<!--引入CSS-->
<link rel="stylesheet" type="text/css" href="/Public/js/dist/webuploader.css">

<!--引入JS-->
<script src="/Public/js/dist/webuploader.js"></script>

</head>

<body>
<div class="container" >
<h3>新增客户附件<small>Add Customer File</small></h3>
<br>
<form action="#" method="post" class="form-horizontal" >

 
  <h4>上传附件</h4>
  <hr>
  <div class="form-group">
   <div class="col-sm-12" style="overflow:hidden;">      
   
   
   
<div id="wrapper">
        <div id="container">
            <!--头部，相册选择和格式选择-->

            <div id="uploader">
                <div class="queueList">
                    <div id="dndArea" class="placeholder">
                        <div id="filePicker"></div>
                        <p>或将照片拖到这里，单次最多可选300张</p>
                    </div>
                </div>
                <div class="statusBar" style="display:none;">
                    <div class="progress">
                        <span class="text">0%</span>
                        <span class="percentage"></span>
                    </div><div class="info"></div>
                    <div class="btns">
                        <div id="filePicker2"></div><div class="uploadBtn">开始上传</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
   	
    <script type="text/javascript">
    	var test_id=<?php echo ($id); ?>
    </script>
    
	<script type="text/javascript" src="/Public/js/dist/upload.js"></script>
	


   </div>
   </div>
  
</form>

</div>

</body>
</html>