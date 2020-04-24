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
 * @version         $Id: userinfo.php 2584 2008-12-07 12:19:32Z dhcst $
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';
include_once $GLOBALS['xoops']->path('modules/system/constants.php');

if ($uid <= 0) {
	header('location: ' . XOOPS_URL.'/');
    exit();
}

$groups = is_object($GLOBALS['xoopsUser']) ? $GLOBALS['xoopsUser']->getGroups() : array(XOOPS_GROUP_ANONYMOUS);

if (is_object($GLOBALS['xoopsUser']) && $uid == $GLOBALS['xoopsUser']->getVar('uid')) {
    $xoopsOption['template_main'] = 'profile_userinfo.html';
    include $GLOBALS['xoops']->path('header.php');    
    $GLOBALS['xoopsTpl']->assign('user_ownpage', true);
    $GLOBALS['xoopsTpl']->assign('lang_editprofile', _US_EDITPROFILE);
    $GLOBALS['xoopsTpl']->assign('lang_changepassword', _PROFILE_MA_CHANGEPASSWORD);
    $GLOBALS['xoopsTpl']->assign('lang_avatar', _US_AVATAR);
    $GLOBALS['xoopsTpl']->assign('lang_inbox', _US_INBOX);
    $GLOBALS['xoopsTpl']->assign('lang_logout', _US_LOGOUT);
    if ($GLOBALS['xoopsConfigUser']['self_delete'] == 1) {
        $GLOBALS['xoopsTpl']->assign('user_candelete', true);
        $GLOBALS['xoopsTpl']->assign('lang_deleteaccount', _US_DELACCOUNT);
    } else {
        $GLOBALS['xoopsTpl']->assign('user_candelete', false);
    }
    $GLOBALS['xoopsTpl']->assign('user_changeemail', $GLOBALS['xoopsConfigUser']['allow_chgmail']);
    $thisUser =& $GLOBALS['xoopsUser'];
} else {
    $member_handler =& xoops_gethandler('member');
    $thisUser =& $member_handler->getUser($uid);
    
    // Redirect if not a user or not active and the current user is not admin
    if (!is_object($thisUser) || (!$thisUser->isActive() && (!$GLOBALS['xoopsUser'] || !$GLOBALS['xoopsUser']->isAdmin() ))) {
        redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n'), 3, _US_SELECTNG);
        exit();
    }
    
    /**
     * Access permission check
     *
     * Note: 
     * "thisUser" refers to the user whose profile will be accessed; "xoopsUser" refers to the current user $xoopsUser
     * "Basic Groups" refer to XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS and XOOPS_GROUP_ANONYMOUS;
     * "Non Basic Groups" refer to all other custom groups 
     * 
     * Admin groups: If thisUser belongs to admin groups, the xoopsUser has access if and only if one of xoopsUser's groups is allowed to access admin group; else
     * Non basic groups: If thisUser belongs to one or more non basic groups, the xoopsUser has access if and only if one of xoopsUser's groups is allowed to allowed to any of the non basic groups; else
     * User group: If thisUser belongs to User group only, the xoopsUser has access if and only if one of his groups is allowed to access User group
     *
     */
    $groups_basic = array(XOOPS_GROUP_ADMIN, XOOPS_GROUP_USERS, XOOPS_GROUP_ANONYMOUS);
    $groups_thisUser = $thisUser->getGroups();
    $groups_thisUser_nonbasic = array_diff($groups_thisUser, $groups_basic);
    $groups_xoopsUser = $groups;
    $gperm_handler =& xoops_gethandler('groupperm');
    $groups_accessible = $gperm_handler->getItemIds('profile_access', $groups_xoopsUser, $GLOBALS['xoopsModule']->getVar('mid'));

    $rejected = false;
    if ($thisUser->isAdmin()) {
        $rejected = !in_array(XOOPS_GROUP_ADMIN, $groups_accessible);
    } else if ($groups_thisUser_nonbasic) {
        $rejected = !array_intersect($groups_thisUser_nonbasic, $groups_accessible);
    } else {
        $rejected = !in_array(XOOPS_GROUP_USERS, $groups_accessible);
    }

    if ($rejected) {
        redirect_header(XOOPS_URL . "/modules/" . $GLOBALS['xoopsModule']->getVar('dirname', 'n'), 3, _NOPERM);
        exit();
    }
    $xoopsOption['template_main'] = 'profile_userinfo.html';
    include $GLOBALS['xoops']->path('header.php');
    $GLOBALS['xoopsTpl']->assign('user_ownpage', false);
}

