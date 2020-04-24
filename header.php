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
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: header.php 2 2012-08-16 08:20:47Z alfred $
 */
 
$xoopsOption['pagetype'] = 'user';
include '../../mainfile.php';
require_once 'include/function.php';

/* for seo */
/*
if((strpos(getenv('REQUEST_URI'), '/modules/'.$xoopsModule->getVar("dirname").'/') === 0  && (!isset($_POST) || count($_POST) <=0))) 
{
        $newurl = str_replace("/modules/".$xoopsModule->getVar('dirname')."/","/saxspace/",getenv('REQUEST_URI'));
        header("HTTP/1.1 301 Moved Permanently");
        header("Location: $newurl");
        exit();
}
*/

$uid 		  = ( isset($_GET['uid']) ) ? intval($_GET['uid']) : ( ($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getVar('uid') : 0 );
/*
if ($uid <= 0) {
 	redirect_header(XOOPS_URL, 2, _US_SELECTNG);
}
*/
$isOwner 	= ( $uid > 0 ) ? ( ( ($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->getVar('uid') == $uid ) ? true : false ) : false;
$isAdmin	= ( is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->isAdmin() ) ? true : false;
$groups 	= ( is_object($GLOBALS['xoopsUser']) ) ? $GLOBALS['xoopsUser']->getGroups() : array(XOOPS_GROUP_ANONYMOUS);

//disable cache
$GLOBALS['xoopsConfig']['module_cache'][$GLOBALS['xoopsModule']->getVar('mid')] = 0;

$dirname 		  = $xoopsModule->getVar('dirname');
$theme_path 	= "/" . $xoopsConfig['theme_set'] . "/modules/" . $dirname;
$lang_path 		= "/images/language/" . $xoopsConfig['language'];
$default_path = "/modules/" . $dirname . "/templates";

//Themepfad
if (file_exists( $GLOBALS['xoops']->path( $theme_path . '/style.css'))) {
	$rel_path = XOOPS_URL . $theme_path . '/style.css';
//language path
} elseif (file_exists( $GLOBALS['xoops']->path( $lang_path . '/style.css'))) {
	$rel_path = XOOPS_URL . $lang_path . '/style.css';
//default
} else {
	$rel_path = XOOPS_URL . $default_path . '/style.css';
}
$xoopsOption['xoops_module_header'] = '<link rel="stylesheet" type="text/css" href="' . $rel_path . '" />';

$config_handler =& xoops_gethandler('config');
$GLOBALS['xoopsConfigUser'] = $config_handler->getConfigsByCat(XOOPS_CONF_USER);
$gperm_handler = & xoops_gethandler( 'groupperm' );
$myts = MyTextSanitizer::getInstance();
$member_handler =& xoops_gethandler('member');

//Permissions
$muid = 1; //is Guest
$profileconfigs_handler = xoops_getmodulehandler('configs');
if ( $xoopsUser && $xoopsUser->isactive() ) {
	if ( $xoopsUser->getVar('uid') == $uid ) 
    $muid = 4; //self 
  elseif ($profileconfigs_handler->isMyFriend($uid))    
		$muid = 3; // Friends
  else $muid = 2;	//isUser
}

$profile_permission = $profileconfigs_handler->getperm('all', $uid, $muid);

if ( $profile_permission['profile_stats'] ) {
  //Visitors
  $visit_handler =& xoops_getmodulehandler('visitors');
  $visit_handler->setvisit($uid);
}

//messages
$pm_count = 0;
if ( $profile_permission['profile_messages'] ) {
	$pm_factory = xoops_getmodulehandler('message');
  if ($isOwner) {   
    $criteria = new CriteriaCompo(new Criteria('read_msg', 0));
    $criteria->add(new Criteria('to_userid', $uid));
    $pm_count = $pm_factory->getCount($criteria);
  }
} 

//Scraps
$scraps_count = 0;
if ( $profile_permission['scraps'] ) {
	$scraps_factory = xoops_getmodulehandler('scraps');
	$scraps_count = $profileconfigs_handler->getCount('profile_scraps','scrap_to',$uid);
} 

//pictures
$pictures_count = 0;
if ( $profile_permission['pictures'] ) {
	$pictures_factory = xoops_getmodulehandler('pictures');
  if ($isOwner) 
    $pictures_count = $profileconfigs_handler->getCount('profile_pictures', 'pic_uid', $uid);
  else 
    $pictures_count = $profileconfigs_handler->getCount('profile_pictures', 'pic_uid', $uid, " private=0");
} 
//friends
$friends_count = $friends_new = 0;
if ( $profile_permission['friends'] ) {
  $friends_factory = xoops_getmodulehandler('friends');
	if ($isOwner) {    
    $friends_new = $profileconfigs_handler->getCount('profile_friends', 'friend_uid', $uid, " level=1 AND self_uid<>" . $uid);
  } 
  $friends_count = $profileconfigs_handler->getCount('profile_friends', 'self_uid', $uid, " level=2 OR (friend_uid=" . $uid . " AND level=2)");
} 

//videos
$videos_count = 0;
if ( $profile_permission['videos'] ) {
  $videos_factory = xoops_getmodulehandler('video');
	$videos_count = $profileconfigs_handler->getCount('profile_videos','uid_owner',$uid);
} 

//audio
$audio_count = 0;
if ( $profile_permission['audio'] ) {
	$audio_factory = xoops_getmodulehandler('audio');
	$audio_count = $profileconfigs_handler->getCount('profile_audio','audio_uid',$uid);
} 

//groups

$tribes_count = 0;
/*
if ( $profile_permission['tribes'] ) {
	$tribes_factory = xoops_getmodulehandler('tribes');
	$tribes_count = $profileconfigs_handler->getCount('profile_tribes','tribes_owner',$uid, " tribes_uid=0");
} 
*/


xoops_load("XoopsLocal");

if ( !$isOwner ) {
	$thisUser =& $member_handler->getUser($uid);
  if ( !$thisUser ) {
    if ( $uid > 0 ) {
      redirect_header(XOOPS_URL, 3, _US_SELECTNG);
    }
	}
	if ( !$thisUser->isActive() && !$isAdmin ) {
    if ( $uid > 0 ) {
      redirect_header(XOOPS_URL, 3, _US_NOACTTPADM);
    } else {
      $thisUser = null;
    }
	}	
} else {
	$thisUser = $GLOBALS['xoopsUser'];
}
?>