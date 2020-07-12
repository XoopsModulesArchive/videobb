<?php
    function errorPng($str) 
    {
        $im = imagecreatetruecolor(256,256);
        $tc  = imagecolorallocate($im, 127, 0, 0);
        imagefill($im,0,0,IMG_COLOR_TRANSPARENT);
        imagestring($im, 5, 5, 5, $str, $tc);

        return $im;
    }

    error_reporting(0);

    require_once "header.php";

    // Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    require_once "./include/config.php";
    require_once "./include/db.php";

    header("Content-type: image/png");
    header('Expires: 0');
    header('Pragma: no-cache');

    if ( !function_exists('gd_info') )
        return;

    if ( !defined('VIDEOBB_INSTALLED') )
    {
        $im = errorPng(_VB_VI_LOAD_ERROR);
        imagepng($im);
        imagedestroy($im);

        exit();
    }

    if ( (!isset($_REQUEST["folder"]) || $_REQUEST["folder"] == "" )&&
(         !isset($_REQUEST['vid']) || $_REQUEST['vid'] == ""))
    {
        $im = errorPng(_VB_VI_LOAD_ERROR);
        imagepng($im);
        imagedestroy($im);

        exit();
    }

    if ( isset($_REQUEST["folder"]) && $_REQUEST["folder"] != '' )
        $video = videobb_get_video_image($_REQUEST["folder"]);
    else
        $video = videobb_get_video_image_by_id(intval($_REQUEST["vid"]));

    if ( $video['image_by'] == 0 || !isset($video['image']) || $video['image'] == '' )
        $im = errorPng(_VB_VI_LOAD_EMPTY);
    else
        $im = imagecreatefromstring($video['image']);

    imagepng($im);
    imagedestroy($im);
?> 