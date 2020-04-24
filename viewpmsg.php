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
 * @version         $Id: viewpmsg.php 28 2013-09-06 21:58:22Z alfred $
 */
include 'header.php';

if ( !$isOwner ) { 
  redirect_header(XOOPS_URL , 3, _NOPERM);
}

if (isset($_POST['send_ok'])) {
  $pm = $pm_factory->create();
  $pm->setVar("msg_time", time());
  $pm->setVar("subject", $_POST['subject']);
  $pm->setVar("msg_text", $_POST['message']);
  $pm->setVar("to_userid", intval($_POST['to_userid']));
  $pm->setVar("from_userid", $xoopsUser->getVar("uid"));
  if (isset($_REQUEST['savecopy']) && $_REQUEST['savecopy'] == 1) {
    //PMs are by default not saved in outbox
    $pm->setVar('from_delete', 0);
  }
  if ($pm_factory->insert($pm)) {
    //send message
    $toUser =& $member_handler->getUser(intval($_POST['to_userid']));
    if ( $toUser && $profileconfigs_handler->getperm('messages_notify', $toUser->uid()) > 0 ) {
      $xoopsMailer =& xoops_getMailer();
      $xoopsMailer->reset();
      $xoopsMailer->useMail();
      $xoopsMailer->setTemplate('pm_new.tpl'); 
      $xoopsMailer->setTemplateDir($xoopsModule->getVar('dirname', 'n'));    
      $xoopsMailer->setToUsers($toUser);  
      $xoopsMailer->assign('X_SITENAME',  $GLOBALS['xoopsConfig']['sitename']);
      $xoopsMailer->assign('X_SITEURL',   XOOPS_URL."/");
      $xoopsMailer->assign('X_ADMINMAIL', $GLOBALS['xoopsConfig']['adminmail']);
      $xoopsMailer->assign('X_UNAME',     $toUser->uname());
      $xoopsMailer->assign('X_FROMUNAME', $xoopsUser->uname());
      $xoopsMailer->assign('X_SUBJECT',   $myts->stripSlashesGPC($_POST['subject']));
      $xoopsMailer->assign('X_MESSAGE',   $myts->stripSlashesGPC($_POST['message']));
      $xoopsMailer->assign('X_ITEM_URL',  XOOPS_URL . "/modules/".$xoopsModule->dirname()."/viewpmsg.php");
      $xoopsMailer->setSubject(_EPROFILE_MA_PMMAILNOTIFYSUBJECT);
      $xoopsMailer->send(); 
    }
  }
  redirect_header(XOOPS_URL ."/modules/".$xoopsModule->dirname()."/viewpmsg.php" , 3, _EPROFILE_MA_PMACTION_DONE);
}

if (isset($_REQUEST['send_messages'])) {
  if (intval($_REQUEST['send_messages']) > 0 ) {
    $pm_user = XoopsUser::getUnameFromId(intval($_REQUEST['send_messages']));
    if ($pm_user == $GLOBALS['xoopsConfig']['anonymous'] ) {
      redirect_header(XOOPS_URL . "/modules/".$xoopsModule->dirname()."/userinfo.php?uid=" . $uid, 3, _EPROFILE_MA_NOUSERSFOUND);
    }
  }
  $xoopsOption['template_main'] = "profile_pmwrite.html";
} else {
  $xoopsOption['template_main'] = "profile_pmmessage.html";
}
include $GLOBALS['xoops']->path('header.php'); 

$xoopsTpl->assign('section_name',   _EPROFILE_MA_PMNAME);
include_once "include/themeheader.php";
$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname')) . " :: "._EPROFILE_MA_PMNAME;

//ToDo
$xoopsModuleConfig['perpage'] = 20;
$xoopsModuleConfig['max_save'] = 100;

$_REQUEST['op'] = empty($_REQUEST['op']) ? "in" : $_REQUEST['op'];
$start = empty($_REQUEST["start"]) ? 0 : intval($_REQUEST["start"]);

