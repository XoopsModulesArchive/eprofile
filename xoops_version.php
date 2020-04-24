<?php
/**
 * Extended User Profile
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code 
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         profile
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: xoops_version.php 41 2014-04-14 20:58:09Z alfred $
 */
 
/**
 * This is a temporary solution for merging XOOPS 2.0 and 2.2 series
 * A thorough solution will be available in XOOPS 3.0
 *
 */
xoops_loadLanguage('main', 'eprofile');
 
$modversion = array();
$modversion['name']         = _EPROFILE_MI_NAME;
$modversion['version']      = 1.79;
$modversion['simple-xoops'] = true; 
$modversion['description']  = _EPROFILE_MI_DESC;
$modversion['author']       = "Jan Pedersen; Taiwen Jiang; alfred";
$modversion['credits']      = "alfred, Ackbarr, mboyden, marco, mamba, etc.";
$modversion['license']      = "GPL see XOOPS LICENSE";
$modversion['image']        = "images/logo.png";
$modversion['dirname']      = "eprofile";

// Admin things
$modversion['hasAdmin']     = 1;
$modversion['adminindex']   = "admin/index.php";
$modversion['adminmenu']    = "admin/menu.php";

// Scripts to run upon installation or update
$modversion['onInstall']    = "include/install.php";
$modversion['onUpdate']     = "include/update.php";

//about
$modversion['release_date']     	  = '2014/05/04';
$modversion["module_website_url"] 	= "http://www.simple-xoops.de/";
$modversion["module_website_name"] 	= "SIMPLE-XOOPS";
$modversion["module_status"] 		    = "RC 1";
$modversion['min_php']				      = "5.3";
$modversion['min_xoops']			      = "2.5.5";
$modversion['min_admin']			      = "1.1";
$modversion['min_db']				        = array('mysql'=>'5.0', 'mysqli'=>'5.0');
$modversion['system_menu'] 			    = 1;

$modversion['dirmoduleadmin'] 		= 'Frameworks/moduleclasses';
$modversion['icons16'] 				    = 'Frameworks/moduleclasses/icons/16';
$modversion['icons32'] 				    = 'Frameworks/moduleclasses/icons/32';

// Menu
$modversion['hasMain']      = 1;
if ($GLOBALS['xoopsUser'] && $GLOBALS['xoopsUser']->isActive()) {
    $modversion['sub'][1]['name']   = _EPROFILE_MI_EDITACCOUNT;
    $modversion['sub'][1]['url']    = "edituser.php";
    $modversion['sub'][2]['name']   = _EPROFILE_MI_PAGE_SEARCH;
    $modversion['sub'][2]['url']    = "search.php";
    $modversion['sub'][3]['name']   = _EPROFILE_MI_CHANGEPASS;
    $modversion['sub'][3]['url']    = "changepass.php";
    
    $config_handler =& xoops_gethandler('config');
    $xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);
    if ($xoopsConfigUser['allow_chgmail'] == 1) {
      $modversion['sub'][4]['name']   = _EPROFILE_MI_CHANGEMAIL;
      $modversion['sub'][4]['url']    = "changemail.php";
    }    
}

// Mysql file
$modversion['sqlfile']['mysql']     = "sql/mysql.sql";

// Tables created by sql file (without prefix!)
$modversion['tables'][1] = "profile_category";
$modversion['tables'][2] = "profile_profile";
$modversion['tables'][3] = "profile_field";
$modversion['tables'][4] = "profile_visibility";
$modversion['tables'][5] = "profile_regstep";
$modversion['tables'][6] = "profile_configs";
$modversion['tables'][7] = "profile_scraps";
$modversion['tables'][8] = "profile_friends";
$modversion['tables'][9] = "profile_pictures";
$modversion['tables'][10]= "profile_visitors";
$modversion['tables'][11]= "profile_audio";
$modversion['tables'][12]= "profile_videos";
$modversion['tables'][13]= "profile_groups";
$modversion['tables'][14]= "profile_tribes";

// Blocks
$i=0;
$modversion['blocks'][$i]['file']         = 'online.php';
$modversion['blocks'][$i]['name']         = _EPROFILE_MI_BLOCK_ONLINE;
$modversion['blocks'][$i]['description']  = '';
$modversion['blocks'][$i]['show_func']    = 'eprofile_online_show';
$modversion['blocks'][$i]['edit_func']    = 'eprofile_online_edit';
$modversion['blocks'][$i]['options']      = '0|0|5';
$modversion['blocks'][$i]['template']     = 'eprofile_online.html';

