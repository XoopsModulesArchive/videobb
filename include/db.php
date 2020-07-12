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

    function videobb_update_video($uid,$date,$vname,$genre,$hits_inc,$description)
    {
        global $videobb_video_id,$videobb_video;

        $myts =& MyTextSanitizer::getInstance();
        $genre = $myts->censorString($genre);
        $description = $myts->censorString($description);

        $xoopsDb =& Database::getInstance();

        if ( isset($videobb_video[$vname]) )
            $row = $videobb_video[$vname];
        else
            $result = $xoopsDb->queryF( "SELECT id,uid,hits FROM ".$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname).";" );

        // Update if we have record
        if ( isset($row) || ( $result && $row = $xoopsDb->fetchArray($result) ) )
        {
            $sql = "UPDATE ".$xoopsDb->prefix('videobb_video')." SET";
            $sql .= " atime = '".date('Y-m-d',time())."' ";
            if ( isset( $videobb_video_id[$row['id']] ) )
                $videobb_video_id[$row['id']]['atime'] = date('Y-m-d',time());

            if ( isset($uid) && $uid > 0 &&
                ( $row["uid"] == '' || !isset($row["uid"]) || $row["uid"] < 1 )
               )
            {
                $sql .= ", uid = ".$uid." ";
                if ( isset( $videobb_video_id[$row['id']] ) )
                    $videobb_video_id[$row['id']]['uid'] = $uid;
            }
            if ( isset($date) && ( !isset($row["ctime"]) || $row["ctime"] == '' || $row["ctime"] == '0000-00-00' ) )
            {
                $sql .= ", ctime = '".$date."' ";
                if ( isset( $videobb_video_id[$row['id']] ) )
                    $videobb_video_id[$row['id']]['ctime'] = $date;
            }
            if ( isset($genre) && $genre != "" )
            {
                $sql .= ", genre = ".$xoopsDb->quoteString($genre)." ";
                if ( isset( $videobb_video_id[$row['id']] ) )
                    $videobb_video_id[$row['id']]['genre'] = $genre;
            }

            // Skip hits from ignored ip list
            $ignore_ip = explode( ';', videobb_get_option("_VB_ADM_HITS_IGNORE_IP") );
            $skip_ip = false;

            foreach ( $ignore_ip as $ip )
            {
                if ( $_SERVER['REMOTE_ADDR'] == $ip )
                {
                    $skip_ip = true;

                    break;
                }
            }

            if ( isset($hits_inc) && $hits_inc > 0 && !$skip_ip )
            {
                $set_hits = $row["hits"];
                $set_hits += $hits_inc;

                $sql .= ", hits = ".$set_hits." ";
                if ( isset( $videobb_video_id[$row['id']] ) )
                    $videobb_video_id[$row['id']]['hits'] = $set_hits;
            }
            
            if ( isset($description) && $description != "" )
            {
                $sql .= ", description = ".$xoopsDb->quoteString($description) ." ";
                if ( isset( $videobb_video_id[$row['id']] ) )
                    $videobb_video_id[$row['id']]['description'] = $description;
            }
            
            $sql .= " WHERE id = ".$row["id"]." LIMIT 1;";

            if ( !$xoopsDb->queryF( $sql ) )
                return false;

            return true;
        }
        
        // Insert if we haven't record
        $sql  = "INSERT INTO ".$xoopsDb->prefix('videobb_video');
        $sql .= " ( ctime, atime, name, hits, uid )";
        $sql .= " VALUES ( '$date','$date',".$xoopsDb->quoteString($vname).",1,$uid );";
        if ( !$xoopsDb->queryF( $sql ) )
            return false;

        return true;
    }

    function videobb_update_video_image($vname,$fname,$uid)
    {
        global $videobb_video_id;

        $xoopsDb =& Database::getInstance();

        if ( isset( $videobb_video[$vname] ) )
            $result = $videobb_video_id[$vname];
        else
            $result = $xoopsDb->queryF( "SELECT id FROM ".$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname).";" );

        // Update if we have record
        if ( $result && $row = $xoopsDb->fetchArray($result) )
        {
            $file_data = file_get_contents( $fname );

            if ( !isset($file_data) || $file_data == '' )
                return false;

            $sql = "UPDATE ".$xoopsDb->prefix('videobb_video')." SET";
            $sql .= " atime = '".date('Y-m-d',time())."',";
            $sql .= " image_by = ".intval($uid);
            $sql .= ", image = '".base64_encode($file_data)."'";
            $sql .= " WHERE id = ".$row["id"]." LIMIT 1;";

            if ( !$xoopsDb->queryF( $sql ) )
                return false;

            if ( isset( $videobb_video_id[$row['id']] ) )
            {
                $videobb_video_id[$row['id']]['atime'] = date('Y-m-d',time());
                $videobb_video_id[$row['id']]['image_by'] = intval($uid);
            }

            return true;
        }
        
        return false;
    }

    function videobb_get_video($vname)
    {
        global $videobb_video, $videobb_video_id;

        if ( isset($videobb_video[$vname]) )
            return $videobb_video[$vname];

        $xoopsDb =& Database::getInstance();

        $sql = "SELECT id,uid,ctime,atime,name,genre,hits,description,image_by FROM ".$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname)." LIMIT 1;";
        $result = $xoopsDb->queryF($sql);

        if ( !$result )
            return false;

        $result = $xoopsDb->fetchArray($result);

        if ( !isset($result) || $result == '' )
            return false;

        foreach ( $result as $name => $value )
        {
            $res[$name] = stripslashes( $value );
        }

        $videobb_video[$vname] = $res;
        $videobb_video_id[$res['id']] = &$videobb_video[$vname];

