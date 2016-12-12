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
<script language="javascript">
$(document).ready(function(e) {
    $("#new_contact").click(function(){
		
		$("#contactmain").append('<div class="form-group"><div class="col-sm-2"><input type="text" class="form-control" name="name[]" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="tel[]" class="form-control" id="inputEmail333" required="required"></div><div class="col-sm-2"><input type="text" name="qq[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="weixin[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="email[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="position[]"  class="form-control" id="inputEmail3"></div></div>')})
	
	
$("#formid").submit(function(){
		if($("#inputEmail3ad").val()=="")
		{
			alert("请填写公司名称");
			$("#inputEmail3ad").select();
			return false;	
		}		
		if($("#website").val()=="" || $("#website").val()=='http://www.')
		{
			alert("请填写公司网址");
			$("#website").select();
			return false;	
		}
		if($("#industry").val()=="")
		{
			alert("请选择客户所属行业");
		
			return false;	
		}
		
		if($("#inputEmail8se").val()=="")
		{
			alert("请选择客户所属省份");
		
			return false;	
		}	
		
	})
	
});


</script>
</head>

<body>
<div class="container" style="width:100%;">
<h3 class="bor-left-bull" >新增客户<small>New Customer</small></h3>
<form action="<?php echo U('addru');?>" method="post" class="form-horizontal" id="formid" >
<!--<h4>客户基本信息</h4>-->
<hr>

  <div class="form-group">
  
    <label for="inputEmail3ad" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
      <input name="advertiser" type="text" class="form-control" id="inputEmail3ad">
    </div>
    
    <label for="inputEmail4" class="col-sm-1 control-label">客户类型</label>
    <div class="col-sm-3">
      <select name="type"  class="form-control" id="inputEmail4">
      	<option value="1">公司</option>
      	<option value="2">个人</option>
      </select>
    </div> 

  </div>


  <!--<div class="form-group">
  
    <label class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-10 text-left">
       <?php if(is_array($product_line_list)): $k = 0; $__LIST__ = $product_line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product_line_list): $mod = ($k % 2 );++$k;?><label class="checkbox-inline"  <?php echo ($k=='1'?'style="margin-left:10px"':''); ?>><input name="product_line[]" type="checkbox" id="inlineCheckbox1" value="<?php echo ($product_line_list["id"]); ?>"><?php echo ($product_line_list["name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
      
    </div>
    


  </div>-->
  
  <div class="form-group">
  
    <label for="inputEmail5" class="col-sm-2 control-label">所属行业</label>
    <div class="col-sm-3">
      <!--<input name="industry" type="text" class="form-control" id="inputEmail3">-->
      <select name="industry"  class="form-control"  id="inputEmail5">
      <option value="" selected>--请选择客户行业--</option>
      <option value="APP/教育学习" >APP/教育学习</option>
      <option value="APP/系统工具">APP/系统工具</option>
      <option value="APP/旅游出行">APP/旅游出行</option>
      <option value="APP/网络购物">APP/网络购物</option>
      <option value="APP/金融理财">APP/金融理财</option>
      <option value="APP/拍摄影音">APP/拍摄影音</option>
      <option value="APP/社交通讯">APP/社交通讯</option>
      <option value="APP/健康医疗">APP/健康医疗</option>
      <option value="APP/新闻阅读">APP/新闻阅读</option>
      <option value="APP/生活服务">APP/生活服务</option>
      <option value="APP/资讯阅读">APP/资讯阅读</option>
      <option value="APP/游戏">APP/游戏</option>
      <option value="APP/平台直播">APP/平台直播</option>

      <option value="本地信息">本地信息</option>
      <option value="旅游住宿">旅游住宿</option>
      <option value="电商">电商</option>
      <option value="资讯">资讯</option>
      <option value="社交">社交</option>
      <option value="商务服务">商务服务</option>
      <option value="金融服务">金融服务</option>
      <option value="彩票">彩票</option>
      <option value="游戏">游戏</option>
      <option value="教育培训">教育培训</option>
      <option value="车辆物流">车辆物流</option>
      <option value="房地产建筑装修">房地产建筑装修</option>
      <option value="家庭日用品">家庭日用品</option>
      <option value="软件">软件</option>
      <option value="网络服务">网络服务</option>
      <option value="商务服务">商务服务</option>
      <option value="成人用品">成人用品</option>
      <option value="箱包饰品">箱包饰品</option>
      <option value="医疗服务">医疗服务</option>
      <option value="智能制造">智能制造</option>
      <option value="食品保健品">食品保健品</option>
      <option value="通讯服务设备">通讯服务设备</option>
      <option value="出版传媒">出版传媒</option>
      <option value="房地产建筑装修">房地产建筑装修</option>
      <option value="生活服务">生活服务</option>
      <option value="电子电工">电子电工</option>
      <option value="机械设备">机械设备</option>
      <option value="化工原料制品">化工原料制品</option>
      <option value="安全安保">安全安保</option>
      <option value="办公文教">办公文教</option>
      <option value="出版传媒">出版传媒</option>
      <option value="电脑硬件">电脑硬件</option>
      <option value="服装鞋帽">服装鞋帽</option>
      <option value="家用电器">家用电器</option>
      <option value="节能环保">节能环保</option>
      <option value="礼品">礼品</option>
      <option value="美容化妆">美容化妆</option>
      <option value="农林牧渔">农林牧渔</option>
      <option value="食品保健品">食品保健品</option>
      <option value="手机数码">手机数码</option>
      <option value="运动休闲娱乐">运动休闲娱乐</option>
      <option value="学术公管社会组织">学术公管社会组织</option>
      <option value="国际组织">国际组织</option>
      <option value="其他">其他</option>
 	  </select>
    </div>
    
    <label for="inputEmail6" class="col-sm-1 control-label">公司官网</label>
    <div class="col-sm-3">      
		<input name="website" id="website" type="url" class="form-control" id="inputEmail6" value="http://www.">
    </div>

  </div>
  
  <div class="form-group">
  
    <label class="col-sm-2 control-label">是否有APP</label>
    <div class="col-sm-2">
    <label class="radio-inline">
    	<input name="isapp" type="radio" id="inlineRadio1" value="1" checked> 有
    </label>
    <label class="radio-inline">
    	<input type="radio" name="isapp" id="inlineRadio2" value="0"> 没有
    </label>
    </div>
    
    <label for="inputEmail7" class="col-sm-2 control-label">APP名称或简称</label>
    <div class="col-sm-3">      
		<input name="appname" type="text" class="form-control" id="inputEmail7">
    </div>

  </div>
  
  <div class="form-group">
  
    <label for="inputEmail8se" class="col-sm-2 control-label">客户所属省</label>
    <div class="col-sm-3">
	<select name="city"  class="form-control" id="inputEmail8se">
    <option value="">--请选择--</option>
<option value="北京市">北京市</option> 
<option value="天津市">天津市</option>
<option value="上海市">上海市</option>
<option value="重庆市">重庆市</option>
<option value="河北省">河北省</option>
<option value="河南省">河南省</option>
<option value="云南省">云南省</option>
<option value="辽宁省">辽宁省</option>
<option value="黑龙江省">黑龙江省</option>
<option value="湖南省">湖南省</option> 
<option value="湖南省">安徽省</option>
<option value="山东省">山东省</option>
<option value="新疆维吾尔">新疆维吾尔</option> 
<option value="江苏省">江苏省</option>
<option value="浙江省">浙江省</option>
<option value="江西省">江西省</option>
<option value="湖北省">湖北省</option>
<option value="广西壮族">广西壮族</option> 
<option value="甘肃省">甘肃省</option>
<option value="山西省">山西省</option>
<option value="内蒙古">内蒙古</option>
<option value="陕西省">陕西省</option>
<option value="吉林省">吉林省</option>
<option value="福建省">福建省</option>
<option value="贵州省">贵州省</option>
<option value="广东省">广东省</option>
<option value="青海省">青海省</option>
<option value="西藏">西藏</option>
<option value="四川省">四川省</option> 
<option value="宁夏回族">宁夏回族</option> 
<option value="海南省">海南省</option>
<option value="台湾省">台湾省</option>
<option value="香港特别行政区">香港特别行政区</option>
<option value="澳门特别行政区">澳门特别行政区</option>
</select>
    
    </div>
  </div>
    
  
  <h4  class="bor-left-bull" >联系人<small>&nbsp;&nbsp;<a  id="new_contact" style="cursor:pointer;"><span class="glyphicon glyphicon-plus"></span>新增联系人</a></small></h4>
  <hr>
  <div id="contactmain">
	<div class="form-group">
  
    
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">联系人</label>
      	<input type="text" class="form-control" name="name[]" id="inputEmail3" required>
    </div>
     <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">电话</label>
      	<input type="text" class="form-control" name="tel[]" id="inputEmail333" required>
    </div>

    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">QQ</label>
      	<input type="text" class="form-control" name="qq[]" id="inputEmail3" >
    </div>
    
    
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">微信</label>
      	<input type="text" class="form-control" name="weixin[]" id="inputEmail3">
    </div>

    
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">邮箱</label>
      	<input type="text" class="form-control" name="email[]" id="inputEmail3">
    </div>
    
   
    <div class="col-sm-2">
    	<label for="inputEmail3" class="control-label">职位</label>
      	<input type="text" class="form-control" name="position[]" id="inputEmail3">
    </div>
    
    



  </div>
 </div>
   <h4  class="bor-left-bull" >开票资料 <small>如暂无开票资料可后期在修改页面添写</small></h4>
  <hr>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">纳税人识别号</label>
    <div class="col-sm-3">
      <input name="tax_identification" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票地址</label>
    <div class="col-sm-3">      
		<input name="ticket_address" type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
    <div class="col-sm-3">
      <input name="open_account" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账号</label>
    <div class="col-sm-3">      
		<input name="account" type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-3">
      <input name="kp_tel" type="text" class="form-control" id="inputEmail3">
    </div>
    


  </div><br>
   <hr>
	<div class="form-group">
  
   <div class="col-sm-2">
   <button type="submit" class="btn btn-primary" style="width:150px;" >提交</button>
   </div>
   </div>

</form>
<script>
inputEmail333.oninput=function(){
  inputEmail333.setCustomValidity("");
};
inputEmail333.oninvalid=function(){
  inputEmail333.setCustomValidity("请填写客户联系方式");
};
</script>
</div>
<br>
<br>

</body>
</html>