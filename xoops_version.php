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
 * @version         $Id: xoops_version.php 2537 2008-11-29 12:03:30Z dhcst $
 */
 
/**
 * This is a temporary solution for merging XOOPS 2.0 and 2.2 series
 * A thorough solution will be available in XOOPS 3.0
 *
 */
$modversion = array();
$modversion['name']         = _PROFILE_MI_NAME;
$modversion['version']      = 1.70;
$modversion['description']  = _PROFILE_MI_DESC;
$modversion['author']       = "Jan Pedersen; Taiwen Jiang <phppp@users.sourceforge.net>; alfred <myxoops@t-online.de>";
$modversion['credits']      = "Ackbarr, mboyden, marco, mamba, etc.";
$modversion['license']      = "GPL see XOOPS LICENSE";
$modversion['image']        = "images/logo.png";
$modversion['dirname']      = "profile";

// Admin things
$modversion['hasAdmin']     = 1;
$modversion['adminindex']   = "admin/user.php";
$modversion['adminmenu']    = "admin/menu.php";

// Scripts to run upon installation or update
$modversion['onInstall']    = "include/install.php";
$modversion['onUpdate']     = "include/update.php";

// Menu
$modversion['hasMain']      = 1;
global $xoopsUser;
if ($xoopsUser) {
    $modversion['sub'][1]['name']   = _PROFILE_MI_EDITACCOUNT;
    $modversion['sub'][1]['url']    = "edituser.php";
    $modversion['sub'][2]['name']   = _PROFILE_MI_PAGE_SEARCH;
    $modversion['sub'][2]['url']    = "search.php";
    $modversion['sub'][3]['name']   = _PROFILE_MI_CHANGEPASS;
    $modversion['sub'][3]['url']    = "changepass.php";
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

// Config items
$i=0;
$modversion['config'][$i]['name']           = 'profile_delimiter0';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_CAT_SETTINGS."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_search';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_SEARCH';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_SEARCH_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1;

$i++;
$modversion['config'][$i]['name']           = 'profile_messages';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_MESSAGES';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_MESSAGES_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1;

$i++;
$modversion['config'][$i]['name']           = 'profile_emails';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_EMAILS';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_EMAILS_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter1';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_PROFILE_FACEBOOKTITLE."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_facebook';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_FACEBOOK';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_FACEBOOK_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_fb_apikey';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_FACEBOOKAPIKEY';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_FACEBOOKKEY_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'text';
$modversion['config'][$i]['default']        = '';

$i++;
$modversion['config'][$i]['name']           = 'profile_fb_apid';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_FACEBOOKAPPID';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_FACEBOOKAPPID_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'text';
$modversion['config'][$i]['default']        = '';

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter2';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_PROFILE_SCRAPSTITLE."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_scraps';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_SCRAPS';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_SCRAPS_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1;

$i++;
$modversion['config'][$i]['name']           = 'profile_scraps_perpage';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_scraps_preview';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter3';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_PROFILE_FRIENDSTITLE."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_friends';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_FRIENDS';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_FRIENDS_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1;

$i++;
$modversion['config'][$i]['name']           = 'profile_friends_perpage';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_friends_preview';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter4';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_PROFILE_PICTURESTITLE."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_pictures';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PICTURES';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PICTURES_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_pictures_perpage';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_pictures_preview';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_max';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PICTURESMAXSPACE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PICTURESMAXSPACE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1024;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_maxsolo';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PICTURESMAXSOLOSPACE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PICTURESMAXSOLOSPACE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 512;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_thumbheigth';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PICTURESTHUMBHEIGTH';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PICTURESTHUMBHEIGTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 150;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_thumbwidth';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PICTURESTHUMBWIDTH';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PICTURESTHUMBWIDTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 150;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_maxheight';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PICTURESMAXHEIGTH';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PICTURESMAXHEIGTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 768;

$i++;
$modversion['config'][$i]['name']           = 'profile_pic_maxwidth';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PICTURESMAXWIDTH';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PICTURESMAXWIDTH_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 1024;

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter4';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_PROFILE_AUDIOSTITLE."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_audios';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_AUDIOS';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_AUDIOS_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_audioplayer';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_AUDIOPLAYER';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_AUDIOPLAYER_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("dewplayer" => 0, "musicplayer" => 1, "flashmp3player" => 2);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_audios_perpage';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_audio_max';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_AUDIOMAXSPACE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_AUDIOMAXSPACE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 5110;

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter5';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_PROFILE_VIDEOSTITLE."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_videos';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_VIDEOS';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_VIDEOS_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_videos_perpage';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'width_tube';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_WITHTUBE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_WITHTUBE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 400;

$i++;
$modversion['config'][$i]['name']           = 'height_tube';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_HEIGTHTUBE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_HEIGTHTUBE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 280;

$i++;
$modversion['config'][$i]['name']           = 'profile_videos_preview';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '1';

$i++;
$modversion['config'][$i]['name']           = 'profile_delimiter6';
$modversion['config'][$i]['title']          = '_PROFILE_MI_LEER';
$modversion['config'][$i]['description']    = '';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array("===  "._PROFILE_MI_PROFILE_GROUPSTITLE."  ===" => 0);
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_TRIBES';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_TRIBES_DESC';
$modversion['config'][$i]['formtype']       = 'yesno';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 0;

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes_perpage';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PERPAGE';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PERPAGE_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 10;

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes_preview';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_PREVIEW';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_PREVIEW_DESC';
$modversion['config'][$i]['formtype']       = 'select';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['options']        = array('0'=>0,'1'=>1,'2'=>2,'3'=>3,'4'=>4,'5'=>5,'6'=>6,'7'=>7,'8'=>8,'9'=>9);
$modversion['config'][$i]['default']        = '4';

$i++;
$modversion['config'][$i]['name']           = 'profile_tribes_max';
$modversion['config'][$i]['title']          = '_PROFILE_MI_PROFILE_TRIBESMAX';
$modversion['config'][$i]['description']    = '_PROFILE_MI_PROFILE_TRIBESMAX_DESC';
$modversion['config'][$i]['formtype']       = 'textbox';
$modversion['config'][$i]['valuetype']      = 'int';
$modversion['config'][$i]['default']        = 5;

// Templates
$i = 0;
$i++;
$modversion['templates'][$i]['file']        = 'profile_breadcrumbs.html';
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
$modversion['templates'][$i]['file']        = 'profile_facebook.html';
$modversion['templates'][$i]['description'] = '';
?>