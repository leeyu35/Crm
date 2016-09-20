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
<script language="javascript">
$(document).ready(function(e) {
    $("#new_contact").click(function(){
		$("#contactmain").append('<div class="form-group"><div class="col-sm-1"><input type="text" class="form-control" name="name_n[]" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="qq_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="weixin_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="email_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="position_n[]"  class="form-control" id="inputEmail3"></div><div class="col-sm-2"><input type="text" name="tel_n[]" class="form-control" id="inputEmail3"></div></div>')
	})
	$(".de_contact").click(function(){
		deid=$(this).attr("id");
		
		
		$.get("<?php echo U("delete_contact");?>",{id:deid,stime:+Math.random()},function(index){
			if(index=='1')
			{
				
				$("#contact_list_id_"+deid).remove();
			}else
			{
				alert('删除失败');	
			}
			
		})
		
	})
	
	
});
</script>
</head>

<body>
<div class="container" style="width:100%;" >
<h3 class="bor-left-bull" >修改客户<small>update Customer</small></h3>
<br>
<form action="<?php echo U('upru');?>" method="post" class="form-horizontal">
<input type="hidden" name="id" value="<?php echo ($info[id]); ?>">
<input type="hidden" name="time" value="<?php echo ($info[ctime]); ?>">
<h4>客户基本信息</h4>
<hr>

  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">广告主公司名称</label>
    <div class="col-sm-3">
      <input name="advertiser" type="text" class="form-control" value="<?php echo ($info['advertiser']); ?>" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">客户类型</label>
    <div class="col-sm-3">
      <select name="type"  class="form-control" id="type">
      	<option value="1" <?php echo ($info['type']=='1'?'selected':''); ?> >公司</option>
      	<option value="2" <?php echo ($info['type']=='2'?'selected':''); ?>>个人</option>
      </select>
    </div> 

  </div>


 <!-- <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">产品线</label>
    <div class="col-sm-10 text-left">
    	
       
       <?php if(is_array($product_line_list)): $k = 0; $__LIST__ = $product_line_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$product_line_list): $mod = ($k % 2 );++$k;?><label class="checkbox-inline"  <?php echo ($k=='1'?'style="margin-left:10px"':''); ?>><input  name="product_line[]" type="checkbox" id="inlineCheckbox1" value="<?php echo ($product_line_list["id"]); ?>" <?php if(in_array(($product_line_list["id"]), is_array($info[product_line])?$info[product_line]:explode(',',$info[product_line]))): ?>checked<?php endif; ?>><?php echo ($product_line_list["name"]); ?></label><?php endforeach; endif; else: echo "" ;endif; ?>
      
    </div>
    

