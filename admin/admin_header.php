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
 * @version         $Id: admin_header.php 29 2014-02-07 21:38:38Z alfred $
 */

include("../../../include/cp_header.php");
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.php";
include_once XOOPS_ROOT_PATH."/Frameworks/art/functions.admin.php";

xoops_loadLanguage('main','eprofile');
if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
  include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
  $moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
	$pathIcon16 = XOOPS_URL .'/'. $moduleInfo->getInfo('icons16');
	$pathIcon32 = XOOPS_URL .'/'. $moduleInfo->getInfo('icons32');
	$indexAdmin = new ModuleAdmin();
}
$myts = &MyTextSanitizer::getInstance();
?>