if (isset($_POST['delete_messages']) && isset($_POST['msg_id'])) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $xoopsTpl->assign('errormsg', implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    } elseif (empty($_REQUEST['ok'])) {
        xoops_confirm(array('ok' => 1, 'delete_messages' => 1, 'op' => $_REQUEST['op'], 'msg_id'=> serialize(array_map("intval", $_POST['msg_id']))), $_SERVER['REQUEST_URI'], _EPROFILE_MA_PMRUSUREDELETE);
        include XOOPS_ROOT_PATH . "/footer.php";
        exit();
    } else {
        $_POST['msg_id'] = unserialize($_POST['msg_id']);
        $size = count($_POST['msg_id']);
        $msg = $_POST['msg_id'];
        for ( $i = 0; $i < $size; $i++ ) {
            $pm = $pm_factory->get($msg[$i]);
            if ($pm->getVar('to_userid') == $xoopsUser->getVar('uid')) {
                $pm_factory->setTodelete($pm);
            } elseif ($pm->getVar('from_userid') == $xoopsUser->getVar('uid')) {
                $pm_factory->setFromdelete($pm);
            }
            unset($pm);
        }
        $xoopsTpl->assign('msg', _EPROFILE_MA_PMDELETED);
    }
}
if (isset($_POST['move_messages']) && isset($_POST['msg_id'])) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $xoopsTpl->assign('errormsg', implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    } else{
        $size = count($_POST['msg_id']);
        $msg = $_POST['msg_id'];
        if ($_POST['op'] == 'save') {
            for ( $i = 0; $i < $size; $i++ ) {
                $pm = $pm_factory->get($msg[$i]);
                if ($pm->getVar('to_userid') == $xoopsUser->getVar('uid')) {
                    $pm_factory->setTosave($pm, 0);
                } elseif ($pm->getVar('from_userid') == $xoopsUser->getVar('uid')) {
                    $pm_factory->setFromsave($pm, 0);
                }
                unset($pm);
            }
        } else {
            if (!$xoopsUser->isAdmin()) {
                $total_save = $pm_factory->getSavecount();
                $size = min($size, ($xoopsModuleConfig['max_save'] - $total_save));
            }
            for ( $i = 0; $i < $size; $i++ ) {
                $pm =& $pm_factory->get($msg[$i]);
                if ($_POST['op']=='in') {
                    $pm_factory->setTosave($pm);
                } elseif ($_POST['op'] == 'out') {
                    $pm_factory->setFromsave($pm);
                }
                unset($pm);
            }
        }
        if ($_POST['op'] == 'save') {
            $xoopsTpl->assign('msg', _EPROFILE_MA_PMUNSAVED);
        } elseif (isset($total_save) && !$xoopsUser->isAdmin()) {
            $xoopsTpl->assign('msg', sprintf(_EPROFILE_MA_PMSAVED_PART, $xoopsModuleConfig['max_save'], $i));
        } else {
            $xoopsTpl->assign('msg', _EPROFILE_MA_PMSAVED_ALL);
        }
    }
}
if (isset($_REQUEST['empty_messages'])) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        $xoopsTpl->assign('errormsg', implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
    } elseif (empty($_REQUEST['ok'])) {
        xoops_confirm(array('ok' => 1, 'empty_messages' => 1, 'op' => $_REQUEST['op']), $_SERVER['REQUEST_URI'], _EPROFILE_MA_PMRUSUREEMPTY);
        include XOOPS_ROOT_PATH."/footer.php";
        exit();
    } else {
        if ($_POST['op'] == 'save') {
            $crit_to = new CriteriaCompo(new Criteria('to_delete', 0));
            $crit_to->add(new Criteria('to_save',1));
            $crit_to->add(new Criteria('to_userid',$xoopsUser->getVar('uid')));
            $crit_from = new CriteriaCompo(new Criteria('from_delete', 0));
            $crit_from->add(new Criteria('from_save', 1));
            $crit_from->add(new Criteria('from_userid',$xoopsUser->getVar('uid')));
            $criteria = new CriteriaCompo($crit_to);
            $criteria->add($crit_from, "OR");
        } elseif ($_POST['op'] == 'out') {                
            $criteria = new CriteriaCompo(new Criteria('from_delete', 0));
            $criteria->add(new Criteria('from_userid', $xoopsUser->getVar('uid')));
            $criteria->add(new Criteria('from_save', 0));
        } else{
            $criteria = new CriteriaCompo(new Criteria('to_delete', 0));
            $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
            $criteria->add(new Criteria('to_save', 0));
        }
        /*
         * The following method has critical scalability problem !
         * deleteAll method should be used instead
         */
        $pms = $pm_factory->getObjects($criteria);
        unset($criteria);
        if (count($pms)>0) {
            foreach (array_keys($pms) as $i) {
                if ($pms[$i]->getVar('to_userid') == $xoopsUser->getVar('uid')) {
                    if ($_POST['op'] == 'save') {
                        $pm_factory->setTosave($pms[$i], 0);
                    } elseif ($_POST['op'] == 'in') {
                        $pm_factory->setTodelete($pms[$i]);
                    }
                }
                if ($pms[$i]->getVar('from_userid') == $xoopsUser->getVar('uid')) {
                    if ($_POST['op']=='save') {
                        $pm_factory->setFromsave($pms[$i],0);
                    } elseif ($_POST['op']=='out') {
                        $pm_factory->setFromdelete($pms[$i]);
                    }
                }
            }
        }
        $xoopsTpl->assign('msg', _EPROFILE_MA_PMEMPTIED);
    }
}


