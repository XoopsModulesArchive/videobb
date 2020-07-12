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

define('_VB_FAQ','Информация');
define('_VB_FEEDBACK','Отзывы');

define('_VB_INDEX','Список');
define('_VB_PREV','Предидущее');
define('_VB_NEXT','Следующее');
define('_VB_BACK','Назад');
define('_VB_OPEN_FOLDER','Папка с медиа');


define('_VB_VIDEO_CAPTION','Название медиа');
define('_VB_VIDEO_FOLDER','Папка с медиа');
define('_VB_VIDEO','Медиа');
define('_VB_VIDEO_VOTE','Оценка');
define('_VB_VIDEO_AGE','Свежесть');
define('_VB_VIDEO_MODIFIED','Залито');
define('_VB_VIDEO_TYPE','Тип файла');
define('_VB_VIDEO_SIZE','Размер в МБ');
define('_VB_VIDEO_GENRE','Жанр');
define('_VB_VIDEO_HITS','Кликов');

define('_VB_GENRE_NAME','Жанр');
define('_VB_GENRE_SUBMIT','Указать');

define('_VB_VOTE_NAME','Оценка:');
define('_VB_VOTE_SUBMIT','Голосовать!');
define('_VB_VOTE_VOTED','Извините, Вы уже участвовали в этом голосовании.');
define('_VB_VOTE_VALUE1','1 балл');
define('_VB_VOTE_VALUE2','2 балла');
define('_VB_VOTE_VALUE3','3 балла');
define('_VB_VOTE_VALUE4','4 балла');
define('_VB_VOTE_VALUE5','5 баллов');

define('_VB_DESCRIPTION_NAME','Описание:');
define('_VB_DESCRIPTION_SUBMIT','Указать');



define('_VB_WARNING','Внимание!');
//define('_VB_OWNER_COPYRIGHTS_WARNING','Все музыкальные/видео произведения представлены исключительно для ознакомления без целей коммерческого использования. Права в отношении музыкальных/видео произведений принадлежат их законным правообладателям. Любое распространение и/или коммерческое использование без разрешения законных правообладателей запрещено. Пожалуйста, удалите полученные музыкальные/видео произведения после ознакомления с ними и приобретите компакт-диск или кассету с понравившимся музыкальным/видео произведением.');

define('_VBB_WRONG_INSTALL','Video-BB is not correctly installed!');
// New
define('_VB_GUEST_CANNOT_1','Недоступно гостю, пожалуйста ');
define('_VB_GUEST_CANNOT_2','зарегестрируйтесь');
define('_VB_GUEST_CANNOT_3','.');
define('_VB_GUEST_NAME','Гость');

define('_VB_CANNOT_OPEN','Не могу открыть ');
define('_VB_VOTE_LINK','[оценить]');
define('_VB_EDIT_LINK','[указать]');
define('_VB_MORE_LINK','[далее]');
define('_VB_RESTRICTED_LINK','[не доступно]');

define('_VB_TOTAL','Всего');
define('_VB_TOTAL_MOVI','медиа');
// Zero or greater than 4
define('_VB_TOTAL_MOVI_0_GT4','');
define('_VB_TOTAL_MOVI_1','');
define('_VB_TOTAL_MOVI_GT1_LT5','');
define('_VB_TOTAL_FOLDE','пап');
// Zero or greater than 4
define('_VB_TOTAL_FOLDE_0_GT4','ок');
define('_VB_TOTAL_FOLDE_1','ка');
define('_VB_TOTAL_FOLDE_GT1_LT5','ки');
define('_VB_TOKEN_ERROR','Извините, Вы попали на эту страницу с другого сервера.');

// _VB_VI
define('_VB_VI_EDIT_IMAGE','[картинка]');
define('_VB_MANAGE_VI','Управление картинкой');
define('_VB_VI_UPLOAD_INFO','Сменить картинку');
define('_VB_VI_UPLOAD','Сменить');
define('_VB_VI_DELETE','Удалить');
define('_VB_VI_DELETE_INFO','Удалить картинку');
define('_VB_VI_DELETE_OK','Картинка успешно удалена');
define('_VB_VI_DELETE_FAILED','Не удалось удалить картинку');
define('_VB_VI_ALLOWED_TYPES','Разрешённые MIME типы');
define('_VB_VI_UPLOADED_BY','Загружена пользователем');
define('_VB_VI_LOAD_ERROR','Ошибка загрузки');
define('_VB_VI_LOAD_EMPTY','Картинка отсутствует');
define('_VB_VI_UPLOAD_OK','Картинка успешно загружена');
define('_VB_VI_UPLOAD_FAILED','Не удалось загрузить картинку');

