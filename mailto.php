<?php
// $Id: mailto.php 35 2014-02-08 17:37:13Z alfred $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//$xoopsOption['pagetype'] = "user";
include 'header.php';
$toid = (!empty($_GET['toid'])) ? intval($_GET['toid']) : 0;
$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

if ( !$profileconfigs_handler->getperm('emails', $toid, $muid) ) {
  redirect_header(XOOPS_URL . '/modules/eprofile/userinfo.php?uid='.$uid, 2, _EPROFILE_MA_NOPERM);
}

$xoopsOption['template_main'] = 'profile_email.html';
include $GLOBALS['xoops']->path('header.php');

$xoopsTpl->assign('section_name',   _EPROFILE_MA_SENDEMAIL);
include_once "include/themeheader.php";

$member_handler =& xoops_gethandler('member');
$toUser =& $member_handler->getUser($toid);

if (!$xoopsUser || !$toUser || !$toUser->isActive() || ($xoopsUser->uid() == $toUser->uid()) ) {
    redirect_header('userinfo.php?uid='.$uid, 2, _EPROFILE_MA_NOPERM);
    exit();
}

if ($op == 'send') {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header(XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname', 'n') . "/", 3, _EPROFILE_MA_EXPIRED );
        exit();
    }
    $title = $myts->displayTarea($_POST['subject']);
    $message = $myts->displayTarea($_POST['body'],1,1,1,1,0);
    $xoopsMailer = xoops_getMailer();
    $xoopsMailer->reset();
    $xoopsMailer->useMail();
    $xoopsMailer->setTemplate('automailer.tpl');    
    $xoopsMailer->setTemplateDir($xoopsModule->getVar('dirname', 'n'));
    $xoopsMailer->assign('X_UNAME', $toUser->uname());
    $xoopsMailer->assign('X_FROMNAME', $xoopsUser->uname());
    $xoopsMailer->assign('X_MESSAGE', $message);
    $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
    $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
    $xoopsMailer->assign('SITEURL', XOOPS_URL."/");                    
    $xcode = base64_encode($xoopsUser->uid()."-".$xoopsUser->uname()."-".time());
    $xoopsMailer->assign('X_CODE', $xcode);
    $xulink = XOOPS_URL."/modules/".$xoopsModule->dirname()."/mailto.php?toid=".$xoopsUser->uid();
    $xoopsMailer->assign('X_ULINK',$xulink);
    $xoopsMailer->setToUsers($toUser);
    $xoopsMailer->assign('X_USPAMMAIL', $xoopsConfig['adminmail']);
    $xoopsMailer->setSubject(_EPROFILE_MA_MAILADDSUBJECT. " - " .$title);
    if ( !$xoopsMailer->send(true) ) {
      xoops_result($xoopsMailer->getErrors());    
    } else {
	    redirect_header(XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname', 'n') . "/", 3, _EPROFILE_MA_MAILSENDED );
      exit();
    }	
} 

//$maxfilesize = 1024000;
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$form = new XoopsThemeForm(_EPROFILE_MA_SENDEMAIL, 'emailform', $_SERVER['REQUEST_URI'], 'post', true);
$form->addElement(new XoopsFormHidden('uid',$uid));
$form->addElement(new XoopsFormHidden('op','send'));
$form->addElement(new XoopsFormLabel(_EPROFILE_MA_FROMMAIL,$xoopsUser->uname()));
$form->addElement(new XoopsFormLabel(_EPROFILE_MA_TOMAIL,$toUser->uname()));
$form->addElement(new XoopsFormText(_EPROFILE_MA_MAILSUBJECT, 'subject', 30, 50), true);
$form->addElement(new XoopsFormDhtmlTextArea(_EPROFILE_MA_MAILTEXT, 'body', "", 5, 50),true);
//$form->addElement(new XoopsFormFile(_EPROFILE_MA_MAILATTACH, 'attach', $maxfilesize));
$form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
$form->assign($xoopsTpl);
//$form->display();
include XOOPS_ROOT_PATH . '/footer.php';
?>