<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/18
 * Time: 10:19
 */
namespace Admin\Controller;
use Think\Controller;

class GdimgController extends Controller
{

    function index(){

        $this->display();

    }
    function upload(){

        $upload = new \Think\Upload();// 实例化上传类
        $upload->maxSize   =     3145728 ;// 设置附件上传大小
        $upload->exts      =     array('jpg', 'gif', 'png', 'jpeg');// 设置附件上传类型
        $upload->rootPath  =     './gdimg/upload/'; // 设置附件上传根目录
        $upload->savePath  =     ''; // 设置附件上传（子）目录
        $upload->autoSub   =false;
        // 上传文件
        $info   =   $upload->upload();
        if(!$info) {// 上传错误提示错误信息
            $array['code']=403;
            //$this->error($upload->getError());
        }else{// 上传成功
               // dump($info);
                $Imgpath='./gdimg/upload/'.$info['file']['savepath'].$info['file']['savename'];
        }
        dump($_POST);
        dump($_FILES);
        exit;
        $x=I('post.img_x');
        $y=I('post.img_y');
        $w=I('post.img_w');
        $h=I('post.img_h');
        $font_size=I('post.font_size');
        $string=I('post.string');
        $cimg= $this->caijian($Imgpath,$x,$y,$w,$h);
        $rimg= $this->img_save($cimg,$string,'./gdimg/MFLiHei.otf',$font_size);
        $rimg=substr($rimg,1,200);
        if($array['code']!=403){
            $array["code"]=200;
            $array["imageurl"]="http://".$_SERVER['HTTP_HOST'].$rimg;
        }


        $this->ajaxReturn($array);
        //echo '<img src="'.$rimg.'">';
    }