define('_VB_VI_NOT_GDLIB_IN_PHP','Поддержка GD библиотеки PHP отключена на сервере.');
define('_VB_WRONG_REQUEST','Ошибочный запрос');
define('_VB_VI_DISABLED','Управление картинками отключено администратором');

define('_VB_IMAGE_NAME','Картинка');
define('_VB_COMMENTS_NAME','Комментарии');

define('_VB_UAGENT_WARNING_TITLE','Предупреждение о браузере');
define('_VB_UAGENT_WARNING','Некоторые необходимые для открытия медиа объекты могут быть не реализованы в Вашем браузере, пожалуйста, обновите его или используйте');

define('_VB_ROOT_LIST','Список папок');
define('_VB_NO_ROOT','Извините, папок нет.');
define('_VB_ROOT_LIST_EMPTY','Извините, список папок пуст.');

define('_VB_NAME_NAME','Наименование медиа');
define('_VB_NAME_SUBMIT','Сменить');
define('_VB_NAME_DENIED','Извините, запрещено!');
define('_VB_NAME_CHANGED','Наименование изменено.');
define('_VB_NAME_CHANGE_FAILED','Извините, возникла ошибка!');
//%%%%%%        File Name settings.php      %%%%%
define('_VBS_SAVED','Сохранено');
define('_VBS_VALUE','Значение');
define('_VBS_OPTION','Опция');
define('_VBS_HEADERS_IN_LIST','Заголовки в списке');
define('_VBS_HEADERS_IN_LIST_STEP_1','Через каждые');
define('_VBS_HEADERS_IN_LIST_STEP_2',' элементов, (10 минимум).');
define('_VBS_USED','Включены');
define('_VBS_YES','Да');
define('_VBS_NO','Нет');
define('_VBS_REPLACE_SMILEYS','Заменять смайлики');

define('_VB_AUTHOR','<a href="mailto:kutovoy@gmail.com">Кутовой Николай</a>');
//%%%%%%        File Name feedback.php      %%%%%
define('_VBF_NOT_FILLED','Извините, но Вы не заполнили всю форму отзыва');
define('_VBF_SEND_ERROR','Извините, с отправкой сообщения возникла проблема.');
define('_VBF_SENT','Сообщение было отправлено, спасибо.');
define('_VBF_CAPTION','Форма для отзыва о проекте');
define('_VBF_SUBJECT','Тема');
define('_VBF_DEF_SUBJECT','Отзыв');
define('_VBF_FROM','От');
define('_VBF_TEXT','Содержание');
define('_VBF_SUBMIT','Отправить');

define('_VBF_NO_FEEDBACK','Нет отзывов');
define('_VBF_DELETED_FEEDBACK','Отзыв удалён');
define('_VBF_FAILED_DELETE','Ошибка удаления отзыва');
define('_VBF_CANCELED_DELETE','Удаление отзыва отменено');
define('_VBF_CONFIRM_DELETING','Подтвердите удаление отзыва');
define('_VBF_DELETE','Удалить');
define('_VBF_CANCEL','Отменить');
define('_VBF_VIEW','Просмотреть отзывы');
define('_VBF_GO_BACK','Назад');

// Module Info
// The name of this module
define('_MD_A_MODULEADMIN','Администрирование');
define('_MD_A_IMPORTED','Импортировано');
define('_MD_A_SAVED','Сохранено');

