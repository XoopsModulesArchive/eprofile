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
 * @version         $Id: userinfo.php 2 2012-08-16 08:20:47Z alfred $
 */

include 'header.php';
include_once $GLOBALS['xoops']->path('modules/system/constants.php');

$xoopsOption['template_main'] = 'profile_userinfo.html';
include $GLOBALS['xoops']->path('header.php');  

$xoopsTpl->assign('section_name',   _EPROFILE_MA_USERINFO);
include_once "include/themeheader.php";
$xoTheme->addStylesheet(XOOPS_URL . "/modules/eprofile/js/lightbox.css");
$xoTheme->addScript(XOOPS_URL . '/modules/eprofile/js/jquery-1.7.2.min.js');
$xoTheme->addScript(XOOPS_URL . '/modules/eprofile/js/lightbox.js');

if ( $isOwner ) {    
    $GLOBALS['xoopsTpl']->assign('isOwner', true);
    $GLOBALS['xoopsTpl']->assign('lang_editprofile', _US_EDITPROFILE);
    $GLOBALS['xoopsTpl']->assign('lang_changepassword', _EPROFILE_MA_CHANGEPASSWORD);
    $GLOBALS['xoopsTpl']->assign('lang_avatar', _US_AVATAR);
    $GLOBALS['xoopsTpl']->assign('lang_inbox', _US_INBOX);
    $GLOBALS['xoopsTpl']->assign('lang_logout', _US_LOGOUT);
    if ( $GLOBALS['xoopsConfigUser']['self_delete'] == 1  && !$isAdmin ) {
        $GLOBALS['xoopsTpl']->assign('user_candelete', true);
        $GLOBALS['xoopsTpl']->assign('lang_deleteaccount', _US_DELACCOUNT);
    } else {
        $GLOBALS['xoopsTpl']->assign('user_candelete', false);
    }
    $GLOBALS['xoopsTpl']->assign('user_changeemail', $GLOBALS['xoopsConfigUser']['allow_chgmail']);
    
} else {
        
    $GLOBALS['xoopsTpl']->assign('isOwner', false);

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
    
 
    if (!empty($_SESSION['xoopsUserId'])) {
      $akt_User =& $member_handler->getUser($_SESSION['xoopsUserId']);
    } else {
      $akt_User =& $member_handler->createUser();
    }
 
    $groups_thisUser = $thisUser->getGroups();
    $groups_accessible = $gperm_handler->getItemIds('profile_access', $groups, $GLOBALS['xoopsModule']->getVar('mid'));
    
   
    $rejected = true;
    if ($akt_User->isAdmin()) {
        //$rejected = !in_array(XOOPS_GROUP_ADMIN, $groups_accessible);
        $rejected = false;
    } else {   
        //$rejected = (count(array_diff($groups_thisUser,$groups_accessible)) != 0) ? false : true;
        foreach ($groups_thisUser as $gt) {          
          if (in_array($gt,$groups_accessible)) $rejected = false;         
        }
    }
    

/*    
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

*/

 
    if ($rejected == true) {
        redirect_header(XOOPS_URL, 3, _NOPERM);
        exit();
    }
    $GLOBALS['xoopsTpl']->assign('user_ownpage', false);
}

$GLOBALS['xoopsTpl']->assign('sendpm_touser', _EPROFILE_MA_KONTAKTTOPM);
$GLOBALS['xoopsTpl']->assign('sendmail_touser', _EPROFILE_MA_KONTAKTTOEMAIL);
$userrank = $thisUser->rank();
if (isset($userrank['image']) && $userrank['image'] !='') {
    $GLOBALS['xoopsTpl']->assign('user_rankimage', '<img src="' . XOOPS_UPLOAD_URL . '/' . $userrank['image'] . '" alt="'.$userrank['title'].'" />');
}
$GLOBALS['xoopsTpl']->assign('user_ranktitle', $userrank['title']);

if ($isAdmin) {	
    $GLOBALS['xoopsTpl']->assign('lang_editprofile', _US_EDITPROFILE);
    $GLOBALS['xoopsTpl']->assign('lang_deleteaccount', _US_DELACCOUNT);
    $GLOBALS['xoopsTpl']->assign('userlevel', $thisUser->isActive());
} 

$avatar = "";
if ($thisUser->getVar('user_avatar') && "blank.gif" != $thisUser->getVar('user_avatar')) {
	$avatar = XOOPS_UPLOAD_URL . "/" . $thisUser->getVar('user_avatar');
}

$email = "";
if ($thisUser->getVar('user_viewemail') == 1) {
	$email = $thisUser->getVar('email', 'E');
} elseif ( $isAdmin || $isOwner ) {
	$email = $thisUser->getVar('email', 'E');
}

