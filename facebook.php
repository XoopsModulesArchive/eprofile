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
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id$
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';
$xoopsOption['template_main'] = 'profile_facebook.html';
include $GLOBALS['xoops']->path('header.php');
$GLOBALS['xoopsTpl']->assign('section_name', _PROFILE_MA_FACEBOOK);
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'social.php';
$xoBreadcrumbs[] = array('title' => _PROFILE_MA_FACEBOOK);

if ( !is_object($GLOBALS['xoopsUser']) || !$isOwner || !$allow_facebook) {
    redirect_header(XOOPS_URL, 3, _NOPERM);
    exit();
}

if (!empty($GLOBALS['xoopsModuleConfig']['profile_fb_apikey']) && !empty($GLOBALS['xoopsModuleConfig']['profile_fb_apid']))
{
    $GLOBALS['xoopsTpl']->assign('api_key',$GLOBALS['xoopsModuleConfig']['profile_fb_apikey']);
    $GLOBALS['xoopsTpl']->assign('apid',$GLOBALS['xoopsModuleConfig']['profile_fb_apid']);
    $GLOBALS['xoopsTpl']->assign('facebook_ok',1);
}
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>