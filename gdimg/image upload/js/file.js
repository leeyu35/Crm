/**
 * Created by dell on 2016/11/18.
 */
    //监听input标签是否上传了照片，如果发生变化就执行后面的代码
var urlData = "";
    function file(){

        document.getElementById('img').onchange = function(){
            //获取上传的照片
            var resultFile = document.getElementById("img").files[0];
            var reader = new FileReader();
            //调用这个方法，并且将获取到的文件传入
            reader.readAsDataURL(resultFile);
            //要想显示这个照片 必须要在这边调用它的onload方法
            reader.onload = function (e) {
                urlData = this.result;
                a = urlData;
                document.getElementById('other').setAttribute('src',urlData);
                //调用img
                img();
            };
        }
    }
    file()
    //上传图片
    function img(){
        var api = $.Jcrop('.cropbox',{
            allowResize:false,
            onChange: showCoords,
            onSelect: showCoords,
            allowSelect:false
        });
        var i, ac;
        function nothing(e)
        {
            e.stopPropagation();
            e.preventDefault();
            return false;
        };
        function anim_handler(ac)
        {
            return function(e) {
                api.animateTo(ac);
                return nothing(e);
            };
        };
        var ac =
        {
            anim1: [0,0,644,280],
            anim2: [0,0,600,248],
            anim3: [0,0,190,190],
            anim4: [0,0,220,220]
        };

        for(i in ac) jQuery('#'+i).click(anim_handler(ac[i]));
        function showCoords(c) {
            jQuery('#x').val(c.x);
            jQuery('#y').val(c.y);
            jQuery('#x2').val(c.x2);
            jQuery('#y2').val(c.y2);
            jQuery('#w').val(c.w);
            jQuery('#h').val(c.h);

        };
    }
//ajax 上传数据
    function btn(){
        if($(".cropbox").attr("src") == ""){
            alert("请选择图片")
        }else{
            //获取图片坐标
            var img_w = $("#w").val(),
                img_h = $("#h").val();
            var img_x = $("#x").val(),
                img_y = $("#y").val(),
            input = $(".font_learn").val();
            console.log(urlData);
            console.log(img_w);
            console.log(img_h);
            console.log(img_x);
            console.log(img_y);
            console.log(input);
            $.ajax({
                type:'POST',

                url: 'http://c.lzad.cc/Admin/Gdimg/upload.html',
                data: {
                    file: urlData,
                    img_x: img_x,
                    img_y:img_y,
                    font_size:"13px",
                    string:input
                },
                //async: false,

                //dataType: 'json',

                success: function(data){


                    console.log(data);

                },
                error: function(err){

                    alert('网络故障');

                }

            });
        }

    }

//点击下载 判断href是否为空
$(".down").click(function(event){
    if($(this).attr("href") == ""){
        $(".mistakes").show();
        return false;
    }else{
        $(".mistakes").hide();
    }
})