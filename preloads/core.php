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
 * @since           2.4.0
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: core.php 2 2012-08-16 08:20:47Z alfred $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Profile core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class EprofileCorePreload extends XoopsPreloadItem
{
    function eventCoreUserStart($args)
    {
        $op = 'main';
        if (isset($_REQUEST['op'])) {
            $op = trim($_REQUEST['op']);
        }
        if ($op != 'login' && (empty($_GET['from']) || 'eprofile' != $_GET['from'])) {
            if (EprofileCorePreload::isActive()) {            
                header("location: " . XOOPS_URL . "/modules/eprofile/user.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
                exit();
            }
       }
    }
    
    function eventCorePmLiteStart($args)
    {
        if (EprofileCorePreload::isActive()) {
            header("location: ./modules/eprofile/pmlite.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
            exit();
        }
    }
    
    function eventCoreReadpmsgStart($args)
    {
        if (EprofileCorePreload::isActive()) {
            header("location: ./modules/eprofile/readpmsg.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
            exit();
        }
    }

    function eventCoreViewpmsgStart($args)
    {
        if (EprofileCorePreload::isActive()) {
            header("location: ./modules/eprofile/viewpmsg.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
            exit();
        }
    }

    function eventCoreClassSmartyXoops_pluginsXoinboxcount($args)
    {
        if (EprofileCorePreload::isActive()) {
            $args[0] =& xoops_getModuleHandler('message', 'eprofile');
        }
    }    
    

    function eventCoreEdituserStart($args)
    {
        if (EprofileCorePreload::isActive()) {
            header("location: ./modules/eprofile/edituser.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
            exit();
        }
        }

    function eventCoreLostpassStart($args)
    {
        if (EprofileCorePreload::isActive()) {
            $email = isset($_GET['email']) ? trim($_GET['email']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : $email;
            header("location: ./modules/eprofile/lostpass.php?email={$email}" . (empty($_GET['code']) ? "" : "&" . $_GET['code']));
            exit();
        }
    }

    function eventCoreRegisterStart($args)
    {
        if (EprofileCorePreload::isActive()) {
            header("location: ./modules/eprofile/register.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
            exit();
        }
    }

    function eventCoreUserinfoStart($args)
    {
        if (EprofileCorePreload::isActive()) {
            header("location: ./modules/eprofile/userinfo.php" . (empty($_SERVER['QUERY_STRING']) ? "" : "?" . $_SERVER['QUERY_STRING']));
            exit();
        }
    }

    function isActive()
    {
        $module_handler =& xoops_getHandler('module');
        $module = $module_handler->getByDirname('eprofile');
        return ($module && $module->getVar('isactive')) ? true : false;
    }
}
?>