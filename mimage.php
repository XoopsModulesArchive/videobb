<?php
//  ------------------------------------------------------------------------ //
//                         VideoBB module for XOOPS                          //
//                    Copyright (c) 2004-2005 Kutovoy Nickolay               //
//                           <kutovoy@gmail.com>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
    require_once "header.php";
    require_once XOOPS_ROOT_PATH."/header.php";
    require_once XOOPS_ROOT_PATH.'/class/uploader.php';

    // Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    require_once "./include/config.php";
    require_once "./include/db.php";
    require_once "./include/tools.php";

    if ( !defined('VIDEOBB_INSTALLED') )
    {
        die(_VBB_WRONG_INSTALL);
        exit;
    }

    // Set up script name
    $php_self_link = XOOPS_URL."$vbb_path/".videobb_php_self();

    // Prepare users uid
    $xoops_admin = false;
    if ( is_object($xoopsUser) ) 
    {
        $xoops_uid   = $xoopsUser->getVar('uid');
        $xoops_uname = $xoopsUser->getVar('uname');
        if ( $xoopsUser->isAdmin($xoopsModule->mid()) )
            $xoops_admin = true;
    }
    else
    {
        $xoops_uid   = 0;
        $xoops_uname = _VB_GUEST_NAME;
    }

    // Log some data if enabled
    if ( videobb_get_option("_VB_ADM_ACCESS_LOG") )
    {
        videobb_logger();
    }


    // Save requested params
    $url_params = "";
    if ( isset($_REQUEST["rid"]) && $_REQUEST["rid"] != "" )
        $url_params .= "rid=".$_REQUEST["rid"]."&";
    if ( isset($_REQUEST["vid"]) && $_REQUEST["vid"] != "" )
        $url_params .= "vid=".$_REQUEST["vid"]."&";


    // Token (referers) security check
    if ( $GLOBALS['xoopsSecurity']->check() == false && ( isset($_FILES['img_file'] ) ) )
    {
        redirect_header("$php_self_link?$url_params", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

        exit();
    }

    if ( !isset($_REQUEST['vid']) || $_REQUEST['vid'] == "" 
         ||
         ( !$xoops_admin && !in_array($xoops_uid,explode(';',videobb_get_option("_VB_ADM_VIM_ADMINS"))) )
         )
    {
        redirect_header("index.php",3,_VB_WRONG_REQUEST);

        exit();
    }

    // Block Guest video info modifications
    if ( $xoops_uid == 0 )
    {
        redirect_header("index.php?$url_params",3,_VB_GUEST_CANNOT_1."<a href=\"".XOOPS_URL."/register.php\">"._VB_GUEST_CANNOT_2."</a>"._VB_GUEST_CANNOT_3);

        exit();
    }

    $myts =& MyTextSanitizer::getInstance();

    $video = videobb_get_video_by_id(intval($_REQUEST['vid']));
    if ( !isset($video) || $video == false )
    {
        redirect_header("$php_self_link?$url_params",3,_VB_WRONG_REQUEST);

        exit();
    }

    // Some restrictions
    $maxfilesize = videobb_get_option("_VB_ADM_UPL_IMG_MAX_SIZE");
    if ( $maxfilesize == '' )
        $maxfilesize = 30000;
    $mfw = videobb_get_option("_VB_ADM_UPL_IMG_MAX_X");
    if ( $mfw == '' )
        $mfw = 256;
    $mfh = videobb_get_option("_VB_ADM_UPL_IMG_MAX_Y");
    if ( $mfh == '' )
        $mfh = 256;

    $images_enabled = videobb_get_option("_VB_ADM_USE_IMAGES");

    if ( function_exists('gd_info') )
    {
        $gd_info = gd_info();
        if ( !$gd_info["PNG Support"] )
            $images_enabled = false;
    }
    else
        $images_enabled = false;

    if ( $images_enabled )
    {
        // Some restrictions
        if ( $gd_info["GIF Read Support"] )
            $allowed_mimetypes[] = 'image/gif';
        if ( $gd_info["JPG Support"] )
        {
            $allowed_mimetypes[] = 'image/jpeg';
            $allowed_mimetypes[] = 'image/pjpeg';
        }
        $allowed_mimetypes[] = 'image/png';
        $allowed_mimetypes[] = 'image/x-png';
    }

    if ( !isset($_REQUEST['m']) || ( $_REQUEST['m'] != 1 && $_REQUEST['m'] != 2 ) )
    {
        echo "<table>";
        echo "<tr><th colspan=\"2\" align=\"center\">".$video['name']."</th></tr>";
        echo "<tr><td colspan=\"2\" align=\"center\"><img src=\"vimage.php?$url_params\">";
        if ( $video['image_by'] != 0 )
            echo "<br>"._VB_VI_UPLOADED_BY." ".xoops_getLinkedUnameFromId($video['image_by']);
        echo "</td></tr>";
        echo "<tr><th colspan=\"2\" align=\"center\">"._VB_MANAGE_VI."</th></tr>";
   
        if ( $images_enabled )
        {
            echo "<tr><th>"._VB_VI_UPLOAD_INFO."</th><th>"._VB_VI_DELETE_INFO."</th></tr>";

            echo "<tr><td>";
            echo "<form enctype=\"multipart/form-data\" action=\"$php_self_link?m=1&$url_params\" method=\"POST\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<input type=\"hidden\" name=\"MAX_FILE_SIZE\" value=\"$maxfilesize\" />";
            echo "<input size=\"32\" name=\"img_file\" type=\"file\"/>";
            echo "<br><input type=\"submit\" value=\""._VB_VI_UPLOAD."\"/>";
            echo "</form>";
            echo "<small><table>";
            echo "<tr><td>"._VB_ADM_UPL_IMG_MAX_SIZE.":</td><td><b>$maxfilesize</b></td></tr>";
            echo "<tr><td>"._VB_ADM_UPL_IMG_MAX_X.":</td><td><b>$mfw</b></td></tr>";
            echo "<tr><td>"._VB_ADM_UPL_IMG_MAX_Y.":</td><td><b>$mfh</b></td></tr>";
            echo "<tr><td>"._VB_VI_ALLOWED_TYPES.":</td><td>";
            foreach ( $allowed_mimetypes as $t )
                echo "$t<br>";
            echo "</td></tr>";
            echo "</table></small>";

            echo "</td><td>";

            echo "<form action=\"$php_self_link?m=2&$url_params\" method=\"POST\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<br><input type=\"submit\" value=\""._VB_VI_DELETE."\"/>";
            echo "</form>";

            echo "</td></tr>";
        }
        else
        {
            echo "<tr><td>";
            if ( !videobb_get_option("_VB_ADM_USE_IMAGES") )
                echo _VB_VI_DISABLED;
            else
                echo _VB_VI_NOT_GDLIB_IN_PHP;

            echo "</td></tr>";
        }

        echo "</table>";

        require_once XOOPS_ROOT_PATH.'/footer.php';
        exit();
    }

    if ( !$images_enabled )
    {
        echo "<table><tr><th>";
        if ( !videobb_get_option("_VB_ADM_USE_IMAGES") )
            echo _VB_VI_DISABLED;
        else
            echo _VB_VI_NOT_GDLIB_IN_PHP;

        echo "</th></tr></table>";

        require_once XOOPS_ROOT_PATH.'/footer.php';
        exit();
    }


    if ( isset($_REQUEST['m']) && $_REQUEST['m'] == 1 )
    {
        $uploader = new XoopsMediaUploader('./upload', $allowed_mimetypes, $maxfilesize );
        if ( $uploader->fetchMedia('img_file') && $uploader->upload() )
        {
            if ( strstr($uploader->getMediaType(),"png") )
                $ims = imagecreatefrompng($uploader->getSavedDestination());
            else if ( strstr($uploader->getMediaType(),"gif") )
                $ims = imagecreatefromgif($uploader->getSavedDestination());
            else if ( strstr($uploader->getMediaType(),"jpg") || strstr($uploader->getMediaType(),"jpeg"))
                $ims = imagecreatefromjpeg($uploader->getSavedDestination());
            else if ( strstr($uploader->getMediaType(),"bmp") )
                $ims = imagecreatefromwbmp($uploader->getSavedDestination());
            else
            {
                redirect_header("$php_self_link?$url_params",5,_VB_VI_UPLOAD_WRONG_FORMAT);

                exit();
            }

            $converted = false;
            if ( $ims )
            {
                $sw = imagesx($ims);
                $sh = imagesy($ims);

                $tw = $sw;
                $th = $sh;
                $tl = $tt = 0;

                if ( videobb_get_option("_VB_ADM_UPL_IMG_STRETCH_IF_LT") )
                {
                    if ( $tw < $mfw )
                        $tw = $mfw;
                    if ( $th < $mfh )
                        $th = $mfh;
                }

                if ( videobb_get_option("_VB_ADM_UPL_IMG_STRETCH_IF_GT") )
                {
                    if ( $tw > $mfw )
                        $tw = $mfw;
                    if ( $th > $mfh )
                        $th = $mfh;
                }                    

                if ( videobb_get_option("_VB_ADM_UPL_IMG_CENTER") )
                {
                    if ( $mfw > $tw )
                        $tl = ( $mfw - $tw ) / 2;
                    else if ( $mfw <= $tw )
                        $tl = ( $tw - $mfw ) / 2;

                    if ( $mfh > $th )
                        $tt = ( $mfh - $th ) / 2;
                    else if ( $mfh <= $th )
                        $tt = ( $th - $mfh ) / 2;
                  }

                $imt = imagecreatetruecolor($mfw,$mfh);
                imagefill($imt,0,0,IMG_COLOR_TRANSPARENT);

                if ( $imt )
                {
                    if ( imagecopyresized ( $imt, $ims, $tl, $tt, 0,0, $tw, $th, $sw, $sh) )
                    {
                        $tfname = "./upload/".uniqid("uimg_").".png";
                        if ( imagepng($imt,$tfname) )
                            $converted = true;
                    }

                    imagedestroy($imt);
                }   

                imagedestroy($ims);
            }

            unlink($uploader->getSavedDestination());

            if ( $converted )
            {
                $ok = false;

                if ( videobb_update_video_image( $video['name'], $tfname, $xoops_uid ) )
                    $ok = true;
                
                unlink($tfname);

                if ( $ok )
                    redirect_header("$php_self_link?$url_params",3,_VB_VI_UPLOAD_OK);
                else
                    redirect_header("$php_self_link?$url_params",5,_VB_VI_UPLOAD_FAILED);

                exit();
            }
            else
            {
                redirect_header("$php_self_link?$url_params",5,_VB_VI_UPLOAD_FAILED);

                exit();
            }
        }
        else
        {
            redirect_header("$php_self_link?$url_params",5,$uploader->getErrors());

            exit();
        }
    }
    else if ( isset($_REQUEST['m']) && $_REQUEST['m'] == 2 )
    {
        if ( videobb_delete_video_image($video['name']) )
            redirect_header("$php_self_link?$url_params",3,_VB_VI_DELETE_OK);
        else
            redirect_header("$php_self_link?$url_params",5,_VB_VI_DELETE_FAILED);

        exit();
    }
    // Unreachable
?>