if ($_REQUEST['op'] == "out") {
    $criteria = new CriteriaCompo(new Criteria('from_delete', 0));
    $criteria->add(new Criteria('from_userid', $xoopsUser->getVar('uid')));
    $criteria->add(new Criteria('from_save', 0));
} elseif ($_REQUEST['op'] == "save") {
    $crit_to = new CriteriaCompo(new Criteria('to_delete', 0));
    $crit_to->add(new Criteria('to_save', 1));
    $crit_to->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
    $crit_from = new CriteriaCompo(new Criteria('from_delete', 0));
    $crit_from->add(new Criteria('from_save', 1));
    $crit_from->add(new Criteria('from_userid', $xoopsUser->getVar('uid')));
    $criteria = new CriteriaCompo($crit_to);
    $criteria->add($crit_from, "OR");
} else {
    $criteria = new CriteriaCompo(new Criteria('to_delete', 0));
    $criteria->add(new Criteria('to_userid', $xoopsUser->getVar('uid')));
    $criteria->add(new Criteria('to_save', 0));
}
$total_messages = $pm_factory->getCount($criteria);
$criteria->setStart($start);
$criteria->setLimit($xoopsModuleConfig['perpage']);
$criteria->setSort("msg_time");
$criteria->setOrder("DESC");
$pm_arr = $pm_factory->getAll($criteria, null, false, false);
unset($criteria);

$xoopsTpl->assign('total_messages', $total_messages);
$xoopsTpl->assign('op', $_REQUEST['op']);

if ( $total_messages > $xoopsModuleConfig['perpage']) {
    include XOOPS_ROOT_PATH . '/class/pagenav.php';
    $nav = new XoopsPageNav($total_messages, $xoopsModuleConfig['perpage'], $start, "start", 'op=' . htmlspecialchars($_REQUEST['op']));
    $xoopsTpl->assign('pagenav', $nav->renderNav(4));
}

