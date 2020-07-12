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
    require_once "../../mainfile.php";
    require_once XOOPS_ROOT_PATH."/header.php";

    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    require_once "./include/config.php";
    require_once "./include/db.php";
    require_once "./include/tools.php";

    if ( !videobb_get_option("_VB_ADM_USE_FAQ") )
    {
        redirect_header( "index.php", 3, _VB_DISABLED );
    }

    $myts =& MyTextSanitizer::getInstance();
    echo "<table><tr><th>"._MI_VB_FAQ."</th></tr></table>";

    $topic        = "";
    $topic_actual = $conf_faq_root;
    

    if ( isset( $_GET['topic'] ) )
    {
        $topic        = $myts->htmlSpecialChars(basename(urldecode($_GET['topic'])));
    
        if ( strstr( $topic, ".." ) )
            $topic = "";
    
        if ( $topic == "" )
        {
            $topic_actual = $conf_faq_root;
        }
        else
        {
            $topic_actual = $conf_faq_root."/".$topic;
        }
    }

    if ( isset( $_GET['m'] ) && intval($_GET['m']) == 1 )
        $action = "topic";
    else
        $action = "topics";
    
    if ( $action == "topics" ) {
        if ( !( $dir = opendir( $topic_actual ) ) )
        {
            redirect_header( "index.php", 3, "Нет доступа к $topic_actual" );

            exit();
        }
        
        echo "<table width=100%>";

        while ( ($fname = readdir( $dir ) ) != false )
        {
            if ( $fname[0] == "." )
                continue;

            $long_fname = $topic_actual."/".$fname;
            if ( !is_dir( $long_fname ) )
                continue;

            echo "<tr><td class=\"blockContent\">";
            echo "<a href=\"".XOOPS_URL."$vbb_path/faq.php?m=1&topic=".urlencode($fname)."\">$fname</a>";
            echo "</td></tr>";
        }

        echo "</table>";

        closedir( $dir );
    
    } else if ( $action == "topic" ) {
        if ( $topic_actual != $conf_faq_root )
        {
            echo "<a href=\"".XOOPS_URL."$vbb_path/faq.php\">"._VB_BACK."</a><br>";
        }
        $text = file_get_contents( $topic_actual."/topic.txt" );

        if ( $text == "" )
        {
            $text = file_get_contents( $topic_actual."/../empty.txt" );
        }
        
        echo "<table width=100%>";
        echo "<tr><th>$topic</th></tr>";
        echo "";
        echo "<tr><td class=\"blockContent\">$text</td></tr></table>";
    }
    
    require_once (XOOPS_ROOT_PATH."/footer.php");
?>
