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
    if ( !defined('IN_VIDEOBB') )
    {
        die('Hacking attempt');
        exit;
    }

    function videobb_uagent()
    {
        if (strstr($_SERVER['HTTP_USER_AGENT'], "Opera 6")) {
            return "Opera 6";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Opera 7")) {
            return "Opera 7";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Opera")) {
            return "Opera";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Konqueror")) {
            return "Konqueror";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Netscape/7")) {
            return "Netscape 7";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Netscape6")) {
            return "Netscape 6";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Netscape4")) {
            return "Netscape 4";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Netscape3")) {
            return "Netscape 3";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Lynx")) {
            return "Lynx";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Links")) {
            return "Links";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "OmniWeb")) {
            return "OmniWeb";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "WebTV")) {
            return "WebTV";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Avant Browser")) {
            return "Avant Browser";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "MyIE2")) {
            return "MyIE2";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Galeon")) {
            return "Galeon";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 6")) {
            return "Internet Explorer 6";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 5")) {
            return "Internet Explorer 5";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE 4")) {
            return "Internet Explorer 4";
        } elseif (strstr($_SERVER['HTTP_USER_AGENT'], "Gecko")) {
            return "Gecko";
        } else {
            return "Other";
        }
    }

    // Return calling script file name, like "index.php"
    function videobb_php_self()
    {
        $lslash = strrpos( $_SERVER["PHP_SELF"], "/" );

        if ( $lslash == false )
            return $_SERVER["PHP_SELF"];
        else
            return substr( $_SERVER["PHP_SELF"], $lslash + 1 );
    }


    function videobb_cmp_by($a, $b) 
    {
        global $video_list, $cmp_by, $cmp_rev;

        if ( !isset($video_list[$a][$cmp_by]) && !isset($video_list[$b][$cmp_by]) )
            return 0;
        else if ( !isset($video_list[$b][$cmp_by]) )
            return -1;
        else if ( !isset($video_list[$a][$cmp_by]) )
            return 1;

        if ( $video_list[$a][$cmp_by] == $video_list[$b][$cmp_by])
            return 0;
        
        if ( $cmp_rev == "1" )
            return ($video_list[$a][$cmp_by] > $video_list[$b][$cmp_by]) ? -1 : 1;
        else
            return ($video_list[$a][$cmp_by] > $video_list[$b][$cmp_by]) ? 1 : -1;
    }

    function videobb_logger()
    {
        global $xoopsModule,$xoopsUser;
        // Skip logging from ignored ip list
        $ignore_ip = explode( ';', videobb_get_option("_VB_ADM_ACCESS_LOG_IGNORE_IP") );
        $skip_ip = false;

        foreach ( $ignore_ip as $ip )
        {
            if ( $_SERVER['REMOTE_ADDR'] == $ip )
            {
                $skip_ip = true;

                break;
            }
        }

        if ( !$skip_ip )
        {
            $myts =& MyTextSanitizer::getInstance();
            $filename = XOOPS_ROOT_PATH.'/modules/'.$xoopsModule->getVar('dirname').'/access.log.php';

            $insert_header = false;
            if ( !file_exists( $filename ) )
                $insert_header = true;

            $handle = fopen($filename, "a+");

            // No log file? Let's create it...
            if ( $insert_header )
            {
                $contents  = '<?php require_once "header.php"; require_once XOOPS_ROOT_PATH."/header.php"; define(\'IN_VIDEOBB\', true); ';
                $contents .= 'if ( !is_object($xoopsUser) || !$xoopsUser->isAdmin($xoopsModule->mid()) ) die("Wrong install"); ?>';
                $contents .= "\n<br>";
                fwrite($handle, $contents);
            }

            if ( is_object($xoopsUser) )
            {
                $ulink = xoops_getLinkedUnameFromId($xoopsUser->getVar('uid'));
                $uid   = $xoopsUser->getVar('uid');
            }
            else
            {
                $ulink = $uid = 0;
            }

            $contents = $ulink.":".$uid.":".$_SERVER['REMOTE_ADDR'].":".date("Y.m.d H:i:s")."\n<br>";
            fwrite($handle, $contents);
            $contents = "";

            while ( list($col, $data) = each($_REQUEST))
            {
                if ( 
                    ( $col != "folder" ) &&
                    ( $col != "rid" ) &&
                    ( $col != "folder_mode" ) &&
                    ( $col != "file" ) &&
                    ( $col != "cmpby" ) &&
                    ( $col != "cmprev" ) &&
                    ( $col != "vote" ) &&
                    ( $col != "genre" ) &&
                    ( $col != "text" )
                   )
                    continue;

                $contents .= "\t\"$col\" = \"$data\"\n%br%";
            }

            // Some anti-hacks...
            if ( $contents != "" )
            {
                $contents = $myts->htmlSpecialChars( $contents );
                $contents = str_replace("%br%", "<br>", $contents );

                fwrite($handle, $contents);
            }
            fclose($handle);
        }
    }
?>