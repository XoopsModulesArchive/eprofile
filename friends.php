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
 * @author          Dirk herrmann <dhcst@users.sourceforge.net>
 * @version         $Id$
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';


$friends_handler = xoops_getmodulehandler('friends');
$member_handler = xoops_gethandler('member');
$xoopsOption['template_main'] = 'profile_friends.html';
include $GLOBALS['xoops']->path('header.php'); 
$xoopsTpl->assign('section_name', _PROFILE_MA_FRIENDS);
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'social.php';
$xoBreadcrumbs[] = array('title' => _PROFILE_MA_FRIENDS);

$valid_op_requests = array('add','waiting','addfriend','delfriend');
$op = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : '' ;


if (empty($allow_friends)|| empty($uid))
{
  if ($op=='add' && $isOwner==0 && (is_object($xoopsUser) && $xoopsUser->isactive())) 
  {
    $isfiend_enabled = ($xoopsModuleConfig['profile_friends']==1) ? $profileconfigs_handler->getstat('friends',$uid):0;
  	if($isfiend_enabled < 1) 
	{
      redirect_header('userinfo.php?uid='.$uid,2,_NOPERM);
      exit();
    }
  } 
  else 
  {
    redirect_header('userinfo.php?uid='.$uid,2,_NOPERM);
    exit();
  } 
}

