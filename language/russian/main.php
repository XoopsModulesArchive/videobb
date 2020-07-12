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

define('_VB_VIDEO_BB_NAME','Video-BB');

define('_VB_FAQ','����������');
define('_VB_FEEDBACK','������');

define('_VB_INDEX','������');
define('_VB_PREV','����������');
define('_VB_NEXT','���������');
define('_VB_BACK','�����');
define('_VB_OPEN_FOLDER','����� � �����');


define('_VB_VIDEO_CAPTION','�������� �����');
define('_VB_VIDEO_FOLDER','����� � �����');
define('_VB_VIDEO','�����');
define('_VB_VIDEO_VOTE','������');
define('_VB_VIDEO_AGE','��������');
define('_VB_VIDEO_MODIFIED','������');
define('_VB_VIDEO_TYPE','��� �����');
define('_VB_VIDEO_SIZE','������ � ��');
define('_VB_VIDEO_GENRE','����');
define('_VB_VIDEO_HITS','������');

define('_VB_GENRE_NAME','����');
define('_VB_GENRE_SUBMIT','�������');

define('_VB_VOTE_NAME','������:');
define('_VB_VOTE_SUBMIT','����������!');
define('_VB_VOTE_VOTED','��������, �� ��� ����������� � ���� �����������.');
define('_VB_VOTE_VALUE1','1 ����');
define('_VB_VOTE_VALUE2','2 �����');
define('_VB_VOTE_VALUE3','3 �����');
define('_VB_VOTE_VALUE4','4 �����');
define('_VB_VOTE_VALUE5','5 ������');

define('_VB_DESCRIPTION_NAME','��������:');
define('_VB_DESCRIPTION_SUBMIT','�������');



define('_VB_WARNING','��������!');
//define('_VB_OWNER_COPYRIGHTS_WARNING','��� �����������/����� ������������ ������������ ������������� ��� ������������ ��� ����� ������������� �������������. ����� � ��������� �����������/����� ������������ ����������� �� �������� ����������������. ����� ��������������� �/��� ������������ ������������� ��� ���������� �������� ���������������� ���������. ����������, ������� ���������� �����������/����� ������������ ����� ������������ � ���� � ����������� �������-���� ��� ������� � ������������� �����������/����� �������������.');

define('_VBB_WRONG_INSTALL','Video-BB is not correctly installed!');
// New
define('_VB_GUEST_CANNOT_1','���������� �����, ���������� ');
define('_VB_GUEST_CANNOT_2','�����������������');
define('_VB_GUEST_CANNOT_3','.');
define('_VB_GUEST_NAME','�����');

define('_VB_CANNOT_OPEN','�� ���� ������� ');
define('_VB_VOTE_LINK','[�������]');
define('_VB_EDIT_LINK','[�������]');
define('_VB_MORE_LINK','[�����]');
define('_VB_RESTRICTED_LINK','[�� ��������]');

define('_VB_TOTAL','�����');
define('_VB_TOTAL_MOVI','�����');
// Zero or greater than 4
define('_VB_TOTAL_MOVI_0_GT4','');
define('_VB_TOTAL_MOVI_1','');
define('_VB_TOTAL_MOVI_GT1_LT5','');
define('_VB_TOTAL_FOLDE','���');
// Zero or greater than 4
define('_VB_TOTAL_FOLDE_0_GT4','��');
define('_VB_TOTAL_FOLDE_1','��');
define('_VB_TOTAL_FOLDE_GT1_LT5','��');
define('_VB_TOKEN_ERROR','��������, �� ������ �� ��� �������� � ������� �������.');

