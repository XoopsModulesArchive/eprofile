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
 * @version         $Id: user.php 2 2012-08-16 08:20:47Z alfred $
 */

$xoopsOption['pagetype'] = 'user';
include_once 'header.php';

$op = ( isset($_REQUEST['op']) ) ? $_REQUEST['op'] : 'main';

if ( !in_array( $op,array('main','login','logout','actv','delete')) ) $op = 'main';

if ($op == 'main') {
    if ( !$xoopsUser ) {
        $xoopsOption['template_main'] = 'profile_userform.html';
        include XOOPS_ROOT_PATH . '/header.php';
        $xoopsTpl->assign('lang_login', _LOGIN);
        $xoopsTpl->assign('lang_username', _USERNAME);
        if (isset($_GET['xoops_redirect'])) {
            $xoopsTpl->assign('redirect_page', htmlspecialchars(trim($_GET['xoops_redirect']), ENT_QUOTES));
        }
        if ($xoopsConfig['usercookie']) {
            $xoopsTpl->assign('lang_rememberme', _US_REMEMBERME);
        }
        $xoopsTpl->assign('lang_password', _PASSWORD);
        $xoopsTpl->assign('lang_notregister', _US_NOTREGISTERED);
        $xoopsTpl->assign('lang_lostpassword', _US_LOSTPASSWORD);
        $xoopsTpl->assign('lang_noproblem', _US_NOPROBLEM);
        $xoopsTpl->assign('lang_youremail', _US_YOUREMAIL);
        $xoopsTpl->assign('lang_sendpassword', _US_SENDPASSWORD);
        $xoopsTpl->assign('mailpasswd_token', $GLOBALS['xoopsSecurity']->createToken());
        include 'footer.php';
        exit();
    }
    if ( !empty($_GET['xoops_redirect']) ) {
        $redirect = trim($_GET['xoops_redirect']);
        $isExternal = false;
        if ($pos = strpos( $redirect, '://' )) {
            $xoopsLocation = substr( XOOPS_URL, strpos( XOOPS_URL, '://' ) + 3 );
            if ( strcasecmp(substr($redirect, $pos + 3, strlen($xoopsLocation)), $xoopsLocation) ) {
                $isExternal = true;
            }
        }
        if (!$isExternal) {
            header('Location: ' . $redirect);
            exit();
        }
    }
    header('Location: ./userinfo.php?uid=' . $xoopsUser->getVar('uid'));
    exit();
}

if ($op == 'login') {
    include_once XOOPS_ROOT_PATH . '/include/checklogin.php';
    exit();
}

if ($op == 'logout') {
    $message = '';
    $_SESSION = array();
    session_destroy();
    setcookie($xoopsConfig['usercookie'], 0, - 1, '/', XOOPS_COOKIE_DOMAIN, 0);
    setcookie($xoopsConfig['usercookie'], 0, - 1, '/');
    // clear entry from online users table
    if (is_object($xoopsUser)) {
        $online_handler =& xoops_gethandler('online');
        $online_handler->destroy($xoopsUser->getVar('uid'));
    }
    $message = _US_LOGGEDOUT . '<br />' . _US_THANKYOUFORVISIT;
    $xoopsPreload =& XoopsPreload::getInstance();
    $xoopsPreload->triggerEvent('core.user.logout');
    redirect_header(XOOPS_URL . '/index.php', 1, $message);
    exit();
}

if ($op == 'actv') {
    $id = intval($_GET['id']);
    $actkey = trim($_GET['actkey']);
    redirect_header("activate.php?op=actv&amp;id={$id}&amp;actkey={$actkey}", 1, '');
    exit();
}

if ($op == 'delete') {
    $config_handler =& xoops_gethandler('config');
    $xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);
    if (!$xoopsUser || $xoopsConfigUser['self_delete'] != 1) {
        redirect_header(XOOPS_URL . '/', 5, _US_NOPERMISS);
        exit();
    } else {
        $groups = $xoopsUser->getGroups();
        if (in_array(XOOPS_GROUP_ADMIN, $groups)){
            // users in the webmasters group may not be deleted
            redirect_header(XOOPS_URL . '/', 5, _US_ADMINNO);
            exit();
        }
        $ok = !isset($_POST['ok']) ? 0 : intval($_POST['ok']);
        if ($ok != 1) {
            include 'header.php';
            $xoopsOption['template_main'] = 'profile_header.html';
            include $GLOBALS['xoops']->path('header.php');
            $xoopsTpl->assign('section_name', _US_SURETODEL);
            include_once "include/themeheader.php";
            xoops_confirm(array('op' => 'delete', 'ok' => 1), 'user.php', _US_SURETODEL . '<br/>' . _US_REMOVEINFO);
            include 'footer.php';
        } else {
            $del_uid = $xoopsUser->getVar("uid");
            $member_handler =& xoops_gethandler('member');
            if (false != $member_handler->deleteUser($xoopsUser)) {
                $online_handler =& xoops_gethandler('online');
                $online_handler->destroy($del_uid);
                xoops_notification_deletebyuser($del_uid);
                redirect_header(XOOPS_URL . '/', 5, _US_BEENDELED);
            }
            redirect_header(XOOPS_URL . '/', 5, _US_NOPERMISS);
        }
        exit();
    }
}
?>