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
 * @since           2.3.3
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: userinfo.php 2584 2008-12-07 12:19:32Z dhcst $
 */
if (!defined("PROFILE_HEADER_INCLUDED")) die("ACCESS DENIED") ;
$GLOBALS['xoopsTpl']->assign('isOwner', $isOwner);
$GLOBALS['xoopsTpl']->assign('uid', $uid);

if (!empty($GLOBALS['thisUser']) ) {
    $socialUser =& $GLOBALS['thisUser'];
}
else
{
    $member_handler =& xoops_gethandler('member');
    $socialUser =& $member_handler->getUser($uid);
}


if ($socialUser && $socialUser->isActive())
{
  	$GLOBALS['xoopsTpl']->assign('uid_username', $socialUser->getVar('uname'));
  	$GLOBALS['xoopsTpl']->assign('sendpm_touser', _PROFILE_MA_KONTAKTTOPM);
  	$GLOBALS['xoopsTpl']->assign('sendmail_touser', _PROFILE_MA_KONTAKTTOEMAIL);
  	$GLOBALS['xoopsTpl']->assign('userlevel', $socialUser->isActive());
    $userrank = $socialUser->rank();
    if (isset($userrank['image']) && $userrank['image']) {
        $GLOBALS['xoopsTpl']->assign('user_rankimage', '<img src="' . XOOPS_UPLOAD_URL . '/' . $userrank['image'] . '" alt="" />');
    }
    $GLOBALS['xoopsTpl']->assign('user_ranktitle', $userrank['title']);
	if ($isOwner)
	{ 
		$GLOBALS['xoopsTpl']->assign('user_ownpage', true);
		$GLOBALS['xoopsTpl']->assign('user_candelete',($GLOBALS['xoopsConfigUser']['self_delete'] == 1) ? true : false);
		$GLOBALS['xoopsTpl']->assign('user_changeemail',($GLOBALS['xoopsConfigUser']['allow_chgmail'] == 1) ? true : false);
	}
    $xoBreadcrumbs[] = array("title" => $socialUser->getVar('uname'), "link" => XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/userinfo.php?uid='.$socialUser->getVar('uid'));
}
else
{
  	$GLOBALS['xoopsTpl']->assign('uid_username', _GUEST);
}

if (!isset($profileconfigs_handler)) 
{
  $profileconfigs_handler = xoops_getmodulehandler('configs');
}

//Visitors
$visit_handler =& xoops_getmodulehandler('visitors');
$visit_handler->setvisit($uid);

//Permissions
$profile_permission = $profileconfigs_handler->getperm('all',$uid);
$GLOBALS['xoTheme']->addScript(XOOPS_URL.'/modules/'.$GLOBALS['xoopsModule']->getVar('dirname').'/js/jquery142.js');
$GLOBALS['xoTheme']->addStylesheet(XOOPS_URL.'/modules/'.$GLOBALS['xoopsModule']->getVar('dirname').'/js/colorbox.css');
$GLOBALS['xoTheme']->addScript(XOOPS_URL.'/modules/'.$GLOBALS['xoopsModule']->getVar('dirname').'/js/jquery.colorbox-min.js');

$GLOBALS['xoopsTpl']->assign('token',$GLOBALS['xoopsSecurity']->getTokenHTML());

//print_r($profile_permission);
//sonstige Profile
$allow_profile = ( !empty($profile_permission['profile_general'] ) ) ? 1:0;
$xoopsTpl->assign('allow_profile',$allow_profile);

// Aktivitaeten
$allow_modules = (!empty($xoopsModuleConfig['profile_search'])) ? (!empty($profile_permission['profile_stats']) ? 1:0) : 0;
$xoopsTpl->assign('allow_modules',$allow_modules);
$xoopsTpl->assign('allow_config_modules',($xoopsModuleConfig['profile_search']==1 ? 1:0));

//Scraps
$scraps_count = 0;
$allow_scraps = (!empty($xoopsModuleConfig['profile_scraps'])) ? (!empty($profile_permission['scraps']) ? 1:0) : 0;
$xoopsTpl->assign('allow_scraps',$allow_scraps);
if ($allow_scraps) 
{
  	$xoopsTpl->assign('allow_send_scraps',(!empty($profile_permission['sendscraps']) ? 1:0));
    $scraps_count = $profileconfigs_handler->getCount('profile_scraps','scrap_to',$uid);
  	$xoopsTpl->assign('scraps_count',$scraps_count);
}
$xoopsTpl->assign('allow_config_scraps',($xoopsModuleConfig['profile_scraps']==1 ? 1:0));

//Pictures
$pictures_count = 0;
$allow_pictures = (!empty($xoopsModuleConfig['profile_pictures'])) ? (!empty($profile_permission['pictures']) ? 1:0) : 0;
$xoopsTpl->assign('allow_pictures',$allow_pictures);
if ($allow_pictures) 
{
  	if (!is_object($xoopsUser) || $xoopsUser->uid()!=$uid)
    	$pictures_count = $profileconfigs_handler->getCount('profile_pictures','pic_uid',$uid,'private=0');
  	else
    	$pictures_count = $profileconfigs_handler->getCount('profile_pictures','pic_uid',$uid);        
    $xoopsTpl->assign('pictures_count',$pictures_count);
}
$xoopsTpl->assign('allow_config_pictures',(!empty($xoopsModuleConfig['profile_pictures']) ? 1:0));

//Emails
$allow_emails = (!empty($xoopsModuleConfig['profile_emails'])) ? (!empty($profile_permission['emails']) ? 1:0) : 0;
$xoopsTpl->assign('allow_emails',$allow_emails);
$xoopsTpl->assign('allow_config_emails',($xoopsModuleConfig['profile_emails']==1 ? 1:0));

//Videos
$videos_count=0;
$allow_videos = (!empty($xoopsModuleConfig['profile_videos'])) ? (!empty($profile_permission['videos']) ? 1:0) : 0;
$xoopsTpl->assign('allow_videos',$allow_videos);
if ($allow_videos) 
{
  	$videos_count = $profileconfigs_handler->getCount('profile_videos','uid_owner',$uid);
    $xoopsTpl->assign('videos_count',$videos_count);
}
$xoopsTpl->assign('allow_config_videos',(!empty($xoopsModuleConfig['profile_videos']) ? 1:0));

//Audios
$audio_count=0;
$allow_audios = (!empty($xoopsModuleConfig['profile_audios'])) ? (!empty($profile_permission['audio']) ? 1:0) : 0;
$xoopsTpl->assign('allow_audios',$allow_audios);
if ($allow_audios) 
{
  	$audio_count = $profileconfigs_handler->getCount('profile_audio','audio_uid',$uid);
    $xoopsTpl->assign('audios_count',$audio_count);
}
$xoopsTpl->assign('allow_config_audios',(!empty($xoopsModuleConfig['profile_audios']) ? 1:0));

//Friends
$friends_count = 0;
$allow_friends = (!empty($xoopsModuleConfig['profile_friends'])) ? (!empty($profile_permission['friends']) ? 1:0) : 0;
$xoopsTpl->assign('allow_friends',$allow_friends);
if ($allow_friends) 
{
    $friends_count =  $profileconfigs_handler->getCount('profile_friends','self_uid',$uid,'level=2');
	$friends_count += $profileconfigs_handler->getCount('profile_friends','friend_uid',$uid,'level=2');
  	$xoopsTpl->assign('friends_count',$friends_count);
}
$xoopsTpl->assign('allow_config_friends',(!empty($xoopsModuleConfig['profile_friends']) ? 1:0));


//Tribes
$allow_tribes = (!empty($xoopsModuleConfig['profile_tribes'])) ? (!empty($profile_permission['tribes']) ? 1:0) : 0;
$xoopsTpl->assign('allow_tribes',$allow_tribes);
if ($allow_tribes) 
{
  	$xoopsTpl->assign('tribes_count',$profileconfigs_handler->getCount('profile_tribes','tribe_uid',$uid));
}
$xoopsTpl->assign('allow_config_tribes',(!empty($xoopsModuleConfig['profile_tribes']) ? 1:0));

//Messages
$allow_messages = (!empty($xoopsModuleConfig['profile_messages'])) ? (!empty($profile_permission['profile_messages']) ? 1:0) : 0;
$xoopsTpl->assign('allow_messages',$allow_messages);
$xoopsTpl->assign('allow_config_messages',(!empty($xoopsModuleConfig['profile_messages']) ? 1:0));

$addfriends='';
if ($isOwner==0 && (is_object($xoopsUser) && $xoopsUser->isactive())) 
{
    $isfiend_enabled = ($xoopsModuleConfig['profile_friends']==1) ? $profileconfigs_handler->getstat('friends',$uid):0;
  	if($isfiend_enabled) 
	{
    	$level = $profileconfigs_handler->selectFriendLevel($uid);
		if ($level == -1) // Antrag vom Freund noch nicht bearbeitet
	    	$addfriends = _PROFILE_MA_WAITTHISFRIENDS;
	    elseif ($level == 1) // Antrag muss von mir bearbeitet werden
	    	$addfriends = sprintf(_PROFILE_MA_FRIENDSMUSTCHECK,$xoopsUser->uid());
    	elseif ($level == 2 || $level == -2)  // Antrag OK
	    	$addfriends = _PROFILE_MA_ISFRIENDS;
		elseif ($level == -3) // Antrag abgelehnt
	    	$addfriends = _PROFILE_MA_ISNOASFRIENDS;
    	elseif ($level == 3) // Antrag von mir abgelehnt
	    	$addfriends = _PROFILE_MA_ISNOASFRIENDSFORME;
		else 
        {
           $addfriends = '<a href="friends.php?op=add&amp;uid='.$uid.'">'._PROFILE_MA_ADDTHISFRIENDS.'</a>';
        }	    	
  	}
} 
if (is_object($xoopsUser) && $xoopsUser->isactive()) 
{
  	//Facebook
    $allow_facebook = (!empty($xoopsModuleConfig['profile_facebook'])) ? (!empty($profile_permission['profile_facebook']) ? 1:0) : 0;
    $xoopsTpl->assign('allow_facebook',$allow_facebook);
    $xoopsTpl->assign('allow_config_facebook',(!empty($xoopsModuleConfig['profile_facebook']) ? 1:0));

    $friends_handler = xoops_getmodulehandler('friends');
  	$newfriends = intval($friends_handler->countNewFriends());
	$addfriends .= ($newfriends>0) ? "<br />".sprintf(_PROFILE_MA_WAITFROMTHISFRIENDS,$xoopsUser->uid(),$newfriends) : '';
}

xoops_load("XoopsLocal");
$GLOBALS['xoopsTpl']->assign('lang_addfriends',$addfriends);
$GLOBALS['xoopsTpl']->assign('eprofile_version','eProfile '.XoopsLocal::number_format(($xoopsModule->getVar('version') / 100)));


?>