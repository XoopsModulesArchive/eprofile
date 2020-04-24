<?php
/**
 * Private message module
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
 * @package         pm
 * @since           2.3.0
 * @author          Jan Pedersen
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: readpmsg.php 28 2013-09-06 21:58:22Z alfred $
 */

include 'header.php';

if ( !$isOwner ) { 
    redirect_header(XOOPS_URL, 3, _NOPERM);
}

xoops_loadLanguage('pmsg');
$xoopsOption['template_main'] = 'profile_pmread.html';

$valid_op_requests = array('out', 'save', 'in');
$_REQUEST['op'] = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : 'in' ;
$msg_id = empty($_REQUEST['msg_id']) ? 0 : intval($_REQUEST['msg_id']);

if ( @$_POST['reply'] == 1 ) {
  header( "location: " . XOOPS_URL . "/modules/eprofile/pmlite.php?msg_id=" . $msg_id . "&reply=1");
  exit();
}

if ($msg_id > 0) {
	$pm =& $pm_factory->get($msg_id);
} else {
	$pm = null;
}

if (is_object($pm) && !$GLOBALS['xoopsUser']->isAdmin() && ($pm->getVar('from_userid') != $GLOBALS['xoopsUser']->getVar('uid'))
	&& ($pm->getVar('to_userid') != $GLOBALS['xoopsUser']->getVar('uid'))
){
    redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar("dirname", "n") . '/index.php', 2, _NOPERM);
}

if (is_object($pm) && !empty($_POST['action']) ) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        echo implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
        exit();
    }
    $res = false;
    if (!empty($_REQUEST['email_message'])) {
   		$res = $pm_factory->sendEmail($pm, $GLOBALS['xoopsUser']);
  	} elseif (
  		!empty($_REQUEST['move_message']) 
  		&& $_REQUEST['op']!='save' 
  		&& !$GLOBALS['xoopsUser']->isAdmin()
  		&& $pm_factory->getSavecount() >= $GLOBALS['xoopsModuleConfig']['max_save']
  		) {
      $res_message = sprintf(EPROFILE_MA_PMSAVED_PART, $GLOBALS['xoopsModuleConfig']['max_save'], 0);
  	} else {
        switch ($_REQUEST['op']) {
			case 'out':
		         if ($pm->getVar('from_userid') != $GLOBALS['xoopsUser']->getVar('uid')) break;
		         if (!empty($_REQUEST['delete_message'])) {
                    xoops_confirm($hiddens, $action, _EPROFILE_MA__PMRUSUREDELETE, $submit = '', true);
		         	//$res = $pm_factory->setFromdelete($pm);
		         } elseif (!empty($_REQUEST['move_message'])) {
		         	$res = $pm_factory->setFromsave($pm); 
		         }
		         break;
			case 'save':
		         if ($pm->getVar('to_userid') == $GLOBALS['xoopsUser']->getVar('uid')) {
			         if (!empty($_REQUEST['delete_message'])) {
			         	$res1 = $pm_factory->setTodelete($pm); 
			         	$res1 = ($res1) ? $pm_factory->setTosave($pm, 0) : false; 
			         } elseif (!empty($_REQUEST['move_message'])) {
			         	$res1 = $pm_factory->setTosave($pm, 0); 
			         }
		         }
		         if ($pm->getVar('from_userid') == $GLOBALS['xoopsUser']->getVar('uid')) {
			         if (!empty($_REQUEST['delete_message'])) {
			         	$res2 = $pm_factory->setFromDelete($pm); 
			         	$res2 = ($res2) ? $pm_factory->setFromsave($pm, 0) : false; 
			         } elseif (!empty($_REQUEST['move_message'])) {
			         	$res2 = $pm_factory->setFromsave($pm, 0); 
			         }
		         }
		         $res = $res1 && $res2; 
		         break;
		         
			case 'in':
			default:
		         if ($pm->getVar('to_userid') != $GLOBALS['xoopsUser']->getVar('uid')) break;
		         if (!empty($_REQUEST['delete_message'])) {
		         	$res = $pm_factory->setTodelete($pm);
		         } elseif (!empty($_REQUEST['move_message'])) {
		         	$res = $pm_factory->setTosave($pm); 
		         }
		         break;
		}
	}
    $res_message = isset($res_message) ? $res_message : ( ($res) ? _EPROFILE_MA_PMACTION_DONE : _EPROFILE_MA_PMACTION_ERROR );
    redirect_header('viewpmsg.php?op=' . htmlspecialchars( $_REQUEST['op'] ) , 2, $res_message);
}
$start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
$total_messages = !empty($_GET['total_messages']) ? intval($_GET['total_messages']) : 0;
include $GLOBALS['xoops']->path( '/header.php' ); 
$xoopsTpl->assign('section_name',   _US_INBOX);
include_once "include/themeheader.php";

