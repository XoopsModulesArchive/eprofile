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
 * @version         $Id: edituser.php 35 2014-02-08 17:37:13Z alfred $
 */

// If not a user, redirect
include 'header.php';

if ( !$isOwner && !$isAdmin ) {
 	redirect_header(XOOPS_URL, 3, _NOPERM);
  exit();
}

$xoopsOption['pagetype'] = 'user';
include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'editprofile';
//check op
if ($op == 'avatarform') {
  header("location: " . XOOPS_URL . "/modules/eprofile/pictures.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
  exit();
}

$config_handler =& xoops_gethandler('config');
$xoopsConfigUser = $config_handler->getConfigsByCat(XOOPS_CONF_USER);

if ($op == 'save') {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header(XOOPS_URL . "/modules/" . $xoopsModule->getVar('dirname', 'n') . "/", 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        exit();
    }
    $uid = $xoopsUser->getVar('uid');
    $errors = array();
    $edituser =& $xoopsUser;
    if ($xoopsUser->isAdmin()) {
        $edituser->setVar('uname', trim($_POST['uname']));
        $edituser->setVar('email', trim($_POST['email']));
    }
    
    xoops_load("XoopsuserUtility");
    $stop = XoopsUserUtility::validate($edituser);
    
    if (!empty($stop)) {
        $op = 'editprofile';
    } else {
    
        // Dynamic fields
        $profile_handler =& xoops_getmodulehandler('profile');
        // Get fields
        $fields = $profile_handler->loadFields();
        // Get ids of fields that can be edited
        $gperm_handler =& xoops_gethandler('groupperm');
        $editable_fields = $gperm_handler->getItemIds('profile_edit', $GLOBALS['xoopsUser']->getGroups(), $GLOBALS['xoopsModule']->getVar('mid'));

        if (!$profile = $profile_handler->get($edituser->getVar('uid'))) {
            $profile = $profile_handler->create();
            $profile->setVar('profile_id', $edituser->getVar('uid')); 
        }
    
        foreach (array_keys($fields) as $i) {
            $fieldname = $fields[$i]->getVar('field_name');
            if (in_array($fields[$i]->getVar('field_id'), $editable_fields) && isset($_REQUEST[$fieldname])) {
                $value = $fields[$i]->getValueForSave($_REQUEST[$fieldname]);
                if (in_array($fieldname, $profile_handler->getUserVars())) {
                    $edituser->setVar($fieldname, $value);
                } else {
                    $profile->setVar($fieldname, $value);
                }
            }
        }
        if (!$member_handler->insertUser($edituser)) {
            $stop = $edituser->getHtmlErrors();
            $op = 'editprofile';
        } else {
            $profile->setVar('profile_id', $edituser->getVar('uid'));
            $profile_handler->insert($profile);
            unset($_SESSION['xoopsUserTheme']);
            redirect_header(XOOPS_URL.'/modules/'.$GLOBALS['xoopsModule']->getVar('dirname', 'n').'/userinfo.php?uid=' . $edituser->getVar('uid'), 2, _US_PROFUPDATED);
        }
    }
}


if ($op == 'editprofile') {
    $xoopsOption['template_main'] = 'profile_editprofile.html';
    include_once XOOPS_ROOT_PATH . '/header.php';    
    include_once "include/themeheader.php";
    $xoopsTpl->assign('section_name', _US_EDITPROFILE);
    include_once 'include/forms.php';
    $form = profile_getUserForm($thisUser);
    $form->assign($xoopsTpl);
    if (!empty($stop)) {
        $xoopsTpl->assign('stop', $stop);
    }
}

if ($op == 'avatarform') {
    $xoopsOption['template_main'] = 'profile_avatar.html';
    include XOOPS_ROOT_PATH . '/header.php';
    $xoopsTpl->assign('section_name', _US_AVATAR);
    include_once "include/themeheader.php";
    
    $oldavatar = $xoopsUser->getVar('user_avatar');
    if (!empty($oldavatar) && $oldavatar != 'blank.gif') {
        $xoopsTpl->assign('old_avatar', XOOPS_UPLOAD_URL . '/' . $oldavatar);
    }
    if ($xoopsConfigUser['avatar_allow_upload'] == 1) {
	  if ($xoopsUser->getVar('posts') >= $xoopsConfigUser['avatar_minposts']) {
        include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
        $form = new XoopsThemeForm(_US_UPLOADMYAVATAR, 'uploadavatar', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/edituser.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormLabel(_US_MAXPIXEL, $xoopsConfigUser['avatar_width'] . ' x ' . $xoopsConfigUser['avatar_height']));
        $form->addElement(new XoopsFormLabel(_US_MAXIMGSZ, $xoopsConfigUser['avatar_maxsize']));
        $form->addElement(new XoopsFormFile(_US_SELFILE, 'avatarfile', $xoopsConfigUser['avatar_maxsize']), true);
        $form->addElement(new XoopsFormHidden('op', 'avatarupload'));
        $form->addElement(new XoopsFormHidden('uid', $xoopsUser->getVar('uid')));
        $form->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        $form->assign($xoopsTpl);
	  } else {
	    xoops_result(sprintf(_EPROFILE_MA_POSTSTOUPLOAD,$xoopsConfigUser['avatar_minposts']));
	  }
    }
    $avatar_handler =& xoops_gethandler('avatar');
    $form2 = new XoopsThemeForm(_US_CHOOSEAVT, 'chooseavatar', XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname', 'n') . '/edituser.php', 'post', true);
    $avatar_select = new XoopsFormSelect('', 'user_avatar', $xoopsUser->getVar('user_avatar'));
    $avatar_list = $avatar_handler->getList('S');
    $avatar_selected = $xoopsUser->getVar("user_avatar", "E");
    $avatar_selected = in_array($avatar_selected, array_keys($avatar_list)) ? $avatar_selected : "blank.gif";
    $avatar_select->addOptionArray($avatar_list);
    $avatar_select->setExtra("onchange='showImgSelected(\"avatar\", \"user_avatar\", \"uploads\", \"\", \"" . XOOPS_URL . "\")'");
    $avatar_tray = new XoopsFormElementTray(_US_AVATAR, '&nbsp;');
    $avatar_tray->addElement($avatar_select);
    $avatar_tray->addElement(new XoopsFormLabel('', "<a href=\"javascript:openWithSelfMain('" . XOOPS_URL . "/misc.php?action=showpopups&amp;type=avatars','avatars',600,400);\">"._LIST."</a><br />"));
    $avatar_tray->addElement(new XoopsFormLabel('', "<br /><img src='" . XOOPS_UPLOAD_URL . "/" . $avatar_selected . "' name='avatar' id='avatar' alt='' />"));
    $form2->addElement($avatar_tray);
    $form2->addElement(new XoopsFormHidden('uid', $xoopsUser->getVar('uid')));
    $form2->addElement(new XoopsFormHidden('op', 'avatarchoose'));
    $form2->addElement(new XoopsFormButton('', 'submit2', _SUBMIT, 'submit'));
    $form2->assign($xoopsTpl);
}

if ($op == 'avatarupload') {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('index.php', 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        exit;
    }
    $xoops_upload_file = array();
    $uid = 0;
    if (!empty($_POST['xoops_upload_file']) && is_array($_POST['xoops_upload_file'])){
        $xoops_upload_file = $_POST['xoops_upload_file'];
    }
    if (!empty($_POST['uid'])) {
        $uid = intval($_POST['uid']);
    }
    if (empty($uid) || $xoopsUser->getVar('uid') != $uid ) {
        redirect_header('index.php', 3, _US_NOEDITRIGHT);
        exit();
    }
    if ($xoopsConfigUser['avatar_allow_upload'] == 1 && $xoopsUser->getVar('posts') >= $xoopsConfigUser['avatar_minposts']) {
        include_once XOOPS_ROOT_PATH.'/class/uploader.php';
        $uploader = new XoopsMediaUploader(XOOPS_UPLOAD_PATH, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $xoopsConfigUser['avatar_maxsize'], $xoopsConfigUser['avatar_width'], $xoopsConfigUser['avatar_height']);
        if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
            $uploader->setPrefix('cavt');
            if ($uploader->upload()) {
                $avt_handler =& xoops_gethandler('avatar');
                $avatar =& $avt_handler->create();
                $avatar->setVar('avatar_file', $uploader->getSavedFileName());
                $avatar->setVar('avatar_name', $xoopsUser->getVar('name'));
                $avatar->setVar('avatar_mimetype', $uploader->getMediaType());
                $avatar->setVar('avatar_display', 1);
                $avatar->setVar('avatar_type', 'C');
                if (!$avt_handler->insert($avatar)) {
                    @unlink($uploader->getSavedDestination());
                } else {
                    $oldavatar = $xoopsUser->getVar('user_avatar');
                    if (!empty($oldavatar) && preg_match("/^cavt/", strtolower($oldavatar))) {
                        $avatars = $avt_handler->getObjects(new Criteria('avatar_file', $oldavatar));
                        if (!empty($avatars) && count($avatars) == 1 && is_object($avatars[0])) {
                            $avt_handler->delete($avatars[0]);
                            $oldavatar_path = str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH.'/'.$oldavatar));
                            if (0 === strpos($oldavatar_path, XOOPS_UPLOAD_PATH) && is_file($oldavatar_path)) {
                                unlink($oldavatar_path);
                            }
                        }
                    }
                    $sql = sprintf("UPDATE %s SET user_avatar = %s WHERE uid = %u", $xoopsDB->prefix('users'), $xoopsDB->quoteString($uploader->getSavedFileName()), $xoopsUser->getVar('uid'));
                    $xoopsDB->query($sql);
                    $avt_handler->addUser($avatar->getVar('avatar_id'), $xoopsUser->getVar('uid'));
                    redirect_header('userinfo.php?t=' . time() . '&amp;uid=' . $xoopsUser->getVar('uid'), 3, _US_PROFUPDATED);
                }
            }
        }
        redirect_header("edituser.php?op=avatarform", 3, $uploader->getErrors());
    }
}

