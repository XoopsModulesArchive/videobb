<?php
// $Id: comment_new.php,v 1.6 2003/03/25 11:08:16 buennagel Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
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

    require '../../mainfile.php';

    // Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    require_once 'include/db.php';

    $com_itemid = isset($_GET['com_itemid']) ? $_GET['com_itemid']:'';

    if ( $com_itemid != '' )
    {
        $myts =& MyTextSanitizer::getInstance();
        $com_itemid = $myts->htmlSpecialChars(basename(urldecode( $com_itemid )));

        if ( strstr( $com_itemid, ".." ) )
            $com_itemid = "";
    
        if ( $com_itemid == "" )
            return;

        // Get title
        $video = videobb_get_video_by_id( $com_itemid );

        if ( !$video )
            return;
        
        $com_replytitle = $com_itemid;
        include XOOPS_ROOT_PATH.'/include/comment_new.php';
    }
?>