if ($op=='add' && (is_object($xoopsUser) && $xoopsUser->uid()>0)) {
  if(empty($_POST['confirm'])) {
        $toUser = $member_handler->getUser($uid);
        $frage = sprintf(_PROFILE_MA_CONFIRMADDTOUSER,$toUser->uname());
	    xoops_confirm(array('confirm'=>1,'op'=>'add'), 'friends.php?uid='.$uid, $frage,_PROFILE_MA_ADDTHISFRIENDS);
	    include XOOPS_ROOT_PATH.'/footer.php';
	    exit();
  } 
  else 
  {
	if (!$GLOBALS['xoopsSecurity']->check()) {
    	redirect_header('userinfo.php?uid='.$uid, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    	exit();
  	}
    $newfriend = $friends_handler->create();
    $newfriend->setVar('friend_uid',$uid);
    $newfriend->setVar('self_uid',$xoopsUser->uid());
	$newfriend->setVar('level',1);
	if ($friends_handler->insert($newfriend,false))
	{
	    if (!empty($profile_permission['friends_notify']))
	   	{
	      	$member_handler =& xoops_gethandler('member');
            $toUser =& $member_handler->getUser($uid);
	      	$xoopsMailer = xoops_getMailer();
			$xoopsMailer->useMail();
			if (file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/language/".$xoopsConfig['language']."/mail_template/friend_notify.tpl"))
			  	$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/language/".$xoopsConfig['language']."/mail_template");
			else
			    $xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/language/english/mail_template");
			$xoopsMailer->setTemplate('friend_notify.tpl');
    		$xoopsMailer->assign('X_UNAME', $toUser->uname());
    		$xoopsMailer->assign('X_FRIENDNAME', $xoopsUser->uname());
			$xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
            $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
            $xoopsMailer->assign('SITEURL', XOOPS_URL."/");                    
    		$xulink = XOOPS_URL."/modules/".$xoopsModule->dirname()."/friends.php?uid=".$toUser->uid()."&amp;op=waiting";
			$xoopsMailer->assign('X_ULINK',$xulink);
    		$xoopsMailer->setToUsers($toUser);
			$xoopsMailer->setSubject(_PROFILE_MA_MAILSUBJECTNEWFRIEND);
			if ( !$xoopsMailer->send(true) ) {
        		//xoops_result($xoopsMailer->getErrors());    
    		} 
	   }
	   redirect_header("userinfo.php?uid=".$uid,3,_PROFILE_MA_MAILSUBJECTNEWFRIEND);
	   exit();
	}
	redirect_header("userinfo.php?uid=".$uid,3,_PROFILE_MA_ERRORDURINGSAVE);
	exit();
  }
} elseif ($op=='waiting' && (is_object($xoopsUser) && $xoopsUser->isActive() && $xoopsUser->uid() == $uid)) {
    $criteria = new CriteriaCompo();
    $criteria->add(new Criteria('friend_uid',$xoopsUser->uid()));
	$criteria->add(new Criteria('level',1));
    $nbnew_friends = $friends_handler->getObjects($criteria,false,false);
	if (count($nbnew_friends)>0) {
	  echo '<table style="width:90%; text-align:center">';
	  echo ' <tr><th></th><th>'._USERNAME.'</th><th>'._PROFILE_MA_USERSINCE.'</th><th>'._PROFILE_MA_FRIENDWILLSINCE.'</th><th></th></tr>';
	  $class ='odd';
	  foreach ($nbnew_friends as $friends) {
	     $nuser = $member_handler->getUser($friends['self_uid']);
		 if (is_object($nuser) && $nuser->isactive()) {
	       $ok_img = '<a href="friends.php?op=addfriend&amp;uid='.$uid.'&amp;friend='.$friends["friend_id"].'"><img src="images/green.gif" title="'._ADD.'" alt="'._GO.'"/></a>';
	       $del_img = '<a href="friends.php?op=delfriend&amp;uid='.$uid.'&amp;friend='.$friends["friend_id"].'"><img src="images/dele.gif" title="'._DELETE.'" alt="'._DELETE.'"/></a>';  
               $rem_img = '<a href="friends.php?op=removefriend&amp;uid='.$uid.'&amp;friend='.$friends["friend_id"].'"><img src="images/dele.gif" title="'._REMOVE.'" alt="'._REMOVE.'"/></a>';
	       $class = ($class=='odd') ? 'even':'odd';
		   echo '<tr><td class="'.$class.'"></td>';
		   echo '<td class="'.$class.'"><a href="'.XOOPS_URL.'/userinfo.php?uid='.$nuser->uid().'">'.$nuser->uname().'</a></td>';
		   echo '<td class="'.$class.'">'.formatTimestamp($nuser->getVar("user_regdate"),'m').'</td>';
		   echo '<td class="'.$class.'">'.formatTimestamp(strtotime($friends['date']),'m').'</td>';
		   echo '<td class="'.$class.'" nowrap="nowrap">'.$ok_img.'&nbsp;&nbsp;'.$del_img.'</td></tr>';
		 } else {
		   $delcon = $friends_handler->get($friends['friend_id']);
		   if (is_object($delcon)) $friends_handler->delete($delcon,true);
		 } 
		 unset($nuser);
	  }
	  echo '</table>';
	  include 'footer.php';
	  exit();
	} 
} 
elseif (($op=='removefriend' || $op=='delfriend' || $op=='addfriend') && (is_object($xoopsUser) && $xoopsUser->uid() == $uid)) 
{
  	$friend_id = (!empty($_GET['friend'])) ? intval($_GET['friend']) : 0;
  	if(empty($_POST['confirm'])) {
    	if ($friend_id>0) {
      		$newfriend = $friends_handler->get($friend_id);
	  		if (is_object($newfriend)) {
			    $friend = ($newfriend->getVar('friend_uid') == $xoopsUser->uid()) ? $newfriend->getVar('self_uid') : $newfriend->getVar('friend_uid');
        		$toUser = $member_handler->getUser($friend);
        		if ( ($toUser && $toUser->isactive()) ) {
		  			if ($op=="delfriend")
						xoops_confirm(array('confirm'=>1,'op'=>$op), 'friends.php?uid='.$uid."&amp;friend=".$friend_id, sprintf(_PROFILE_MA_CONFIRMDELNEWUSER,$toUser->uname()),_PROFILE_MA_DELETEUSER);
	      			elseif ($op=="removefriend")
					    xoops_confirm(array('confirm'=>1,'op'=>$op), 'friends.php?uid='.$uid."&amp;friend=".$friend_id, sprintf(_PROFILE_MA_CONFIRMREMOVEUSER,$toUser->uname()),_PROFILE_MA_REMOVEUSER);
            		else
						xoops_confirm(array('confirm'=>1,'op'=>$op), 'friends.php?uid='.$uid."&amp;friend=".$friend_id, sprintf(_PROFILE_MA_CONFIRMADDNEWUSER,$toUser->uname()),_PROFILE_MA_ADDUSER);
		    		include XOOPS_ROOT_PATH.'/footer.php';
				} else {
		  			redirect_header("friends.php?uid=".$uid,3,_PROFILE_MA_NOUSERSFOUND);
				} 
	    		exit();
	  		} 
		}
  	} else {
	    if (!$GLOBALS['xoopsSecurity']->check()) {
     		redirect_header('friends.php?uid='.$uid, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
     		exit();
  	    }
    	if ($friend_id>0) {
        	$newfriend = $friends_handler->get($friend_id);
        	if (is_object($newfriend)) {
            	if ($op=='delfriend') {
                	$newfriend->setVar('level',3);
                	$friends_handler->insert($newfriend,true);
            	} elseif ($op=='removefriend') {
               	 	$friends_handler->delete($newfriend,true);
            	} elseif ($op=='addfriend') {
                	$newfriend->setVar('level',2);
                	$friends_handler->insert($newfriend,true);
                }
            }
        }
        redirect_header("userinfo.php?uid=".$uid,3,_PROFILE_MA_DATASENDET);
	    exit();
	}
}

$friends = $friends_handler->getFriends($uid);
if(count($friends) == 0) {
  $xoopsTpl->assign('lang_nofriendsyet',_PROFILE_MA_NOFRIENDS);
} else {
  $xoopsTpl->assign('friends',$friends);
}
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>