include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'social.php';
$GLOBALS['xoopsTpl']->assign('section_name', _PROFILE_MA_USERINFO);

$GLOBALS['xoopsTpl']->assign('user_uid', $thisUser->getVar('uid'));
if (is_object($GLOBALS['xoopsUser']) && $GLOBALS['xoopsUser']->isAdmin()) {
    $GLOBALS['xoopsTpl']->assign('lang_editprofile', _US_EDITPROFILE);
    $GLOBALS['xoopsTpl']->assign('lang_deleteaccount', _US_DELACCOUNT);
    $GLOBALS['xoopsTpl']->assign('userlevel', $thisUser->isActive());
} 

$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname'));


// Dynamic User Profiles
$thisUsergroups = $thisUser->getGroups();
$visibility_handler = xoops_getmodulehandler('visibility');
//search for visible Fields or null for none
$field_ids_visible = $visibility_handler->getVisibleFields($thisUsergroups,$groups);

$profile_handler =& xoops_getmodulehandler('profile');
$fields = $profile_handler->loadFields();
$cat_handler =& xoops_getmodulehandler('category');
$cat_crit = new CriteriaCompo();
$cat_crit->setSort("cat_weight");
$cats = $cat_handler->getObjects($cat_crit, true, false);
unset($cat_crit);

$avatar = "";
if ($thisUser->getVar('user_avatar') && "blank.gif" != $thisUser->getVar('user_avatar')) {
   $avatar = XOOPS_UPLOAD_URL . "/" . $thisUser->getVar('user_avatar');
}

$email = "";
if ($thisUser->getVar('user_viewemail') == 1) {
    $email = $thisUser->getVar('email', 'E');
} else if (is_object($GLOBALS['xoopsUser'])) {
    // Module admins will be allowed to see emails
    if ($GLOBALS['xoopsUser']->isAdmin() || ($GLOBALS['xoopsUser']->getVar("uid") == $thisUser->getVar("uid"))) {
        $email = $thisUser->getVar('email', 'E');
    }
}
foreach (array_keys($cats) as $i) {
    $categories[$i] = $cats[$i];
}

$profile_handler = xoops_getmodulehandler('profile');
$profile = $profile_handler->get($thisUser->getVar('uid'));
// Add dynamic fields
foreach (array_keys($fields) as $i) {
    //If field is not visible, skip
    //if ( $field_ids_visible && !in_array($fields[$i]->getVar('field_id'), $field_ids_visible) ) continue;
    if (!in_array($fields[$i]->getVar('field_id'), $field_ids_visible)) {
        continue;
    }
    $cat_id = $fields[$i]->getVar('cat_id');
    $value = $fields[$i]->getOutputValue($thisUser, $profile);
    if (is_array($value)) {
        $value = implode('<br />', array_values($value) );
    }
    if ($value) {
        $categories[$cat_id]['fields'][$fields[$i]->getVar('field_weight') . "_" . $i] = array('title' => $fields[$i]->getVar('field_title'), 'value' => $value);
       	ksort($categories[$cat_id]['fields']);
        $weights[$cat_id][] = $fields[$i]->getVar('cat_id');
    }
}

//sort fields order in categories
foreach (array_keys($categories) as $i) {
    if (isset($categories[$i]['fields'])) {
        array_multisort($weights[$i], SORT_ASC, array_keys($categories[$i]['fields']), SORT_ASC, $categories[$i]['fields']);
    } else {
        unset($categories[$i]);
    }
}

//ksort($categories);
if ($allow_profile) {
    $GLOBALS['xoopsTpl']->assign('categories', $categories);
}
// Dynamic user profiles end

