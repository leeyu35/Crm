webpackJsonp([13],{545:function(t,e){},0:function(t,e,o){(function(t){"use strict";o(87),o(195),o(216),o(545);var e=o(546),a=o(221),n=o(227);t(function(){function o(){for(var e=0,o=0;o<t(".progress-width").length;o++)o>=1&&(e=parseInt(t(".progress-width").eq(o).parent("td").find(".many").text())*parseInt(t(".progress-width").eq(0).css("width"))/parseInt(t(".progress-width").eq(0).parent("td").find(".many").text()),console.log(o+":"+parseInt(t(".progress-width").eq(o).parent("td").find(".many").text())),console.log(o+":"+parseInt(parseInt(t(".progress-width").eq(0).css("width")))),console.log(o+":"+parseInt(t(".progress-width").eq(0).parent("td").find(".many").text())),t(".progress-width").eq(o).css("width",e+"px"))}window.onresize=function(t){o()};var r="",s=location.href.substring(location.href.indexOf("?")+1,location.href.indexOf("=")),i=location.href.substring(location.href.indexOf("=")+1);"yesterday"==s?(t(".box-table-title").text("昨日消耗"),t(".xiaohao-titl").text("昨日消耗")):"week"==s?(t(".box-table-title").text("本周消耗"),t(".xiaohao-titl").text("本周消耗")):"month"==s?(t(".xiaohao-titl").text("本月消耗"),t(".box-table-title").text("本月消耗")):"smonth"==s&&(t(".xiaohao-titl").text("上月消耗"),t(".box-table-title").text("上月消耗")),t(".first-page").click(function(){window.history.back()}),a.ajax(n.getApiUrl("getSpecifyDateCounsumptionList"),{type:s}).done(function(a){console.log(a);var n=a.data;"昨日消耗"==t(".box-table-title").text()&&"boss"==i?r="zuorixiaohao-boss":"本周消耗"==t(".box-table-title").text()&&"boss"==i?r="benzhouxiaohao-boss":"本月消耗"==t(".box-table-title").text()&&"boss"==i?r="benyuexiaohao-boss":"上月消耗"==t(".box-table-title").text()&&"boss"==i&&(r="shangyuexiaohao-boss"),"昨日消耗"==t(".box-table-title").text()&&"boss"!=i?r="zuorixiaohao-market":"本周消耗"==t(".box-table-title").text()&&"boss"!=i?r="benzhouxiaohao-market":"本月消耗"==t(".box-table-title").text()&&"boss"!=i?r="benyuexiaohao-market":"上月消耗"==t(".box-table-title").text()&&"boss"!=i&&(r="shangyuexiaohao-market"),t(".box-table-tbody").html(e({data:n,time:r})),o();for(var s=0;s<t("td").length;s++)""==t("td").eq(s).text()&&(t("td").eq(s).text("暂无信息"),t("td").eq(s).css("color","orangered"))})})}).call(e,o(84))},546:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj){for(var word in data)__p+='\r\n<tr >\r\n    <td style="text-align: left"><a href="../zhanghuxiaohao/page.html?'+(null==(__t=data[word].account_id)?"":__t)+"="+(null==(__t=time)?"":__t)+'">'+(null==(__t=data[word].a_users)?"":__t)+"</a></td>\r\n    <td>"+(null==(__t=data[word].appname)?"":__t)+'</td>\r\n    <td style="text-align: left; text-indent: 20px;" ><a style="text-decoration: none" href="../companyexpend/page.html?'+(null==(__t=data[word].avid)?"":__t)+"="+(null==(__t=time)?"":__t)+'">'+(null==(__t=data[word].advertiser)?"":__t)+'</a></td>\r\n    <td class="progress-td" style="position:relative;">\r\n        <div class="progressed  progress-width"></div>\r\n        <span>&nbsp;&nbsp;&nbsp;￥<i class="many">'+(null==(__t=data[word].baidu_cost_total)?"":__t)+"</i></span>\r\n    </td>\r\n    <td>"+(null==(__t=data[word].sem)?"":__t)+"</td>\r\n    <td>"+(null==(__t=data[word].market)?"":__t)+"</td>\r\n</tr>\r\n";__p+="\r\n"}return __p}}});