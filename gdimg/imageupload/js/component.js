
var resizeableImage = function(image_target) {
  var $container,
      orig_src = new Image(),
      image_target = $(image_target).get(0),
      event_state = {},
      dataURL = "",
      constrain = false,
      min_width = 60,
      min_height = 60,
      max_width = 10000,
      max_height = 10000,
      resize_canvas = document.createElement('canvas');

  init = function(){
    orig_src.src=image_target.src;
    $(image_target).wrap('<div class="resize-container"></div>')
        .before('<span class="resize-handle resize-handle-nw"></span>')
        .before('<span class="resize-handle resize-handle-ne"></span>')
        .after('<span class="resize-handle resize-handle-se"></span>')
        .after('<span class="resize-handle resize-handle-sw"></span>');
    $container =  $(image_target).parent('.resize-container');

    // Add events
    $container.on('mousedown touchstart', '.resize-handle', startResize);
    $container.on('mousedown touchstart', 'img', startMoving);
    $('.js-crop').on('click', crop);
    $(".close").on('click',close);
  };

  startResize = function(e){
    e.preventDefault();
    e.stopPropagation();
    saveEventState(e);
    $(document).on('mousemove touchmove', resizing);
    $(document).on('mouseup touchend', endResize);
  };

  endResize = function(e){
    e.preventDefault();
    $(document).off('mouseup touchend', endResize);
    $(document).off('mousemove touchmove', resizing);
  };

  saveEventState = function(e){
    event_state.container_width = $container.width();
    event_state.container_height = $container.height();
    event_state.container_left = $container.offset().left;
    event_state.container_top = $container.offset().top;
    event_state.mouse_x = (e.clientX || e.pageX || e.originalEvent.touches[0].clientX) + $(window).scrollLeft();
    event_state.mouse_y = (e.clientY || e.pageY || e.originalEvent.touches[0].clientY) + $(window).scrollTop();
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
      if(constrain || e.shiftKey){
        top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
      }
    } else if($(event_state.evnt.target).hasClass('resize-handle-ne') ){
      width = mouse.x - event_state.container_left;
      height = event_state.container_height - (mouse.y - event_state.container_top);
      left = event_state.container_left;
      top = mouse.y;
      if(constrain || e.shiftKey){
        top = mouse.y - ((width / orig_src.width * orig_src.height) - height);
      }
    }

    // Optionally maintain aspect ratio
    if(constrain || e.shiftKey){
      height = width / orig_src.width * orig_src.height;
    }

    if(width > min_width && height > min_height && width < max_width && height < max_height){
      resizeImage(width, height);
      $container.offset({'left': left, 'top': top});
    }
  }

  resizeImage = function(width, height){
    resize_canvas.width = width;
    resize_canvas.height = height;
    resize_canvas.getContext('2d').drawImage(orig_src, 0, 0, width, height);
    dataURL = resize_canvas.toDataURL("image/png");
    $(image_target).attr('src', dataURL);
  };

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

    if(event_state.touches && event_state.touches.length > 1 && touches.length > 1){
      var width = event_state.container_width, height = event_state.container_height;
      var a = event_state.touches[0].clientX - event_state.touches[1].clientX;
      a = a * a;
      var b = event_state.touches[0].clientY - event_state.touches[1].clientY;
      b = b * b;
      var dist1 = Math.sqrt( a + b );

      a = e.originalEvent.touches[0].clientX - touches[1].clientX;
      a = a * a;
      b = e.originalEvent.touches[0].clientY - touches[1].clientY;
      b = b * b;
      var dist2 = Math.sqrt( a + b );

      var ratio = dist2 /dist1;

      width = width * ratio;
      height = height * ratio;

      resizeImage(width, height);
    }
  };

  crop = function(){
    $(".box").fadeIn(1000)
    var crop_canvas,
        left = $('.overlay').offset().left - $container.offset().left,
        top =  $('.overlay').offset().top - $container.offset().top,
        width = $('.overlay').width(),
        height = $('.overlay').height();

    crop_canvas = document.createElement('canvas');
    crop_canvas.width = width;
    crop_canvas.height = height;

    crop_canvas.getContext('2d').drawImage(image_target, left, top, width, height, 0, 0, width, height);

    $(".box_img").attr("src",crop_canvas.toDataURL("image/png"));

  }
  close = function(){
    $(".box").fadeOut()
  }
  init();
};

//图片显示
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
      $('#other').attr('src',urlData);
      if($(".resize-image").attr("src") !="" ){
        resizeableImage($('.resize-image'));

      }
    };
  }
}
//选择大小
$(".a-tip").on("click","button",function(){
  var b = $(this).text();
  switch (b){
    case "644*280px":{
      $(".overlay").css({width:644,height:280,marginLeft:0,left:140});
      $(".box").css({width:700,height:300});
    }
      break;
    case "600*248px" :{
      $(".overlay").css({width:600,height:248,marginLeft:0,left:120});
      $(".box").css({width:700,height:300});
    }
      break;
    case "220*220px" :{
      $(".overlay").css({width:220,height:220,marginLeft:300,left:0});
      $(".box").css({width:300,height:300});
    }
      break;
    case "190*190px" :{
      $(".overlay").css({width:190,height:190,marginLeft:300,left:0});
      $(".box").css({width:250,height:250});
    }
      break;
  }
})

function btn(){
    //获取图片地址
    var urlData = $(".box_img").attr("src");//图片信息
      // console.log(urlData)
    var text = $(".text").val();//上传文字
    console.log(text)
    var row = $(".row").val();//行数
    console.log(row)
    var font_size = $("#set option:selected").val();//字体大小
    console.log(set)
    var font = $("#font option:selected").val();//字体样式
  console.log(font)
     $.ajax({
       type:'POST',
       url: 'http://http://192.168.199.153//Admin/Gdimg/upload_ajax.html',
       data: {
         file: urlData,
         font_size: font_size,
         string:text,
         line:row,
         color:"255,255,255",
         font:font,
         logo:""
       },
       async: true,
       dataType: 'json',
       success: function(data){
         if(data.code == 200){
           $(".box_img").attr({src:data.imageurl})
           $(".put").attr({href:data.imageurl})
         }else{
           alert('上传失败');
         }
       },
       error: function(err){

         alert('网络故障');

       }

     });
}
$(".fonts_color").click(function(){
  $(".show").fadeIn();
})