// _VB_VI
define('_VB_VI_EDIT_IMAGE','[��������]');
define('_VB_MANAGE_VI','���������� ���������');
define('_VB_VI_UPLOAD_INFO','������� ��������');
define('_VB_VI_UPLOAD','�������');
define('_VB_VI_DELETE','�������');
define('_VB_VI_DELETE_INFO','������� ��������');
define('_VB_VI_DELETE_OK','�������� ������� �������');
define('_VB_VI_DELETE_FAILED','�� ������� ������� ��������');
define('_VB_VI_ALLOWED_TYPES','����������� MIME ����');
define('_VB_VI_UPLOADED_BY','��������� �������������');
define('_VB_VI_LOAD_ERROR','������ ��������');
define('_VB_VI_LOAD_EMPTY','�������� �����������');
define('_VB_VI_UPLOAD_OK','�������� ������� ���������');
define('_VB_VI_UPLOAD_FAILED','�� ������� ��������� ��������');

define('_VB_VI_NOT_GDLIB_IN_PHP','��������� GD ���������� PHP ��������� �� �������.');
define('_VB_WRONG_REQUEST','��������� ������');
define('_VB_VI_DISABLED','���������� ���������� ��������� ���������������');

define('_VB_IMAGE_NAME','��������');
define('_VB_COMMENTS_NAME','�����������');

define('_VB_UAGENT_WARNING_TITLE','�������������� � ��������');
define('_VB_UAGENT_WARNING','��������� ����������� ��� �������� ����� ������� ����� ���� �� ����������� � ����� ��������, ����������, �������� ��� ��� �����������');

define('_VB_ROOT_LIST','������ �����');
define('_VB_NO_ROOT','��������, ����� ���.');
define('_VB_ROOT_LIST_EMPTY','��������, ������ ����� ����.');

define('_VB_NAME_NAME','������������ �����');
define('_VB_NAME_SUBMIT','�������');
define('_VB_NAME_DENIED','��������, ���������!');
define('_VB_NAME_CHANGED','������������ ��������.');
define('_VB_NAME_CHANGE_FAILED','��������, �������� ������!');
//%%%%%%        File Name settings.php      %%%%%
define('_VBS_SAVED','���������');
define('_VBS_VALUE','��������');
define('_VBS_OPTION','�����');
define('_VBS_HEADERS_IN_LIST','��������� � ������');
define('_VBS_HEADERS_IN_LIST_STEP_1','����� ������');
define('_VBS_HEADERS_IN_LIST_STEP_2',' ���������, (10 �������).');
define('_VBS_USED','��������');
define('_VBS_YES','��');
define('_VBS_NO','���');
define('_VBS_REPLACE_SMILEYS','�������� ��������');

define('_VB_AUTHOR','<a href="mailto:kutovoy@gmail.com">������� �������</a>');
//%%%%%%        File Name feedback.php      %%%%%
define('_VBF_NOT_FILLED','��������, �� �� �� ��������� ��� ����� ������');
define('_VBF_SEND_ERROR','��������, � ��������� ��������� �������� ��������.');
define('_VBF_SENT','��������� ���� ����������, �������.');
define('_VBF_CAPTION','����� ��� ������ � �������');
define('_VBF_SUBJECT','����');
define('_VBF_DEF_SUBJECT','�����');
define('_VBF_FROM','��');
define('_VBF_TEXT','����������');
define('_VBF_SUBMIT','���������');

define('_VBF_NO_FEEDBACK','��� �������');
define('_VBF_DELETED_FEEDBACK','����� �����');
define('_VBF_FAILED_DELETE','������ �������� ������');
define('_VBF_CANCELED_DELETE','�������� ������ ��������');
define('_VBF_CONFIRM_DELETING','����������� �������� ������');
define('_VBF_DELETE','�������');
define('_VBF_CANCEL','��������');
define('_VBF_VIEW','����������� ������');
define('_VBF_GO_BACK','�����');

// Module Info
// The name of this module
define('_MD_A_MODULEADMIN','�����������������');
define('_MD_A_IMPORTED','�������������');
define('_MD_A_SAVED','���������');