$i++;
$modversion['blocks'][$i]['file']         = 'online.php';
$modversion['blocks'][$i]['name']         = _EPROFILE_MI_BLOCK_POPULAR;
$modversion['blocks'][$i]['description']  = '';
$modversion['blocks'][$i]['show_func']    = 'eprofile_popular_show';
$modversion['blocks'][$i]['edit_func']    = 'eprofile_popular_edit';
$modversion['blocks'][$i]['options']      = '0|0|5';
$modversion['blocks'][$i]['template']     = 'eprofile_popular.html';

$i++;
$modversion['blocks'][$i]['file']         = 'online.php';
$modversion['blocks'][$i]['name']         = _EPROFILE_MI_BLOCK_NEIGTHBAR;
$modversion['blocks'][$i]['description']  = '';
$modversion['blocks'][$i]['show_func']    = 'eprofile_neigthbar_show';
$modversion['blocks'][$i]['edit_func']    = 'eprofile_neigthbar_edit';
$modversion['blocks'][$i]['options']      = '25|0|0|5|demo';
$modversion['blocks'][$i]['template']     = 'eprofile_neigthbar.html';

// Config items
$i=0;
$modversion['config'][$i]['name']           = 'profile_delimiter0';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['title']          = "_EPROFILE_MI_CAT_SETTINGS";
$modversion['config'][$i]['valuetype']      = 'line_break';

$i++;
$modversion['config'][$i]['name']           = 'profile_general';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_SEARCH';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_SEARCH_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSEVERYONE'=>1,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3,
                                              '_EPROFILE_MA_CONFIGSONLYME'=>4
                                              );
$modversion['config'][$i]['default']        = '1';

$i++;
$modversion['config'][$i]['name']           = 'profile_stats';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_STATS';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_STATS_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_messages';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_MESSAGES';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_MESSAGES_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3
                                              );
$modversion['config'][$i]['default']        = '2';

$i++;
$modversion['config'][$i]['name']           = 'profile_emails';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_EMAILS';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_EMAILS_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3
                                              );
$modversion['config'][$i]['default']        = '2';

$i++;
$modversion['config'][$i]['name']           = 'profile_searchavatar';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_AVATARSEARCH';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_AVATARSEARCH_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter2';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_SCRAPSTITLE';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['valuetype']      = 'line_break';



$i++;
$modversion['config'][$i]['name']           = 'profile_scraps';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_SCRAPS';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_SCRAPS_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSEVERYONE'=>1,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3,
                                              '_EPROFILE_MA_CONFIGSONLYME'=>4
                                              );
$modversion['config'][$i]['default']        = '0';

$i++;
$modversion['config'][$i]['name']           = 'profile_scraps_perpage';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_scraps_preview';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter3';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_FRIENDSTITLE';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['valuetype']      = 'line_break';

$i++;
$modversion['config'][$i]['name']           = 'profile_friends';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_FRIENDS';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_FRIENDS_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSEVERYONE'=>1,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3,
                                              '_EPROFILE_MA_CONFIGSONLYME'=>4
                                              );
$modversion['config'][$i]['default']        = '0';

$i++;
$modversion['config'][$i]['name']           = 'profile_friends_perpage';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_friends_preview';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter4';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURESTITLE';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['valuetype']      = 'line_break';

$i++;
$modversion['config'][$i]['name']           = 'profile_pictures';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURES';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PICTURES_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSEVERYONE'=>1,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3,
                                              '_EPROFILE_MA_CONFIGSONLYME'=>4
                                              );
$modversion['config'][$i]['default']        = '0';

$i++;
$modversion['config'][$i]['name']           = 'profile_pictures_perpage';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_pictures_preview';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_max';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURESMAXSPACE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PICTURESMAXSPACE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1024;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_maxsolo';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURESMAXSOLOSPACE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PICTURESMAXSOLOSPACE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 512;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_thumbheigth';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURESTHUMBHEIGTH';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PICTURESTHUMBHEIGTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 150;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_thumbwidth';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURESTHUMBWIDTH';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PICTURESTHUMBWIDTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 150;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_maxheight';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURESMAXHEIGTH';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PICTURESMAXHEIGTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 768;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_maxwidth';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PICTURESMAXWIDTH';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PICTURESMAXWIDTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1024;

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter4';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_AUDIOSTITLE';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['valuetype']      = 'line_break';

