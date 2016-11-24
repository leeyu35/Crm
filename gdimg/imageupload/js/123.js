/*if(window.location.protocol == 'file:'){
 alert('To test this demo properly please use a local server such as XAMPP or WAMP. See README.md for more details.');
 }*/

var resizeableImage = function(image_target) {
    // 设置变量
    var $container,
        orig_src = new Image(),
        image_target = $(image_target).get(0), //获取页面图片
        event_state = {},
        constrain = false,
        imgURL = "",
        min_width = 60,
        min_height = 60,
        max_width = 1980,
        max_height = 1980,
        resize_canvas = document.createElement('canvas');//创建对象

    init = function(){

        // 获取图=图片的src
        orig_src.src=image_target.src;

        //给图片添加span标签
        $(image_target).wrap('<div class="resize-container"></div>')    //图片包裹div
            .before('<span class="resize-handle resize-handle-nw"></span>')
            .before('<span class="resize-handle resize-handle-ne"></span>')
            .after('<span class="resize-handle resize-handle-se"></span>')
            .after('<span class="resize-handle resize-handle-sw"></span>');

        $container =  $(image_target).parent('.resize-container'); //获取图片的父元素

        $container.on('mousedown', '.resize-handle', startResize);
        $container.on('mousedown', 'img', startMoving);
        $('.js-crop').on('click', crop);
        $('.js-data').on('click',file);
    };
//调用startResize 图片添加事件
    startResize = function(e){
        e.preventDefault(); //取消默认事件
        e.stopPropagation(); //阻止事件冒泡
        saveEventState(e);
        $(document).on('mousemove', resizing); //移动时
        $(document).on("mousewheel",zoom);
        $(document).on('mouseup', endResize);  //抬起时阻止
    };

    endResize = function(e){
        e.preventDefault();
        $(document).off('mouseup touchend', endResize);
        $(document).off('mousemove touchmove', resizing);
    };

    saveEventState = function(e){
        event_state.container_width = $container.width(); // 获取图片父元素div的宽
        event_state.container_height = $container.height(); //获取图片父元素div的高
        event_state.container_left = $container.offset().left;
        event_state.container_top = $container.offset().top;
        event_state.mouse_x = (e.clientX || e.pageX) + $(window).scrollLeft();
        event_state.mouse_y = (e.clientY || e.pageY) + $(window).scrollTop();

        // This is a fix for mobile safari
        // For some reason it does not allow a direct copy of the touches property
        if(typeof e.originalEvent.touches !== 'undefined'){
            event_state.touches = [];
            $.each(e.originalEvent.touches, function(i, ob){
                event_state.touches[i] = {};
                event_state.touches[i].clientX = 0+ob.clientX;
                event_state.touches[i].clientY = 0+ob.clientY;
            });
        }
        event_state.evnt = e;
    };

    resizing = function(e){
        var mouse={},width,height,left,top,offset=$container.offset();
        mouse.x = (e.clientX || e.pageX || e.originalEvent.touches[0].clientX) + $(window).scrollLeft();
        mouse.y = (e.clientY || e.pageY || e.originalEvent.touches[0].clientY) + $(window).scrollTop();
        console.log(e.clientY)
        console.log(e.clientX)


        if( $(event_state.evnt.target).hasClass('resize-handle-se') ){
          width = mouse.x - event_state.container_left;
          height = mouse.y  - event_state.container_top;
          left = event_state.container_left;
          top = event_state.container_top;
        } else if($(event_state.evnt.target).hasClass('resize-handle-sw') ){
          width = event_state.container_width - (mouse.x - event_state.container_left);
          height = mouse.y  - event_state.container_top;
          left = mouse.x;
          top = event_state.container_top;
        } else if($(event_state.evnt.target).hasClass('resize-handle-nw') ){
          width = event_state.container_width - (mouse.x - event_state.container_left);
          height = event_state.container_height - (mouse.y - event_state.container_top);
          left = mouse.x;
          top = mouse.y;
          if(constrain){
            top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
          }
        } else if($(event_state.evnt.target).hasClass('resize-handle-ne') ){
          width = mouse.x - event_state.container_left;
          height = event_state.container_height - (mouse.y - event_state.container_top);
          left = event_state.container_left;
          top = mouse.y;
          if(constrain ){
            top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
          }
        }

        // 选择长宽比
        if(constrain ){
          height = width / orig_src.width * orig_src.height;
        }

        if(width > min_width && height > min_height && width < max_width && height < max_height){
          resizeImage(width, height);

          $container.offset({'left': left, 'top': top});
        }
    }

    resizeImage = function(){
    //  resize_canvas.width = width;
    //  resize_canvas.height = height;
    //  console.log(width)
    //  console.log(height)
    //console.log(image_target)
      var width = $(".overlay").css("width");
      var height = $(".overlay").css("height");
      resize_canvas.getContext('2d').drawImage(image_target, 0, 0, width, height);

      $(image_target).attr('src', resize_canvas.toDataURL("image/png"));
    };
    resizeImage()
    startMoving = function(e){
        e.preventDefault();
        e.stopPropagation();
        saveEventState(e);
        $(document).on('mousemove touchmove', moving);
        $(document).on('mouseup touchend', endMoving);
    };

    endMoving = function(e){
        e.preventDefault();
        $(document).off('mouseup touchend', endMoving);
        $(document).off('mousemove touchmove', moving);
    };
//移动
    moving = function(e){
        var  mouse={}, touches;
        e.preventDefault();
        e.stopPropagation();

        touches = e.originalEvent.touches;

        mouse.x = (e.clientX || e.pageX || touches[0].clientX) + $(window).scrollLeft();
        mouse.y = (e.clientY || e.pageY || touches[0].clientY) + $(window).scrollTop();
        $container.offset({
            'left': mouse.x - ( event_state.mouse_x - event_state.container_left ),
            'top': mouse.y - ( event_state.mouse_y - event_state.container_top )
        });
    };
    //
    crop = function(){
        //找到图片部分作为一个盒子
        var crop_canvas,
            left = $('.overlay').offset().left - $container.offset().left,
            top =  $('.overlay').offset().top - $container.offset().top,
            width = $('.overlay').width(),
            height = $('.overlay').height();

        crop_canvas = document.createElement('canvas');
        crop_canvas.width = width;
        crop_canvas.height = height;
        crop_canvas.getContext('2d').drawImage(image_target, left, top, width, height, 0, 0, width, height);
        window.open(crop_canvas.toDataURL("image/png"));
    }
    file = function (){
        var urlData = "";
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
            };
        }
    }

    $(".a-tip").on("click","button",function(){
        var b = $(this).text();
        switch (b){
            case "644*280px":{
                $(".overlay").css({width:644,height:280,marginLeft:0,left:140});
            }
                break;
            case "600*248px" :{
                $(".overlay").css({width:600,height:248,marginLeft:0,left:120});
            }
                break;
            case "220*220px" :{
                $(".overlay").css({width:220,height:220,marginLeft:300,left:0});
            }
                break;
            case "190*190px" :{
                $(".overlay").css({width:190,height:190,marginLeft:300,left:0})
            }
                break;
        }
    })
    //调用
    init();
};
//将图片出入到函数内部
resizeableImage($('.resize-image'));