//        $xoopsDb->queryF('UPDATE '.$xoopsDb->prefix('videobb_video')." SET atime = '".date('Y-m-d',time())."' WHERE id = ".$result['id'].';');

        return $res;
    }

    function videobb_get_video_by_id($id)
    {
        global $videobb_video, $videobb_video_id;

        if ( isset($videobb_video_id[$id]) )
            return $videobb_video_id[$id];

        $xoopsDb =& Database::getInstance();

        $sql = "SELECT id,uid,ctime,atime,name,genre,hits,description,image_by FROM ".$xoopsDb->prefix('videobb_video')." WHERE id = ".intval($id)." LIMIT 1;";
        $result = $xoopsDb->queryF($sql);

        if ( !$result )
            return false;

        $result = $xoopsDb->fetchArray($result);

        if ( !isset($result) || $result == '' )
            return false;

//        $xoopsDb->queryF('UPDATE '.$xoopsDb->prefix('videobb_video')." SET atime = '".date('Y-m-d',time())."' WHERE id = ".$result['id'].';');

        foreach ( $result as $name => $value )
        {
            $res[$name] = stripslashes( $value );
        }

        $videobb_video[$res['name']] = $res;
        $videobb_video_id[$res['id']] = &$videobb_video[$res['name']];

        return $res;
    }

    function videobb_delete_video( $vname )
    {
        global $xoopsModule,$videobb_video_id,$videobb_video;

        xoops_comment_delete($xoopsModule->getVar('mid'), $vname);
        $xoopsDb =& Database::getInstance();

        $sql = 'DELETE FROM '.$xoopsDb->prefix('videobb_video').' WHERE name = '.$xoopsDb->quoteString($vname).';';
        $xoopsDb->queryF($sql);
        
        if ( isset( $videobb_video[$vname] ) )
        {
            $id = $videobb_video[$vname]['id'];
            unset($videobb_video[$vname]);
            unset($videobb_video_id[$id]);
        }
    }

    function videobb_delete_video_by_id( $id )
    {
        global $xoopsModule;

        $video = videobb_get_video_by_id( $id );

        if ( $video )
            videobb_delete_video( $video['name'] );
    }

    function videobb_get_video_image($vname)
    {
        $xoopsDb =& Database::getInstance();

        $sql = "SELECT id, image, image_by FROM ".$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname)." AND image_by <> 0 LIMIT 1;";
        $result = $xoopsDb->queryF($sql);

        if ( !$result )
            return false;

        $result = $xoopsDb->fetchArray($result);

        if ( !isset($result) || $result == '' )
            return false;

