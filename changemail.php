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
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: changemail.php 2020 2008-08-31 01:54:14Z phppp $
 */

//$xoopsOption['pagetype'] = "user";
include 'header.php';
//$config_handler =& xoops_gethandler('config');
//$xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);

if (!$xoopsUser || $xoopsConfigUser['allow_chgmail'] != 1) {
    redirect_header(XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname', 'n') . "/", 2, _NOPERM);
}

$xoopsOption['template_main'] = 'profile_editprofile.html';
include XOOPS_ROOT_PATH . "/header.php";
include_once 'social.php';
$xoopsTpl->assign('section_name', _PROFILE_MA_CHANGEMAIL);

if (!isset($_POST['submit']) || !isset($_POST['passwd'])) {
    //show change password form
    include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
    $form = new XoopsThemeForm(_PROFILE_MA_CHANGEMAIL, 'userinfo', $_SERVER['REQUEST_URI'], 'post', true);
    $form->addElement(new XoopsFormPassword(_US_PASSWORD, 'passwd', 15, 50), true);
    $form->addElement(new XoopsFormText(_PROFILE_MA_NEWMAIL, 'newmail', 40, 50), true);
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $form->assign($xoopsTpl);
} else {
    $myts =& MyTextSanitizer::getInstance();
    $pass = @$myts->stripSlashesGPC(trim($_POST['passwd']));
    $email = @$myts->stripSlashesGPC(trim($_POST['newmail']));
    $errors = array();
    if (md5($pass) != $xoopsUser->getVar('pass', 'n')) {
        $errors[] = _PROFILE_MA_WRONGPASSWORD;
    }
    if (!checkEmail($email)) {
        $errors[] = _US_INVALIDMAIL;
    }
    
    if ($errors) {
        $msg = implode('<br />', $errros);
    } else {
        //update password
        $xoopsUser->setVar('email', trim($_POST['newmail']));
        
        $member_handler =& xoops_gethandler('member');
        if ($member_handler->insertUser($xoopsUser)) {
            $msg = _PROFILE_MA_EMAILCHANGED;
            
            //send email to new email address
            $xoopsMailer =xoops_getMailer();
            $xoopsMailer->useMail();
            $xoopsMailer->setTemplateDir($xoopsModule->getVar('dirname', 'n'));
            $xoopsMailer->setTemplate('emailchanged.tpl');
            $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
            $xoopsMailer->assign("ADMINMAIL", $xoopsConfig['adminmail']);
            $xoopsMailer->assign("SITEURL", XOOPS_URL . "/");
            $xoopsMailer->assign("NEWEMAIL", $email);
            $xoopsMailer->setToEmails($email);
            $xoopsMailer->setSubject(sprintf(_PROFILE_MA_NEWEMAIL, $xoopsConfig['sitename']));
            $xoopsMailer->send();
            
        } else {
            $msg = implode('<br />', $xoopsUser->getErrors());
        }
    }
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/userinfo.php?uid=' . $xoopsUser->getVar('uid'), 2, $msg);
}
    
$xoBreadcrumbs[] = array('title' => _PROFILE_MA_CHANGEMAIL);

include "footer.php";
?>