    function img_save($img,$str,$font,$fonpx){

        //主图片
        $images=$img;

//文字
        $string=$str;
//字体
        $font=$font;
//字号实际像素
        $fontpx=$fonpx;
//映射php字号大小
        $fonsz=$this->fontsize($fonpx);
//根据图片类型返回的图像资源
        $im=$this->imagetype($images);

//获取主图宽度高度
        list($width, $height, $type, $attr) = getimagesize($images);


//背景图片高度
        $bghigh=$fontpx*3;
//背景色图片
        $im2 = imagecreatetruecolor($width,$bghigh);

//获取字符串长度
        $txt_len=imagettfbbox($fonsz,0 , $font,$string);
        $txt_width=$txt_len[2];//获得字符串长度;
        $left_pa=($width/2)-($txt_width/2);//获取左移像素
        imagecopymerge ($im, $im2, 0, $height-($fontpx*3), 0, 0, $width,$fontpx*3, 50);
//文字颜色
        $im_str_color = imagecolorallocate($im, 255, 255, 255);
//imagestring($im,3, 150, $width-30, $string, $im_str_color);//写入文字
//写入文字

        imagettftext($im, $fonsz,0, $left_pa, $height-($fontpx*1),$im_str_color,$font,$string);

        //header("Content-type:image/png");
        $rand=rand(1000,9999);
        $imgname='./gdimg/upload/'.time().$rand.'.jpg';
        imagepng($im,$imgname);
        imagedestroy($im);
        return $imgname;

    }

//获取图片类型 和长宽高
    function imagetype($image){
        $img=getimagesize($image);

        switch ($img[2])
        {
            case 1;
                $im=imagecreatefromgif($image);
                break;
            case 2;
                $im=imagecreatefromjpeg($image);
                break;
            case 3;
                $im=imagecreatefrompng($image);
                break;
        }

        return $im;

    }

//字号对应关系
    function fontsize($sizepx){
        $array=array(
            1=>4,
            2=>5, //PPI=180
            3=>7, //PPI=168
            4=>8, //PPI=144
            5=>9, //PPI=129.6
            6=>10, //PPI=120
            7=>11, //PPI=113.14285714286
            8=>12, //PPI=108
            9=>14, //PPI=112
            10=>15, //PPI=108
            11=>16, //PPI=104.72727272727
            12=>17, //PPI=102
            13=>18, //PPI=99.692307692308
            14=>19, //PPI=97.714285714286
            15=>21, //PPI=100.8
            16=>22, //PPI=99
            17=>23, //PPI=97.411764705882
            18=>25, //PPI=100
            19=>26, //PPI=98.526315789474
            20=>27, //PPI=97.2
            21=>28, //PPI=96
            22=>29, //PPI=94.909090909091
            23=>30, //PPI=93.913043478261
            24=>32, //PPI=96
            25=>33, //PPI=95.04
            26=>34, //PPI=94.153846153846
            27=>35, //PPI=93.333333333333
            28=>36, //PPI=92.571428571429
            29=>38, //PPI=94.344827586207
            30=>39, //PPI=93.6
            31=>40, //PPI=92.903225806452
            32=>41, //PPI=92.25
            33=>43, //PPI=93.818181818182
            34=>44, //PPI=93.176470588235
            35=>46, //PPI=94.628571428571
            36=>47, //PPI=94
            37=>48, //PPI=93.405405405405
            38=>48, //PPI=90.947368421053
            39=>50, //PPI=92.307692307692
            40=>51, //PPI=91.8
            41=>52, //PPI=91.317073170732
            42=>53, //PPI=90.857142857143
            43=>55, //PPI=92.093023255814
            44=>56, //PPI=91.636363636364
            45=>57, //PPI=91.2
            46=>58, //PPI=90.782608695652
            47=>60, //PPI=91.914893617021
            48=>62, //PPI=93
            49=>63, //PPI=92.571428571429
            50=>63, //PPI=90.72
            51=>64, //PPI=90.352941176471
            52=>67, //PPI=92.769230769231
            53=>68, //PPI=92.377358490566
            54=>69, //PPI=92
            55=>70, //PPI=91.636363636364
            56=>71, //PPI=91.285714285714
            57=>72, //PPI=90.947368421053
            58=>74, //PPI=91.862068965517
            59=>75, //PPI=91.525423728814
            60=>76, //PPI=91.2
            61=>77, //PPI=90.885245901639
            62=>78, //PPI=90.58064516129
            63=>79, //PPI=90.285714285714
            64=>81, //PPI=91.125
            65=>83, //PPI=91.938461538462
            66=>84, //PPI=91.636363636364
            67=>85, //PPI=91.34328358209
            68=>86, //PPI=91.058823529412
            69=>86, //PPI=89.739130434783
            70=>88, //PPI=90.514285714286
            71=>90, //PPI=91.267605633803
            72=>91, //PPI=91
            73=>92, //PPI=90.739726027397
            74=>93, //PPI=90.486486486486
        );
        return array_keys($array,$sizepx)[0];
    }


//img_save('1.jpg','你好啊笑话','MFLiHei.otf',18);



    /*
     *
    $dst_image：新建的图片

    $src_image：需要载入的图片

    $dst_x：设定需要载入的图片在新图中的x坐标

    $dst_y：设定需要载入的图片在新图中的y坐标

    $src_x：设定载入图片要载入的区域x坐标

    $src_y：设定载入图片要载入的区域y坐标

    $dst_w：设定载入的原图的宽度（在此设置缩放）

    $dst_h：设定载入的原图的高度（在此设置缩放）

    $src_w：原图要载入的宽度

    $src_h：原图要载入的高度
     * */
//裁剪图片
    function caijian($img,$x,$y,$w,$h){
        $images=$img;

        list($width, $height) = getimagesize($images);

        $im=$this->imagetype($images);
        $image_p = imagecreatetruecolor($w,$h);
        imagecopyresampled($image_p,$im,0,0,$x,$y,$w,$h,$w,$h);
        $rand=rand(1000,9999);
        $imgname='./gdimg/upload/'.time().$rand.'.jpg';
        imagepng($image_p,$imgname);
        return $imgname;
    }


}