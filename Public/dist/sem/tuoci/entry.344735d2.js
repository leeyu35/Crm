webpackJsonp([25],{628:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj){for(var word in data)__p+='\r\n<tr title="'+(null==(__t=data[word].taskname)?"":__t)+'">\r\n    <td colspan="3" style="text-indent: 30px;">\r\n        '+(null==(__t=utils.dateFormat(data[word].taskname,"yyyy-MM-dd hh:mm:ss"))?"":__t)+'\r\n    </td>\r\n    <td colspan="7">\r\n        <p class="progress_num" style="text-indent:'+(null==(__t=data[word].rate)?"":__t)+'%" >'+(null==(__t=data[word].rate)?"":__t)+'%</p>\r\n        <div class="progress">\r\n            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width:'+(null==(__t=data[word].rate)?"":__t)+'%">\r\n            </div>\r\n        </div>\r\n    </td>\r\n    <td>\r\n        ',__p+=data[word].isfinish?"\r\n        已完成\r\n        ":"\r\n        未完成\r\n        ",__p+='\r\n    </td>\r\n    <td class="icon icon_td refresh" data-name="'+(null==(__t=data.taskname)?"":__t)+'">\r\n        <i class="iconfont icon-size"> &#xe600;</i>\r\n    </td>\r\n    <td class="icon icon_td look word" data-raterate=\''+(null==(__t=data.rate)?"":__t)+"' data-word_b_black='"+(null==(__t=data.word_b_black)?"":__t)+"' data-word_b_cf='"+(null==(__t=data.word_b_cf)?"":__t)+"' data-word_b_inorno='"+(null==(__t=data.word_b_inorno)?"":__t)+"' data-word_ok='"+(null==(__t=data.word_ok)?"":__t)+'\'  >\r\n        <i class="iconfont icon-size">&#xe642;</i>\r\n    </td>\r\n    <td class="icon icon_td down">\r\n        <i class="iconfont icon-size"> &#xe62a;</i>\r\n    </td>\r\n    <td class="icon icon_td remove" >\r\n        <i class="iconfont icon-size">&#xe6ba;</i>\r\n    </td>\r\n</tr>\r\n';__p+="\r\n"}return __p}},629:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj){__p+="\r\n";for(var word in data)__p+='\r\n    <tr>\r\n        <td class="wordh">'+(null==(__t=data[word].plan)?"":__t)+'</td>\r\n        <td class="dy">'+(null==(__t=data[word].unit)?"":__t)+'</td>\r\n        <td class="gwordc">'+(null==(__t=data[word].word)?"":__t)+'</td>\r\n        <td class="zzc">'+(null==(__t=data[word].seedword)?"":__t)+'</td>\r\n        <td class="dword">'+(null==(__t=data[word].recBid)?"":__t)+'</td>\r\n        <td class="ly1">'+(null==(__t=data[word].ly)?"":__t)+'</td>\r\n        <td class="url">'+(null==(__t=data[word].url)?"":__t)+"</td>\r\n    </tr>\r\n"}return __p}},630:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj)__p+='\r\n<tr >\r\n    <td colspan="3" style="text-indent: 30px;">\r\n        '+(null==(__t=utils.dateFormat(data.date,"yyyy-MM-dd hh:mm:ss"))?"":__t)+'\r\n    </td>\r\n    <td colspan="7">\r\n        <p class="progress_num"></p>\r\n        <div class="progress">\r\n            <div class="progress-bar progress-bar-info" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%">\r\n            </div>\r\n        </div>\r\n    </td>\r\n    <td>\r\n        ',__p+=data.isfinish?"\r\n            已完成\r\n        ":"\r\n            未完成\r\n        ",__p+='\r\n    </td>\r\n    <td class="icon icon_td refresh" >\r\n        <i class="iconfont icon-size"> &#xe600;</i>\r\n    </td>\r\n    <td class="icon icon_td look" >\r\n        <i class="iconfont icon-size">&#xe642;</i>\r\n    </td>\r\n    <td class="icon icon_td down">\r\n        <i class="iconfont icon-size"> &#xe62a;</i>\r\n    </td>\r\n    <td class="icon icon_td remove" >\r\n        <i class="iconfont icon-size">&#xe6ba;</i>\r\n    </td>\r\n</tr>\r\n';return __p}},632:function(t,n){},0:function(t,n,r){(function(t){"use strict";function n(t){return t&&t.__esModule?t:{default:t}}function a(){_.ajax(u.getApiUrl("getTask"),{appid:p.appid}).done(function(n){var r=n;console.log(n),t(".shenhe_tbody").html(d({data:r,utils:_}))})}var o=r(197),e=n(o);r(85);var d=r(628),i=r(629),s=r(630);r(243),r(244),r(242),r(631),r(238),r(632),r(223);var l=r(116),c=r(191),_=r(117),u=r(199),p=c.getCurrentAccount();l.on("account_change",function(){p=c.getCurrentAccount(),window.location.reload(),a()}),a(),t(function(){t(".click_up").click(function(){"none"==t(this).parent("div").next("div").css("display")?t(this).parent("div").next("div").slideDown(100):"block"==t(this).parent("div").next("div").css("display")&&t(this).parent("div").next("div").slideUp(100)}),t(".yulan_out").click(function(){t(".input_all > p > input").val("")}),t(".prompt").hover(function(){},function(){}),t(".yulan_table ").tablesorter(),t(".zhongzi").change(function(){t(this).val().length>5e3?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".yidongwen").change(function(){t(this).val().length>1024?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".yidongxian").change(function(){t(this).val().length>36?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".chuangtitle").change(function(){t(this).val().length>50||t(this).val().length<8?t(this).css("border","2px solid red"):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".chuang1,.chuang2").change(function(){t(this).val().length>80||t(this).val().length<8?t(this).css("border","2px solid red"):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".danyuan,.jihua").change(function(){t(this).val().length>15?t(this).css("border","2px solid red"):t(this).css("border","1px solid #ccc")}),t(".out").change(function(){Number(t(this).val())>10||Number(t(this).val())<.3?t(this).css("border","2px solid red"):t(this).css("border","1px solid #ccc")}),t(".ys").change(function(){parseFloat(t(this).val())>3e3||parseFloat(t(this).val())<50?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t("#myForm").validate({submitHandler:function(){parseInt(t(".shenhe_tbody").css("height"))<=120&&t("#last").css("width","17px"),t(".shenhe_container-fluid").css("display","block"),console.log(t("#myForm").serializeArray());var n=t("#myForm").serializeArray();n.push({name:"appid",value:p.appid});var r={};return t.each(n,function(t,n){if("words"==n.name){var a=n.value,o=a.split(/\r\n/g);r[n.name]=(0,e.default)(o)}else if("include"==n.name){var d=n.value,i=d.split(/\r\n/g);r[n.name]=(0,e.default)(i)}else if("noinclude"==n.name){var s=n.value,l=s.split(/\r\n/g);r[n.name]=(0,e.default)(l)}else r[n.name]=n.value}),_.ajaxPost(u.getApiUrl("getZhong"),r).done(function(n){_.dateFormat(n.date,"yyyy-MM-dd hh:mm:ss");t(".shenhe_tbody").prepend(s({data:n,utils:_}))}),!1}}),t(document).on("click",".look",function(){t(".tuoci_yulan").css("display","block"),_.ajax(u.getApiUrl("getSomeTask"),{appid:p.appid,taskname:t(this).parent("tr").attr("title")}).done(function(n){console.log(n);var r=n;console.log(r),t(".yulan_tbody").html(i({data:r}))}),t(".raterate").text(t(this).data("raterate")),t(".word_b_black").text(t(this).data("word_b_black")),t(".word_b_cf").text(t(this).data("word_b_cf")),t(".word_b_inorno").text(t(this).data("word_b_inorno")),t(".word_ok").text(t(this).data("word_ok"))}),t(document).on("click",".refresh",function(){a()}),t(document).on("click",".down",function(){_.formSubmit(u.getApiUrl("OutTuoCi"),{appid:p.appid,taskname:t(this).data("name")})}),t(document).on("click",".remove",function(){t(this).parents("tr").remove()})})}).call(n,r(83))},631:function(t,n,r){var a,o,e;!function(d){o=[r(83),r(242)],a=d,e="function"==typeof a?a.apply(n,o):a,!(void 0!==e&&(t.exports=e))}(function(t){t.extend(t.validator.messages,{required:"这是必填字段",remote:"请修正此字段",email:"请输入有效的电子邮件地址",url:"请输入有效的网址",date:"请输入有效的日期",dateISO:"请输入有效的日期 (YYYY-MM-DD)",number:"请输入有效的数字",digits:"只能输入数字",creditcard:"请输入有效的信用卡号码",equalTo:"你的输入不相同",extension:"请输入有效的后缀",maxlength:t.validator.format("最多可以输入 {0} 个字符"),minlength:t.validator.format("最少要输入 {0} 个字符"),rangelength:t.validator.format("请输入长度在 {0} 到 {1} 之间的字符串"),range:t.validator.format("请输入范围在 {0} 到 {1} 之间的数值"),max:t.validator.format("请输入不大于 {0} 的数值"),min:t.validator.format("请输入不小于 {0} 的数值")})})}});