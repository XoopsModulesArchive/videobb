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

    // Just for sure set this to module name (modules/xxx)
    if ( !defined('VBB_MODULE_NAME') )
        if ( isset($xoopsModule) )
            define('VBB_MODULE_NAME',$xoopsModule->getVar('dirname'));
        else
            define('VBB_MODULE_NAME','videobb');

    // Here is relative physical path for faq sections
    $conf_faq_root      = './faq';

    if ( isset($xoopsModule ) )
        $vbb_path       = '/modules/'.$xoopsModule->getVar('dirname');
    else
        $vbb_path       = '/modules/'.VBB_MODULE_NAME;

    $vbb_version        = '2.2';

    if ( !defined('VIDEOBB_INSTALLED') )
        define('VIDEOBB_INSTALLED', true);
?>