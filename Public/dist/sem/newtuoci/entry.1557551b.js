webpackJsonp([19],{493:function(t,n){},0:function(t,n,a){(function(t){"use strict";function n(t){return t&&t.__esModule?t:{default:t}}function r(){c.ajax(u.getApiUrl("getTask"),{appid:h.appid}).done(function(n){console.log(n);for(var a=0;a<n.length;a++)n[a].date=c.dateFormat(new Date(n[a].taskname),"yyyy-MM-dd hh:mm:ss"),n[a].isfinish?n[a].flag="已完成":n[a].flag="未完成";t(".new").html(d({data:n}))})}var e=a(197),s=n(e);a(85);var d=a(491),l=a(492);a(243),a(244),a(238),a(493),a(223);var o=a(116),i=a(191),c=a(117),u=a(199),h=i.getCurrentAccount();o.on("account_change",function(){h=i.getCurrentAccount(),r(),t(".xiang").html("")}),t(function(){t(".flush").click(function(){r()}),t(".zhongzi").change(function(){t(this).val().length>5e3?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".yidongwen").change(function(){t(this).val().length>1024?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".yidongxian").change(function(){t(this).val().length>36?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".chuangtitle").change(function(){t(this).val().length>50||t(this).val().length<8?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".chuang1,.chuang2").change(function(){t(this).val().length>80||t(this).val().length<8?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".danyuan,.jihua").change(function(){t(this).val().length>15?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".out").change(function(){parseFloat(t(this).val())>10||parseFloat(t(this).val())<.3?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),t(".ys").change(function(){parseFloat(t(this).val())>3e3||parseFloat(t(this).val())<50?(t(this).css("border","2px solid red"),t(".record button").attr("disabled","disabled")):(t(".record button").removeAttr("disabled"),t(this).css("border","1px solid #ccc"))}),r();var n=1,a=1;t(".gj").click(function(){1==n?(t(".luo").slideUp(),n=0):(t(".luo").slideDown(),n=1)}),t(".cy").click(function(){1==a?(t(".sheng").slideUp(),a=0):(t(".sheng").slideDown(),a=1)}),t("#myForm").validate({submitHandler:function(){console.log(t("#myForm").serializeArray());var n=t("#myForm").serializeArray();n.push({name:"appid",value:h.appid});var a={};return t.each(n,function(t,n){if("words"==n.name){var r=n.value,e=r.split(/\r\n/g);a[n.name]=(0,s.default)(e)}else if("include"==n.name){var d=n.value,l=d.split(/\r\n/g);a[n.name]=(0,s.default)(l)}else if("noinclude"==n.name){var o=n.value,i=o.split(/\r\n/g);a[n.name]=(0,s.default)(i)}else a[n.name]=n.value}),c.ajaxPost(u.getApiUrl("getZhong"),a).done(function(n){console.log(n);var a=c.dateFormat(n.date,"yyyy-MM-dd hh:mm:ss"),r="",e="";n.isfinish?(r="已完成",e='<td class="rate"><span>'+r+"</span><button data-name="+n.taskname+">下载</button>"):(r="未完成",e='<td class="rate"><span>'+r+"</span><button data-name="+n.taskname+" disabled>下载</button>");var s="";s+='<tr class="lin" title="'+n.taskname+'">',s+='<td class="click" style="width: 50%;">'+a,s+="</td>",s+='<td class="down"> <span>'+r+"</span>",s+="</td>",s+=e,s+="</td>",s+="</tr>",t("#myTable .list").prepend(s)}),!1}}),t(document).on("click",".lin",function(){t(this).find("td").addClass("llin"),t(this).siblings().find("td").removeClass("llin"),alert(t(this).attr("title")),c.ajax(u.getApiUrl("getSomeTask"),{appid:h.appid,taskname:t(this).attr("title")}).done(function(n){console.log(n),t(".xiang").html(l({data:n})),t("#table").tablesorter()})}),t(document).on("click",".rate button",function(){c.formSubmit(u.getApiUrl("OutTuoCi"),{appid:h.appid,taskname:t(this).data("name")})})})}).call(n,a(83))},491:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj){__p+='<table id="myTable" class="tablesorter table">\r\n    <thead>\r\n    <tr>\r\n        <!--<th>名字</th>-->\r\n        <th>时间</th>\r\n        <th colspan=\'2\'>状态</th>\r\n    </tr>\r\n    </thead>\r\n    <tbody class="list">\r\n';for(var j=0;j<data.length;j++)__p+='\r\n<tr class="lin" title="'+(null==(__t=data[j].taskname)?"":__t)+'" style="cursor: pointer;">\r\n    <!--<td class="id" style="cursor: pointer;text-decoration: underline;color: #00afe9;"></td>-->\r\n\r\n    <td class="click"> '+(null==(__t=data[j].date)?"":__t)+'\r\n    </td>\r\n    <td class="down"> <span>'+(null==(__t=data[j].flag)?"":__t)+'</span>\r\n    </td>\r\n    <td class="rate"> <span>'+(null==(__t=data[j].flag)?"":__t)+"</span>\r\n        ",__p+=data[j].isfinish?'\r\n        <button data-name="'+(null==(__t=data[j].taskname)?"":__t)+'">下载</button>\r\n        ':'\r\n        <button data-name="'+(null==(__t=data[j].taskname)?"":__t)+'" disabled>下载</button>\r\n        ',__p+="\r\n    </td>\r\n</tr>\r\n";__p+="\r\n    </tbody>\r\n</table>\r\n\r\n\r\n\r\n"}return __p}},492:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj){__p+='<table id="table" class="tablesorter table">\r\n    <thead>\r\n    <tr>\r\n        <th>名</th>\r\n        <th>计划</th>\r\n        <th>单元</th>\r\n        <th>关键词</th>\r\n        <th>种子词</th>\r\n        <th>单价</th>\r\n        <th>来源</th>\r\n        <th>URL</th>\r\n    </tr>\r\n    </thead>\r\n    <tbody class="list">\r\n    ';for(var j=0;j<data.length;j++)__p+='\r\n    <tr>\r\n        <td class="ming">'+(null==(__t=data[j].taskname)?"":__t)+'</td>\r\n        <td class="jh">'+(null==(__t=data[j].plan)?"":__t)+'</td>\r\n        <td class="dy">'+(null==(__t=data[j].unit)?"":__t)+'</td>\r\n        <td class="gjc">'+(null==(__t=data[j].word)?"":__t)+'</td>\r\n        <td class="zzc">'+(null==(__t=data[j].seedword)?"":__t)+'</td>\r\n        <td class="dj">'+(null==(__t=data[j].recBid)?"":__t)+'</td>\r\n        <td class="ly1">'+(null==(__t=data[j].ly)?"":__t)+'</td>\r\n        <td class="url">'+(null==(__t=data[j].url)?"":__t)+"</td>\r\n    </tr>\r\n    ";__p+="\r\n    </tbody>\r\n</table>\r\n\r\n"}return __p}}});