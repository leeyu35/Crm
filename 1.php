<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/22
 * Time: 10:41

 */

 ini_set('display_errors',1);
 header('Content-type: image/jpeg');
 $image = new Imagick('1.jpg');
 $image->rollImage(20,39);
 echo $image;
