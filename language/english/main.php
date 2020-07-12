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
//%%%%%%        File Name index.php         %%%%%
if ( defined('_VB_FAQ') && _VB_FAQ != '' )
    return;

define('_VB_FAQ','FAQ');
define('_VB_FEEDBACK','Feedback');

define('_VB_INDEX','List');
define('_VB_PREV','Prev');
define('_VB_NEXT','Next');
define('_VB_BACK','Back');
define('_VB_OPEN_FOLDER','Folder');

define('_VB_VIDEO_CAPTION','Media name');
define('_VB_VIDEO_FOLDER','Media folder');
define('_VB_VIDEO','Media');
define('_VB_VIDEO_FOLDER_LINK','folder');
define('_VB_VIDEO_VOTE','Vote');
define('_VB_VIDEO_AGE','Age');
define('_VB_VIDEO_MODIFIED','Modified');
define('_VB_VIDEO_TYPE','Type');
define('_VB_VIDEO_SIZE','Size in Mb');
define('_VB_VIDEO_GENRE','Genre');
define('_VB_VIDEO_HITS','Hits');

define('_VB_GENRE_NAME','Genre');
define('_VB_GENRE_SUBMIT','Submit');

define('_VB_VOTE_NAME','Vote:');
define('_VB_VOTE_SUBMIT','Vote!');
define('_VB_VOTE_VOTED','Sorry, you can\'t vote more than one time.');
define('_VB_VOTE_VALUE1','1');
define('_VB_VOTE_VALUE2','2');
define('_VB_VOTE_VALUE3','3');
define('_VB_VOTE_VALUE4','4');
define('_VB_VOTE_VALUE5','5');

define('_VB_DESCRIPTION_NAME','Description:');
define('_VB_DESCRIPTION_SUBMIT','Submit');



define('_VB_WARNING','Warning!');
define('_VBB_WRONG_INSTALL','Video-BB is not correctly installed!');
// New
define('_VB_GUEST_CANNOT_1','Forbiden for guest, please ');
define('_VB_GUEST_CANNOT_2','register');
define('_VB_GUEST_CANNOT_3','.');
define('_VB_GUEST_NAME','Guest');

define('_VB_CANNOT_OPEN','Can\'t open ');
define('_VB_VOTE_LINK','[vote]');
define('_VB_EDIT_LINK','[edit]');
define('_VB_MORE_LINK','[more]');
define('_VB_RESTRICTED_LINK','[forbiden]');

define('_VB_TOTAL','Total');
define('_VB_TOTAL_MOVI','movi');
// Zero or greater than 4
define('_VB_TOTAL_MOVI_0_GT4','es');
define('_VB_TOTAL_MOVI_1','e');
define('_VB_TOTAL_MOVI_GT1_LT5','es');
define('_VB_TOTAL_FOLDE','folde');
// Zero or greater than 4
define('_VB_TOTAL_FOLDE_0_GT4','rs');
define('_VB_TOTAL_FOLDE_1','r');
define('_VB_TOTAL_FOLDE_GT1_LT5','rs');

define('_VB_TOKEN_ERROR','Sorry referer checking failed.');
//
define('_VB_VI_EDIT_IMAGE','[image]');
define('_VB_MANAGE_VI','Manage media image');
define('_VB_VI_UPLOAD_INFO','Change current image');
define('_VB_VI_UPLOAD','Upload');
define('_VB_VI_DELETE','Delete');
define('_VB_VI_DELETE_INFO','Delete current image');
define('_VB_VI_ALLOWED_TYPES','Allowed MIME types');
define('_VB_VI_UPLOADED_BY','Uploaded by');
define('_VB_VI_LOAD_ERROR','Error loading');
define('_VB_VI_LOAD_EMPTY','Image is empty');
define('_VB_VI_UPLOAD_OK','Image uploaded');
define('_VB_VI_UPLOAD_FAILED','Failed to upload image');

define('_VB_VI_NOT_GDLIB_IN_PHP','Sorry, there is no GD library support in PHP');
define('_VB_WRONG_REQUEST','Wrong request');
define('_VB_VI_DISABLED','Image managment has been disbled by administator');
define('_VB_VI_DELETE_FAILED','Deleting of image failed');
define('_VB_VI_DELETE_OK','Image deleted');

define('_VB_IMAGE_NAME','Image');
define('_VB_COMMENTS_NAME','Comments');

define('_VB_UAGENT_WARNING_TITLE','User Agent Warning');
define('_VB_UAGENT_WARNING','Some needed for media show features may be unavailable in your browser, try to upgrade browser, or use');

define('_VB_ROOT_LIST','Root list');
define('_VB_NO_ROOT','Sorry, no root available.');
define('_VB_ROOT_LIST_EMPTY','Sorry, root list is empty');

define('_VB_NAME_NAME','Media name');
define('_VB_NAME_SUBMIT','Change');
define('_VB_NAME_DENIED','Sorry, denied!');
define('_VB_NAME_CHANGED','Name changed.');
define('_VB_NAME_CHANGE_FAILED','Sorry, an error occured, failed to rename.');
//%%%%%%        File Name settings.php      %%%%%
define('_VBS_SAVED','Settings saved');
define('_VBS_VALUE','Value');
define('_VBS_OPTION','Option');
define('_VBS_HEADERS_IN_LIST','Headers in list');
define('_VBS_HEADERS_IN_LIST_STEP_1','After each ');
define('_VBS_HEADERS_IN_LIST_STEP_2',' movies, (10 minimum).');
define('_VBS_USED','Used');
define('_VBS_YES','Yes');
define('_VBS_NO','No');
define('_VBS_REPLACE_SMILEYS','Replace smileys');