$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname'));

if ($profile_permission['profile_general']) {
	$xoopsTpl->assign('allow_profile',1);
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

	foreach (array_keys($cats) as $i) {
		$categories[$i] = $cats[$i];
	}

	$profile_handler = xoops_getmodulehandler('profile');
	$profile = $profile_handler->get($uid);
	// Add dynamic fields
	foreach (array_keys($fields) as $i) {
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
    $GLOBALS['xoopsTpl']->assign('categories', $categories);
}
// Dynamic user profiles end

if ($profile_permission['user_module'] > 0) {
    $module_handler =& xoops_gethandler('module');
    $criteria = new CriteriaCompo(new Criteria('hassearch', 1));
    $criteria->add(new Criteria('isactive', 1));
    $modules = $module_handler->getObjects($criteria, true);
    $mids = array_keys($modules);
    $allowed_mids = $gperm_handler->getItemIds('module_read', $groups);
	  $xoopsTpl->assign('allow_profile',1);
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
if ($profile_permission['scraps'] && $scraps_count > 0) { 
  $allow_more = true;
  $xoopsTpl->assign('myscraps_text',_EPROFILE_MA_PREVIEWSCRAPS);
  $limit_scraps = (isset($xoopsModuleConfig['profile_scraps_preview'])) ? intval($xoopsModuleConfig['profile_scraps_preview']) : 0;
  $array_scraps = array("scrap_to" => $uid,"private" => 0);
  $list_scraps  = $profileconfigs_handler->readPreList( "scraps" , $limit_scraps , $array_scraps );
  $lscraps = array();
	
  foreach ($list_scraps as $v) {
    $text = str_replace("<br />"," ",$myts->nl2Br($v->getVar('scrap_text','s')));
    $text = $myts->displayTarea($text, 0, 0, 1, 0, 1);
	    
   	//$text = strip_tags($text);
    $text = $myts->truncate($text);
    $gbuser = $member_handler->getUser($v->getVar('scrap_from'));
    $gbuname = (is_object($gbuser)) ? '<a href='.XOOPS_URL.'/userinfo.php?uid='.$gbuser->uid().'>'.$gbuser->uname().'</a>' : _GUEST;
   	$lscraps[] = array(
              'name' => $gbuname,
              'text' => $text,
              );
  }
  if (intval(count($lscraps)) > 0) {
    $xoopsTpl->assign('myscraps',$lscraps);
  }
}

if ($profile_permission['friends']) {
  if (is_object($xoopsUser) && $xoopsUser->uid() != $uid) {
    $myfriend = $profileconfigs_handler->selectFriendLevel($uid);
    if ($myfriend == -1) {
      $xoopsTpl->assign('lang_addfriends',_EPROFILE_MA_WAITTHISFRIENDS);
    } elseif ($myfriend == -2) {
      $xoopsTpl->assign('lang_addfriends',_EPROFILE_MA_ISFRIENDS);
    } elseif ($myfriend == -3) {
      $xoopsTpl->assign('lang_addfriends',_EPROFILE_MA_ISNOASFRIENDS);
    } elseif ($myfriend == 0) {
      $xoopsTpl->assign('lang_addfriends','<a href="' . XOOPS_URL . '/modules/eprofile/friends.php?op=add&uid=' . $uid . '">' . _EPROFILE_MA_ADDTHISFRIENDS . '</a>');      
    } elseif ($myfriend == 1) {
      $xoopsTpl->assign('lang_addfriends',_EPROFILE_MA_FRIENDSMUSTCHECK);       
    } elseif ($myfriend == 2) {
      $xoopsTpl->assign('lang_addfriends',_EPROFILE_MA_ISFRIENDS);
    } elseif ($myfriend == 3) {
      $xoopsTpl->assign('lang_addfriends',sprintf(_EPROFILE_MA_ISNOASFRIENDSFORME,$uid));
    } else {
      $xoopsTpl->assign('lang_addfriends','Code:' . $myfriend);
    }
  }
}

if ($profile_permission['friends'] && $friends_count > 0) {
  $allow_more = true;
  $xoopsTpl->assign('myfriends_text',_EPROFILE_MA_PREVIEWFRIENDS);
  $limit_friends = (isset($xoopsModuleConfig['profile_friends_preview'])) ? intval($xoopsModuleConfig['profile_friends_preview']) : 0;
  $list_friends = $profileconfigs_handler->getLatestFriends($uid,$limit_friends);
  $lfriends = array();
  foreach ($list_friends as $f) {
   	$friend = ($f->getVar('friend_uid') == $uid) ? $f->getVar('self_uid') : $f->getVar('friend_uid');
   	$nUser =& $member_handler->getUser($friend);
    if (is_object($nUser) && $nUser->isactive()) {
	    $navatar = ($nUser->getVar('user_avatar') !='blank.gif' && $nUser->getVar('user_avatar') !='') ? XOOPS_UPLOAD_URL."/".$nUser->getVar('user_avatar') : XOOPS_URL."/modules/".$xoopsModule->dirname()."/images/noavatar.gif";
			$navatar = '<img src="'.$navatar.'" alt="'.$nUser->name().'" />';
	    $lfriends[] = array('uid'=>$nUser->uid(),'name'=>$nUser->uname(),'avatar'=>$navatar);
	 	}
  }  
  $myfriendscount = intval(count($lfriends));
  if ($myfriendscount>0) {
  	$xoopsTpl->assign('myfriends',$lfriends);
  }
}

if ($profile_permission['pictures'] && $pictures_count > 0) {
  $allow_more = true;
  $xoopsTpl->assign('mypictures_text',_EPROFILE_MA_PREVIEWPICTURES);
  $limit_pictures = (isset($xoopsModuleConfig['profile_pictures_preview'])) ? intval($xoopsModuleConfig['profile_pictures_preview']) : 0;
  $array_pictures = array("pic_uid"=>$uid,"private"=>0);
  $list_pictures  = $profileconfigs_handler->readPreList( "pictures" , $limit_pictures , $array_pictures );
  $lpictures = array();
  foreach ($list_pictures as $f) {
    $title = $myts->displayTarea($f->getVar("pic_title",'n'),0,0,0,0,1);
    $desc =  $myts->displayTarea($f->getVar("pic_desc",'n'),0,0,0,0,1);
   	$purl =	'<a rel="lightbox['.$uid.']" href="' . XOOPS_UPLOAD_URL . '/' . $xoopsModule->dirname() . '/' . $f->getvar("pic_url") . '">
              <img src="' . XOOPS_UPLOAD_URL . '/' . $xoopsModule->dirname() . '/thumbs/' . $f->getVar("pic_url") . '" alt="' . $desc . '" title="' . $desc . '" />
            </a>';
   	$lpictures[] = array('title'=>$title,'pic'=>$purl);
  }
  if (intval(count($lpictures)) > 0) {
	 	$xoopsTpl->assign('mypictures',$lpictures);
	}
}

if ($profile_permission['videos'] && $videos_count > 0) {
  $allow_more = true;
  $xoopsTpl->assign('myvideos_text',_EPROFILE_MA_PREVIEWVIDEOS);
  $limit_videos = (isset($xoopsModuleConfig['profile_videos_preview'])) ? intval($xoopsModuleConfig['profile_videos_preview']) : 0;
  $array_videos = array("uid_owner"=>$uid);
  $list_videos  = $profileconfigs_handler->readPreList( "video" , $limit_videos , $array_videos, 'video_id' );
  $lvideos = array();
  foreach ($list_videos as $f) {
    $purl = str_replace("http://www.youtube.com/watch?v=","",$f->getVar("youtube_code","n"));
    $purl = str_replace("http://www.youtube.com/embed/","",$purl);
    $lvideos[] = array('title'=>$f->getVar('video_desc','n'),'pic'=>$purl);
  }
  if (intval(count($lvideos)) > 0) {
	  $xoopsTpl->assign('myvideos',$lvideos);
	}
}


if ( $profile_permission['profile_stats'] ) {
  $allow_more = true;
  $xoopsTpl->assign('myvisit_text',_EPROFILE_MA_PREVIEWVISTORS); 
  if (is_object($xoopsUser) && $xoopsUser->uid() == $uid) {
    $visit_user = $visit_handler->getvisit();	
    if (count($visit_user) > 0) {
      $xoopsTpl->assign('visit_user',$visit_user);
    }
	}
}  

$xoopsTpl->assign('social_isactivte', $allow_more);

//User info
$GLOBALS['xoopsTpl']->assign('uname', $thisUser->getVar('uname'));
$GLOBALS['xoopsTpl']->assign('email', $email);
$GLOBALS['xoopsTpl']->assign('avatar', $avatar);
$GLOBALS['xoopsTpl']->assign('recent_activity', _EPROFILE_MA_RECENTACTIVITY);
$GLOBALS['xoopsTpl']->assign('eprofile_version','eProfile '.XoopsLocal::number_format(($xoopsModule->getVar('version') / 100)));

include 'footer.php';
?>