if ($GLOBALS['xoopsModuleConfig']['profile_search'] && $allow_modules) {
    $module_handler =& xoops_gethandler('module');
    $criteria = new CriteriaCompo(new Criteria('hassearch', 1));
    $criteria->add(new Criteria('isactive', 1));
    $modules = $module_handler->getObjects($criteria, true);
    $mids = array_keys($modules);

    $allowed_mids = $gperm_handler->getItemIds('module_read', $groups);
    if (count($mids) > 0 && count($allowed_mids) > 0) {
        foreach ($mids as $mid ) {
            if (  in_array($mid, $allowed_mids)  ) {
                $results = $modules[$mid]->search('', '', 5, 0, $thisUser->getVar('uid') );
                $count = count($results);
                if (is_array($results) && $count > 0) {
                    for ($i = 0; $i < $count; $i++ ) {
                        if (isset($results[$i]['image']) && $results[$i]['image'] != '') {
                            $results[$i]['image'] = XOOPS_URL . '/modules/' . $modules[$mid]->getVar('dirname', 'n') . '/' . $results[$i]['image'];
                        } else {
                            $results[$i]['image'] = XOOPS_URL . '/images/icons/posticon2.gif';
                        }
                        if (!preg_match("/^http[s]*:\/\//i", $results[$i]['link'])) {
                            $results[$i]['link'] = XOOPS_URL . "/modules/" . $modules[$mid]->getVar('dirname', 'n') . "/" . $results[$i]['link'];
                        }
                        $results[$i]['title'] = $myts->htmlspecialchars($results[$i]['title']);
                        $results[$i]['time'] = $results[$i]['time'] ? formatTimestamp($results[$i]['time']) : '';
                    }
                    if ($count == 5) {
                        $showall_link = '<a href="' . XOOPS_URL . '/search.php?action=showallbyuser&amp;mid=' . $mid . '&amp;uid=' . $thisUser->getVar('uid') . '">' . _US_SHOWALL . '</a>';
                    } else {
                        $showall_link = '';
                    }
                    $GLOBALS['xoopsTpl']->append('modules', array('name' => $modules[$mid]->getVar('name'), 'results' => $results, 'showall_link' => $showall_link));
                }
                unset($modules[$mid]);
            }
        }
    }
}

$allow_more = false;
if ($allow_scraps) 
{ 
    $allow_more = true;
    $xoopsTpl->assign('myscraps_text',_PROFILE_MA_PREVIEWSCRAPS);
  	$limit_scraps = (isset($xoopsModuleConfig['profile_scraps_preview'])) ? intval($xoopsModuleConfig['profile_scraps_preview']) : 0;
	$array_scraps = array("scrap_to"=>$uid,"private"=>0);
  	$list_scraps  = $profileconfigs_handler->readPreList( "scraps" , $limit_scraps , $array_scraps );
  	$lscraps = array();
  	foreach ($list_scraps as $v) {
	    $text = str_replace("<br />"," ",$myts->nl2Br($v->getVar('scrap_text')));
     	$text = strip_tags($text);
	 	$text = $myts->truncate($text);
		$gbuser = $member_handler->getUser($v->getVar('scrap_from'));
		$gbuname = (is_object($gbuser)) ? '<a href='.XOOPS_URL.'/userinfo.php?uid='.$gbuser->uid().'>'.$gbuser->uname().'</a>' : _GUEST;
     	$lscraps[] = array(
	           'name' => $gbuname,
			   'text' => $text,
			   );
  	}
  	if (intval(count($lscraps)) > 0) 
	{
		$xoopsTpl->assign('myscraps',$lscraps);
	}
}

