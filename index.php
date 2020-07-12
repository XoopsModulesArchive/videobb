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
    $xoopsOption['template_main'] = "videobb_root_view.html";
    require_once XOOPS_ROOT_PATH."/header.php";

    // Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    // Prepare our root path
    $videobb_module_root = XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname');

    require_once "$videobb_module_root/include/config.php";
    require_once "$videobb_module_root/include/fman.php";
    require_once "$videobb_module_root/include/db.php";
    require_once "$videobb_module_root/include/tools.php";

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
    // Get username within <a href> tags to userinfo.php or "anonymous"
    $xoops_ulink = xoops_getLinkedUnameFromId($xoops_uid);
    $xoops_uagent = videobb_uagent();
    $videobb_moderator = $xoops_admin || in_array($xoops_uid,explode(';',videobb_get_option("_VB_ADM_VIM_ADMINS")));

    // Log some data if enabled
    if ( videobb_get_option("_VB_ADM_ACCESS_LOG") )
    {
        videobb_logger();
    }

    // Token (referers) security check
    if ( $GLOBALS['xoopsSecurity']->check() == false && ( isset($_REQUEST['text']) || isset($_REQUEST['genre']) || isset($_REQUEST['vote']) || isset($_REQUEST['nname']) ) )
    {
        $url = $php_self_link."?vid=";
        $url .= isset($vid)?intval($vid):'';

        if ( isset($_REQUEST["rid"]) && $_REQUEST["rid"] != "" )
            $url .= "rid=".$_REQUEST["rid"]."&";
        if ( isset($_REQUEST["folder"]) && $_REQUEST["folder"] != "" )
            $url .= "folder=".$_REQUEST["folder"]."&";
        if ( isset($_REQUEST["video"]) && $_REQUEST["video"] != "" )
            $url .= "video=".$_REQUEST["video"]."&";

        redirect_header($url, 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

        exit();
    }

    $deny_mod_as_non_moderator = !$videobb_moderator && ( isset($_REQUEST['text']) || isset($_REQUEST['genre']) );
    $deny_mod_as_non_registered = $xoops_uid == 0 && ( isset($_REQUEST['text']) || isset($_REQUEST['genre']) || isset($_REQUEST['vote']) );
    // Block Guest video info modifications
    if ( $deny_mod_as_non_moderator || $deny_mod_as_non_registered )
    {
        if ( $deny_mod_as_non_registered )
            redirect_header($php_self_link."?rid=".$_REQUEST['rid']."&folder=".$_REQUEST["folder"]."&video=".$_REQUEST["video"],3,_VB_GUEST_CANNOT_1."<a href=\"".XOOPS_URL."/register.php\">"._VB_GUEST_CANNOT_2."</a>"._VB_GUEST_CANNOT_3);
        else
            redirect_header($php_self_link."?rid=".$_REQUEST['rid']."&folder=".$_REQUEST["folder"]."&video=".$_REQUEST["video"],3,_VB_NON_MODERATORS_CANNOT);

        exit();
    }

    $xoopsTpl->assign('videobb_javascript',"<script src=\"".XOOPS_URL."$vbb_path/inc/functions.js\" type=\"text/javascript\" language=\"javascript\"></script>");
    $myts =& MyTextSanitizer::getInstance();

    if ( isset($_REQUEST["folder_mode"]) && ( $_REQUEST["folder_mode"] == "vo" || $_REQUEST["folder_mode"] == "vg" ))
    {
        $folder_mode = $_REQUEST["folder_mode"];
    }
    else
        $folder_mode = "list";

// Get vid if any
    if ( isset($_REQUEST['vid']) )
        $vid = intval($_REQUEST['vid']);
    else
        $vid = '';
//
    $rid = "";
    $root = false;
    $roots = videobb_list_root(true);

    if ( isset( $_REQUEST['rid'] ) && $_REQUEST['rid'] != "" )
    {
        if ( $_REQUEST['rid'] == 'list' )
        {
            redirect_header("rlist.php",3,_VB_REDIRECTING);

            exit();
        }

        $rid = intval($_REQUEST['rid']);
    }
    else
    {
        if ( $roots != false )
        {
            $rid = $roots[0]['id'];
        }
        else
        {
            redirect_header("rlist.php",3,_VB_NO_ROOT);

            exit();
        }
    }

    $root = videobb_get_root( $rid );

    if ( $root == false || ( isset($root) && !$root['enabled'] ) )
    {
        if ( $roots != false )
        {
            redirect_header("rlist.php",3,_VB_WRONG_ROOT);

            exit();
        }
        else
        {
            redirect_header("rlist.php",3,_VB_NO_ROOTS);

            exit();
        }
    }

    $rfolder_path = $root['path'];
    $rfolder_url  = $root['url'];

    $rtr = 0;
    $rtd = 0;
    foreach ( $roots as $r )
    {
        if ( $rtd == 8 )
        {
            $rtd = 0;
            $rtr++;
        }

        $rlist[$rtr][$rtd] = "<a href=\"index.php?rid=".$r['id']."\">".$myts->htmlSpecialChars($r['caption'])."</a>";

        $rtd++;
    }

    $xoopsTpl->assign('videobb_roots_list',$rlist);
//

    $folder      = "";
    $folder_path = $rfolder_path;
    $folder_url  = $rfolder_url;

    if ( isset( $_REQUEST["folder"] ) && $_REQUEST["folder"] != "" )
    {
        $folder = $myts->htmlSpecialChars(urldecode( $_REQUEST["folder"] ));

        if ( strstr( $folder, ".." ) )
            $folder = "";
    
        if ( $folder == "" )
        {
            $folder_path = $rfolder_path;
            $folder_url = $rfolder_url;
        }
        else
        {
            $folder_path = $rfolder_path."/".$folder;
            $folder_url = $rfolder_url."/".$folder;
        }
    }
    
    $file      = "";
    $file_path = "";
    $file_url  = "";

    if ( isset( $_REQUEST['file'] ) && $_REQUEST['file'] != "" )
    {
        $file = $myts->htmlSpecialChars(urldecode($_REQUEST['file']));

        if ( strstr( $file, ".." ) )
        {
            $file = "";
        }
        else
        {
            $file_path = $folder_path."/".$file;
            $file_url = $folder_url."/".$file;
        }
    }

// Name changing
    if ( isset( $_REQUEST["nname"] ) && $_REQUEST["nname"] != "" &&
        isset( $_REQUEST["oname"] ) && $_REQUEST["oname"] != ""
         )
    {
        $nname = $myts->htmlSpecialChars(urldecode( $_REQUEST["nname"] ));
        $oname = $myts->htmlSpecialChars(urldecode( $_REQUEST["oname"] ));

        if ( !$videobb_moderator )
            redirect_header("index.php",3,_VB_NAME_DENIED);
        else if ( videobb_rename_video( $folder_path, $oname, $nname ) )
            redirect_header("index.php",3,_VB_NAME_CHANGED);
        else
            redirect_header("index.php",3,_VB_NAME_CHANGE_FAILED);

        exit();
    }

    // Structurize video folder
    if ( videobb_get_option("_VB_ADM_RESTRUCTURIZE_MOVIES") )
        videobb_fman_structurize_root_folder( $rfolder_path, $root['extensions'] );

    if ( $file == "" )
    {
        if ( $folder_path != $rfolder_path )
        {
            // Non Root folder
            if ( $folder_mode == "list" )
            {
                // Insert links for all folders in $folder
                $folders_list = explode('/',$folder);
                $no = 1;
                foreach ( $folders_list as $cf )
                {
                    if ( $no != 1 )
                        $flist .= "/";
    
                    if ( isset($cfs) && $cfs != '' )
                        $cfs .= $cf;
                    else
                        $cfs = $cf;

                    $cv = videobb_get_video($cf);
                    $cvid = $cv['id'];

                    $s = "<a href=\"$php_self_link?rid=$rid&vid=$cvid&folder=".urlencode($cfs)."\">$cf</a>";
                    if ( !isset($flist) )
                        $flist = $s;
                    else
                        $flist .= $s;

                    $cfs .= '/';
                    $no++;
                }
                
                $xoopsTpl->assign('videobb_folders_list',$flist);

                $xoopsTpl->assign('videobb_flink_index',"<a href=\"$php_self_link?rid=$rid\">"._VB_INDEX."</a>");

                if ( $folder != "" && strrchr($folder, "/") != FALSE )
                {
                    $bfolder = substr($folder,0,strrpos($folder,"/") );
                    $xoopsTpl->assign('videobb_flink_back',"&nbsp;&bull;&nbsp;<a href=\"$php_self_link?rid=$rid&folder=$bfolder\">"._VB_BACK."</a>");
                }

                if ( file_exists( $folder_path."/readme.txt" ) )
                {
                    $txtfile_path = $folder_path."/readme.txt";
                    $txtfile_url = $folder_url."/readme.txt";
                }
                else if ( file_exists( $folder_path."/description.txt" ) )
                {
                    $txtfile_path = $folder_path."/description.txt";
                    $txtfile_url = $folder_url."/description.txt";
                }
            
                if ( isset($_REQUEST['text']) && $_REQUEST['text'] != "" && videobb_get_option("_VB_ADM_UPDATE_INFO_IN_FILES") )
                {
                    $file = fopen( $folder_path."/readme.txt", "w" );
                    fwrite( $file, $myts->htmlSpecialChars(urldecode($_REQUEST['text'])) );
                    fclose( $file );

                    $txtfile_path = $folder_path."/readme.txt";
                    $txtfile_url = $folder_url."/readme.txt";
                }

                if ( isset($txtfile_path) && $txtfile_path != "" && videobb_get_option("_VB_ADM_UPDATE_INFO_FROM_FILES") )
                {
                    $vdescr_ffile = file_get_contents( $txtfile_path );
                }

                if ( file_exists( $folder_path."/genre.txt" ) )
                {
                    $genrefile_path = $folder_path."/genre.txt";
                    $genrefile_url = $folder_url."/genre.txt";
                }

                if ( isset($_REQUEST['genre']) && $_REQUEST['genre'] != "" && videobb_get_option("_VB_ADM_UPDATE_INFO_IN_FILES") )
                {
                    $file = fopen( $folder_path."/genre.txt", "w" );
                    fwrite( $file, $myts->htmlSpecialChars(urldecode($_REQUEST['genre'])) );
                    fclose( $file );
        
                    $genrefile_path = $folder_path."/genre.txt";
                    $genrefile_url = $folder_url."/genre.txt";
                }
                
                if ( isset($genrefile_path) && $genrefile_path != "" && videobb_get_option("_VB_ADM_UPDATE_INFO_FROM_FILES") )
                {
                    $vgenre_ffile = file_get_contents( $genrefile_path );
                }
            }

            if ( $folder != "" && strrchr($folder, "/") )
                $lfolder = substr($folder,strrpos($folder,"/")+1 );
            else
                $lfolder = $folder;

            // Get video for folder
            $video = videobb_get_video($lfolder);

            // Lost info from files if we have something in DB
            if ( $video != false && isset($video["genre"]) )
                unset($vgenre_ffile);
            if ( $video != false && isset($video["description"]) )
                unset($vdescr_ffile);

            // If info need to be changed due to REQUEST - do it
            if ( isset($_REQUEST["genre"]) && $_REQUEST["genre"] != "" )
                $vgenre_ffile = $_REQUEST["genre"];
            if ( isset($_REQUEST["text"]) && $_REQUEST["text"] != "" )
                $vdescr_ffile = $_REQUEST["text"];

            if ( isset($vgenre_ffile) && isset($vdescr_ffile) )
                videobb_update_video( $xoops_uid, date("Y-m-d",filectime($folder_path)),$lfolder,$vgenre_ffile,1,$vdescr_ffile);
            else if ( isset($vgenre_ffile) )
                videobb_update_video( $xoops_uid, date("Y-m-d",filectime($folder_path)),$lfolder,$vgenre_ffile,1,NULL);
            else if ( isset($vdescr_ffile) )
                videobb_update_video( $xoops_uid, date("Y-m-d",filectime($folder_path)),$lfolder,NULL,1,$vdescr_ffile);
            else
                videobb_update_video( $xoops_uid, date("Y-m-d",filectime($folder_path)),$lfolder,NULL,1,NULL);

            // Get video for folder
            $video = videobb_get_video($lfolder);

            videobb_fman_list_media( $video, $rid, $folder, $folder_mode );
        }

        // Get folders/files from target folder via prepared array
        $videos = videobb_fman_list_folder( $rid, $vid, $folder, $php_self_link, $xoops_uid );
        
        if ( isset( $videos['error'] ) && $videos['error'] != "" )
        {
            redirect_header($videos['error_redirect'],3,$videos['error']);

            exit();
        }
        
        // Our comparsion function work with global variable $video_list ;(
        if ( isset( $videos['list'] ) )
            $video_list = $videos['list'];
    
        if ( $folder_mode == "list" )
        {
            $cmp_by = "img_id";
            $cmp_rev = "0";
        
            if ( isset( $_REQUEST['cmpby'] ) && $_REQUEST['cmpby'] != "" )
            {
                $cmp_by = $myts->htmlSpecialChars($_REQUEST['cmpby']);
                $_SESSION[$xoopsModule->getVar('dirname')."_cmpby"] = $cmp_by;
                setcookie ($xoopsModule->getVar('dirname')."_cmpby", $cmp_by, time() + 2678400);
            }
            else if ( isset( $_COOKIE[$xoopsModule->getVar('dirname')."_cmpby"] ) && $_COOKIE[$xoopsModule->getVar('dirname')."_cmpby"] != "" )
            {
                $cmp_by = $_COOKIE[$xoopsModule->getVar('dirname')."_cmpby"];
            }
            else if ( isset( $_SESSION[$xoopsModule->getVar('dirname')."_cmpby"] ) && $_SESSION[$xoopsModule->getVar('dirname')."_cmpby"] != "" )
            {
                $cmp_by = $_SESSION[$xoopsModule->getVar('dirname')."_cmpby"];
            }

            if ( ( isset( $_REQUEST['cmprev'] ) && $_REQUEST['cmprev'] != "" ) or isset( $_REQUEST['cmpby'] ) )
            {
                $cmp_rev = isset($_REQUEST['cmprev'])?$myts->htmlSpecialChars($_REQUEST['cmprev']):0;
                $_SESSION[$xoopsModule->getVar('dirname')."_cmprev"] = $cmp_rev;
                setcookie ($xoopsModule->getVar('dirname')."_cmprev", $cmp_rev, time() + 2678400);
            }
            else if ( isset( $_COOKIE[$xoopsModule->getVar('dirname')."_cmprev"] ) && $_COOKIE[$xoopsModule->getVar('dirname')."_cmprev"] != "" && !isset( $_REQUEST['cmpby'] ) )
            {
                $cmp_rev = $_COOKIE[$xoopsModule->getVar('dirname')."_cmprev"];
            }
            else if ( isset( $_SESSION[$xoopsModule->getVar('dirname')."_cmprev"] ) && $_SESSION[$xoopsModule->getVar('dirname')."_cmprev"] != "" && !isset( $_REQUEST['cmpby'] ) )
            {
                $cmp_rev = $_SESSION[$xoopsModule->getVar('dirname')."_cmprev"];
            }

            if ( isset($video_list) )
            {
                uksort($video_list, "videobb_cmp_by");
                reset($video_list);
            }

            $fcaptions[1][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=name\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_CAPTION."&nbsp;<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=name&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $fcaptions[1][2] = "name";
            $fcaptions[1][3] = 1;
            $fcaptions[2][1] = _VB_VIDEO_FOLDER;
            $fcaptions[2][2] = "link_url";
            $fcaptions[2][3] = 2;
            $fcaptions[3][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=vote\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_VOTE."&nbsp;</a><a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=vote&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $fcaptions[3][2] = "vote";
            $fcaptions[3][3] = 3;
            $fcaptions[4][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=img_id\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_AGE."&nbsp;</a><a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=img_id&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $fcaptions[4][2] = "img";
            $fcaptions[4][3] = 4;
            $fcaptions[5][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=date\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_MODIFIED."&nbsp;</a><a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=date&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $fcaptions[5][2] = "date";
            $fcaptions[5][3] = 5;
            $fcaptions[6][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=genre\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_GENRE."&nbsp;</a><a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=genre&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $fcaptions[6][2] = "genre";
            $fcaptions[6][3] = 6;
            $fcaptions[7][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=hits\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_HITS."&nbsp;</a><a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=hits&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $fcaptions[7][2] = "hits";
            $fcaptions[7][3] = 7;

            $ffcaptions[1][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=name\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_CAPTION."&nbsp;<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=name&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $ffcaptions[1][2] = "name";
            $ffcaptions[1][3] = 1;
            $ffcaptions[2][1] = _VB_VIDEO;
            $ffcaptions[2][2] = "link_url";
            $ffcaptions[2][3] = 2;
            $ffcaptions[3][1] = _VB_VIDEO_SIZE;
            $ffcaptions[3][2] = "size";
            $ffcaptions[3][3] = 3;
            $ffcaptions[4][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=img_id\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_AGE."&nbsp;</a><a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=img_id&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $ffcaptions[4][2] = "img";
            $ffcaptions[4][3] = 4;
            $ffcaptions[5][1] = "<a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=date\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_up.gif\">&nbsp;"._VB_VIDEO_MODIFIED."&nbsp;</a><a href=\"$php_self_link?rid=$rid&folder=$folder&cmpby=date&cmprev=1\"><img src=\"".XOOPS_URL."$vbb_path/images/sort_down.gif\"></a>";
            $ffcaptions[5][2] = "date";
            $ffcaptions[5][3] = 5;
            $ffcaptions[6][1] = _VB_VIDEO_TYPE;
            $ffcaptions[6][2] = "type";
            $ffcaptions[6][3] = 6;

            $tplresult["ffcaptions"] = $ffcaptions;
            $tplresult["fcaptions"] = $fcaptions;
            $tplresult["ffcaptions_c"] = 7;
            $tplresult["fcaptions_c"] = 6;

            if ( isset($video_list) )
            while ( list($key, $val) = each($video_list))
            {
                if ( !$val['is_file'] )
                {
                    $vid = videobb_get_video($val['fname']);

                    if ( isset($vid['image_by']) && $vid['image_by'] != 0 )
                        $val['image'] = "<a href=\"".$val['vlink']."\"><img src=\"vimage.php?folder=".urlencode($val['fname'])."\"></a>";

                    // Vote
                    if ( $val['vote'] == 0 )
                    {
                        if ( $xoops_uid > 0 )
                            $val['vote'] = "<a href=\"".$val["vlink"]."&folder_mode=vo\">"._VB_VOTE_LINK."</a>";
                        else
                            $val['vote'] = "-";
                    }
                    else
                    {
                        if ( $xoops_uid == 0 )
                            $val['vote'] .= "<br><a href=\"/modules/ipboard/index.php?act=Login&CODE=00\">"._VB_RESTRICTED_LINK."</a>";
                        else if ( videobb_vote_canvote( $val["fname"], $xoops_uid ) )
                            $val['vote'] .= "<br><a href=\"".$val["vlink"]."&folder_mode=vo\">"._VB_VOTE_LINK."</a>";
                    }

                    if ( !isset($val['genre']) || $val['genre'] == '' )
                    {
                        if ( $videobb_moderator )
                            $val['genre'] = "<a href=\"".$val["vlink"]."&folder_mode=vg\">"._VB_EDIT_LINK."</a>";
                        else
                            $val['genre'] = "-";
                    }
                    else
                        $val['genre'] = $myts->xoopsCodeDecode(trim($val['genre']));
                }
                else
                {
                    // List file

                    // Size
                    $val['size'] = $val['size']." Mb";
                }
                
                if ( !$val['is_file'] && isset($val["description"]) )
                {
                    $val["description"] = nl2br($myts->xoopsCodeDecode(trim($val["description"])));

                    if ( !isset($_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"]) || $_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"] == "1" )
                        $val["description"] = $myts->smiley($val["description"]);

                    $val["description"] = _VB_DESCRIPTION_NAME."&nbsp;&bull;&nbsp;".$val["description"];
                }
        
                unset($valf);
                if ( !$val['is_file'] )
                {
                    foreach ( $fcaptions as $fc)
                        $valf[$fc[3]] = $val[$fc[2]];

                    if ( isset($val["description"]) )
                        $valf['descr'] = $val["description"];
                    if ( isset($val['image']) )
                        $valf['image'] = $val['image'];

                    $tplresult["folders"][] = $valf;
                }
                else
                {
                    foreach ( $ffcaptions as $ffc)
                        $valff[$ffc[3]] = $val[$ffc[2]];

                    $tplresult["files"][] = $valff;
                }
            }

            $tplresult["total_title"] = _VB_TOTAL;
            $tplresult["total_files"] = $videos['files_count']." "._VB_TOTAL_MOVI;
            if ( $videos['files_count'] == 0 || $videos['files_count'] > 4 )
                $tplresult["total_files"] .= _VB_TOTAL_MOVI_0_GT4;
            else if ( $videos['files_count'] == 1 )
                $tplresult["total_files"] .= _VB_TOTAL_MOVI_1;
            else if ( $videos['files_count'] >= 2 && $videos['files_count'] <= 4 )
                $tplresult["total_files"] .= _VB_TOTAL_MOVI_GT1_LT5;

            $tplresult["total_folders"] = $videos['folders_count']." "._VB_TOTAL_FOLDE;
            if ( $videos['folders_count'] == 0 || $videos['folders_count'] > 4 )
                $tplresult["total_folders"] .= _VB_TOTAL_FOLDE_0_GT4;
            else if ( $videos['folders_count'] == 1 )
                $tplresult["total_folders"] .= _VB_TOTAL_FOLDE_1;
            else if ( $videos['folders_count'] >= 2 && $videos['folders_count'] <= 4 )
                $tplresult["total_folders"].= _VB_TOTAL_FOLDE_GT1_LT5;

            $xoopsTpl->assign('videobb_fresult',$tplresult);
        }


        if ( $folder_path != $rfolder_path )
            $xoopsTpl->assign('videobb_view',"media");
        else
            $xoopsTpl->assign('videobb_view',"folder");
    }
    else
    {
        $xoopsTpl->assign('videobb_file_link_index',"<a href=\"$php_self_link?rid=$rid\">"._VB_INDEX."</a>");

        if ( $folder != "" && strrchr($folder, "/") != FALSE )
        {
            $bfolder = substr($folder,0,strrpos($folder,"/") );
            $xoopsTpl->assign('videobb_file_link_back',"<a href=\"$php_self_link?rid=$rid&folder=$bfolder&vid=$vid\">"._VB_BACK."</a>");
        }
        else
            $xoopsTpl->assign('videobb_file_link_back',"<a href=\"$php_self_link?rid=$rid&folder=$folder&vid=$vid\">"._VB_BACK."</a>");

        $xoopsTpl->assign('videobb_file_link_ofolder',"<a href=\"$folder_url\" target=\"_blank\">"._VB_OPEN_FOLDER."</a>");

        if ( videobb_get_option("_VB_ADM_SHOW_UAGENT_WARNING") &&
            strstr( $xoops_uagent, "Internet Explorer" ) == false &&
            strstr( $xoops_uagent, "Gecko" ) == false
           )
        {
            $xoopsTpl->assign('videobb_uagent_warning_title', _VB_UAGENT_WARNING_TITLE );
            $xoopsTpl->assign('videobb_uagent_warning', _VB_UAGENT_WARNING_1." <a href=\"http://www.spreadfirefox.com/?q=affiliates&id=112398&t=80\">FireFox</a>." );
        }


        $object = "<object id=\"MediaPlayer1\"";
        $object .= "classid=\"CLSID:22D6F312-B0F6-11D0-94AB-0080C74C7E95\"";
        $object .= "standby=\"Loading Microsoft Windows Media Player components...\">";
        $object .= "<param name=\"Filename\" value=\"$file_url\">";
        $object .= "<param name=\"AnimationAtStart\" value=\"true\">";
        $object .= "<param name=\"TransparentAtStart\" value=\"false\">";
        $object .= "<param name=\"ShowControls\" value=\"true\">";
        $object .= "<param name=\"PlayCount\" value=\"true\">";
        $object .= "<embed TYPE=\"application/x-mplayer2\" width=\"320\" height=\"285\" src=\"$file_url\" controller=true autoplay=true playeveryframe=false pluginspage=\"http://microsoft.com/windows/mediaplayer/en/download\">";
        $object .= "</embed>";
        $object .= "</object>";
        $xoopsTpl->assign('videobb_file_object',$object);

        $xoopsTpl->assign('videobb_view',"file");
    }
    
    if ( !defined('VIDEOBB_SKIP_LICENSE') )
    {
        if ( videobb_get_option("_VB_ADM_SHOW_LICENSE_WARNING") )
        {
            $xoopsTpl->assign('videobb_license_title', _VB_ADM_LICENSE);
            $xoopsTpl->assign('videobb_license', videobb_get_option("_VB_ADM_LICENSE"));
        }
    }

    $xoopsTpl->assign('videobb_footer', "<a href=\"http://dev.xoops.org/modules/xfmod/project/?videobb\">Video-BB</a> v$vbb_version &copy; 2004-2005 "._VB_AUTHOR);

    require_once XOOPS_ROOT_PATH.'/include/comment_view.php';
    require_once (XOOPS_ROOT_PATH."/footer.php");
?>
