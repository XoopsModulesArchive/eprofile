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
 * @version         $Id: changepass.php 2020 2008-08-31 01:54:14Z phppp $
 */

$xoopsOption['pagetype'] = "user";
include 'header.php';
if (!$xoopsUser) {
    redirect_header(XOOPS_URL, 2, _NOPERM);
}
$xoopsOption['template_main'] = 'profile_editprofile.html';
include XOOPS_ROOT_PATH . "/header.php";
include_once 'social.php';
$xoopsTpl->assign('section_name', _PROFILE_MA_CHANGEPASSWORD);

if (!isset($_POST['submit'])) {
    //show change password form
    include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
    $form = new XoopsThemeForm(_PROFILE_MA_CHANGEPASSWORD, 'userinfo', $_SERVER['REQUEST_URI'], 'post', true);
    $form->addElement(new XoopsFormPassword(_PROFILE_MA_OLDPASSWORD, 'oldpass', 15, 50), true);
    $form->addElement(new XoopsFormPassword(_PROFILE_MA_NEWPASSWORD, 'newpass', 15, 50), true);
    $form->addElement(new XoopsFormPassword(_US_VERIFYPASS, 'vpass', 15, 50), true);
    $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
    $form->assign($xoopsTpl);
    $xoBreadcrumbs[] = array('title' => _PROFILE_MA_CHANGEPASSWORD);

} else {
    $config_handler =& xoops_gethandler('config');
    $xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);
    $myts =& MyTextSanitizer::getInstance();
    $oldpass = @$myts->stripSlashesGPC(trim($_POST['oldpass']));
    $password = @$myts->stripSlashesGPC(trim($_POST['newpass']));
    $vpass = @$myts->stripSlashesGPC(trim($_POST['vpass']));
    $errors = array();
    if (md5($oldpass) != $xoopsUser->getVar('pass', 'n')) {
        $errors[] = _PROFILE_MA_WRONGPASSWORD;
    }
    if (strlen($password) < $xoopsConfigUser['minpass']) {
        $errors[] = sprintf(_US_PWDTOOSHORT, $xoopsConfigUser['minpass']);
    }
    if ($password != $vpass) {
        $errors[] = _US_PASSNOTSAME;
    }
    
    if ($errors) {
        $msg = implode('<br />', $errors);
    } else {
        //update password
        $xoopsUser->setVar('pass', md5($password));
        
        $member_handler =& xoops_gethandler('member');
        if ($member_handler->insertUser($xoopsUser)) {
            $msg = _PROFILE_MA_PASSWORDCHANGED;
        } else {
            $msg = _PROFILE_MA_ERRORDURINGSAVE;
        }
    }
    redirect_header(XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/userinfo.php?uid=' . $xoopsUser->getVar('uid'), 2, $msg);
}

include "footer.php";
?>