webpackJsonp([4],{388:function(n,t){},0:function(n,t,r){(function(n){"use strict";r(87),r(195),r(216),r(388);var t=r(389),a=r(221),o=r(227);n(function(){a.ajax(o.getApiUrl("getDiankuanCompare")).done(function(r){console.log(r),n(".box-table-tbody").html(t({data:data}))})})}).call(t,r(84))},389:function(module,exports){module.exports=function(obj){function print(){__p+=__j.call(arguments,"")}obj||(obj={});var __t,__p="",__j=Array.prototype.join;with(obj){for(var word in data){__p+='\r\n<tr >\r\n    <td style="text-align: left">'+(null==(__t=data[word].advertiser)?"":__t)+"</td>\r\n    <td>"+(null==(__t=data[word].yue)?"":__t)+'</td>\r\n    <td style="text-align: left;" >\r\n        <div >\r\n            ';for(var num in data[word].huikuan_record)__p+='\r\n            <span style="display: inline-block;text-align: center">\r\n                <i>('+(null==(__t=data[word].huikuan_record[num].money)?"":__t)+")</i></br>\r\n                <i>"+(null==(__t=data[word].huikuan_record[num].payment_time)?"":__t)+"</i>\r\n            </span>\r\n            ";__p+="\r\n        </div>\r\n    </td>\r\n</tr>\r\n"}__p+="\r\n"}return __p}}});