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
    require_once '../../../include/cp_header.php';
    if ( file_exists("../language/".$xoopsConfig['language']."/main.php") )
    {
        include "../language/".$xoopsConfig['language']."/main.php";
        include_once "../language/".$xoopsConfig['language']."/modinfo.php";
    }
    else
    {
        include "../language/english/main.php";
        include_once "../language/english/modinfo.php";
    }

    // Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);
    require_once '../include/db.php';

    function prepare_yes_no_option( $option, $name, $value='' )
    {
        if ( !isset( $value ) || $value == '' )
            $value = videobb_get_option($option);

        $h = "<tr><th>".constant($option)."</th><td>";
        $h .= "<select name=\"$name\">";
        $h .= "<option value=\"1\"";
        if ( $value )
            $h .= " selected";
        $h .= ">"._VB_ADM_YES."</option>";
        $h .= "<option value=\"0\"";
        if ( !$value )
            $h .= " selected";
        $h .= ">"._VB_ADM_NO."</option>";
        $h .= "</select></td></tr>";

        return $h;
    }

    function adminEntryWrapper( $title, $index, $op)
    {
        xoops_cp_header();
        adminmenu($index,$title);
        echo "<fieldset><legend style='font-weight: bold; color: #900;'>
                     ".$title."</legend>";
        echo "<br><br><table width='100%' border='0' cellspacing='1' class='outer'>
                     <tr><td class=\"odd\">";

        $op();

        echo "</td></tr></table>";
        echo "</fieldset>";
            
        xoops_cp_footer();
    }

    function adminmenu ( $currentoption=0,$breadcrumb )
    {      
        function adminHeaderEntry( $title, $url, $color='#DDE' )
        {
            echo "<li style=\"list-style: none; margin: 0; display: inline; \">
             <a href=\"$url\" style=\"padding: 3px 0.5em;
             margin-left: 3px;
             border: 1px solid #778; background: $color;
             text-decoration: none; \">$title</a></li>";
        }
        global $xoopsModule, $xoopsConfig;

        $tblColors=Array();

        $tblColors[0] = $tblColors[1] = $tblColors[2] = $tblColors[3] = $tblColors[4] = $tblColors[5] = '#DDE';

        $tblColors[$currentoption]='white';

        echo "<table width=100% class'outer'><tr><td align=right>
              <font size=2>"._MD_A_MODULEADMIN." ".$xoopsModule->name().":".$breadcrumb."</font>
              </td></tr></table><br>";
        echo "<div id=\"navcontainer\"><ul style=\"padding: 3px 0; margin-left:
             0;font: bold 12px Verdana, sans-serif; \">";


        adminHeaderEntry( _MI_VB_NAME, XOOPS_URL.'/modules/'.$xoopsModule->name().'/index.php' );
        adminHeaderEntry( _MD_A_ROOT, 'index.php?op=root', $tblColors[0] );
        adminHeaderEntry( _MD_A_EDITCSETTINGS, 'index.php?op=csettings', $tblColors[1] );
        adminHeaderEntry( _MD_A_EDITSSETTINGS, 'index.php?op=ssettings', $tblColors[2] );
        adminHeaderEntry( _MD_A_IMAGES, 'index.php?op=images', $tblColors[3] );
        adminHeaderEntry( _MD_A_IMPORT, 'index.php?op=import', $tblColors[4] );
        adminHeaderEntry( _MD_A_PRUNE, 'index.php?op=prune', $tblColors[5] );
        echo "<br><br>";
    } 

    function op_root()
    {
        $db =& Database::getInstance();
        $myts =& MyTextSanitizer::getInstance();

        if ( !isset($_GET["sop"]) )
        {
            echo "<form action=\"index.php?op=root&sop=edit\" method=\"POST\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<input type=\"submit\" value=\""._VB_ADM_ROOT_ADD."\">";
            echo "</form>";

            $roots = videobb_list_root();

            if ( $roots != false )
            {
                echo "<table><tr>";
                echo "<th>"._VB_ADM_ROOT_ENABLED."</th><th>"._VB_ADM_ROOT_CAPTION."</th><th>"._VB_ADM_ROOT_PATH."</th><th>"._VB_ADM_ROOT_URL."</th><th>"._VB_ADM_ROOT_EXTENSIONS."</th><th>"._VB_ADM_ROOT_COMMENT."</th><th>"._VB_ADM_ROOT_EDIT."</th><th>"._VB_ADM_ROOT_DELETE."</th></tr>";

                foreach ( $roots as $root )
                {
                    echo "<tr><td>";
                    echo $root['enabled']?_VB_ADM_YES:_VB_ADM_NO;
                    echo "</td>";
                    echo "<td>".$myts->htmlSpecialChars($root['caption'])."</td><td>".$myts->htmlSpecialChars($root['path'])."</td><td>".$myts->htmlSpecialChars($root['url'])."</td><td>".$myts->htmlSpecialChars($root['extensions'])."</td><td>".$myts->htmlSpecialChars($root['comment'])."</td>";
                    echo "<td><a href=\"index.php?op=root&sop=edit&id=".$root['id']."\">[*]</a></td>";
                    echo "<td><a href=\"index.php?op=root&sop=delete&id=".$root['id']."\">[X]</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
            }
        }
        else if ( isset($_GET["sop"]) && $_GET["sop"] == "edit" )
        {
            if ( !isset($_GET['m']) || intval($_GET['m']) != 1 )
            {
                if ( isset($_GET['id']) )
                {
                    $root = videobb_get_root(intval($_GET['id']));
                    if ( $root == false )
                        unset( $root );
                }
                else if ( !$GLOBALS['xoopsSecurity']->check() )
                {
                    redirect_header("index.php?op=root", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                    exit();
                }

                if ( isset($_GET['id']) )
                    $id = intval($_GET['id']);
                else
                    $id = '';
                echo "<form action=\"index.php?op=root&sop=edit&m=1&id=".$id."\" method=\"POST\">";
                echo $GLOBALS['xoopsSecurity']->getTokenHTML();
                echo "<table>";
                //
                echo "<tr><th>"._VB_ADM_ROOT_ENABLED."</th><td>";
                if ( isset( $root ) && isset( $root['enabled'] ) )
                    $optvalue = $root['enabled'];
                else
                    $optvalue = 1;
                echo "<select name=\"renabled\">";
                echo "<option value=\"1\"";
                if ( $optvalue )
                    echo " selected";
                echo ">"._VB_ADM_YES."</option>";
                echo "<option value=\"0\"";
                if ( !$optvalue )
                    echo " selected";
                echo ">"._VB_ADM_NO."</option>";
                "</select></td></tr>";
                //
                echo "<tr><th>"._VB_ADM_ROOT_CAPTION."</th><td><input type=\"text\" name =\"rcapt\" size=\"64\" value=\"";
                if ( isset( $root ) && isset ( $root['caption'] ) )
                    echo $root['caption'];
                echo "\"></td></tr>";
                //
                echo "<tr><th>"._VB_ADM_ROOT_PATH."</th><td><input type=\"text\" name =\"rpath\" size=\"64\" value=\"";
                if ( isset( $root ) && isset ( $root['path'] ) )
                    echo $root['path'];
                echo "\"></td></tr>";
                //
                echo "<tr><th>"._VB_ADM_ROOT_URL."</th><td><input type=\"text\" name =\"rurl\" size=\"64\" value=\"";
                if ( isset( $root ) && isset ( $root['url'] ) )
                    echo $root['url'];
                echo "\"></td></tr>";
                //
                echo "<tr><th>"._VB_ADM_ROOT_EXTENSIONS."</th><td><input type=\"text\" name =\"rext\" size=\"64\" value=\"";
                if ( isset( $root ) && isset ( $root['extensions'] ) )
                    echo $root['extensions'];
                echo "\"></td></tr>";
                //
                echo "<tr><th>"._VB_ADM_ROOT_COMMENT."</th><td><input type=\"text\" name =\"rcom\" size=\"64\" value=\"";
                if ( isset( $root ) && isset ( $root['comment'] ) )
                    echo $root['comment'];
                echo "\"></td></tr>";
                echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
                echo "</table>";
            }
            else
            {
                if ( !$GLOBALS['xoopsSecurity']->check() )
                {
                    redirect_header("index.php?op=root&sop=edit&id=".$_GET['id'], 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                    exit();
                }

                if ( !isset( $_POST['rpath'] ) || !isset( $_POST['rurl'] ) || !isset( $_POST['rext'] ) || !isset( $_POST['rcom'] ) )
                {
                    redirect_header("index.php?op=root&sop=edit&id=".$_GET['id'], 3, _VB_ADM_PLEASE_FILL_ALL_ROOT);

                    exit();
                }

                if ( isset( $_GET['id'] ) && $_GET['id'] != '' )
                {
                    if ( !videobb_update_root(intval( $_GET['id'] ),$_POST['rcapt'],$_POST['rpath'],$_POST['rurl'],$_POST['rext'],$_POST['rcom'],$_POST['renabled']) )
                    {
                        redirect_header("index.php?op=root&sop=edit&id=".$_GET['id'], 3, _VB_ADM_ROOT_EDIT_FAILED);

                        exit();
                    }
                }
                else
                {
                    if ( !videobb_add_root($_POST['rcapt'],$_POST['rpath'],$_POST['rurl'],$_POST['rext'],$_POST['rcom'],$_POST['renabled']) )
                    {
                        redirect_header("index.php?op=root&sop=edit", 3, _VB_ADM_ROOT_ADD_FAILED);

                        exit();
                    }
                }

                redirect_header("index.php?op=root", 3, _VB_ADM_ROOT_OK);

                exit();
            }
        }
        else if ( isset($_GET["sop"]) && $_GET["sop"] == "delete" )
        {
            if ( !isset($_GET['m']) || intval($_GET['m']) != 1 )
            {
                if ( !isset( $_GET['id'] ) && $_GET['id'] == '' )
                {
                    redirect_header("index.php?op=root", 3, _VB_ADM_ROOT_DELETE_FAILED);

                    exit();
                }

                echo "<form action=\"index.php?op=root&sop=delete&m=1&id=".$_GET['id']."\" method=\"POST\">";
                echo $GLOBALS['xoopsSecurity']->getTokenHTML();
                echo "<table>";
                echo "<tr><td>"._VB_ADM_ROOT_DELETE_CONFIRM."</td></tr>";
                echo "<tr><td><input type=\"submit\" value=\""._VB_ADM_DELETE."\"></td></tr>";
                echo "</table>";
            }
            else
            {
                if ( !$GLOBALS['xoopsSecurity']->check() )
                {
                    redirect_header("index.php?op=root", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                    exit();
                }

                if ( isset( $_GET['id'] ) && $_GET['id'] != '' )
                {
                    if ( videobb_delete_root(intval($_GET['id']) ) )
                    {
                        redirect_header("index.php?op=root", 3, _VB_ADM_ROOT_OK);

                        exit();
                    }
                }
            
                redirect_header("index.php?op=root", 3, _VB_ADM_ROOT_DELETE_FAILED);

                exit();
            }
        }
    }

    function op_csettings()
    {
        if ( !isset($_GET["m"]) || $_GET["m"] != "1" )
        {
            echo "<form method=\"POST\" action=\"index.php?op=csettings&m=1\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table>";
            // 
            echo prepare_yes_no_option('_VB_ADM_SHOW_UAGENT_WARNING',"show_uaw");
            // 
            echo prepare_yes_no_option('_VB_ADM_SHOW_LICENSE_WARNING',"show_lw");
            //
            echo "<tr><th>"._VB_ADM_LICENSE."</th><td>";
            echo "<textarea name=\"wlicense\" rows=\"6\" cols=\"10\" wrap=\"virtual\">".videobb_get_option("_VB_ADM_LICENSE")."</textarea></td></tr>";
            // 
            echo prepare_yes_no_option('_VB_ADM_RESTRUCTURIZE_MOVIES',"restr_movies");
            // 
            echo prepare_yes_no_option('_VB_ADM_UPDATE_INFO_IN_FILES',"upd_ifiles");
            // 
            echo prepare_yes_no_option('_VB_ADM_UPDATE_INFO_FROM_FILES',"upd_ffiles");
            // 
            echo prepare_yes_no_option('_VB_ADM_USE_FAQ',"use_faq");
            // 
            echo prepare_yes_no_option('_VB_ADM_USE_FEEDBACK',"use_feedback");
            // 
            echo "<tr><td colspan=\"1\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "</table>";
        }
        else
        {
            if ( !$GLOBALS['xoopsSecurity']->check() )
            {
                redirect_header("index.php?op=csettings", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                exit();
            }
            videobb_set_option("_VB_ADM_SHOW_UAGENT_WARNING",intval($_REQUEST["show_uaw"]));
            videobb_set_option("_VB_ADM_SHOW_LICENSE_WARNING",intval($_REQUEST["show_lw"]));
            videobb_set_option("_VB_ADM_LICENSE",$_REQUEST["wlicense"]);
            videobb_set_option("_VB_ADM_RESTRUCTURIZE_MOVIES",intval($_REQUEST["restr_movies"]));
            videobb_set_option("_VB_ADM_UPDATE_INFO_IN_FILES",intval($_REQUEST["upd_ifiles"]));
            videobb_set_option("_VB_ADM_UPDATE_INFO_FROM_FILES",intval($_REQUEST["upd_ffiles"]));
            videobb_set_option("_VB_ADM_USE_FEEDBACK",intval($_REQUEST["use_feedback"]));
            videobb_set_option("_VB_ADM_USE_FAQ",intval($_REQUEST["use_faq"]));
            
            redirect_header("index.php?op=csettings", 3, _MD_A_SAVED );

            exit();
            
        }
    }

    function op_ssettings()
    {
        if ( !isset($_GET["m"]) || $_GET["m"] != "1" )
        {
            echo "<form method=\"POST\" action=\"index.php?op=ssettings&m=1\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table>";
            //
            echo "<tr><th>"._VB_ADM_VIM_ADMINS."</th><td><input type=\"text\" name=\"vim_admins\" value=\"".videobb_get_option("_VB_ADM_VIM_ADMINS")."\"></td></tr>";
            // 
            echo prepare_yes_no_option('_VB_ADM_ACCESS_LOG',"logging");
            // 
            echo "<tr><th>"._VB_ADM_ACCESS_LOG_IGNORE_IP."</th><td><input type=\"text\" name=\"logging_iip\" value=\"".videobb_get_option("_VB_ADM_ACCESS_LOG_IGNORE_IP")."\"></td></tr>";
            //
            echo "<tr><th>"._VB_ADM_HITS_IGNORE_IP."</th><td><input type=\"text\" name=\"hits_iip\" value=\"".videobb_get_option("_VB_ADM_HITS_IGNORE_IP")."\"></td></tr>";
            
            echo "<tr><td colspan=\"1\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "</table>";
        }
        else
        {
            if ( !$GLOBALS['xoopsSecurity']->check() )
            {
                redirect_header("index.php?op=ssettings", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                exit();
            }

            videobb_set_option("_VB_ADM_VIM_ADMINS",$_REQUEST["vim_admins"]);
            videobb_set_option("_VB_ADM_ACCESS_LOG",intval($_REQUEST["logging"]));
            videobb_set_option("_VB_ADM_ACCESS_LOG_IGNORE_IP",$_REQUEST["logging_iip"]);
            videobb_set_option("_VB_ADM_HITS_IGNORE_IP",$_REQUEST["hits_iip"]);

            redirect_header("index.php?op=ssettings", 3, _MD_A_SAVED );

            exit();
            
        }
    }

    function op_images()
    {
        if ( !isset($_GET["m"]) || $_GET["m"] != "1" )
        {
            echo "<form method=\"POST\" action=\"index.php?op=images&m=1\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table>";
            //
            echo prepare_yes_no_option('_VB_ADM_USE_IMAGES',"vim_enabled");
            //
            echo "<tr><th>"._VB_ADM_UPL_IMG_MAX_SIZE."</th><td><input type=\"text\" name=\"vim_max_size\" value=\"".videobb_get_option("_VB_ADM_UPL_IMG_MAX_SIZE")."\"></td></tr>";
            //
            echo "<tr><th>"._VB_ADM_UPL_IMG_MAX_X."</th><td><input type=\"text\" name=\"vim_max_x\" value=\"".videobb_get_option("_VB_ADM_UPL_IMG_MAX_X")."\"></td></tr>";
            //
            echo "<tr><th>"._VB_ADM_UPL_IMG_MAX_Y."</th><td><input type=\"text\" name=\"vim_max_y\" value=\"".videobb_get_option("_VB_ADM_UPL_IMG_MAX_Y")."\"></td></tr>";
            //
            echo prepare_yes_no_option('_VB_ADM_UPL_IMG_CENTER',"vim_center");
            //
            echo prepare_yes_no_option('_VB_ADM_UPL_IMG_STRETCH_IF_LT',"vim_str_if_lt");
            //
            echo prepare_yes_no_option('_VB_ADM_UPL_IMG_STRETCH_IF_GT',"vim_str_if_gt");
            
            echo "<tr><td colspan=\"1\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "</table>";
        }
        else
        {
            if ( !$GLOBALS['xoopsSecurity']->check() )
            {
                redirect_header("index.php?op=images", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                exit();
            }

            // Some restrictions apply
            $vim_max_size = intval($_REQUEST["vim_max_size"]);
            if ( $vim_max_size < 0 )
                $vim_max_size = 0;
            else if ( $vim_max_size > 65536 )
                $vim_max_size = 65536;

            $vim_max_x = intval($_REQUEST["vim_max_x"]);
            if ( $vim_max_x < 1 )
                $vim_max_x = 1;
            else if ( $vim_max_x > 2048 )
                $vim_max_x = 2048;
            
            $vim_max_y = intval($_REQUEST["vim_max_y"]);
            if ( $vim_max_y < 1 )
                $vim_max_y = 1;
            else if ( $vim_max_y > 2048 )
                $vim_max_y = 2048;

            videobb_set_option("_VB_ADM_USE_IMAGES",intval($_REQUEST["vim_enabled"]));
            videobb_set_option("_VB_ADM_UPL_IMG_MAX_SIZE",$vim_max_size);
            videobb_set_option("_VB_ADM_UPL_IMG_MAX_X",$vim_max_x);
            videobb_set_option("_VB_ADM_UPL_IMG_MAX_Y",$vim_max_y);
            videobb_set_option("_VB_ADM_UPL_IMG_CENTER",intval($_REQUEST["vim_center"]));
            videobb_set_option("_VB_ADM_UPL_IMG_STRETCH_IF_LT",intval($_REQUEST["vim_str_if_lt"]));
            videobb_set_option("_VB_ADM_UPL_IMG_STRETCH_IF_GT",intval($_REQUEST["vim_str_if_gt"]));

            redirect_header("index.php?op=images", 3, _MD_A_SAVED );

            exit();
            
        }
    }

    function op_import()
    {
        $db =& Database::getInstance();

        if ( !isset($_GET["m"]) || ( $_GET["m"] != "1" && $_GET["m"] != "2" ) )
        {
            echo "<form method=\"POST\" action=\"index.php?op=import&m=1\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table>";
            //
            echo "<tr><th>"._VB_ADM_IMPORT_LE_20_TABLE."</th><td><select name=\"import_le_20_table\">";
            
            $result = $db->queryF("SHOW TABLES FROM ".XOOPS_DB_NAME.";");
    
            while ( $row = $db->fetchArray($result) )
            {
                foreach ($row as $key => $value)
                    echo "<option value=\"$value\">$value</option>";
            }
            
            echo "</select></td></tr>";
            //
            
            echo "<tr><td colspan=\"1\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "<form method=\"POST\" action=\"index.php?op=import&m=2\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            //
            echo "<tr><th>"._VB_ADM_IMPORT_LE_20_VOTING."</th><td>".XOOPS_ROOT_PATH."/<select name=\"import_le_20_voting\">";
            if ( $dir = opendir( ".." ) )
            {
                while ( ($fname = readdir( $dir ) ) != false )
                {
                    // Skip "." && ".."
                    if ( $fname[0] == "." )
                        continue;

                    // Skip subdirectories
                    if ( is_file( "../$fname" ) )
                        continue;
    
                    echo "<option value=\"modules/videobb/$fname\">modules/videobb/$fname</option>";
                }
            }
            
            echo "</select></td></tr>";
            //
            echo "<tr><td colspan=\"1\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "</form><form method=\"POST\" action=\"index.php?op=import&m=2\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<tr><td colspan=\"2\">"._VB_ADM_OR."</td></tr>";
            echo "<tr><th>"._VB_ADM_IMPORT_LE_20_VOTING."</th><td>".XOOPS_ROOT_PATH."/<input type=\"text\" name=\"import_le_20_voting\" value=\"\"></td></tr>";
            //
            
            echo "<tr><td colspan=\"1\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "</table></form>";
        }
        else if ( isset($_GET["m"]) && $_GET["m"] == "1" )
        {
            if ( !$GLOBALS['xoopsSecurity']->check() )
            {
                redirect_header("index.php?op=import", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                exit();
            }


            if ( isset($_REQUEST["import_le_20_table"]) && $_REQUEST["import_le_20_table"] != "" )
                videobb_import_le_2_0_table($_REQUEST["import_le_20_table"] );

            redirect_header("index.php?op=import", 3, _MD_A_IMPORTED );

            exit();
            
        }
        else if ( isset($_GET["m"]) && $_GET["m"] == "2" )
        {
            if ( !$GLOBALS['xoopsSecurity']->check() )
            {
                redirect_header("index.php?op=import", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                exit();
            }

            if ( isset($_REQUEST["import_le_20_voting"]) && $_REQUEST["import_le_20_voting"] != "" )
                videobb_import_le_2_0_voting(XOOPS_ROOT_PATH."/".$_REQUEST["import_le_20_voting"] );

            redirect_header("index.php?op=import", 3, _MD_A_IMPORTED );

            exit();
            
        }
    }

    function op_prune()
    {
        $db =& Database::getInstance();

        if ( !isset($_GET["m"]) || ( $_GET["m"] != "1" && $_GET["m"] != "2" ) )
        {
            echo "<form method=\"POST\" action=\"index.php?op=prune&m=1\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
            echo "<table>";
            echo "<tr><th colspan=\"2\">"._VB_ADM_PRUNE_OLD_INFO."</th></tr>";
            //
            echo "<tr><td>"._VB_ADM_PRUNE_OLDER_THAN."</td><td><input type=\"text\" name=\"prune_days\" value=\"93\"> "._VB_ADM_PRUNE_OLDER_THAN_DAYS."<br>";
            //
            echo "<input type=\"checkbox\" name=\"prune_lo\" value=\"1\" checked=\"checked\">"._VB_ADM_PRUNE_LIST_ONLY."</td></tr>";
            //
            echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "</form>";
            //
            echo "<form method=\"POST\" action=\"index.php?op=prune&m=2\">";
            echo $GLOBALS['xoopsSecurity']->getTokenHTML();
//            echo "<tr><th colspan=\"2\">"._VB_ADM_PRUNE_OLD_INFO."</th></tr>";
            //
            echo "<tr><td>"._VB_ADM_PRUNE_IMAGES_BIGGER."</td><td><input type=\"text\" name=\"prune_size\" value=\"".videobb_get_option("_VB_ADM_UPL_IMG_MAX_SIZE")."\"> "._VB_ADM_PRUNE_IMAGES_BIGGER_THAN."<br>";
            //
            echo "<input type=\"checkbox\" name=\"prune_lo\" value=\"1\" checked=\"checked\">"._VB_ADM_PRUNE_LIST_ONLY."</td></tr>";
            //
            echo "<tr><td colspan=\"2\"><input type=\"submit\" value=\""._VB_ADM_SUBMIT."\"></td></tr>";
            echo "</table>";
            echo "</form>";
        }
        else if ( isset($_GET["m"]) && $_GET["m"] == "1" )
        {
            if ( !$GLOBALS['xoopsSecurity']->check() )
            {
                redirect_header("index.php?op=prune", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                exit();
            }

            if ( isset($_REQUEST['prune_lo']) )
                $prune_lo = intval($_REQUEST['prune_lo']);
            else
                $prune_lo = 0;

            if ( isset($_REQUEST["prune_days"]) && $_REQUEST["prune_days"] != "" )
            {
                $r = videobb_prune_video_older_than(intval($_REQUEST['prune_days']),$prune_lo);

                echo _VB_ADM_PRUNE_LIST."<hr>";
                foreach( $r['list'] as $ritem )
                {
                    echo $ritem."<br>";
                }
                echo "<hr>"._VB_ADM_PRUNE_LIST_TOTAL.":".($r['count']?$r['count']:0)."<br>";
            }
        }
        else if ( isset($_GET["m"]) && $_GET["m"] == "2" )
        {
            if ( !$GLOBALS['xoopsSecurity']->check() )
            {
                redirect_header("index.php?op=prune", 3, implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));

                exit();
            }

            if ( isset($_REQUEST['prune_lo']) )
                $prune_lo = intval($_REQUEST['prune_lo']);
            else
                $prune_lo = 0;

            if ( isset($_REQUEST["prune_size"]) && $_REQUEST["prune_size"] != "" )
            {
                $r = videobb_prune_video_images_bigger(intval($_REQUEST['prune_size']),$prune_lo);

                echo _VB_ADM_PRUNE_LIST."<hr>";
                foreach( $r['list'] as $ritem )
                {
                    echo $ritem."<br>";
                }
                echo "<hr>"._VB_ADM_PRUNE_LIST_TOTAL.":".($r['count']?$r['count']:0)."<br>";
            }
        }
    }

    $op = 'root';

    if ( isset( $_GET['op'] ) && $_GET['op'] != '' )
    {
        $op = $_GET['op'];
    }

    switch ($op)
    {
        default:
        case "root":
            adminEntryWrapper(_MD_A_ROOT,0,'op_root');
            break;
        case "csettings":
            adminEntryWrapper(_MD_A_EDITCSETTINGS,1,'op_csettings');
            break;
        case "ssettings":
            adminEntryWrapper(_MD_A_EDITSSETTINGS,2,'op_ssettings');
            break;
        case "images":
            adminEntryWrapper(_MD_A_IMAGES,3,'op_images');
            break;
        case "import":
            adminEntryWrapper(_MD_A_IMPORT,4,'op_import');
            break;
        case "prune":
            adminEntryWrapper(_MD_A_PRUNE,5,'op_prune');
            break;
    }
?>
