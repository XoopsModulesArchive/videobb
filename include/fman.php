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

    $mime_types = include_once XOOPS_ROOT_PATH."/class/mimetypes.inc.php";


    if ( !defined('IN_VIDEOBB') )
    {
        die('Hacking attempt');
        exit;
    }

    function videobb_fman_list_folder( $rid, $vid, $folder, $script_url, $xoops_uid )
    {
        global $xoopsModule;

        $root = videobb_get_root( $rid );

        if ( $root == false )
        {
            $result["error"] = _VB_WRONG_ROOT;
            $result["error_redirect"] = "rlist.php?a=b";

            return $result;
        }

        $rfolder_path = $root['path'];
        $rfolder_url  = $root['url'];
        $vid_extensions = $root['extensions'];

        $result = array();

        $result['count'] = 0;
        $result['folders_count'] = 0;
        $result['files_count'] = 0;
        $result['files_size'] = 0;

        if ( $folder != '' )
            $folder_path = "$rfolder_path/$folder";
        else
            $folder_path = $rfolder_path;

        if ( !file_exists($folder_path) || !( $dir = opendir( $folder_path ) ) )
        {
            $result["error"] = _VB_CANNOT_OPEN.$folder_path;
            $result["error_redirect"] = "rlist.php";

            return $result;
        }
        
        $myts =& MyTextSanitizer::getInstance();

        while ( ($fname = readdir( $dir ) ) != false )
        {
            // Skip "." && ".."
            if ( $fname[0] == "." )
                continue;

            // There was a case when someone mount shared samba folder
            //  as network drive and in such some folders will be created
            //  by Windows...
            if ( $fname == "RECYCLER" || $fname == "RECYCLED" || $fname == "System Volume Information" )
                continue;

            $long_fname = $folder_path."/".$fname;
            $dot_pos = strrpos( $fname, "." );
            $is_file = is_file( $folder_path ."/". $fname );

            if ( $dot_pos == "" )
            {
                $fext = "";
                $fsname = $fname;
            } else {
                $fext = substr( $fname, $dot_pos + 1, strlen( $fname ) );
                $fsname = trim(substr( $fname, 0, $dot_pos ));
            }

            if ( $is_file && !videobb_fman_is_video_extension( $fext, $vid_extensions ) )
                continue;
                
            $result['count']++;
            // ID
            $video_list[$result['count']]["id"] = $result['count'];

            // File/Folder flag
            $video_list[$result['count']]["is_file"] = $is_file?1:0;

            // Short name
            $video_list[$result['count']]["fname"] = $fname;

            // URL
            if ( $folder == "" )
                $flink = $fname;
            else
                $flink = $folder.'/'.$fname;
            $felink = urlencode($flink);
            $video_list[$result['count']]["link_url"] = "<a href=\"$rfolder_url/$flink\"><img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/";
            if ( $is_file )
                $video_list[$result['count']]["link_url"] .= "video.png\" alt=\"video";
            else
                $video_list[$result['count']]["link_url"] .= "folder.png\" alt=\"folder";
            $video_list[$result['count']]["link_url"] .= "\"/></a>";

            // Dates check
            $video_list[$result['count']]["date"] = date("Y-m-d", filectime($folder_path."/".$fname));
            $now_d = date("d");
            $now_m   = date("m");
            $now_y   = date("y");
            $file_d = date("d",filectime($folder_path."/".$fname));
            $file_m = date("m",filectime($folder_path."/".$fname));
            $file_y = date("y",filectime($folder_path."/".$fname));
            $diff_d = $now_d - $file_d;
            $diff_m = $now_m - $file_m;
            $diff_y = $now_y - $file_y;

            $diff_d += $diff_m*30;
            $diff_d += $diff_y*365;
                    
            if ( $diff_d < 0 )
                $diff_d = 28;
            
            if ( $diff_d <= 2 )
                $video_list[$result['count']]["img_id"] = 6;
            else if ( $diff_d <= 7)
                $video_list[$result['count']]["img_id"] = 5;
            else if ( $diff_d <= 14 )
                $video_list[$result['count']]["img_id"] = 4;
            else if ( $diff_d <= 31 )
                $video_list[$result['count']]["img_id"] = 3;
            else if ( $diff_d <= 62 )
                $video_list[$result['count']]["img_id"] = 2;
            else if ( $diff_d <= 93 )
                $video_list[$result['count']]["img_id"] = 1;
            else
                $video_list[$result['count']]["img_id"] = 0;
                    
            $video_list[$result['count']]["img"] = "";
            for ( $ic = 1; $ic <= $video_list[$result['count']]["img_id"]; $ic++ )
                $video_list[$result['count']]["img"] .= "<img src=\"".XOOPS_URL."/modules/".$xoopsModule->getVar('dirname')."/images/fresh.gif\">";

            if ( $is_file )
            {
                $video['id'] = '';
                $result['files_count']++;
                // Link to video in folder
                $video_list[$result['count']]["name"] = "<a href=\"$script_url?rid=$rid&vid=$vid&file=".urlencode($fname);
                if ( $folder != "" )
                    $video_list[$result['count']]["name"] .= "&folder=".urlencode($folder);
                $video_list[$result['count']]["name"] .= "\">$fname</a>";

                // Video MIME type
                $video_list[$result['count']]["type"] = $fext;
                if ( isset( $mime_types ) && isset ( $mime_types[$fext] ) )
                    $video_list[$result['count']]['type'] .= " ( ".$mime_types[$fext]." )";

                // Video size
                $fsize = filesize($folder_path."/".$fname);
                $result['files_size'] += $fsize;
                $video_list[$result['count']]["size"] = round($fsize/1024/1024,2);
                $video_list[$result['count']]["vlink"] = "$script_url?rid=$rid&folder=".$felink."&vid=".$video['id']."&file=".urlencode($fname);
            }
            else
            {
                $result['folders_count']++;
                videobb_update_video( $xoops_uid, date("Y-m-d",filectime($folder_path."/".$fname)),$fname,NULL,NULL,NULL);
                $video = videobb_get_video($fname);

                $video_list[$result['count']]["name"] = "<a href=\"$script_url?rid=$rid&folder=$felink&vid=".$video['id']."\">$fname</a>";
                $video_list[$result['count']]["vlink"] = "$script_url?rid=$rid&folder=$felink&vid=".$video['id'];

                $video_list[$result['count']]["vote"] = videobb_vote_getvote($fname);
                
                if ( isset($video["genre"]) && $video["genre"] != '' )
                {
                    if ( !isset($_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"]) || $_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"] == "1" )
                       $video_list[$result['count']]["genre"] = $myts->smiley($video["genre"]);
                    else
                        $video_list[$result['count']]["genre"] = $video["genre"];
                }
                
                if ( isset($video["hits"]) && $video['hits'] != '' )
                    $video_list[$result['count']]["hits"] = $video["hits"];
                else
                    $video_list[$result['count']]["hits"] = 0;

                if ( isset($video["description"]) && $video["description"] != '' )
                {
                    if ( !isset($_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"]) || $_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"] == "1" )
                        $video_list[$result['count']]["description"] = $myts->smiley($video["description"]);
                    else
                        $video_list[$result['count']]["description"] = $video["description"];
                }
            }
        }

        if ( isset($video_list) )
            $result['list'] = $video_list;

        return $result;
    }
    
    function videobb_fman_list_media( $video, $rid, $folder, $folder_mode )
    {
        global $xoopsUser,$xoopsModule, $xoopsTpl, $videobb_moderator;
        $myts =& MyTextSanitizer::getInstance();

        $show_genre = $folder_mode == 'vg' || $folder_mode == 'list';
        $show_vote = $folder_mode == 'vo' || $folder_mode == 'list';
        $show_descr = $folder_mode == 'list';
        $show_image = $folder_mode == 'list';
        $show_name = $folder_mode == 'list';
        $show_comm = $folder_mode == 'list';

        if ( $show_comm )
            $xoopsTpl->assign('videobb_comments_title', _VB_COMMENTS_NAME);
        $xoopsTpl->assign('videobb_comments', $show_comm);

        // Prepare users uid
        $xoops_admin = false;
        if ( is_object($xoopsUser) ) 
        {
            $xoops_uid   = $xoopsUser->getVar('uid');
            if ( $xoopsUser->isAdmin($xoopsModule->mid()) )
                $xoops_admin = true;
        }
        else
        {
            $xoops_uid   = 0;
        }

        if ( $folder != "" && strrchr($folder, "/") )
            $lfolder = substr($folder,strrpos($folder,"/")+1 );
        else
            $lfolder = $folder;

        if ( !$video )
            return false;

        // Prepare image if any
        if ( $show_image )
        {
            $image_data_title = _VB_IMAGE_NAME;

            if ( isset($video['image_by']) && $video['image_by'] != 0 )
            {
                $image_data = "<img src=\"vimage.php?folder=".urlencode($lfolder)."\">";
                if ( $videobb_moderator )
                    $image_data .= "<br>";
            }
        
            if ( $videobb_moderator )
            {
                $link = "<a href=\"mimage.php?vid=".$video['id']."\">"._VB_VI_EDIT_IMAGE."</a>";
                if ( isset($image_data) )
                    $image_data .= $link;
                else
                    $image_data = $link;
            }

            $xoopsTpl->assign('videobb_media_image_data', $image_data);
            $xoopsTpl->assign('videobb_media_image_data_title', $image_data_title);
        }

        // Prepare vote
        if ( isset( $_REQUEST['vote'] ) )
        {
            videobb_vote_vote( $lfolder, $xoops_uid, $_REQUEST['vote'] );
        }
    
        $vote = videobb_vote_getvote($lfolder);

        if ( $show_vote )
        {
            $vote_data_title = _VB_VOTE_NAME;
        
            $vote_data = $vote;
            
            if ( $vote != "0" )
                $vote_data = "<div class='media_vote'>".$vote_data."</div>";

            if ( $xoops_uid > 0 && videobb_vote_canvote( $lfolder, $xoops_uid ) )
            {
                $vote_data .= "<form action=\"index.php?rid=$rid&folder=".urlencode($folder)."&vid=".$video['id']."\" method=\"POST\">";
                $vote_data .= $GLOBALS['xoopsSecurity']->getTokenHTML();
                $vote_data .= "<input type=\"radio\" name=\"vote\" value=\"1\">"._VB_VOTE_VALUE1."<br>";
                $vote_data .= "<input type=\"radio\" name=\"vote\" value=\"2\">"._VB_VOTE_VALUE2."<br>";
                $vote_data .= "<input type=\"radio\" name=\"vote\" value=\"3\">"._VB_VOTE_VALUE3."<br>";
                $vote_data .= "<input type=\"radio\" name=\"vote\" value=\"4\">"._VB_VOTE_VALUE4."<br>";
                $vote_data .= "<input type=\"radio\" name=\"vote\" value=\"5\">"._VB_VOTE_VALUE5."<br>";

                $vote_data .= "<input type=submit value=\""._VB_VOTE_SUBMIT."\">";
                $vote_data .= "</form>";
            }
            else
            {
                if ( $xoops_uid == 0 )
                    $vote_data .= "<br>"._VB_GUEST_CANNOT_1."<a href=\"".XOOPS_URL."/register.php\">"._VB_GUEST_CANNOT_2."</a>"._VB_GUEST_CANNOT_3;
                else
                    $vote_data .= "<br>"._VB_VOTE_VOTED;
            }
            
            $xoopsTpl->assign('videobb_media_vote_data', $vote_data);
            $xoopsTpl->assign('videobb_media_vote_data_title', $vote_data_title);
        }
    
        if ( $show_name )
        {
            $name_data_title = _VB_NAME_NAME;
            $name_data = '';
        
            if ( $videobb_moderator )
            {
                $name_data .= "<form action=\"index.php?rid=$rid\" method=\"POST\">";
                $name_data .= $GLOBALS['xoopsSecurity']->getTokenHTML();
                $name_data .= "<input type=\"hidden\" name=\"oname\" value=\"".urlencode($lfolder)."\">";
            }

            $name_data .= "<input type=\"text\" name=\"nname\" value=\"$lfolder\">";
            
            if ( $videobb_moderator )
            {
                $name_data .= "<br><input type=\"submit\" value=\""._VB_NAME_SUBMIT."\">";
                $name_data .= "</form>";
            }

            $xoopsTpl->assign('videobb_media_name_data', $name_data);
            $xoopsTpl->assign('videobb_media_name_data_title', $name_data_title);
        }

        // Genre data
        if ( $show_genre )
        {
            $genre_data_title = _VB_GENRE_NAME;
            
            $genre_data = '';

            if ( $videobb_moderator )
            {
                $genre_data .= "<form action=\"index.php?rid=$rid&folder=".urlencode($folder)."&vid=".$video['id']."\" method=\"POST\">";
                $genre_data .= $GLOBALS['xoopsSecurity']->getTokenHTML();
            }

            if ( isset($video["genre"]) )
                $genre_msg = $myts->htmlSpecialChars($video["genre"]);
            else
                $genre_msg = '';

            $genre_data .= "<input type=\"text\" name=\"genre\" size=\"79\" value=\"$genre_msg\">";
            if ( $videobb_moderator )
            {
                $genre_data .= "<br><input type=\"submit\" value=\""._VB_GENRE_SUBMIT."\">";
                $genre_data .= "</form>";
            }

            $xoopsTpl->assign('videobb_media_genre_data', $genre_data);
            $xoopsTpl->assign('videobb_media_genre_data_title', $genre_data_title);
        }

        // Description data
        if ( $show_descr )
        {
            $descr_data_title = _VB_DESCRIPTION_NAME;
            $descr_data = '';

            if ( $videobb_moderator )
            {
                $descr_data .= "<form action=\"index.php?rid=$rid&folder=".urlencode($folder)."&vid=".$video['id']."\" method=\"POST\">";
                $descr_data .= $GLOBALS['xoopsSecurity']->getTokenHTML();
            }

            if ( isset($video["description"]) )
                $descr_msg = $myts->htmlSpecialChars($video["description"]);
            else
                $descr_msg = '';
            $descr_data .= "<textarea name=\"text\" rows=\"8\" cols=\"60\" wrap=\"virtual\" bgcolor=\"AAAAAA\">$descr_msg</textarea>";
            if ( $videobb_moderator )
            {
                $descr_data .= "<br><input type=\"submit\" value=\""._VB_GENRE_SUBMIT."\">";
                $descr_data .= "</form>";
            }

            $xoopsTpl->assign('videobb_media_descr_data', $descr_data);
            $xoopsTpl->assign('videobb_media_descr_data_title', $descr_data_title);
        }
    }

    function videobb_fman_structurize_root_folder( $folder_path, $vid_extensions )
    {
        if ( !file_exists( $folder_path ) || !( $dir = opendir( $folder_path ) ) )
        {
            return false;
        }
        
        while ( ($fname = readdir( $dir ) ) != false )
        {
            if ( $fname[0] == "." )
                continue;
    
            $long_fname = $folder_path."/".$fname;
    
            if ( !is_dir( $long_fname ) )
            {
                $dot_pos = strrpos( $fname, "." );

                if ( $dot_pos == "" )
                    continue;

                $fext = substr( $fname, $dot_pos + 1, strlen( $fname ) );
                $fsname = trim(substr( $fname, 0, $dot_pos ));
                
                if ( !videobb_fman_is_video_extension( $fext, $vid_extensions ) )
                    continue;

                mkdir( "$folder_path\\$fsname" );
                if ( !rename( $long_fname, "$folder_path\\$fsname\\$fsname.$fext" ) )
                {
                    $fsname_uniq = $fsname . "_" . rand(1, 65535);
                    rename( $long_fname, "$folder_path\\$fsname\\$fsname_uniq.$fext" );
                }
            }
        }
    
        closedir( $dir );

        return true;
    }


    function videobb_fman_is_video_extension( $fext, $vid_extensions='' )
    {
        if ( !isset($vid_extensions) || $vid_extensions == '' )
            $vid_extensions = videobb_get_option("_VB_ADM_VIDEO_EXTENSIONS");

        $varray = explode( ';', $vid_extensions );
        $fext_lower = strtolower($fext);

        foreach ( $varray as $vext )
        {
            if ( $vext == $fext_lower )
                return true;
        }

        return false;
    }

    // Video Prune
    function videobb_get_media_names( $folder_path, &$arr, $recurse )
    {
        global $xoopsModule;

        if ( !( $dir = opendir( $folder_path ) ) )
            return;
        
        while ( ($fname = readdir( $dir ) ) != false )
        {
            // Skip "." && ".."
            if ( $fname[0] == "." )
                continue;

            // There was a case when someone mount shared samba folder
            //  as network drive and in such some folders will be created
            //  by Windows...
            if ( $fname == "RECYCLER" || $fname == "RECYCLED" || $fname == "System Volume Information" )
                continue;

            if ( is_file( $folder_path ."/". $fname ) )
                continue;

            $lfname = $folder_path."/".$fname;

            if ( !in_array($fname,$arr) )
                $arr[] = $fname;
            
            if ( $recurse )
                videobb_get_media_names( $lfname, &$arr, $recurse );
        }
    }
?>