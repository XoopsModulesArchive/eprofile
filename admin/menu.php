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
 * @version         $Id: menu.php 2 2012-08-16 08:20:47Z alfred $
 */

if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
  $module_handler =& xoops_gethandler('module');
	$xoopsModule =& XoopsModule::getByDirname('eprofile');
	$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
	$pathIcon32 = $moduleInfo->getInfo('icons32');
}

$i = 0;
$adminmenu[$i]['title'] = _EPROFILE_MI_INDEX;
$adminmenu[$i]['link'] = "admin/index.php";
$adminmenu[$i]['icon'] = '../../'.$pathIcon32.'/home.png';

$i++;
$adminmenu[$i]['title'] = _EPROFILE_MI_USERS;
$adminmenu[$i]['link'] = "admin/admin_user.php";
$adminmenu[$i]['icon'] = '../../'.$pathIcon32.'/users.png';

$i++;
$adminmenu[$i]['title'] = _EPROFILE_MI_CATEGORIES;
$adminmenu[$i]['link'] = "admin/admin_category.php";
$adminmenu[$i]['icon'] = '../../'.$pathIcon32.'/folder_txt.png';

$i++;
$adminmenu[$i]['title'] = _EPROFILE_MI_FIELDS;
$adminmenu[$i]['link'] = "admin/admin_field.php";
$adminmenu[$i]['icon'] = '../../'.$pathIcon32.'/identity.png';

$i++;
$adminmenu[$i]['title'] = _EPROFILE_MI_STEPS;
$adminmenu[$i]['link'] = "admin/admin_step.php";
$adminmenu[$i]['icon'] = '../../'.$pathIcon32.'/groupmod.png';

$i++;
$adminmenu[$i]['title'] = _EPROFILE_MI_PERMISSIONS;
$adminmenu[$i]['link'] = "admin/admin_permissions.php";
$adminmenu[$i]['icon'] = '../../'.$pathIcon32.'/firewall.png';

$i++;
$adminmenu[$i]['title'] = _EPROFILE_MI_ABOUT;
$adminmenu[$i]['link'] = "admin/about.php";
$adminmenu[$i]['icon'] = '../../'.$pathIcon32.'/about.png';
?>