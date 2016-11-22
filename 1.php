<?php
/**
 * Created by PhpStorm.
 * User: 侯建东
 * Date: 2016/11/22
 * Time: 10:41

 */
 phpinfo();


$im = new imagick( 'a.jpg' );
// resize by 200 width and keep the ratio
$im->thumbnailImage( 200, 0);
// write to disk
$im->writeImage( 'a_thumbnail.jpg' );
