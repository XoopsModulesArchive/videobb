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

// Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

//require_once "include/db.php";

$modversion['name'] = _MI_VB_NAME;
$modversion['version'] = "2.3";
$modversion['author'] = "Kutovoy Nickolay";
$modversion['description'] = _MI_VB_DESC;
$modversion['credits'] = "Kutovoy Nickolay<br />( mailto:kutovoy@gmail.com )";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = "images/video-bb.png";
$modversion['dirname'] = "videobb";

$modversion['author_realname'] = "Kutovoy Nickolay";
$modversion['author_website_url'] = "http://www.kutovoy.com.ua/";
$modversion['author_website_name'] = "Kutovoy's Homepage";
$modversion['author_email'] = "kutovoy@gmail.com";

// Templates
$modversion['templates'][1]['file'] = 'videobb_footer.html';
$modversion['templates'][1]['description'] = 'Footer template';
$modversion['templates'][2]['file'] = 'videobb_header.html';
$modversion['templates'][2]['description'] = 'Header template';
$modversion['templates'][3]['file'] = 'videobb_folder_view.html';
$modversion['templates'][3]['description'] = 'Folder view template';
$modversion['templates'][4]['file'] = 'videobb_file_view.html';
$modversion['templates'][4]['description'] = 'File view template';
$modversion['templates'][5]['file'] = 'videobb_root_view.html';
$modversion['templates'][5]['description'] = 'Root template';
$modversion['templates'][6]['file'] = 'videobb_media_view.html';
$modversion['templates'][6]['description'] = 'Media view template';


// Menu
$modversion['hasMain'] = 1;
$modversion['sub'][1]['name'] = _MI_VB_SETTINGS;
$modversion['sub'][1]['url'] = "settings.php";

global $xoopsModule, $xoopsUser;
if (is_object($xoopsModule) && $xoopsModule->getVar('dirname') == $modversion['dirname'])
{
//    if ( videobb_get_option("_VB_ADM_USE_FAQ") )
//    {
        $modversion['sub'][2]['name'] = _MI_VB_FAQ;
        $modversion['sub'][2]['url'] = "faq.php";
//    }

    if ( is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid()) )
    {
        $modversion['sub'][3]['name'] = _MI_VB_LOG;
        $modversion['sub'][3]['url'] = "access.log.php";
    }

//    if ( videobb_get_option("_VB_ADM_USE_FEEDBACK") )
//    {
        $modversion['sub'][4]['name'] = _MI_VB_FEEDBACK;
        $modversion['sub'][4]['url'] = "feedback.php";
//    }
}

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'vid';
$modversion['comments']['pageName'] = 'index.php';
$modversion['comments']['extraParams'] = array('folder','rid');

// Admin things
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = "admin/index.php";
$modversion['adminmenu'] = "admin/menu.php";

// Search
//$modversion['hasSearch'] = 1;
//$modversion['search']['file'] = "include/search.inc.php";
//$modversion['search']['func'] = "videobb_search";

$modversion['sqlfile']['mysql'] = "sql/mysql.sql";

$modversion['tables'][0]   = "videobb_video";
$modversion['tables'][1]   = "videobb_vote";
$modversion['tables'][2]   = "videobb_config";
$modversion['tables'][3]   = "videobb_root";
$modversion['tables'][4]   = "videobb_comments";

// Install && Uninstall
$modversion['onInstall'] = 'install_funcs.php';
$modversion['onUninstall'] = 'install_funcs.php';
$modversion['onUpdate'] = 'install_funcs.php'; 

?>