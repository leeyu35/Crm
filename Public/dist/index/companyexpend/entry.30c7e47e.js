webpackJsonp([5],{396:function(t,a,n){"use strict";var i=n(238);n(337),n(372),n(376),n(377);var o={renderLine:function(t,a,n,o){var e=i.init(document.getElementById(t)),r={tooltip:{trigger:"axis"},legend:{show:!1,data:["消耗"]},toolbox:{feature:{saveAsImage:{}}},xAxis:{type:"category",boundaryGap:!1,axisLabel:{show:!0,textStyle:{color:"#FOFOFO"}},axisLine:{lineStyle:{color:"#FOFOFO",width:1}},data:a},yAxis:{type:"value",name:"(元)",splitLine:{show:!0,lineStyle:{type:"dotted"}}},series:[{name:"消耗",type:"line",stack:"总量",lineStyle:{normal:{color:o}},data:n}]};e.setOption(r),window.onresize=e.resize}};t.exports=o},397:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj)for(var i in data){__p+='\r\n<div class="col-sm-4">\r\n    <div class="hetong">合同负责人：<span>'+(null==(__t=data[i].market)?"":__t)+'</span></div>\r\n    <div class="col-sm-12 identifier">\r\n        <div class="col-sm-8">\r\n            合同编号：<span>'+(null==(__t=data[i].contract_no)?"":__t)+'</span>\r\n        </div>\r\n        <div class="col-sm-4" style="text-align: right;">\r\n            余额：<span>'+(null==(__t=data[i].yu_e)?"":__t)+'</span>\r\n        </div>\r\n    </div>\r\n    <div>\r\n        <table class="table-hover tablesorter table table-bordered">\r\n            <thead>\r\n            <tr>\r\n                <th>账户名称</th>\r\n                <th>'+(null==(__t=data[i].ming)?"":__t)+"</th>\r\n                <th>账户负责人</th>\r\n            </tr>\r\n            </thead>\r\n            <tbody>\r\n            ";for(var j in data[i].accountlist)__p+="\r\n            <tr>\r\n                <td>"+(null==(__t=data[i].accountlist[j].a_users)?"":__t)+'</td>\r\n                <td class="alter">'+(null==(__t=data[i].accountlist[j].xh)?"":__t)+"</td>\r\n                <td>"+(null==(__t=data[i].accountlist[j].semname)?"":__t)+"</td>\r\n            </tr>\r\n            ";__p+="\r\n            </tbody>\r\n        </table>\r\n\r\n    </div>\r\n</div>\r\n"}return __p}},395:function(t,a){},0:function(t,a,n){(function(t){"use strict";function a(t,a,n){i.ajax(o.getApiUrl("getcustomer_date_counsumption_line"),{id:t,type:a}).done(function(t){for(var a=[],i=[],o=t.data,r=n,c=0;c<o.length;c++)a.push(o[c].date),i.push(o[c].consumption);e.renderLine("main",a,i,r),console.log(t)})}n(87),n(195),n(216),n(395);var i=n(221),o=n(227),e=n(396),r=n(397),c=location.href.substring(location.href.indexOf("?")+1,location.href.indexOf("=")),s=location.href.substring(location.href.indexOf("=")+1),l=location.href.substring(location.href.indexOf("=")+1,location.href.indexOf("-")),d=location.href.substring(location.href.indexOf("-")+1),h=void 0;t(function(){var n=(i.getCookie("u_id"),"day"),e="#e78074";a(c,n,e),"zuorixiaohao"==l?t(".back").text("  昨日消耗/"):"benzhouxiaohao"==l?t(".back").text("  本周消耗/"):"benyuexiaohao"==l?t(".back").text("  本月消耗/"):"shangyuexiaohao"==l&&t(".back").text("  上月消耗/"),"diankuan"==s&&(t(".back").text(" 垫款总额/"),t(".back").click(function(){window.history.back()}),t(".first").attr("href","../boss/page.html")),"boss"==d?(t(".back").click(function(){window.history.back()}),t(".first").attr("href","../boss/page.html")):"market"==d&&(t(".first").attr("href","../market/page.html"),t(".back").click(function(){window.history.back()})),"boss"==s?(t(".back").hide(),t(".first").attr("href","../boss/page.html")):"market"==s&&(t(".back").hide(),t(".first").attr("href","../market/page.html")),"backmoneyDay"==s?(t(".back").text("  今日收款/"),t(".back").click(function(){window.history.back()}),t(".first").attr("href","../boss/page.html")):"backmoneyMonth"==s?(t(".back").text("  本月收款/"),t(".back").click(function(){window.history.back()}),t(".first").attr("href","../boss/page.html")):"fukuanDay"==s?(t(".back").text("  今日付款/"),t(".back").click(function(){window.history.back()}),t(".first").attr("href","../boss/page.html")):"fukuanMonth"==s?(t(".back").text("  本月付款/"),t(".back").click(function(){window.history.back()}),t(".first").attr("href","../boss/page.html")):"bukuanMonth"==s&&(t(".back").text("  总部款/"),t(".back").click(function(){window.history.back()}),t(".first").attr("href","../boss/page.html")),t(".xiaohao1,.xiaohao2,.xiaohao3").click(function(){if("xiaohao1"==t(this).attr("class")){a(c,"day","#e78074");for(var n=0;n<h.data.length;n++){h.data[n].ming="日消耗";for(var i=0;i<h.data[n].accountlist.length;i++)h.data[n].accountlist[i].xh=h.data[n].accountlist[i].yesterday}t(".contract").html(r({data:h.data}))}else if("xiaohao2"==t(this).attr("class")){a(c,"week","#62b49d");for(var o=0;o<h.data.length;o++){h.data[o].ming="周消耗";for(var e=0;e<h.data[o].accountlist.length;e++)h.data[o].accountlist[e].xh=h.data[o].accountlist[e].week}t(".contract").html(r({data:h.data}))}else if("xiaohao3"==t(this).attr("class")){a(c,"month","#63b5ca");for(var s=0;s<h.data.length;s++){h.data[s].ming="月消耗";for(var l=0;l<h.data[s].accountlist.length;l++)h.data[s].accountlist[l].xh=h.data[s].accountlist[l].month}t(".contract").html(r({data:h.data}))}t(this).siblings().find(".xiaohao1-2").css("visibility","hidden"),t(this).find(".xiaohao1-2").css("visibility","visible")}),i.ajax(o.getApiUrl("getcustomer_info"),{id:c}).done(function(a){t(".company").text(a.data.advertiser),t(".comname").text(a.data.advertiser),t(".appname").text(a.data.appname),t(".website").text(a.data.website),t(".industry").text(a.data.industry),t(".city").text(a.data.city),console.log(a)}),i.ajax(o.getApiUrl("getcompany_contract_list"),{id:c}).done(function(a){console.log(a),h=a;for(var n=0;n<h.data.length;n++){h.data[n].ming="日消耗";for(var i=0;i<h.data[n].accountlist.length;i++)h.data[n].accountlist[i].xh=h.data[n].accountlist[i].yesterday}t(".contract").html(r({data:h.data}))})})}).call(a,n(84))}});