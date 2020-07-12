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

    require_once ($videobb_root_path.'include/config.php');
    require_once "$videobb_root_path/include/db.php";
    require_once "$videobb_root_path/include/tools.php";

    if ( !videobb_get_option("_VB_ADM_USE_FEEDBACK") )
    {
            redirect_header( "index.php", 3, _VB_DISABLED );
    }

    $myts =& MyTextSanitizer::getInstance();

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

    if ( isset($_REQUEST["admin"]) && $_REQUEST["admin"] != '' )
    {
        if ( $xoops_admin )
        {
            $db =& Database::getInstance();

            if ( $_REQUEST["admin"] == 'view' )
            {
                $result = $db->queryF("SELECT * FROM ".$db->prefix("videobb_comments")." WHERE video_id = 0;" );
                if ( $result )
                {
                    if ( $db->getRowsNum($result) < 1 )
                    {
                        redirect_header('feedback.php',3,_VBF_NO_FEEDBACK);

                        exit();
                    }

                    echo "<table>";
                    echo "<tr><th colspan=\"2\"><a href='javascript:history.go(-1)'>"._VBF_GO_BACK."</a>&nbsp;&bull;&nbsp;"._VBF_VIEW."</th></tr>";
                    while ( $row = $db->fetchArray($result) )
                    {
                        echo "<tr><td>";
                        
                        echo "<table>";
                        echo "<tr><th>"._VBF_FROM.":</th><td>".xoops_getLinkedUnameFromId($row['uid'])."</td></tr>";
                        echo "<tr><th></th><td>(".$row['ip']."/".$row['datetime'].")</td></tr>";
                        echo "<tr><th>"._VBF_SUBJECT.":</th><td>".$row['subject']."</td></tr>";
                        echo "<tr><th></th><td><a href=\"feedback.php?admin=delete&id=".$row['id']."\"><img src=\"".XOOPS_URL."/images/icons/delete.gif\"></a>";
                        echo "&nbsp;<a href=\"".XOOPS_URL."/pmlite.php?send2=1&to_userid=".$row['uid']."\" target=\"_blank\"><img src=\"".XOOPS_URL."/images/icons/pm.gif\"></a>";
                        echo "</td></tr></table>";

                        echo "</td><td width=\"100%\"></td></tr>";
                        echo "<tr><td><textarea rows=\"15\" cols=\"78\" wrap=\"virtual\">".stripslashes($row['comment'])."</textarea></td><td></td></tr>";
                    }
                    echo "</table>";
                }
                else
                {
                        redirect_header('feedback.php',3,_VBF_NO_FEEDBACK);

                        exit();
                }

            } else if ( $_REQUEST['admin'] == 'delete' )
            {

                if ( isset($_REQUEST['m']) && intval($_REQUEST['m']) == 1 )
                {
                    if ( $db->queryF("DELETE FROM ".$db->prefix("videobb_comments")." WHERE id = ".intval($_REQUEST['id'])." AND video_id = 0;" ) )
                        redirect_header('feedback.php?admin=view',3,_VBF_DELETED_FEEDBACK);
                    else
                        redirect_header('feedback.php?admin=view',3,_VBF_FAILED_DELETE);
                    exit();
                }
                else if ( isset($_REQUEST['m']) && intval($_REQUEST['m']) == 2 )
                {
                        redirect_header('feedback.php?admin=view',3,_VBF_CANCELED_DELETE);

                        exit();
                }
                else
                {
                    echo _VBF_CONFIRM_DELETING."<br>";

                    echo "<form action=\"feedback.php?admin=delete&id=".intval($_REQUEST['id'])."&m=1\" method=\"POST\">";
                    echo "<input type=\"submit\" value=\""._VBF_DELETE."\">";
                    echo "</form>";
                    echo "<form action=\"feedback.php?admin=delete&id=".intval($_REQUEST['id'])."&m=2\" method=\"POST\">";
                    echo "<input type=\"submit\" value=\""._VBF_CANCEL."\">";
                    echo "</form>";
                }

            }

            require_once (XOOPS_ROOT_PATH."/footer.php");
            return;
        }
        else
        {
            redirect_header("feedback.php",3,_VB_GUEST_NAME);
        }
    }

    $from   = "";
    $title  = "";
    $text   = "";
    $ip = $_SERVER['REMOTE_ADDR'];

    // Prepare users uid
    if ( is_object($xoopsUser) ) 
    {
        $xoops_uid   = $xoopsUser->getVar('uid');
        $xoops_uname = $xoopsUser->getVar('uname');
    }
    else
    {
        $xoops_uid   = 0;
        $xoops_uname = _VB_GUEST_NAME;
    }
    $xoops_ulink = xoops_getLinkedUnameFromId($xoops_uid);

    if ( isset( $_REQUEST['title'] ) || isset( $_REQUEST['text'] ) )
    {
        $title  = $myts->htmlSpecialChars(urldecode($_REQUEST['title']));
        $text   = $myts->htmlSpecialChars(urldecode($_REQUEST['text']));

        if ( ( $title == "" ) || ( $text == "" ) )
        {
            redirect_header( "feedback.php", 3, _VBF_NOT_FILLED );
            
            exit();
        }
    }

    if ( $title != "" )
    {
        if ( !videobb_add_feedback( $title, $xoops_uid, $ip, $text ) )
        {
            redirect_header( "feedback.php", 3, _VBF_SEND_ERROR );

            exit();
        }

        
        redirect_header( "feedback.php", 3, _VBF_SENT );
    } else {
        if ( $xoops_admin )
        {
            echo "<a href=\"".XOOPS_URL."$vbb_path/feedback.php?admin=view\">"._VBF_VIEW."</a><hr>";
        }
        echo "<table><form action=\"".XOOPS_URL."$vbb_path/feedback.php\" method=\"POST\">";
?>
<tr><th colspan="2"><center><?php echo _VBF_CAPTION; ?><center></th></tr>
<tr><th><?php echo _VBF_SUBJECT; ?></th><td width="100%"></td></tr>
<tr><td class="blockContent"><input type=text name="title" value="<?php echo _VBF_DEF_SUBJECT; ?>" size="104"></td><td></td></tr>
<tr><th><?php echo _VBF_FROM; ?></th><td></td></tr>
<tr><td class="blockContent"><?php echo $xoops_ulink; ?></td><td></td></tr>
<tr><th><?php echo _VBF_TEXT; ?></th><td></td></tr>
<tr><td class="blockContent">
<textarea name="text" rows="15" cols="78" wrap="virtual"></textarea>
</td><td></td></tr>
<tr><td class="blockContent"><input type=submit value="<?php echo _VBF_SUBMIT; ?>"></td><td></td></tr>
</form></table>
<?php
    }

    require_once (XOOPS_ROOT_PATH."/footer.php");

?>