define('_VB_AUTHOR','<a href="mailto:kutovoy@gmail.com">Kutovoy Nickolay</a>');
//%%%%%%        File Name feedback.php      %%%%%
define('_VBF_NOT_FILLED','Please go back and fill all fields in form.');
define('_VBF_SEND_ERROR','Sorry, there was an error sending your request.');
define('_VBF_SENT','Message sent, thank you for feedback.');
define('_VBF_CAPTION','Feedback form:');
define('_VBF_SUBJECT','Subject');
define('_VBF_DEF_SUBJECT','Feedback');
define('_VBF_FROM','From');
define('_VBF_TEXT','Text');
define('_VBF_SUBMIT','Submit');

define('_VBF_NO_FEEDBACK','No feedback available');
define('_VBF_DELETED_FEEDBACK','Feedback deleted');
define('_VBF_FAILED_DELETE','Failed to delete feedback');
define('_VBF_CANCELED_DELETE','Deleting feedback canceled');
define('_VBF_CONFIRM_DELETING','Confirm deleting feedback');
define('_VBF_DELETE','Delete');
define('_VBF_CANCEL','Cancel');
define('_VBF_VIEW','View feedback');
define('_VBF_GO_BACK','Go back');

// Module Info
// The name of this module
define('_MD_A_MODULEADMIN','Administrating');
define('_MD_A_IMPORTED','Imported');
define('_MD_A_SAVED','Saved');

// Config options
define('_VB_ADM_ACCESS_LOG','Use logging');
define('_VB_ADM_ACCESS_LOG_IGNORE_IP','Skip logging from IP list');
define('_VB_ADM_RESTRUCTURIZE_MOVIES','Restructurize movies folder');
define('_VB_ADM_SHOW_LICENSE_WARNING','Show license for movies');
define('_VB_ADM_LICENSE','License');
define('_VB_ADM_SHOW_UAGENT_WARNING','Show user agent warning');
define('_VB_ADM_VIDEO_EXTENSIONS','Media extensions list');
define('_VB_ADM_FOLDER_ROOT','Path to root folder with movies');
define('_VB_ADM_FOLDER_ROOT_URL','Path to shared folder, URL');
define('_VB_ADM_UPDATE_INFO_IN_FILES','Save info in .txt files');
define('_VB_ADM_UPDATE_INFO_FROM_FILES','Read info from .txt files when no info available');
define('_VB_ADM_HITS_IGNORE_IP','Don\'t count hits from IP list');
define('_VB_ADM_USE_FAQ','Use internal FAQ system');
define('_VB_ADM_USE_FEEDBACK','Use internal FEEDBACK system');
define('_VB_ADM_YES','Yes');
define('_VB_ADM_NO','No');
define('_VB_ADM_OR','or');

define('_VB_ADM_SUBMIT','Submit');
define('_VB_ADM_IMPORT_LE_20_TABLE','Import video table (from v2.0 or lower)');
define('_VB_ADM_IMPORT_LE_20_VOTING','Import voting directory (from v2.0 or lower)');

// ADM IMAGES
define('_VB_ADM_USE_IMAGES','Use image uploading');
define('_VB_ADM_VIM_ADMINS','Moderators (genre/description/images) ID list<br>(XOOPs registered user IDs, admins already can upload/moderate)');
define('_VB_ADM_UPL_IMG_MAX_SIZE','Max file size<br>(this is max file size for uploading, resulted image size may be different)');
define('_VB_ADM_UPL_IMG_MAX_X','Target image width<br>(image width to produce)');
define('_VB_ADM_UPL_IMG_MAX_Y','Target image height<br>(image height to produce)');
define('_VB_ADM_UPL_IMG_CENTER','Center uploaded image in target image');
define('_VB_ADM_UPL_IMG_STRETCH_IF_LT','Stretch uploaded image<br>(if uploaded image is smaller than target image)');
define('_VB_ADM_UPL_IMG_STRETCH_IF_GT','Stretch uploaded image<br>(if uploaded image is larger than target image)');

// ADM ROOT
define('_VB_ADM_ROOT_ADD','Add root folder');
define('_VB_ADM_ROOT_ENABLED','Enabled');
define('_VB_ADM_ROOT_CAPTION','Caption');
define('_VB_ADM_ROOT_PATH','Internal path');
define('_VB_ADM_ROOT_URL','Public URL');
define('_VB_ADM_ROOT_EXTENSIONS','Extensions to display');
define('_VB_ADM_ROOT_COMMENT','Comment');
define('_VB_ADM_ROOT_EDIT','Edit');
define('_VB_ADM_ROOT_DELETE','Delete');

// ADM PRUNE
define('_VB_ADM_PRUNE_OLD_INFO','Prune some media information');
define('_VB_ADM_PRUNE_OLDER_THAN','Prune media info in DB that is older than<br>(and there is no media in any root folder (recurse) with that name)');
define('_VB_ADM_PRUNE_OLDER_THAN_DAYS',' days');
define('_VB_ADM_PRUNE_LIST_ONLY','Don\'t actually prune, list media names only');
define('_VB_ADM_PRUNE_LIST','Media to prune:');
define('_VB_ADM_PRUNE_LIST_TOTAL','Total:');
define('_VB_ADM_PRUNE_IMAGES_BIGGER','Prune media images in DB that are bigger than<br>(not media info, just images)');
define('_VB_ADM_PRUNE_IMAGES_BIGGER_THAN','bytes');

//
define('_VB_ADM_ROOT_OK','Root saved');
?>