$i++;
$modversion['config'][$i]['name']           = 'profile_audio';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_AUDIOS';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_AUDIOS_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSEVERYONE'=>1,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3,
                                              '_EPROFILE_MA_CONFIGSONLYME'=>4
                                              );
$modversion['config'][$i]['default']        = '0';

$i++;
$modversion['config'][$i]['name']           = 'profile_audioplayer';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_AUDIOPLAYER';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_AUDIOPLAYER_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("dewplayer" => 0, "musicplayer" => 1, "flashmp3player" => 2);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_audios_perpage';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_audio_max';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_AUDIOMAXSPACE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_AUDIOMAXSPACE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 5110;

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter5';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_VIDEOSTITLE';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['valuetype']      = 'line_break';

$i++;
$modversion['config'][$i]['name']           = 'profile_videos';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_VIDEOS';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_VIDEOS_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSEVERYONE'=>1,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3,
                                              '_EPROFILE_MA_CONFIGSONLYME'=>4
                                              );
$modversion['config'][$i]['default']        = '0';

$i++;
$modversion['config'][$i]['name']           = 'profile_videos_perpage';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'width_tube';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_WITHTUBE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_WITHTUBE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 400;

$i++;
$modversion['config'][$i]['name']           = 'height_tube';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_HEIGTHTUBE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_HEIGTHTUBE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 280;

$i++;
$modversion['config'][$i]['name']           = 'profile_videos_preview';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '1';

/*
// Groups Feature
$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter6';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_GROUPSTITLE';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['valuetype']      = 'line_break';

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_TRIBES';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_TRIBES_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array(
                                              '_EPROFILE_MA_CONFIGSNOTHING'=>0,
                                              '_EPROFILE_MA_CONFIGSEVERYONE'=>1,
                                              '_EPROFILE_MA_CONFIGSONLYEUSERS'=>2 ,
                                              '_EPROFILE_MA_CONFIGSONLYEFRIENDS'=>3,
                                              '_EPROFILE_MA_CONFIGSONLYME'=>4
                                              );
$modversion['config'][$i]['default']        = '0';

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes_perpage';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes_preview';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes_max';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_TRIBESMAX';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_EPROFILE_TRIBESMAX_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 5;
*/

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter6';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_EPROFILE_ANTISPAM';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'line_break';
$modversion['config'][$i]['valuetype']      = 'line_break';

$i++;
$modversion['config'][$i]['name']           = 'profile_antispam_apikey';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_ANTISPAM_APIKEY';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_ANTISPAM_APIKEY_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'text';
$modversion['config'][$i]['default']        = '';

$i++;
$modversion['config'][$i]['name']           = 'profile_antispam_submit';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_ANTISPAM_SUBMIT';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_ANTISPAM_SUBMIT_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1;

$i++;
$modversion['config'][$i]['name']           = 'profile_antispam_sendmail';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_ANTISPAM_SENDMAIL';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_ANTISPAM_SENDMAIL_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1;

$i++;
$modversion['config'][$i]['name']           = 'profile_antispam_ip';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_ANTISPAM_IP';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_ANTISPAM_IP_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = '5';

$i++;
$modversion['config'][$i]['name']           = 'profile_antispam_email';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_ANTISPAM_EMAIL';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_ANTISPAM_EMAIL_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = '5';

$i++;
$modversion['config'][$i]['name']           = 'profile_antispam_uname';
$modversion['config'][$i]['title']          = '_EPROFILE_MI_ANTISPAM_UNAME';
$modversion['config'][$i]['description']    = '_EPROFILE_MI_ANTISPAM_UNAME_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = '5';

// Templates
$i = 0;
$i++;
$modversion['templates'][$i]['file']        = 'profile_header.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_form.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_admin_fieldlist.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_userinfo.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_admin_categorylist.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_search.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_results.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_admin_visibility.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_admin_steplist.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_register.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_editprofile.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_userform.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_avatar.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_configs.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_scrapbook.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_friends.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_footer.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_pictures.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_audios.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_videos.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_pmmessage.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_pmread.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_pmwrite.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_tribes.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_email.html';
$modversion['templates'][$i]['description'] = '';

$i++;
$modversion['templates'][$i]['file']        = 'profile_start.html';
$modversion['templates'][$i]['description'] = '';
?>