// Config options
define('_VB_ADM_ACCESS_LOG','Вести лог');
define('_VB_ADM_ACCESS_LOG_IGNORE_IP','Не логировать доступ с IP списка');
define('_VB_ADM_RESTRUCTURIZE_MOVIES','Создавать папки для кино');
define('_VB_ADM_SHOW_LICENSE_WARNING','Показывать видео-лицензию');
define('_VB_ADM_LICENSE','Лицензия');
define('_VB_ADM_SHOW_UAGENT_WARNING','Предупреждать о несовместимом браузере');
define('_VB_ADM_VIDEO_EXTENSIONS','Список видео расширений');
define('_VB_ADM_FOLDER_ROOT','Путь к корневой папке');
define('_VB_ADM_FOLDER_ROOT_URL','Пусть к общедоступной папке, URL');
define('_VB_ADM_UPDATE_INFO_IN_FILES','Сохранять информацию в текстовые файлы');
define('_VB_ADM_UPDATE_INFO_FROM_FILES','Считывать информацию из текстовых файлов при её отсутствии в БД');
define('_VB_ADM_HITS_IGNORE_IP','Не учитывать клики с IP списка');
define('_VB_ADM_USE_FAQ','Использовать внутреннюю FAQ систему');
define('_VB_ADM_USE_FEEDBACK','Использовать внутреннюю FEEDBACK систему');
define('_VB_ADM_YES','Да');
define('_VB_ADM_NO','Нет');
define('_VB_ADM_OR','или');

define('_VB_ADM_SUBMIT','Отправить');
define('_VB_ADM_IMPORT_LE_20_TABLE','Импортировать таблицу видео (ранние 2.0- версии)');
define('_VB_ADM_IMPORT_LE_20_VOTING','Импортировать каталог голосования (ранние 2.0- версии)');

// ADM IMAGES
define('_VB_ADM_USE_IMAGES','Разрешить загрузку');
define('_VB_ADM_VIM_ADMINS','Список идентификаторов модераторов<br>(номера пользователей XOOPs через ";", администраторы уже включены)');
define('_VB_ADM_UPL_IMG_MAX_SIZE','Максимальный размер файла для загрузки<br>(результирующий размер может отличаться)');
define('_VB_ADM_UPL_IMG_MAX_X','Целевая ширина сохранённой картинки<br>(ширина картинки, которая будет храниться в системе)');
define('_VB_ADM_UPL_IMG_MAX_Y','Целевая высота сохранённой картинки<br>(высота картинки, которая будет храниться в системе)');
define('_VB_ADM_UPL_IMG_CENTER','Центрировать загружаемую картинку');
define('_VB_ADM_UPL_IMG_STRETCH_IF_LT','Растягивать загружаемую картинку<br>(если она меньше целевых размеров)');
define('_VB_ADM_UPL_IMG_STRETCH_IF_GT','Сжимать загружаемую картинку<br>(если она больше целевых размеров)');

// ADM ROOT
define('_VB_ADM_ROOT_ADD','Добавить папку');
define('_VB_ADM_ROOT_ENABLED','Включена');
define('_VB_ADM_ROOT_CAPTION','Заголовок');
define('_VB_ADM_ROOT_PATH','Внутренний путь');
define('_VB_ADM_ROOT_URL','Внешний URL');
define('_VB_ADM_ROOT_EXTENSIONS','Список расширений');
define('_VB_ADM_ROOT_COMMENT','Комментарий');
define('_VB_ADM_ROOT_EDIT','Редактировать');
define('_VB_ADM_ROOT_DELETE','Удалить');

// ADM PRUNE
define('_VB_ADM_PRUNE_OLD_INFO','Удалить данные медиа');
define('_VB_ADM_PRUNE_OLDER_THAN','Удалить записи о медиа старее<br>(при условии, что нет медиа с таким именем ни в одной корневой папке (рекурсивно) )');
define('_VB_ADM_PRUNE_OLDER_THAN_DAYS',' дней');
define('_VB_ADM_PRUNE_LIST_ONLY','Не удалять, показать только список');
define('_VB_ADM_PRUNE_LIST','Список на удаление:');
define('_VB_ADM_PRUNE_LIST_TOTAL','Всего:');
define('_VB_ADM_PRUNE_IMAGES_BIGGER','Удалить картинки, занимающие больше чем');
define('_VB_ADM_PRUNE_IMAGES_BIGGER_THAN','байт');

//
define('_VB_ADM_ROOT_OK','Папка сохранена');
?>