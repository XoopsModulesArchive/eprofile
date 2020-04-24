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
 * @since           4.4
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: themeheader.php 2 2012-08-16 08:20:47Z alfred $
 */
 
$xoopsTpl->assign('uid',            $uid);
$xoopsTpl->assign('userlevel',      $thisUser->level());
$xoopsTpl->assign('isAdmin',        $isAdmin);
$xoopsTpl->assign('isOwner',        $isOwner);
$xoopsTpl->assign('uname',          $thisUser->uname());	
$xoopsTpl->assign('eprofile_version','eProfile '.XoopsLocal::number_format(($xoopsModule->getVar('version') / 100)));
$xoopsTpl->assign('perms',	        $profile_permission); 
$xoopsTpl->assign('scraps_count',	  $scraps_count);
$xoopsTpl->assign('pictures_count',	$pictures_count);
$xoopsTpl->assign('friends_count',	$friends_count);
$xoopsTpl->assign('videos_count',	  $videos_count);
$xoopsTpl->assign('audio_count',	  $audio_count);
$xoopsTpl->assign('tribes_count',	  $tribes_count);
$xoopsTpl->assign('pm_count',	      $pm_count);
$xoopsTpl->assign('token',$GLOBALS['xoopsSecurity']->getTokenHTML());
$xoopsTpl->assign('modul_url', XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/");

$addfriends='';
if ($isOwner==0 && (is_object($xoopsUser) && $xoopsUser->isactive())) 
{
    $isfiend_enabled = ($xoopsModuleConfig['profile_friends']==1) ? $profileconfigs_handler->getstat('friends',$uid,$muid):0;
  	if($isfiend_enabled) 	{
    	$level = $profileconfigs_handler->selectFriendLevel($uid);
		if ($level == -1) // Antrag vom Freund noch nicht bearbeitet
	    	$addfriends = _EPROFILE_MA_WAITTHISFRIENDS;
	  elseif ($level == 1) // Antrag muss von mir bearbeitet werden
	    	$addfriends = sprintf(_EPROFILE_MA_FRIENDSMUSTCHECK,$xoopsUser->uid());
    elseif ($level == 2 || $level == -2)  // Antrag OK
	    	$addfriends = _EPROFILE_MA_ISFRIENDS;
		elseif ($level == -3) // Antrag abgelehnt
	    	$addfriends = _EPROFILE_MA_ISNOASFRIENDS;
    elseif ($level == 3) // Antrag von mir abgelehnt
	    	$addfriends = sprintf(_EPROFILE_MA_ISNOASFRIENDSFORME,$uid);
		else 
        $addfriends = '<a href="friends.php?op=add&amp;uid='.$uid.'">'._EPROFILE_MA_ADDTHISFRIENDS.'</a>';
  	}
} 
if ( $friends_new > 0 ) $addfriends = sprintf(_EPROFILE_MA_WAITFRIENDUSER,$friends_new) . "<br />". $addfriends;
$xoopsTpl->assign('lang_addfriends',$addfriends);
$xoTheme->addMeta('meta', 'Author', 'http://www.simple-xoops.de');
?>