$xoopsTpl->assign('display', $total_messages > 0);
$xoopsTpl->assign('anonymous', $xoopsConfig['anonymous']);
if (count($pm_arr)>0) {
    foreach (array_keys($pm_arr) as $i) {
        if (isset($_REQUEST['op']) && $_REQUEST['op'] == "out") {
            $uids[] = $pm_arr[$i]['to_userid'];
        } else {
            $uids[] = $pm_arr[$i]['from_userid'];
        }
    }
    $member_handler =& xoops_gethandler('member');
    $senders = $member_handler->getUserList(new Criteria('uid', "(" . implode(", ", array_unique($uids) ) . ")", "IN"));
    foreach (array_keys($pm_arr) as $i) {
        if (trim($pm_arr[$i]['msg_image']) == '') $pm_arr[$i]['msg_image'] = 'icon1.gif';
        $message = $pm_arr[$i];
        $message['msg_time'] = formatTimestamp($message["msg_time"]);
        if (isset($_REQUEST['op']) && $_REQUEST['op'] == "out") {
            $message['postername'] = $senders[$pm_arr[$i]['to_userid']];
            $message['posteruid'] = $pm_arr[$i]['to_userid'];
        } else {
            $message['postername'] = $senders[$pm_arr[$i]['from_userid']];
            $message['posteruid'] = $pm_arr[$i]['from_userid'];
        }
        $message['msg_no'] = $i;
        $xoopsTpl->append('pm_array', $message);
    }
}

include_once XOOPS_ROOT_PATH . "/class/xoopsformloader.php";

if (isset($_REQUEST['send_messages'])) {
  $action = XOOPS_URL . "/modules/eprofile/viewpmsg.php"; //$_SERVER['REQUEST_URI'];
  $pmform = new XoopsThemeForm(_EPROFILE_MA_SENDNEWMESSAGE, "pmform", $action, "post", true);
  
  if ( intval($_REQUEST['send_messages']) > 0 ) {
    $pm_uname = XoopsUser::getUnameFromId(intval($_REQUEST['send_messages']));
    $pmform->addElement(new XoopsFormHidden('to_userid', intval($_REQUEST['send_messages'])));
    $pmform->addElement(new XoopsFormLabel(_EPROFILE_MA_PMTO, $pm_uname));
  } else {
    $pmform->addElement(new XoopsFormSelectUser(_EPROFILE_MA_PMTO, 'to_userid'),true);
  }
  $pmform->addElement(new XoopsFormText(_EPROFILE_MA_PMSUBJECTC, 'subject', 30, 100, ""), true);
  $pmform->addElement(new XoopsFormDhtmlTextArea(_EPROFILE_MA_PMMESSAGEC, 'message', "", 10, 60), true);
  $pmform->addElement(new XoopsFormRadioYN(_EPROFILE_MA_PMSAVEINOUTBOX, 'savecopy', 1));
    
  $submit = new XoopsFormElementTray("", "");
  $submit->addElement(new XoopsFormButton('', 'send_ok', _EPROFILE_MA_PMSEND, 'submit'));
  $submit->addElement(new XoopsFormButton('', 'reset', _EPROFILE_MA_PMCLEAR, 'reset'));    
  $cancel_send = new XoopsFormButton('', 'cancel', _EPROFILE_MA_PMCANCELSEND, 'button');
  $cancel_send->setExtra("onclick='self.location.href=\"" . $action ."\"';");
  $submit->addElement($cancel_send);
  $pmform->addElement($submit);
  
  $pmform->assign($xoopsTpl);
  include XOOPS_ROOT_PATH . "/footer.php";
  exit();
}

$send_button    = new XoopsFormButton('', 'send_messages', _EPROFILE_MA_SENDNEWMESSAGE, 'submit');
$delete_button  = new XoopsFormButton('', 'delete_messages', _EPROFILE_MA_PMDELETE, 'submit');
$move_button    = new XoopsFormButton('', 'move_messages', ($_REQUEST['op'] == 'save') ? _EPROFILE_MA_PMUNSAVE : _EPROFILE_MA_PMTOSAVE, 'submit');
$empty_button   = new XoopsFormButton('', 'empty_messages', _EPROFILE_MA_PMEMPTY, 'submit');

$pmform = new XoopsForm('', 'pmform', 'viewpmsg.php', 'post', true);
$pmform->addElement($send_button);
$pmform->addElement($move_button);
$pmform->addElement($delete_button);
$pmform->addElement($empty_button);
$pmform->addElement(new XoopsFormHidden('op', $_REQUEST['op']));
$pmform->assign($xoopsTpl);
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>