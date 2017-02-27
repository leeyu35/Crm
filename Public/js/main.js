/**
 * Created by 侯建东 on 2017/2/27.
 */
$(document).ready(function(e) {
    $("#pagesum_btn").click(function () {
        var pageusm = $("#page_sum").val();
        if (isNaN(pageusm) || pageusm=='') {
            alert('条数不是数字');
            return false;
        } else {
            $.get("/Admin/Renew/set_page", {pagesum: pageusm}, function (data) {
                if(window.location.href.substr(window.location.href.length-5,5)=='.html')
                {
                    thref=window.location.href.length-5;
                }else
                {
                    thref=window.location.href.length;
                }
                window.location.href = window.location.href.substr(0,thref)+ "/p/1";
            }
        )
        }
    })
});