$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $xoopsUser->getVar('name'));
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


if (!is_object($pm)) {
    if ($_REQUEST['op'] == "out") {
        $criteria = new CriteriaCompo(new Criteria('from_delete', 0));
        $criteria->add(new Criteria('from_userid', $GLOBALS['xoopsUser']->getVar('uid')));
        $criteria->add(new Criteria('from_save', 0));
    } elseif ($_REQUEST['op'] == "save") {
        $crit_to = new CriteriaCompo(new Criteria('to_delete', 0));
        $crit_to->add(new Criteria('to_save', 1));
        $crit_to->add(new Criteria('to_userid',$GLOBALS['xoopsUser']->getVar('uid')));
        $crit_from = new CriteriaCompo(new Criteria('from_delete', 0));
        $crit_from->add(new Criteria('from_save', 1));
        $crit_from->add(new Criteria('from_userid', $GLOBALS['xoopsUser']->getVar('uid')));
        $criteria = new CriteriaCompo($crit_to);
        $criteria->add($crit_from, "OR");
    } else {
        $criteria = new CriteriaCompo(new Criteria('to_delete', 0));
        $criteria->add(new Criteria('to_userid', $GLOBALS['xoopsUser']->getVar('uid')));
        $criteria->add(new Criteria('to_save', 0));
    }

    $criteria->setLimit(1);
    $criteria->setStart($start);
    $criteria->setSort('msg_time');
    $criteria->setOrder("DESC");
    list($pm) = $pm_factory->getObjects($criteria);
}

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$pmform = new XoopsForm('', 'pmform', 'readpmsg.php', 'post', true);
if (is_object($pm)&&!empty($pm)) {
	if ($pm->getVar('from_userid') != $GLOBALS['xoopsUser']->getVar('uid')) {
		$pmform->addElement(new XoopsFormHidden('reply', 1));
    $pmform->addElement(new XoopsFormHidden('msg_id', $pm->getVar("msg_id")));
    $pmform->addElement(new XoopsFormButton('', 'reply_message', _EPROFILE_MA_PMREPLY, 'submit'));
	}
	$pmform->addElement(new XoopsFormButton('', 'delete_message', _EPROFILE_MA_PMDELETE, 'submit'));
	$pmform->addElement(new XoopsFormButton('', 'move_message', ($_REQUEST['op'] == 'save') ? _EPROFILE_MA_PMUNSAVE : _EPROFILE_MA_PMTOSAVE, 'submit'));
	$pmform->addElement(new XoopsFormButton('', 'email_message', _EPROFILE_MA_PMEMAIL, 'submit'));
	$pmform->addElement(new XoopsFormHidden('msg_id', $pm->getVar("msg_id")));
	$pmform->addElement(new XoopsFormHidden('op', $_REQUEST['op']));
	$pmform->addElement(new XoopsFormHidden('action', 1));
	$pmform->assign($GLOBALS['xoopsTpl']);
	
	if ($pm->getVar("from_userid") == $GLOBALS['xoopsUser']->getVar("uid")) {
		$poster = new XoopsUser($pm->getVar("to_userid"));
	} else {
		$poster = new XoopsUser($pm->getVar("from_userid"));
	}
	if (!is_object($poster)) {
		$GLOBALS['xoopsTpl']->assign('poster', false);
		$GLOBALS['xoopsTpl']->assign('anonymous', $xoopsConfig['anonymous']);
	} else {
		$GLOBALS['xoopsTpl']->assign('poster', $poster);
	}
	
	if ($pm->getVar("to_userid") == $GLOBALS['xoopsUser']->getVar("uid") && $pm->getVar('read_msg') == 0) {
		$pm_factory->setRead($pm);
	}
	
	$message = $pm->getValues();
	$message['msg_time'] = formatTimestamp($pm->getVar("msg_time"));
}
$GLOBALS['xoopsTpl']->assign('message', $message);
$GLOBALS['xoopsTpl']->assign('op', $_REQUEST['op']);
$GLOBALS['xoopsTpl']->assign('previous', $start-1);
$GLOBALS['xoopsTpl']->assign('next', $start+1);
$GLOBALS['xoopsTpl']->assign('total_messages', $total_messages);

include 'footer.php';
?>