<?php
    // Set anti-hack flag
    if ( !defined('IN_VIDEOBB') )
        define('IN_VIDEOBB', true);

    require_once "include/db.php";

    function xoops_module_install_videobb( $xoopsMod )
    {
        $ok = true;

        if ( !videobb_insert_options() )
            $ok = false;

        if ( !videobb_add_root("Example root","/tmp/no/such/folder","file://example.com/movies","mpg;vid;wmv;dat;avi;mpeg",'Example root') )
            $ok = false;

        return $ok;
    }

    function xoops_module_uninstall_videobb( $xoopsMod )
    {
        return true;
    }

    function xoops_module_update_videobb ( $xoopsMod, $oldversion)
    {
        $db =& Database::getInstance();

        switch ($oldversion)
        { //remember that version is multiplied with 100 to get an integer
            case 100:
            case 110:
            case 120:
            case 130:
            case 200: //perform actions to upgrade from version 2.00
                // Get our root path
                $videobb_module_root = XOOPS_ROOT_PATH.'/modules/'.$xoopsMod->getVar('dirname');

                $t_tables_ok = true;
                $sql = "CREATE TABLE IF NOT EXISTS ".$db->prefix("videobb_config")." (";
                $sql .= "`id` int(11) NOT NULL auto_increment,";
                $sql .= "`name` varchar(255) NOT NULL default '',";
                $sql .= "`value` text NOT NULL default '',";
                $sql .= "PRIMARY KEY (`id`)";
                $sql .= ");";

                if ( !$db->queryF($sql) )
                {
                    $errors .= "Failed to create tables!<br>$sql<br>";
                    $t_tables_ok = false;
                }
                $sql = "CREATE TABLE IF NOT EXISTS ".$db->prefix("videobb_root")." (";
                $sql .= "`id` int(11) NOT NULL auto_increment,";
                $sql .= "`path` varchar(255) NOT NULL default '',";
                $sql .= "`url` varchar(255) NOT NULL default '',";
                $sql .= "`comment` varchar(255) NOT NULL default '',";
                $sql .= "PRIMARY KEY (`id`)";
                $sql .= ");";

                if ( !$db->queryF($sql) )
                {
                    $errors .= "Failed to create tables!<br>$sql<br>";
                    $t_tables_ok = false;
                }
                $sql = "CREATE TABLE IF NOT EXISTS ".$db->prefix("videobb_video")." (";
                $sql .= "`id` int(11) NOT NULL auto_increment,";
                $sql .= "`uid` int(11) unsigned,";
                $sql .= "`date` date NOT NULL,";
                $sql .= "`name` varchar(255) NOT NULL default '',";
                $sql .= "`genre` varchar(255) default '',";
                $sql .= "`hits` int(11) unsigned,";
                $sql .= "`description` text default '',";
                $sql .= "PRIMARY KEY (`id`)";
                $sql .= ");";

                if ( !$db->queryF($sql) )
                {
                    $errors .= "Failed to create tables!<br>$sql<br>";
                    $t_tables_ok = false;
                }
                $sql = "CREATE TABLE IF NOT EXISTS ".$db->prefix("videobb_comments")." (";
                $sql .= "`id` int(11) NOT NULL auto_increment,";
                $sql .= "`video_id` int(11) unsigned NOT NULL,";
                $sql .= "`ip` varchar(128) NOT NULL default '',";
                $sql .= "`uid` int(11) unsigned NOT NULL,";
                $sql .= "`comment` text NOT NULL,";
                $sql .= "`datetime` datetime NOT NULL,";
                $sql .= "PRIMARY KEY (`id`)";
                $sql .= ");";

                if ( !$db->queryF($sql) )
                {
                    $errors .= "Failed to create tables!<br>$sql<br>";
                    $t_tables_ok = false;
                }
                $sql = "CREATE TABLE IF NOT EXISTS ".$db->prefix("videobb_vote")." (";
                $sql .= "`id` int(11) NOT NULL auto_increment,";
                $sql .= "`video_id` int(11) NOT NULL,";
                $sql .= "`uid` int(11) unsigned NOT NULL,";
                $sql .= "`vote` int(11) NOT NULL,";
                $sql .= "`datetime` datetime NOT NULL,";
                $sql .= "PRIMARY KEY (`id`)";
                $sql .= ");";

                if ( !$db->queryF($sql) )
                {
                    $errors .= "Failed to create tables!<br>$sql<br>";
                    $t_tables_ok = false;
                }

                if ( $t_tables_ok )
                    videobb_insert_options( $db );

                // If we have new tables - we can move old tables data to new...
                if ( $t_tables_ok )
                    videobb_import_le_2_0_table( $db->prefix("vbb_video"), $db->prefix("videobb_video"), true );

                // Drop empty table in 2.0- versions
                $db->queryF("DROP TABLE IF EXISTS ".$db->prefix("vbb_vote").";");

                // move all vote data from files to table
                $vote_dir = $videobb_module_root."/voting";

                if ( $t_tables_ok )
                    $errors .= videobb_import_le_2_0_voting( $vote_dir, true, true );

                // Rename access.log to something unique
                $time = date("Y.m.d.H.i.s", time());
                rename ( $videobb_module_root."/access.log", $videobb_module_root."/access.log.$time" );

                unlink( "$videobb_module_root/include/voting.php" );
            case 210: //perform actions to upgrade from version 2.1
                $sql = "ALTER TABLE `".$db->prefix("videobb_root")."` ADD `enabled` TINYINT UNSIGNED DEFAULT '0' NOT NULL AFTER `id`;";
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_root")." (field `enabled`)!<br>$sql<br>";
                $sql = "ALTER TABLE `".$db->prefix("videobb_root")."` ADD `caption` VARCHAR( 255 ) NOT NULL AFTER `enabled`;";
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_root")." (field `caption`)!<br>$sql<br>";
                $sql = "ALTER TABLE `".$db->prefix("videobb_root")."` ADD `extensions` VARCHAR( 255 ) NOT NULL AFTER `url`;";
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_root")." (field `extensions`)!<br>$sql<br>";

                // 'date' field -> 'ctime' field
                $sql = "ALTER TABLE `".$db->prefix("videobb_video")."` CHANGE `date` `ctime` DATE DEFAULT '0000-00-00' NOT NULL;";
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_video")." (field `ctime`)!<br>$sql<br>";
                $sql = "ALTER TABLE `".$db->prefix("videobb_video")."` ADD `atime` DATE DEFAULT '0000-00-00' NOT NULL AFTER `ctime`;";
                // +'atime' field
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_video")." (field `atime`)!<br>$sql<br>";
                // 'ctime' fiedl data => 'atime' field
                $sql = "UPDATE `".$db->prefix("videobb_video")."` SET `atime` = `ctime` WHERE `ctime` <> '0000-00-00';";
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_video")." (`ctime` field data => `atime` field data )!<br>$sql<br>";

                $sql = "ALTER TABLE `".$db->prefix("videobb_video")."` ADD `image` MEDIUMBLOB default '' AFTER `description`;";
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_video")." (field `image`)!<br>$sql<br>";
                $sql = "ALTER TABLE `".$db->prefix("videobb_video")."` ADD `image_by` INT(11) UNSIGNED DEFAULT 0 NOT NULL AFTER `image`;";
                if ( !$db->queryF($sql) )
                    $errors .= "Failed to alter ".$db->prefix("videobb_video")." (field `image_by`)!<br>$sql<br>";

                if ( videobb_get_option("_VB_ADM_FOLDER_ROOT") != '' ||
                     videobb_get_option("_VB_ADM_FOLDER_ROOT_URL") != '' )
                    videobb_add_root(
                        "Imported",
                        videobb_get_option("_VB_ADM_FOLDER_ROOT"),
                        videobb_get_option("_VB_ADM_FOLDER_ROOT_URL"),
                        videobb_get_option("_VB_ADM_VIDEO_EXTENSIONS"),
                        "Imported root",
                        false
                    );

                videobb_delete_option("_VB_ADM_FOLDER_ROOT");
                videobb_delete_option("_VB_ADM_FOLDER_ROOT_URL");
                videobb_delete_option("_VB_ADM_VIDEO_EXTENSIONS");

                videobb_delete_option("_VB_ADM_INSERT_HR");
            case 220://perform actions to upgrade from version 2.2
                videobb_delete_option("_VB_ADM_RESTRICT_GUEST_POSTING");
            
        }

        if ( isset( $errors ) && $errors != '' )
        {
            $xoopsMod->setErrors($errors);

            echo $errors;
        }

        return true;
    } 
?>