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
    require_once ("../../mainfile.php");
    require_once (XOOPS_ROOT_PATH."/header.php");

    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    $videobb_root_path = './';

    require_once "$videobb_root_path/include/config.php";
    require_once "$videobb_root_path/include/db.php";
    require_once "$videobb_root_path/include/tools.php";

    echo "<table><tr><th>"._MI_VB_SETTINGS."</th></tr></table>";
    $myname = "settings.php";

    if ( isset($_REQUEST["m"]) && intval($_REQUEST["m"]) == 1 )
    {
        setcookie ($xoopsModule->getVar('dirname')."_menu_in_list", intval($_REQUEST['menu_in_list']), time() + 2678400);
        setcookie ($xoopsModule->getVar('dirname')."_replace_smileys", intval($_REQUEST['replace_smileys']), time() + 2678400);
        if ( intval($_REQUEST['menu_in_list_step']) >= "10" )
            setcookie ($xoopsModule->getVar('dirname')."_menu_in_list_step", intval($_REQUEST['menu_in_list_step']), time() + 2678400);
        else
            setcookie ($xoopsModule->getVar('dirname')."_menu_in_list_step", "10", time() + 2678400);
        redirect_header(XOOPS_URL.$vbb_path."/".$myname, 1, _VBS_SAVED);
    }
    else
    {
        echo "<form method=\"POST\" action=\"".XOOPS_URL."$vbb_path/$myname\">";
        echo "<table width=\"100%\">";
        echo "<tr><th width=\"25%\">"._VBS_OPTION."</th><th width=\"25%\">"._VBS_VALUE."</th><td width=\"100%\"></td></tr>";
// +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
        echo "<tr><td>"._VBS_HEADERS_IN_LIST."</td><td>"._VBS_USED."&nbsp;<select name=\"menu_in_list\">";
        if ( isset($_COOKIE[$xoopsModule->getVar('dirname')."_menu_in_list"]) && $_COOKIE[$xoopsModule->getVar('dirname')."_menu_in_list"] == "1")
            $menu_in_list = 1;
        else
            $menu_in_list = 0;
        echo "<option value=\"1\"";
        if ( $menu_in_list == 1 )
            echo " selected>";
        else
            echo ">";
        echo _VBS_YES."</option>";
        echo "<option value=\"0\"";
        if ( $menu_in_list == 0 )
            echo " selected>";
        else
            echo ">";
        echo _VBS_NO."</option>";
            
        echo "</select><br>";

        $menu_in_list_step = $_COOKIE[$xoopsModule->getVar('dirname')."_menu_in_list_step"];
        if ( $menu_in_list_step == "" || $menu_in_list_step < 10 )
            $menu_in_list_step = 10;
        echo _VBS_HEADERS_IN_LIST_STEP_1."&nbsp;<input type=\"text\" name=\"menu_in_list_step\" value=\"".$menu_in_list_step."\">"._VBS_HEADERS_IN_LIST_STEP_2;
        echo "</td><td></td></tr>";
// +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
        echo "<tr><td>"._VBS_REPLACE_SMILEYS."</td><td>"._VBS_USED."&nbsp;<select name=\"replace_smileys\">";
        if ( isset($_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"]) && $_COOKIE[$xoopsModule->getVar('dirname')."_replace_smileys"] == "0")
            $replace_smileys = 0;
        else
            $replace_smileys = 1;
        echo "<option value=\"1\"";
        if ( $replace_smileys == 1 )
            echo " selected>";
        else
            echo ">";
        echo _VBS_YES."</option>";
        echo "<option value=\"0\"";
        if ( $replace_smileys == 0 )
            echo " selected>";
        else
            echo ">";
        echo _VBS_NO."</option>";
            
        echo "</select></td><td></td></tr>";
// +-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-+-
        echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\""._VB_GENRE_SUBMIT."\"></td><td></td></tr>";
        echo "</table>";
        echo "<input type=\"hidden\" name=\"m\" value=\"1\"></form>";
    }

    require_once (XOOPS_ROOT_PATH."/footer.php");

?>