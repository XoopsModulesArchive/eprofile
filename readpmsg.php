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
 * @version         $Id: readpmsg.php 3701 2009-10-04 23:14:31Z wishcraft $
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

if ( !is_object($GLOBALS['xoopsUser']) ) {
    redirect_header(XOOPS_URL, 3, _NOPERM);
    exit();
}

xoops_loadLanguage('pmsg');
$xoopsOption['template_main'] = 'profile_pmread.html';

$valid_op_requests = array('out', 'save', 'in');
$_REQUEST['op'] = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : 'in' ;
$msg_id = empty($_REQUEST['msg_id']) ? 0 : intval($_REQUEST['msg_id']);
$pm_handler =& xoops_getModuleHandler('message');

if ($msg_id > 0) {
	$pm =& $pm_handler->get($msg_id);
} else {
	$pm = null;
}

if (is_object($pm) && !$GLOBALS['xoopsUser']->isAdmin() && ($pm->getVar('from_userid') != $GLOBALS['xoopsUser']->getVar('uid'))
	&& ($pm->getVar('to_userid') != $GLOBALS['xoopsUser']->getVar('uid'))
){
    redirect_header(XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar("dirname", "n") . '/index.php', 2, _NOPERM);
    exit();
}

if (is_object($pm) && !empty($_POST['action']) ) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        echo implode('<br />', $GLOBALS['xoopsSecurity']->getErrors());
        exit();
    }
    $res = false;
	if (!empty($_REQUEST['email_message'])) {
   		$res = $pm_handler->sendEmail($pm, $GLOBALS['xoopsUser']);
  	} elseif (
  		!empty($_REQUEST['move_message']) 
  		&& $_REQUEST['op']!='save' 
  		&& !$GLOBALS['xoopsUser']->isAdmin()
  		&& $pm_handler->getSavecount() >= $GLOBALS['xoopsModuleConfig']['max_save']
  		) {
		$res_message = sprintf(PROFILE_MA_PMSAVED_PART, $GLOBALS['xoopsModuleConfig']['max_save'], 0);
  	} else {
        switch ($_REQUEST['op']) {
			case 'out':
		         if ($pm->getVar('from_userid') != $GLOBALS['xoopsUser']->getVar('uid')) break;
		         if (!empty($_REQUEST['delete_message'])) {
                    xoops_confirm($hiddens, $action, _PROFILE_MA__PMRUSUREDELETE, $submit = '', true);
		         	//$res = $pm_handler->setFromdelete($pm);
		         } elseif (!empty($_REQUEST['move_message'])) {
		         	$res = $pm_handler->setFromsave($pm); 
		         }
		         break;
			case 'save':
		         if ($pm->getVar('to_userid') == $GLOBALS['xoopsUser']->getVar('uid')) {
			         if (!empty($_REQUEST['delete_message'])) {
			         	$res1 = $pm_handler->setTodelete($pm); 
			         	$res1 = ($res1) ? $pm_handler->setTosave($pm, 0) : false; 
			         } elseif (!empty($_REQUEST['move_message'])) {
			         	$res1 = $pm_handler->setTosave($pm, 0); 
			         }
		         }
		         if ($pm->getVar('from_userid') == $GLOBALS['xoopsUser']->getVar('uid')) {
			         if (!empty($_REQUEST['delete_message'])) {
			         	$res2 = $pm_handler->setFromDelete($pm); 
			         	$res2 = ($res2) ? $pm_handler->setFromsave($pm, 0) : false; 
			         } elseif (!empty($_REQUEST['move_message'])) {
			         	$res2 = $pm_handler->setFromsave($pm, 0); 
			         }
		         }
		         $res = $res1 && $res2; 
		         break;
		         
			case 'in':
			default:
		         if ($pm->getVar('to_userid') != $GLOBALS['xoopsUser']->getVar('uid')) break;
		         if (!empty($_REQUEST['delete_message'])) {
		         	$res = $pm_handler->setTodelete($pm);
		         } elseif (!empty($_REQUEST['move_message'])) {
		         	$res = $pm_handler->setTosave($pm); 
		         }
		         break;
		}
	}
    $res_message = isset($res_message) ? $res_message : ( ($res) ? _PROFILE_MA_PMACTION_DONE : _PROFILE_MA_PMACTION_ERROR );
    redirect_header('viewpmsg.php?op=' . htmlspecialchars( $_REQUEST['op'] ) , 2, $res_message);
}
$start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
$total_messages = !empty($_GET['total_messages']) ? intval($_GET['total_messages']) : 0;
include $GLOBALS['xoops']->path( '/header.php' ); 
$xoopsTpl->assign('section_name', _US_INBOX);
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'social.php';
$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $xoopsUser->getVar('uname'));
$xoBreadcrumbs[] = array('title' => _PROFILE_MA_PMNAME);

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
    list($pm) = $pm_handler->getObjects($criteria);
}

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";

$pmform = new XoopsForm('', 'pmform', 'readpmsg.php', 'post', true);
if (is_object($pm)&&!empty($pm)) {
	if ($pm->getVar('from_userid') != $GLOBALS['xoopsUser']->getVar('uid')) {
		$reply_button = new XoopsFormButton('', 'send', _PROFILE_MA_PMREPLY);
		$reply_button->setExtra("onclick='javascript:openWithSelfMain(\"" . XOOPS_URL . "/pmlite.php?reply=1&amp;msg_id={$pm->getVar("msg_id")}\", \"pmlite\", 550, 450);'");
		$pmform->addElement($reply_button);
	}
	$pmform->addElement(new XoopsFormButton('', 'delete_message', _PROFILE_MA_PMDELETE, 'submit'));
	$pmform->addElement(new XoopsFormButton('', 'move_message', ($_REQUEST['op'] == 'save') ? _PROFILE_MA_PMUNSAVE : _PROFILE_MA_PMTOSAVE, 'submit'));
	$pmform->addElement(new XoopsFormButton('', 'email_message', _PROFILE_MA_PMEMAIL, 'submit'));
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
		$pm_handler->setRead($pm);
	}
	
	$message = $pm->getValues();
	$message['msg_time'] = formatTimestamp($pm->getVar("msg_time"));
}
$GLOBALS['xoopsTpl']->assign('message', $message);
$GLOBALS['xoopsTpl']->assign('op', $_REQUEST['op']);
$GLOBALS['xoopsTpl']->assign('previous', $start-1);
$GLOBALS['xoopsTpl']->assign('next', $start+1);
$GLOBALS['xoopsTpl']->assign('total_messages', $total_messages);

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>