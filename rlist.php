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

    // Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    require_once "include/config.php";
    require_once "include/db.php";

    if ( !defined('VIDEOBB_INSTALLED') )
    {
        die(_VBB_WRONG_INSTALL);
        exit;
    }


    $db =& Database::getInstance();
    $myts =& MyTextSanitizer::getInstance();

    $roots = videobb_list_root(true);

    echo "<table><tr><th align=\"center\">"._VB_ROOT_LIST."</th></tr></table>";
    if ( $roots != false )
    {
        echo "<table>";

        foreach ( $roots as $root )
        {
            echo "<tr><td><a href=\"index.php?rid=".$root['id']."\">".$myts->htmlSpecialChars($root['caption'])."</a></td></tr>";
        }

        echo "</table>";
    }
    else
    {
        echo "<center>"._VB_ROOT_LIST_EMPTY."</center>";
    }

    define('VIDEOBB_SKIP_LICENSE',true);

    require_once XOOPS_ROOT_PATH."/footer.php";
?>