if ($op == 'avatarchoose') {
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('index.php',3,_US_NOEDITRIGHT."<br />".implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
        exit;
    }
    $uid = 0;
    if (!empty($_POST['uid'])) {
        $uid = intval($_POST['uid']);
    }
    if (empty($uid) || $xoopsUser->getVar('uid') != $uid ) {
        redirect_header('index.php', 3, _US_NOEDITRIGHT);
        exit();
    }
    $user_avatar = '';
    $avt_handler =& xoops_gethandler('avatar');
    if (!empty($_POST['user_avatar'])) {
        $user_avatar = $myts->addSlashes( trim($_POST['user_avatar']) );
        $criteria_avatar = new CriteriaCompo(new Criteria('avatar_file', $user_avatar));
        $criteria_avatar->add(new Criteria('avatar_type', "S"));
        $avatars = $avt_handler->getObjects($criteria_avatar);
        if (!is_array($avatars) || !count($avatars)) {
            $user_avatar = 'blank.gif';
        }
        unset($avatars, $criteria_avatar);
    }
    $user_avatarpath = str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH.'/'.$user_avatar));
    if (0 === strpos($user_avatarpath, XOOPS_UPLOAD_PATH) && is_file($user_avatarpath)) {
        $oldavatar = $xoopsUser->getVar('user_avatar');
        $xoopsUser->setVar('user_avatar', $user_avatar);
        $member_handler =& xoops_gethandler('member');
        if (!$member_handler->insertUser($xoopsUser)) {
            include XOOPS_ROOT_PATH.'/header.php';
            echo $xoopsUser->getHtmlErrors();
            include XOOPS_ROOT_PATH.'/footer.php';
            exit();
        }
        if ($oldavatar && preg_match("/^cavt/", strtolower($oldavatar))) {
            $avatars = $avt_handler->getObjects(new Criteria('avatar_file', $oldavatar));
            if (!empty($avatars) && count($avatars) == 1 && is_object($avatars[0])) {
                $avt_handler->delete($avatars[0]);
                $oldavatar_path = str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH.'/'.$oldavatar));
                if (0 === strpos($oldavatar_path, XOOPS_UPLOAD_PATH) && is_file($oldavatar_path)) {
                    unlink($oldavatar_path);
                }
            }
        }
        if ($user_avatar != 'blank.gif') {
            $avatars = $avt_handler->getObjects(new Criteria('avatar_file', $user_avatar));
            if (is_object($avatars[0])) {
                $avt_handler->addUser($avatars[0]->getVar('avatar_id'), $xoopsUser->getVar('uid'));
            }
        }
    }
    redirect_header('userinfo.php?uid='.$uid, 0, _US_PROFUPDATED);
}
include 'footer.php';
?>