// Config options
define('_VB_ADM_ACCESS_LOG','����� ���');
define('_VB_ADM_ACCESS_LOG_IGNORE_IP','�� ���������� ������ � IP ������');
define('_VB_ADM_RESTRUCTURIZE_MOVIES','��������� ����� ��� ����');
define('_VB_ADM_SHOW_LICENSE_WARNING','���������� �����-��������');
define('_VB_ADM_LICENSE','��������');
define('_VB_ADM_SHOW_UAGENT_WARNING','������������� � ������������� ��������');
define('_VB_ADM_VIDEO_EXTENSIONS','������ ����� ����������');
define('_VB_ADM_FOLDER_ROOT','���� � �������� �����');
define('_VB_ADM_FOLDER_ROOT_URL','����� � ������������� �����, URL');
define('_VB_ADM_UPDATE_INFO_IN_FILES','��������� ���������� � ��������� �����');
define('_VB_ADM_UPDATE_INFO_FROM_FILES','��������� ���������� �� ��������� ������ ��� � ���������� � ��');
define('_VB_ADM_HITS_IGNORE_IP','�� ��������� ����� � IP ������');
define('_VB_ADM_USE_FAQ','������������ ���������� FAQ �������');
define('_VB_ADM_USE_FEEDBACK','������������ ���������� FEEDBACK �������');
define('_VB_ADM_YES','��');
define('_VB_ADM_NO','���');
define('_VB_ADM_OR','���');

define('_VB_ADM_SUBMIT','���������');
define('_VB_ADM_IMPORT_LE_20_TABLE','������������� ������� ����� (������ 2.0- ������)');
define('_VB_ADM_IMPORT_LE_20_VOTING','������������� ������� ����������� (������ 2.0- ������)');

// ADM IMAGES
define('_VB_ADM_USE_IMAGES','��������� ��������');
define('_VB_ADM_VIM_ADMINS','������ ��������������� �����������<br>(������ ������������� XOOPs ����� ";", �������������� ��� ��������)');
define('_VB_ADM_UPL_IMG_MAX_SIZE','������������ ������ ����� ��� ��������<br>(�������������� ������ ����� ����������)');
define('_VB_ADM_UPL_IMG_MAX_X','������� ������ ���������� ��������<br>(������ ��������, ������� ����� ��������� � �������)');
define('_VB_ADM_UPL_IMG_MAX_Y','������� ������ ���������� ��������<br>(������ ��������, ������� ����� ��������� � �������)');
define('_VB_ADM_UPL_IMG_CENTER','������������ ����������� ��������');
define('_VB_ADM_UPL_IMG_STRETCH_IF_LT','����������� ����������� ��������<br>(���� ��� ������ ������� ��������)');
define('_VB_ADM_UPL_IMG_STRETCH_IF_GT','������� ����������� ��������<br>(���� ��� ������ ������� ��������)');

// ADM ROOT
define('_VB_ADM_ROOT_ADD','�������� �����');
define('_VB_ADM_ROOT_ENABLED','��������');
define('_VB_ADM_ROOT_CAPTION','���������');
define('_VB_ADM_ROOT_PATH','���������� ����');
define('_VB_ADM_ROOT_URL','������� URL');
define('_VB_ADM_ROOT_EXTENSIONS','������ ����������');
define('_VB_ADM_ROOT_COMMENT','�����������');
define('_VB_ADM_ROOT_EDIT','�������������');
define('_VB_ADM_ROOT_DELETE','�������');

// ADM PRUNE
define('_VB_ADM_PRUNE_OLD_INFO','������� ������ �����');
define('_VB_ADM_PRUNE_OLDER_THAN','������� ������ � ����� ������<br>(��� �������, ��� ��� ����� � ����� ������ �� � ����� �������� ����� (����������) )');
define('_VB_ADM_PRUNE_OLDER_THAN_DAYS',' ����');
define('_VB_ADM_PRUNE_LIST_ONLY','�� �������, �������� ������ ������');
define('_VB_ADM_PRUNE_LIST','������ �� ��������:');
define('_VB_ADM_PRUNE_LIST_TOTAL','�����:');
define('_VB_ADM_PRUNE_IMAGES_BIGGER','������� ��������, ���������� ������ ���');
define('_VB_ADM_PRUNE_IMAGES_BIGGER_THAN','����');

//
define('_VB_ADM_ROOT_OK','����� ���������');
?>