//        $xoopsDb->queryF('UPDATE '.$xoopsDb->prefix('videobb_video')." SET atime = '".date('Y-m-d',time())."' WHERE id = ".$result['id'].';');

        $result['image'] = base64_decode($result['image']);

        return $result;
    }

    function videobb_get_video_image_by_id($id)
    {
        $xoopsDb =& Database::getInstance();

        $sql = "SELECT id, image, image_by FROM ".$xoopsDb->prefix('videobb_video')." WHERE id = ".$id." AND image_by <> 0 LIMIT 1;";
        $result = $xoopsDb->queryF($sql);

        if ( !$result )
            return false;

        $result = $xoopsDb->fetchArray($result);

        if ( !isset($result) || $result == '' )
            return false;

//        $xoopsDb->queryF('UPDATE '.$xoopsDb->prefix('videobb_video')." SET atime = '".date('Y-m-d',time())."' WHERE id = ".$result['id'].';');

        $result['image'] = base64_decode($result['image']);

        return $result;
    }

    function videobb_delete_video_image($vname)
    {
        global $videobb_video_id,$videobb_video;
        $xoopsDb =& Database::getInstance();

        if ( isset($videobb_video[$vname]) )
            $row = $videobb_video[$vname];
        else
            $result = $xoopsDb->queryF( "SELECT id FROM ".$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname).";" );

        // delete if we have record
        if ( isset($row) || ( $result && $row = $xoopsDb->fetchArray($result) ) )
        {
            $sql = "UPDATE ".$xoopsDb->prefix('videobb_video')." SET";
            $sql .= " image_by = 0, image = '', atime = '".date('Y-m-d',time())."'";
            $sql .= " WHERE id = ".$row["id"]." LIMIT 1;";

            if ( !$xoopsDb->queryF( $sql ) )
                return false;

            if ( isset( $videobb_video_id[$row['id']] ) )
            {
                $videobb_video_id[$row['id']]['image_by'] = 0;
                $videobb_video_id[$row['id']]['atime'] = date('Y-m-d',time());
            }

            return true;
        }
        
        return true;
    }

    function videobb_rename_video($folder,$voname,$vnname)
    {
        global $videobb_video_id,$videobb_video;

        if ( !file_exists( "$folder/$voname" ) || file_exists( "$folder/$vnname" ) )
            return false;

        if ( !rename( "$folder/$voname", "$folder/$vnname" ) )
            return false;

        $video = videobb_get_video( $voname );

        if ( !isset($video) || !$video )
            return true;

        $xoopsDb =& Database::getInstance();

        $sql = "UPDATE ".$xoopsDb->prefix('videobb_video')." SET";
        $sql .= " name = ".$xoopsDb->quoteString($vnname).",";
        $sql .= " atime = '".date('Y-m-d',time())."' ";
        $sql .= " WHERE id = ".$video['id']." LIMIT 1;";

        if ( !$xoopsDb->queryF( $sql ) )
            return false;

        unset( $videobb_video_id[$video['name']] );
        unset( $videobb_video_id[$video['id']] );

        return true;
    }

    function videobb_vote_getvote( $vname )
    {
        global $videobb_video;

        $xoopsDb =& Database::getInstance();

        if ( isset($videobb_video[$vname]) )
            $row = $videobb_video[$vname];
        else
        {
            $sql = 'SELECT id FROM '.$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname).";";
            $result = $xoopsDb->queryF($sql);
            if ( !$result || $xoopsDb->getRowsNum($result) == 0 )
                return 0;

            $row = $xoopsDb->fetchArray($result);
        }

        $vid = $row['id'];

        $sql  = 'SELECT * FROM '.$xoopsDb->prefix("videobb_vote");
        $sql .= " WHERE video_id = $vid;";

        $result = $xoopsDb->queryF($sql);
        if ( !$result || $xoopsDb->getRowsNum($result) == 0 )
            return 0;

        $count = 0;
        $sum   = 0.0;
        while ( $row = $xoopsDb->fetchArray($result) )
        {
            $sum += $row['vote'];

            $count++;
        }

        return round($sum / $count,1);
    }
    
    function videobb_vote_canvote( $vname, $uid )
    {
        global $videobb_video;

        $xoopsDb =& Database::getInstance();

        if ( isset( $videobb_video[$vname] ) )
            $row = $videobb_video[$vname];
        else
        {
            $sql = 'SELECT id FROM '.$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname).";";
            $result = $xoopsDb->queryF($sql);
            if ( !$result || $xoopsDb->getRowsNum($result) == 0 )
                return true;

            $row = $xoopsDb->fetchArray($result);
        }

        $vid = $row['id'];

        $sql  = 'SELECT * FROM '.$xoopsDb->prefix("videobb_vote");
        $sql .= " WHERE video_id = $vid AND uid = $uid;";

        $result = $xoopsDb->queryF($sql);
        if ( !$result || $xoopsDb->getRowsNum($result) == 0 )
            return true;

        return false;
    }

    function videobb_vote_vote( $vname, $uid, $vote )
    {
        global $videobb_video;

        $xoopsDb =& Database::getInstance();

        if ( isset($videobb_video[$vname]) )
            $row = $videobb_video[$vname];
        else
        {
            $sql = 'SELECT id FROM '.$xoopsDb->prefix('videobb_video')." WHERE name = ".$xoopsDb->quoteString($vname).";";

            $result = $xoopsDb->queryF($sql);
            if ( !$result || $xoopsDb->getRowsNum($result) == 0 )
                return false;

            $row = $xoopsDb->fetchArray($result);
        }

        $vid = $row['id'];

        $sql  = "INSERT INTO ".$xoopsDb->prefix("videobb_vote");
        $sql .= " ( video_id, uid, vote, datetime )";
        $sql .= " VALUES ( $vid, $uid, $vote, '".date("Y-m-d H:m:s",time())."' );";

        if ( !$xoopsDb->queryF($sql) )
            return false;

        return true;
    }

    function videobb_get_option( $name )
    {
        global $videobb_options_hash;

        if ( !isset( $videobb_options_hash ) )
        {
            $xoopsDb =& Database::getInstance();
            $videobb_options_hash = array();

            $sql = 'SELECT * FROM '.$xoopsDb->prefix("videobb_config").";";
            $result = $xoopsDb->queryF($sql);
            if ( !$result || $xoopsDb->getRowsNum($result) == 0 )
                return '';

            while ( $row = $xoopsDb->fetchArray($result) )
                $videobb_options_hash[$row['name']] = stripslashes($row['value']);
        }

        if ( isset($videobb_options_hash[$name]) )
            return $videobb_options_hash[$name];
        else
            return '';
    }

    function videobb_delete_option( $name )
    {
        global $videobb_options_hash;
        $xoopsDb =& Database::getInstance();

        $sql = "DELETE FROM ".$xoopsDb->prefix("videobb_config")." WHERE name = ".$xoopsDb->quoteString($name).";";
        $xoopsDb->queryF($sql);

        if ( isset($videobb_options_hash[$name]) )
            unset($videobb_options_hash[$name]);
    }

    function videobb_set_option( $name, $value, $replace=true )
    {
        global $videobb_options_hash;
        $xoopsDb =& Database::getInstance();

        $result = $xoopsDb->queryF( 'SELECT * FROM '.$xoopsDb->prefix("videobb_config")." WHERE name = ".$xoopsDb->quoteString($name).";" );

        // Update if we have record
        if ( $result && $row = $xoopsDb->fetchArray($result) )
        {
            if ( !$replace )
                return true;

            $sql = "UPDATE ".$xoopsDb->prefix("videobb_config")." SET";
            $sql .= " value = ".$xoopsDb->quoteString($value);
            $sql .= " WHERE id = ".$row["id"]." LIMIT 1;";

            if ( !$xoopsDb->queryF( $sql ) )
                return false;

            $videobb_options_hash[$name]=$value;

            return true;
        }
        

        $sql  = "INSERT INTO ".$xoopsDb->prefix("videobb_config");
        $sql .= " ( name, value )";
        $sql .= " VALUES ( ".$xoopsDb->quoteString($name).",".$xoopsDb->quoteString($value)." );";
        if ( !$xoopsDb->queryF( $sql ) )
            return false;

        $videobb_options_hash[$name]=$value;

        return true;
    }

    function videobb_add_feedback( $subject, $uid, $ip, $text )
    {
        $xoopsDb =& Database::getInstance();

        $sql  = "INSERT INTO ".$xoopsDb->prefix("videobb_comments");
        $sql .= " ( video_id, ip, uid, comment, subject, datetime )";
        $sql .= " VALUES ( 0, '$ip', $uid, ".$xoopsDb->quoteString($text).",".$xoopsDb->quoteString($subject).",'".date("Y:m:d H:m:s",time())."' );";

        if ( !$xoopsDb->queryF($sql) )
            return false;

        return true;
    }

    function videobb_import_le_2_0_table( $from_table, $drop_old=false )
    {
        $db =& Database::getInstance();
        // move all data from old 2.0 or older table into new 2.1 table
        $all_data_moved = true;

        $result = $db->queryF("SELECT * FROM $from_table;");
        if ( $result )
        {
            while ( $row = $db->fetchArray($result) )
            {
                $sql  = 'SELECT * FROM '.$db->prefix('videobb_video');
                $sql .= " WHERE name = ".$db->quoteString($row['name'])." LIMIT 1;";

                $res = $db->queryF( $sql );
                if ( $db->getRowsNum( $res ) < 1 )
                {
                    $sql =  "INSERT INTO ".$db->prefix('videobb_video');
                    $sql .= " ( uid, date, name, genre, hits, description )";
                    $sql .= " VALUES ( ".$row['user'].",'".$row['date']."',".$db->quoteString($row['name']).",".$db->quoteString($row['genre']).",".$row['hits'].",".$db->quoteString($row['description'])." );";

                    if ( !$db->queryF( $sql ) )
                        $all_data_moved = false;
                }

                // Delete old record
                $sql  = "DELETE FROM $from_table";
                $sql .= " WHERE name = ".$db->quoteString($row['name']).";";
                
                $db->queryF( $sql );
            }                    
        }
                    
        if ( $$drop_old && $all_data_moved ) // if and only if...
            $db->queryF("DROP TABLE IF EXISTS $from_table;");
    }

    function videobb_import_le_2_0_voting( $from_dir, $drop_dir=false, $drop_files=false )
    {
        $errors = "";
        $db =& Database::getInstance();

        // move all vote data from files to table
        if ( $dir = opendir( $from_dir ) )
        {
            while ( ($fname = readdir( $dir ) ) != false )
            {
                // Skip "." && ".."
                if ( $fname[0] == "." )
                    continue;
                
                $lfname = "$from_dir/$fname";

                // Skip subdirectories
                if ( !is_file( $lfname ) )
                    continue;
            
                $dpos = strrpos( $fname, '.' );
                if ( $dpos == '' )
                {
                    // Skip non .vote files
                    continue;
                }
                else
                    $fext = substr( $fname, $dpos + 1, strlen( $fname ) );
                        
                if ( $fext != "vote" )
                {
                    // Skip non .vote files
                    continue;
                }

                $fparsed = true;

                $time = date("Y:m:d H:i:s", filemtime($lfname));

                $sfname = substr( $fname, 0, $dpos );
                $sql = 'SELECT * FROM '.$db->prefix('videobb_video')." WHERE name = ".$db->quoteString($sfname).";";
                $result = $db->queryF($sql);
                if ( !$result || $db->getRowsNum($result) == 0 )
                {
                    $sql =  "INSERT INTO ".$db->prefix('videobb_video');
                    $sql .= " ( date, name, hits )";
                    $sql .= " VALUES ( '$time',".$db->quoteString($sfname).",0 );";
                    
                    if ( $db->queryF( $sql ) )
                        $vid = $db->getInsertId();
                    else
                        $vid = "-1";
                }
                else
                {
                    $row = $db->fetchArray($result);
                    $vid = $row['id'];
                }

                if ( $vid < 0 || $vid == '' )
                {
                    if ( $vid == -1 )
                        $errors .= "Failed to insert votes for $sfname.<br>";
                    else
                        $errors .= "Failed to insert votes for $sfname, vid:?.<br>";

                    continue;
                }

                $lines = file($lfname);

                foreach ( $lines as $line_num => $line)
                {
                    $data = explode( ':', trim($line) );
                    if ( strstr($data[0],'xoops_user_') )
                        $uid = substr( $data[0], 11 );
                    else
                        $uid = 0;

                    $sql  = 'SELECT * FROM '.$db->prefix("videobb_vote");
                    $sql .= " WHERE video_id = $vid AND uid = $uid;";

                    // One user can vote one time for one video, just for sure
                    $result = $db->queryF( $sql );
                    if ( $db->getRowsNum($result) < 1 )
                    {
                        $sql  = "INSERT INTO ".$db->prefix("videobb_vote");
                        $sql .= " ( video_id, uid, vote, datetime )";
                        $sql .= " VALUES ( $vid, $uid, ".$data[1].", '$time' );";

                        if (!$db->queryF( $sql ))
                        {
                            $errors .= "Failed to save vote for $sfname, uid:$uid, vid:$vid, vote:".$data[1].", internal module error.<br>";
                            $fparsed = false;
                        }
                    }
                }

                // Delete file
                if ( $fparsed && $drop_files )
                    if ( !unlink( $lfname ) )
                        $errors .= "Failed to delete file \"$fname\".<br>";
            }

            // Delete file index.htm
            if ( $drop_dir )
                unlink( $from_dir."/index.html" );
            // Delete dir
            if ( $drop_dir && $drop_files )
                if ( !rmdir($from_dir) )
                    $errors .= "Failed to delete directory \"$from_dir\".<br>";
        }

        return $errors;
    }

    function videobb_insert_options()
    {
        $db =& Database::getInstance();
        $ok = true;

        if ( !videobb_set_option("_VB_ADM_ACCESS_LOG","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_ACCESS_LOG_IGNORE_IP","127.0.0.1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_RESTRUCTURIZE_MOVIES","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_SHOW_LICENSE_WARNING","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_LICENSE","Movies are published for preview only purpose. If you like some movie, please buy it.", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_SHOW_UAGENT_WARNING","1", false) )
            $ok = false;

        if ( !videobb_set_option("_VB_ADM_UPDATE_INFO_IN_FILES","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_UPDATE_INFO_FROM_FILES","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_HITS_IGNORE_IP","127.0.0.1", false) )
            $ok = false;

        if ( !videobb_set_option("_VB_ADM_USE_FAQ","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_USE_FEEDBACK","1", false) )
            $ok = false;

        if ( !videobb_set_option("_VB_ADM_USE_IMAGES","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_UPL_IMG_MAX_SIZE","30000", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_UPL_IMG_MAX_X","256", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_UPL_IMG_MAX_Y","256", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_UPL_IMG_CENTER","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_UPL_IMG_STRETCH_IF_LT","1", false) )
            $ok = false;
        if ( !videobb_set_option("_VB_ADM_UPL_IMG_STRETCH_IF_GT","1", false) )
            $ok = false;

        return $ok;
    }

    function videobb_list_root($enabled_only=false)
    {
        $xoopsDb =& Database::getInstance();

        $sql = "SELECT * FROM ".$xoopsDb->prefix("videobb_root");
        if ( $enabled_only )
            $sql .= " WHERE enabled = 1";
        $result = $xoopsDb->queryF($sql);

        if ( !$result )
            return false;

        while ( $row = $xoopsDb->fetchArray($result) )
        {
            $res[] = $row;
        }

        if ( isset($res) )
            return $res;
        else
            return false;
    }

    function videobb_get_root($rid)
    {
        $xoopsDb =& Database::getInstance();

        $sql = "SELECT * FROM ".$xoopsDb->prefix("videobb_root")." WHERE id = ".intval($rid)." LIMIT 1;";
        $result = $xoopsDb->queryF($sql);

        if ( !$result )
            return false;

        $result = $xoopsDb->fetchArray($result);

        if ( !isset($result) || $result == "" )
            return false;

        foreach ( $result as $name => $value )
        {
            $res[$name] = stripslashes( $value );
        }

        return $res;
    }

    function videobb_update_root($rid,$caption,$path,$url,$extensions,$comment,$enabled)
    {
        $xoopsDb =& Database::getInstance();

        $result = $xoopsDb->queryF( 'SELECT * FROM '.$xoopsDb->prefix('videobb_root')." WHERE id = ".intval($rid)." LIMIT 1;" );

        // Update if we have record
        if ( $result && $row = $xoopsDb->fetchArray($result) )
        {

            $sql = "UPDATE ".$xoopsDb->prefix('videobb_root')." SET";
            $sql .= " caption = ".$xoopsDb->quoteString($caption)." ";
            $sql .= ", path = ".$xoopsDb->quoteString($path)." ";
            $sql .= ", url = ".$xoopsDb->quoteString($url)." ";
            $sql .= ", extensions = ".$xoopsDb->quoteString($extensions)." ";
            $sql .= ", comment = ".$xoopsDb->quoteString($comment)." ";
            $sql .= ", enabled = ".($path!=''?intval($enabled):0)." ";
            $sql .= " WHERE id = ".intval($rid)." LIMIT 1;";

            if ( !$xoopsDb->queryF( $sql ) )
                return false;

            return true;
        }

        return false;
    }
        
    function videobb_add_root($caption,$path,$url,$extensions,$comment='',$enabled=0)
    {
        $xoopsDb =& Database::getInstance();

        // Insert if we haven't record
        $sql  = "INSERT INTO ".$xoopsDb->prefix('videobb_root');
        $sql .= " ( enabled, caption, path, url, extensions, comment )";
        $sql .= " VALUES ( ".($path!=''?intval($enabled):0).",".$xoopsDb->quoteString($caption).",".$xoopsDb->quoteString($path).",".$xoopsDb->quoteString($url).",".$xoopsDb->quoteString($extensions).",".$xoopsDb->quoteString($comment)." );";

        if ( !$xoopsDb->queryF( $sql ) )
            return false;

        return true;
    }

    function videobb_delete_root($rid)
    {
        $xoopsDb =& Database::getInstance();

        $sql  = "DELETE FROM ".$xoopsDb->prefix('videobb_root');
        $sql .= " WHERE id = ".intval($rid)." LIMIT 1;";

        if ( !$xoopsDb->queryF( $sql ) )
            return false;

        return true;
    }

    function videobb_prune_video_older_than( $days, $show_only=true )
    {
        $roots = videobb_list_root(true);

        if ( !$roots )
            return null;

        foreach ( $roots as $root )
            videobb_get_media_names( $root['path'], $media_list, true );

        $xoopsDb =& Database::getInstance();

        $sql = "SELECT id,name FROM ".$xoopsDb->prefix('videobb_video');
        $sql .= " WHERE DATE_ADD(atime,INTERVAL $days DAY) <= CURDATE();";
        $result = $xoopsDb->queryF( $sql );

        if ( $xoopsDb->getRowsNum($result) == 0 || !$result )
            return null;

        while ( $media_row = $xoopsDb->fetchArray($result) )
            if ( !in_array( stripslashes($media_row['name']), $media_list ) )
            {
                $r['list'][] = stripslashes($media_row['name']);
                $r['count']++;

                if ( !$show_only )
                    videobb_delete_video_by_id( $media_row['id'] );
            }

        return $r;
    }

    function videobb_prune_video_images_bigger( $size, $show_only=true )
    {
        $xoopsDb =& Database::getInstance();

        $sql = "SELECT id,name FROM ".$xoopsDb->prefix('videobb_video');
        $sql .= " WHERE image_by <> 0 AND LENGTH(image) >= $size;";
        $result = $xoopsDb->queryF( $sql );

        if ( $xoopsDb->getRowsNum($result) == 0 || !$result )
            return null;

        while ( $media_row = $xoopsDb->fetchArray($result) )
        {
            $r['list'][] = stripslashes($media_row['name']);
            $r['count']++;

            if ( !$show_only )
                videobb_delete_video_image( $media_row['name'] );
        }

        return $r;
    }

    $rfolder_path = videobb_get_option("_VB_ADM_FOLDER_ROOT");
    $rfolder_url = videobb_get_option("_VB_ADM_FOLDER_ROOT_URL");
?>