</div>
  -->
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">所属行业</label>
    <div class="col-sm-3">
      <!--<input name="industry" type="text" class="form-control" id="inputEmail3">-->
      <select name="industry"  class="form-control"  id="inputEmail5">
      <option value="<?php echo ($info[industry]); ?>" selected><?php echo ($info[industry]); ?></option>
      <option value="旅游出行">旅游出行</option>
      <option value="金融理财">金融理财</option>
      <option value="视频">视频</option>
      <option value="购物">购物</option>
      <option value="音乐">音乐</option>
      <option value="图像">图像</option>
      <option value="新闻阅读">新闻阅读</option>
      <option value="生活实用工具">生活实用工具</option>
      <option value="系统工具">系统工具</option>
      <option value="美化手机">美化手机</option>
      <option value="效率办公">效率办公</option>
      <option value="聊天社交">聊天社交</option>
      <option value="电话通讯">电话通讯</option>
      <option value="交通导航">交通导航</option>
      <option value="生活服务">生活服务</option>
      <option value="运动健康">运动健康</option>
      <option value="教育培训">教育培训</option>
      <option value="丽人母婴">丽人母婴</option>
      
      <OPTION  value=机械及行业设备>机械及行业设备</OPTION><OPTION value=普通机械制造>普通机械制造</OPTION><OPTION value=通用零部件>通用零部件</OPTION><OPTION value=五金配件>五金配件</OPTION><OPTION value=金属工具>金属工具</OPTION><OPTION value=电动工具>电动工具</OPTION><OPTION value=电子元件>电子元件</OPTION><OPTION value=电子器件>电子器件</OPTION><OPTION value=照明及照明器具>照明及照明器具</OPTION><OPTION value=安全防护设备>安全防护设备</OPTION><OPTION value=包装>包装</OPTION><OPTION value=造纸及纸制品>造纸及纸制品</OPTION><OPTION value=电机、电工电器>电机、电工电器</OPTION><OPTION value=电工器材>电工器材</OPTION><OPTION value=通用仪器仪表>通用仪器仪表</OPTION><OPTION value=专用仪器仪表>专用仪器仪表</OPTION><OPTION value=交通运输设备、零部件>交通运输设备、零部件</OPTION><OPTION value=办公、文教用品>办公、文教用品</OPTION><OPTION value=数码、电脑及网络配件>数码、电脑及网络配件</OPTION><OPTION value=日常家居用品>日常家居用品</OPTION><OPTION value=木材、木制品>木材、木制品</OPTION><OPTION value=家具>家具</OPTION><OPTION value=家用电器>家用电器</OPTION><OPTION value=礼品、工艺美术品>礼品、工艺美术品</OPTION><OPTION value=食品、饮料>食品、饮料</OPTION><OPTION value=通信产品>通信产品</OPTION><OPTION value=玩具>玩具</OPTION><OPTION value=印刷设备>印刷设备</OPTION><OPTION value=运动、休闲、保健用品>运动、休闲、保健用品</OPTION><OPTION value=鞋、帽>鞋、帽</OPTION><OPTION value=服装>服装</OPTION><OPTION value=日用化学品>日用化学品</OPTION><OPTION value=农用化学品>农用化学品</OPTION><OPTION value=胶粘剂>胶粘剂</OPTION><OPTION value=染料、颜料、涂料和油墨>染料、颜料、涂料和油墨</OPTION><OPTION value=催化剂和助剂>催化剂和助剂</OPTION><OPTION value=库存精细化学品>库存精细化学品</OPTION><OPTION value=食品和饲料添加剂>食品和饲料添加剂</OPTION><OPTION value=塑料>塑料</OPTION><OPTION value=橡胶制品>橡胶制品</OPTION><OPTION value=环保、环保设备>环保、环保设备</OPTION><OPTION value=建筑、建材>建筑、建材</OPTION><OPTION value=能源>能源</OPTION><OPTION value=粮油、农制品>粮油、农制品</OPTION><OPTION value=金属>金属</OPTION><OPTION value=医药、保健及医疗设备>医药、保健及医疗设备</OPTION><OPTION value=纺织>纺织</OPTION><OPTION value=皮革>皮革</OPTION><OPTION value=煤焦化产品>煤焦化产品</OPTION><OPTION value=日常服务>日常服务</OPTION><OPTION value=广告服务>广告服务</OPTION><OPTION value=教育培训>教育培训</OPTION><OPTION value=认证>认证</OPTION><OPTION value=创意设计>创意设计</OPTION><OPTION value=物流服务>物流服务</OPTION><OPTION value=进出口代理>进出口代理</OPTION><OPTION value=维修及安装服务>维修及安装服务</OPTION><OPTION value=广告、展览器材>广告、展览器材</OPTION><OPTION value=专业录音、放音设备>专业录音、放音设备</OPTION><OPTION value=光学摄影器材>光学摄影器材</OPTION><OPTION value=编辑制作设备>编辑制作设备</OPTION><OPTION value=播出、前端设备>播出、前端设备</OPTION><OPTION value=建筑、装饰业>建筑、装饰业</OPTION><OPTION value=房地产>房地产</OPTION><OPTION value=安装工程>安装工程</OPTION><OPTION value=邮电通信>邮电通信</OPTION><OPTION value=事务所、公证>事务所、公证</OPTION><OPTION value=卫生、体育、社会、福利>卫生、体育、社会、福利</OPTION><OPTION value=公共服务业>公共服务业</OPTION><OPTION value=金融、保险>金融、保险</OPTION><OPTION value=实业公司、商业贸易>实业公司、商业贸易</OPTION><OPTION value=高新技术开发区>高新技术开发区</OPTION><OPTION value=卡类市场>卡类市场</OPTION><OPTION value=其他>其他</OPTION>

 	</select>   
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">公司官网</label>
    <div class="col-sm-3">      
		<input name="website" type="text" value="<?php echo ($info['website']); ?>" class="form-control" id="inputEmail3">
    </div>

  </div>
  
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">是否有APP</label>
    <div class="col-sm-3">
    <label class="radio-inline">
    	<input name="isapp" type="radio" id="inlineRadio1" value="1" <?php echo ($info['isapp']=='1'?'checked':''); ?> > 有
    </label>
    <label class="radio-inline">
    	<input type="radio" name="isapp" id="inlineRadio2" value="0" <?php echo ($info['isapp']=='0'?'checked':''); ?> > 没有
    </label>
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">APP名称</label>
    <div class="col-sm-3">      
		<input name="appName" type="text" class="form-control" value="<?php echo ($info['appname']); ?>" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail8" class="col-sm-2 control-label">客户所属省</label>
    <div class="col-sm-3">
	<select name="city"  class="form-control" id="inputEmail4">
    <option value="<?php echo ($info[city]); ?>" selected><?php echo ($info[city]); ?></option>
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
<option value="江苏省">浙江省</option>
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
  
  <h4>联系人<small>&nbsp;&nbsp;<a  id="new_contact" style="cursor:pointer;"><span class="glyphicon glyphicon-plus"></span>新增联系人</a></small></h4>
  <hr>
  <div id="contactmain">
  <?php if(is_array($contact_list)): $k = 0; $__LIST__ = $contact_list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$contact_list): $mod = ($k % 2 );++$k;?><div class="form-group" id="contact_list_id_<?php echo ($contact_list[id]); ?>">
    <input type="hidden" name="contactid[]" value="<?php echo ($contact_list[id]); ?>">
    <div class="col-sm-1">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?>  class="control-label">联系人</label>
      	<input type="text" class="form-control" name="name[]" value="<?php echo ($contact_list[name]); ?>" id="inputEmail3">
    </div>
    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">QQ</label>
      	<input type="text" class="form-control" name="qq[]" value="<?php echo ($contact_list[qq]); ?>" id="inputEmail3">
    </div>
    
    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">微信</label>
      	<input type="text" class="form-control" name="weixin[]"  value="<?php echo ($contact_list[weixin]); ?>" id="inputEmail3">
    </div>

    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">邮箱</label>
      	<input type="text" class="form-control" name="email[]"  value="<?php echo ($contact_list[email]); ?>" id="inputEmail3">
    </div>
    
   
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">职位</label>
      	<input type="text" class="form-control" name="position[]"  value="<?php echo ($contact_list[position]); ?>" id="inputEmail3">
    </div>
    
    
    <div class="col-sm-2">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label">电话</label>
      	<input type="text" class="form-control" name="tel[]" value="<?php echo ($contact_list[tel]); ?>" id="inputEmail3">
    </div>
    
    <div class="col-sm-1">
    	<label <?php echo ($k!='1'?'style="display:none;"':''); ?> class="control-label" style="color:#fff;">操作执行 </label>
    	<input type="button" value="删" id="<?php echo ($contact_list[id]); ?>" class="btn btn-danger de_contact">
    </div>

  </div><?php endforeach; endif; else: echo "" ;endif; ?>
 </div>
   <h4>开票资料</h4>
  <hr>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">纳税人识别号</label>
    <div class="col-sm-3">
      <input name="tax_identification" value="<?php echo ($info['tax_identification']); ?>" type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">开票地址</label>
    <div class="col-sm-3">      
		<input name="ticket_address" value="<?php echo ($info['ticket_address']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">开户行</label>
    <div class="col-sm-3">
      <input name="open_account" value="<?php echo ($info['open_account']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>
    
    <label for="inputEmail3" class="col-sm-1 control-label">账号</label>
    <div class="col-sm-3">      
		<input name="account" value="<?php echo ($info['account']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>

  </div>
  <div class="form-group">
  
    <label for="inputEmail3" class="col-sm-2 control-label">电话</label>
    <div class="col-sm-3">
      <input name="kp_tel" value="<?php echo ($info['kp_tel']); ?>"  type="text" class="form-control" id="inputEmail3">
    </div>
    


  </div>
   <br>
   <hr>
	<div class="form-group">
  
   <div class="col-sm-2">
   <button type="submit" class="btn btn-primary" style="width:150px;" >提交</button>
   </div>
   </div>

</form>

</div>
<br>
<br>

</body>
</html>