if ($allow_friends) 
{
    $allow_more = true;
    $xoopsTpl->assign('myfriends_text',_PROFILE_MA_PREVIEWFRIENDS);
  	$limit_friends = (isset($xoopsModuleConfig['profile_friends_preview'])) ? intval($xoopsModuleConfig['profile_friends_preview']) : 0;
  	$list_friends = $profileconfigs_handler->getLatestFriends($uid,$limit_friends);
  	$lfriends = array();
  	foreach ($list_friends as $f) {
     	$friend = ($f->getVar('friend_uid') == $uid) ? $f->getVar('self_uid') : $f->getVar('friend_uid');
     	$nUser =& $member_handler->getUser($friend);
	 	if (is_object($nUser) && $nUser->isactive()) {
	    	$navatar = ($nUser->getVar('user_avatar') !='blank.gif' && $nUser->getVar('user_avatar') !='') ? XOOPS_UPLOAD_URL."/".$nUser->getVar('user_avatar') : XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/noavatar.gif";
			$navatar = '<img src="'.$navatar.'" alt="'.$nUser->uname().'" />';
	    	$lfriends[] = array('uid'=>$nUser->uid(),'name'=>$nUser->uname(),'avatar'=>$navatar);
	 	}
  	}  
  	$myfriendscount = intval(count($lfriends));
  	if ($myfriendscount>0) 
  	{
  		$xoopsTpl->assign('myfriends',$lfriends);
  	}
}

if ($allow_pictures) 
{
    $allow_more = true;
    $xoopsTpl->assign('mypictures_text',_PROFILE_MA_PREVIEWPICTURES);
  	$limit_pictures = (isset($xoopsModuleConfig['profile_pictures_preview'])) ? intval($xoopsModuleConfig['profile_pictures_preview']) : 0;
	$array_pictures = array("pic_uid"=>$uid,"private"=>0);
  	$list_pictures  = $profileconfigs_handler->readPreList( "pictures" , $limit_pictures , $array_pictures );
  	$lpictures = array();
  	foreach ($list_pictures as $f) 
	{
     	$purl = '<a href="'.XOOPS_UPLOAD_URL."/".$xoopsModule->dirname()."/".$f->getvar('pic_url').'" title="" rel="lightbox[pic_group_'.$uid.']"><img src="'.XOOPS_UPLOAD_URL."/".$xoopsModule->dirname()."/thumbs/".$f->getVar('pic_url').'" alt="'.$f->getVar('pic_title').'"/></a>';
     	$lpictures[] = array('title'=>$f->getVar('pic_title'),'pic'=>$purl);
  	}
  	if (intval(count($lpictures)) > 0) 
	{
	  	$xoopsTpl->assign('mypictures',$lpictures);
	}
}

if ($allow_videos) 
{
    $allow_more = true;
    $xoopsTpl->assign('myvideos_text',_PROFILE_MA_PREVIEWVIDEOS);
  	$limit_videos = (isset($xoopsModuleConfig['profile_videos_preview'])) ? intval($xoopsModuleConfig['profile_videos_preview']) : 0;
	$array_videos = array("uid_owner"=>$uid);
  	$list_videos  = $profileconfigs_handler->readPreList( "video" , $limit_videos , $array_videos, 'video_id' );
  	$lvideos = array();
  	foreach ($list_videos as $f) 
	{
     	$purl = str_replace("http://www.youtube.com/watch?v=","",$f->getVar("youtube_code","s"));
     	$lvideos[] = array('title'=>$f->getVar('video_desc'),'pic'=>$purl);
  	}
  	if (intval(count($lvideos)) > 0) 
	{
	  	$xoopsTpl->assign('myvideos',$lvideos);
	}
}

if (is_object($xoopsUser) && $xoopsUser->uid() == $uid) {
  $visit_user = $visit_handler->getvisit();
  $allow_more = true;
  $xoopsTpl->assign('myvisit_text',_PROFILE_MA_PREVIEWVISTORS);
  if (count($visit_user)>0) {
    $xoopsTpl->assign('visit_user',$visit_user);
  } else {
    $xoopsTpl->assign('visit_user','No user found');
  }  
}


if ($allow_more == true) 
{
    $xoopsTpl->assign('social_isactivte',true);
} 
else 
{
    $xoopsTpl->assign('social_isactivte',false);
}


//User info
$GLOBALS['xoopsTpl']->assign('uname', $thisUser->getVar('uname'));
$GLOBALS['xoopsTpl']->assign('email', $email);
$GLOBALS['xoopsTpl']->assign('avatar', $avatar);
$GLOBALS['xoopsTpl']->assign('recent_activity', _PROFILE_MA_RECENTACTIVITY);
$xoBreadcrumbs[] = array('title' => _PROFILE_MA_USERINFO);
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>