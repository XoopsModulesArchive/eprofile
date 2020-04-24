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
 * @version         $Id: admin_permissions.php 28 2013-09-06 21:58:22Z alfred $
 */
include 'admin_header.php';
xoops_cp_header();

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : "edit";
if ( !in_array($op,array('edit', 'search', 'access', 'visibility')) ) $op = 'edit';

include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
$opform = new XoopsSimpleForm('', 'opform', 'admin_permissions.php', "get");
$op_select = new XoopsFormSelect("", 'op', $op);
$op_select->setExtra('onchange="document.forms.opform.submit()"');
$op_select->addOption('visibility', _EPROFILE_AM_PROF_VISIBLE);
$op_select->addOption('edit', 		_EPROFILE_AM_PROF_EDITABLE);
$op_select->addOption('search', 	_EPROFILE_AM_PROF_SEARCH);
$op_select->addOption('access', 	_EPROFILE_AM_PROF_ACCESS);
$opform->addElement($op_select);
$opform->display();

$perm_desc = "";
switch ($op) {
case "visibility":
    redirect_header("admin_visibility.php", 0, _EPROFILE_AM_PROF_VISIBLE);
    break;
    
case "edit":
    $title_of_form = _EPROFILE_AM_PROF_EDITABLE;
    $perm_name = "profile_edit";
    $restriction = "field_edit";
    $anonymous = false;
    break;
    
case "search":
    $title_of_form = _EPROFILE_AM_PROF_SEARCH;
    $perm_name = "profile_search";
    $restriction = "";
    $anonymous = true;
    break;
    
case "access":
    $title_of_form = _EPROFILE_AM_PROF_ACCESS;
    $perm_name = "profile_access";
    $perm_desc = _EPROFILE_AM_PROF_ACCESS_DESC;
    $restriction = "";
    $anonymous = true;
    break;
}
$module_id = $xoopsModule->getVar('mid');
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
$form = new XoopsGroupPermForm($title_of_form, $module_id, $perm_name, $perm_desc, 'admin/admin_permissions.php', $anonymous);

if ($op == "access") {
    $member_handler =& xoops_gethandler('member');
    $glist = $member_handler->getGroupList();
    foreach (array_keys($glist) as $i) {
        if ($i != XOOPS_GROUP_ANONYMOUS) {
            $form->addItem($i, $glist[$i]);
        }
    }
    
} else {
    $profile_handler =& xoops_getmodulehandler('profile');
    $fields = $profile_handler->loadFields();
    
    if ($op != "search") {
        foreach (array_keys($fields) as $i) {
            if ($restriction == "" || $fields[$i]->getVar($restriction)) {
                $form->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
            }
        }
    } else {
        $searchable_types = array('textbox',
        'select',
        'radio',
        'yesno',
        'date',
        'datetime',
        'timezone',
        'language');
        foreach (array_keys($fields) as $i) {
            if (in_array($fields[$i]->getVar('field_type'), $searchable_types)) {
                $form->addItem($fields[$i]->getVar('field_id'), xoops_substr($fields[$i]->getVar('field_title'), 0, 25));
            }
        }
    }
}
$form->display();
xoops_cp_footer();
?>