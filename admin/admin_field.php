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
 * @version         $Id: field.php 2 2012-08-16 08:20:47Z alfred $
 */
include 'admin_header.php';
xoops_cp_header();

$op = eprofile_cleanVars($_REQUEST, 'op', 'list', 'string', true);
$id = eprofile_cleanVars($_REQUEST, 'id', 0, 'int', true);

if ( !in_array( $op,array('list', 'new', 'edit', 'reorder', 'save', 'delete') ) ) $op = "list";

$profilefield_handler = xoops_getmodulehandler('field');

switch($op) {
default:
case "list":
	$indexAdmin->addItemButton(_EPROFILE_AM_ADDFIELD, 'admin_field.php?op=new', $icon = 'add');
	echo $indexAdmin->renderButton();

    $fields = $profilefield_handler->getObjects(null, true, false);
    $module_handler =& xoops_gethandler('module');
    $modules = $module_handler->getObjects(null, true);
    $cat_handler =& xoops_getmodulehandler('category');
    $criteria = new CriteriaCompo();
    $criteria->setSort('cat_weight');
    $cats = $cat_handler->getObjects($criteria, true);
    unset($criteria);

    $categories[0] = _EPROFILE_AM_DEFAULT;
    if (count($cats) > 0) {
        foreach (array_keys($cats) as $i) {
            $categories[$cats[$i]->getVar('cat_id')] = $cats[$i]->getVar('cat_title');
        }
    }
    $xoopsTpl->assign('categories', $categories);
    unset($categories);
    $valuetypes = array(XOBJ_DTYPE_ARRAY 	=> _EPROFILE_AM_ARRAY,
                        XOBJ_DTYPE_EMAIL 	=> _EPROFILE_AM_EMAIL,
                        XOBJ_DTYPE_INT 		=> _EPROFILE_AM_INT,
                        XOBJ_DTYPE_TXTAREA 	=> _EPROFILE_AM_TXTAREA,
                        XOBJ_DTYPE_TXTBOX 	=> _EPROFILE_AM_TXTBOX,
                        XOBJ_DTYPE_URL 		=> _EPROFILE_AM_URL,
                        XOBJ_DTYPE_OTHER 	=> _EPROFILE_AM_OTHER,
                        XOBJ_DTYPE_MTIME 	=> _EPROFILE_AM_DATE);

    $fieldtypes = array('checkbox' 		=> _EPROFILE_AM_CHECKBOX,
                        'group' 		=> _EPROFILE_AM_GROUP,
                        'group_multi' 	=> _EPROFILE_AM_GROUPMULTI,
                        'language' 		=> _EPROFILE_AM_LANGUAGE,
                        'radio' 		=> _EPROFILE_AM_RADIO,
                        'select' 		=> _EPROFILE_AM_SELECT,
                        'select_multi' 	=> _EPROFILE_AM_SELECTMULTI,
                        'textarea' 		=> _EPROFILE_AM_TEXTAREA,
                        'dhtml' 		=> _EPROFILE_AM_DHTMLTEXTAREA,
                        'textbox' 		=> _EPROFILE_AM_TEXTBOX,
                        'timezone' 		=> _EPROFILE_AM_TIMEZONE,
                        'yesno' 		=> _EPROFILE_AM_YESNO,
                        'date' 			=> _EPROFILE_AM_DATE,
                        'datetime' 		=> _EPROFILE_AM_DATETIME,
                        'longdate' 		=> _EPROFILE_AM_LONGDATE,
                        'theme' 		=> _EPROFILE_AM_THEME,
                        'autotext' 		=> _EPROFILE_AM_AUTOTEXT,
                        'rank' 			=> _EPROFILE_AM_RANK);

    foreach (array_keys($fields) as $i) {
        $fields[$i]['canEdit'] = $fields[$i]['field_config'] || $fields[$i]['field_show'] || $fields[$i]['field_edit'];
        $fields[$i]['canDelete'] = $fields[$i]['field_config'];
        $fields[$i]['fieldtype'] = $fieldtypes[$fields[$i]['field_type']];
        $fields[$i]['valuetype'] = $valuetypes[$fields[$i]['field_valuetype']];
        $categories[$fields[$i]['cat_id']][] = $fields[$i];
        $weights[$fields[$i]['cat_id']][] = $fields[$i]['field_weight'];
    }
    //sort fields order in categories
    foreach (array_keys($categories) as $i) {
        array_multisort($weights[$i], SORT_ASC, array_keys($categories[$i]), SORT_ASC, $categories[$i]);
    }
    ksort($categories);
    $xoopsTpl->assign('fieldcategories', $categories);
    $xoopsTpl->assign('token', $GLOBALS['xoopsSecurity']->getTokenHTML());
    $template_main = "profile_admin_fieldlist.html";
    break;

case "new":
    include_once('../include/forms.php');
    $obj =& $profilefield_handler->create();
    $form = profile_getFieldForm($obj);
    $form->display();
    break;

case "edit":
    $obj =& $profilefield_handler->get($_REQUEST['id']);
    if (!$obj->getVar('field_config') && !$obj->getVar('field_show') && !$obj->getVar('field_edit')) { //If no configs exist
        redirect_header('admin_field.php', 2, _EPROFILE_AM_FIELDNOTCONFIGURABLE);
		exit();
    }
    include_once('../include/forms.php');
    $form = profile_getFieldForm($obj);
    $form->display();
    break;

case "reorder":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin_field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		exit();
    }
    if (isset($_POST['field_ids']) && count($_POST['field_ids']) > 0) {
        $oldweight = $_POST['oldweight'];
        $oldcat = $_POST['oldcat'];
        $category = $_POST['category'];
        $weight = $_POST['weight'];
        $ids = array();
        foreach ($_POST['field_ids'] as $field_id) {
            if ($oldweight[$field_id] != $weight[$field_id] || $oldcat[$field_id] != $category[$field_id]) {
                //if field has changed
                $ids[] = intval($field_id);
            }
        }
        if (count($ids) > 0) {
            $errors = array();
            //if there are changed fields, fetch the fieldcategory objects
            $field_handler =& xoops_getmodulehandler('field');
            $fields = $field_handler->getObjects(new Criteria('field_id', "(" . implode(',', $ids) . ")", "IN"), true);
            foreach ($ids as $i) {
                $fields[$i]->setVar('field_weight', intval($weight[$i]));
                $fields[$i]->setVar('cat_id', intval($category[$i]));
                if (!$field_handler->insert($fields[$i])) {
                    $errors = array_merge($errors, $fields[$i]->getErrors());
                }
            }
            if (count($errors) == 0) {
                //no errors
                redirect_header('admin_field.php', 2, sprintf(_EPROFILE_AM_SAVEDSUCCESS, _EPROFILE_AM_FIELDS));
            } else {
                redirect_header('admin_field.php', 3, implode('<br />', $errors));
            }
			exit();
        }
    }
    break;

case "save":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('admin_field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
		exit();
    }
    $redirect_to_edit = false;
    if (isset($_REQUEST['id'])) {
        $obj =& $profilefield_handler->get($_REQUEST['id']);
        if (!$obj->getVar('field_config') && !$obj->getVar('field_show') && !$obj->getVar('field_edit')) { //If no configs exist
            redirect_header('admin.php', 2, _EPROFILE_AM_FIELDNOTCONFIGURABLE);
        }
    } else {
        $obj =& $profilefield_handler->create();
        $obj->setVar('field_name', $_REQUEST['field_name']);
        $obj->setVar('field_moduleid', $xoopsModule->getVar('mid'));
        $obj->setVar('field_show', 1);
        $obj->setVar('field_edit', 1);
        $obj->setVar('field_config', 1);
        $redirect_to_edit = true;
    }
    $obj->setVar('field_title', $_REQUEST['field_title']);
    $obj->setVar('field_description', $_REQUEST['field_description']);
    if ($obj->getVar('field_config')) {
        $obj->setVar('field_type', $_REQUEST['field_type']);
        if (isset($_REQUEST['field_valuetype'])) {
            $obj->setVar('field_valuetype', $_REQUEST['field_valuetype']);
        }
        $options = $obj->getVar('field_options');
        
        if (isset($_REQUEST['removeOptions']) && is_array($_REQUEST['removeOptions'])) {
            foreach ($_REQUEST['removeOptions'] as $index) {
                unset($options[$index]);
            }
            $redirect_to_edit = true;
        }
        
        if (!empty($_REQUEST['addOption'])) {
            foreach ($_REQUEST['addOption'] as $option) {
                if (empty($option['value'])) continue;
                $options[$option['key']] = $option['value'];
                $redirect_to_edit = true;
            }
        }
        $obj->setVar('field_options', $options);
    }
    if ($obj->getVar('field_edit')) {
        $required = isset($_REQUEST['field_required']) ? $_REQUEST['field_required'] : 0;
        $obj->setVar('field_required', $required); //0 = no, 1 = yes
        if (isset($_REQUEST['field_maxlength'])) {
            $obj->setVar('field_maxlength', $_REQUEST['field_maxlength']);
        }
        if (isset($_REQUEST['field_default'])) {
            $field_default = $obj->getValueForSave($_REQUEST['field_default']);
            //Check for multiple selections
            if (is_array($field_default)) {
                $obj->setVar('field_default', serialize($field_default));
            } else {
                $obj->setVar('field_default', $field_default);
            }
        }
    }

    if ($obj->getVar('field_show')) {
        $obj->setVar('field_weight', $_REQUEST['field_weight']);
        $obj->setVar('cat_id', $_REQUEST['field_category']);
    }
    if (/*$obj->getVar('field_edit') && */isset($_REQUEST['step_id'])) {
        $obj->setVar('step_id', $_REQUEST['step_id']);
    }
    if ($profilefield_handler->insert($obj)) {
        $groupperm_handler =& xoops_gethandler('groupperm');

        $perm_arr = array();
        if ($obj->getVar('field_show')) {
            $perm_arr[] = 'profile_show';
            $perm_arr[] = 'profile_visible';
        }
        if ($obj->getVar('field_edit')) {
            $perm_arr[] = 'profile_edit';
        }
        if ($obj->getVar('field_edit') || $obj->getVar('field_show')) {
            $perm_arr[] = 'profile_search';
        }
        if (count($perm_arr) > 0) {
            foreach ($perm_arr as $perm) {
                $criteria = new CriteriaCompo(new Criteria('gperm_name', $perm));
                $criteria->add(new Criteria('gperm_itemid', intval($obj->getVar('field_id'))));
                $criteria->add(new Criteria('gperm_modid', intval($xoopsModule->getVar('mid'))));
                if (isset($_REQUEST[$perm]) && is_array($_REQUEST[$perm])) {
                    $perms = $groupperm_handler->getObjects($criteria);
                    if (count($perms) > 0) {
                        foreach (array_keys($perms) as $i) {
                            $groups[$perms[$i]->getVar('gperm_groupid')] =& $perms[$i];
                        }
                    } else {
                        $groups = array();
                    }
                    foreach ($_REQUEST[$perm] as $groupid) {
                        $groupid = intval($groupid);
                        if (!isset($groups[$groupid])) {
                            $perm_obj =& $groupperm_handler->create();
                            $perm_obj->setVar('gperm_name', $perm);
                            $perm_obj->setVar('gperm_itemid', intval($obj->getVar('field_id')));
                            $perm_obj->setVar('gperm_modid', $xoopsModule->getVar('mid'));
                            $perm_obj->setVar('gperm_groupid', $groupid);
                            $groupperm_handler->insert($perm_obj);
                            unset($perm_obj);
                        }
                    }
                    $removed_groups = array_diff(array_keys($groups), $_REQUEST[$perm]);
                    if (count($removed_groups) > 0) {
                        $criteria->add(new Criteria('gperm_groupid', "(".implode(',', $removed_groups).")", "IN"));
                        $groupperm_handler->deleteAll($criteria);
                    }
                    unset($groups);

                } else {
                    $groupperm_handler->deleteAll($criteria);
                }
                unset($criteria);
            }
        }	
        $url = $redirect_to_edit ? 'admin_field.php?op=edit&amp;id=' . $obj->getVar('field_id') : 'admin_field.php';
        redirect_header($url, 3, sprintf(_EPROFILE_AM_SAVEDSUCCESS, _EPROFILE_AM_FIELD));
		exit();
    } else {
		redirect_header('admin_field.php', 3, sprintf(_EPROFILE_AM_SAVEDSUCCESS, _EPROFILE_AM_FIELD));
		exit();
	}
    include_once('../include/forms.php');
    echo $obj->getHtmlErrors();
    $form = profile_getFieldForm($obj);
    $form->display();
    break;

case "delete":
	$id = intval($_REQUEST['id']);
    $obj =& $profilefield_handler->get($id);
    if (!$obj->getVar('field_config')) {
        redirect_header('index.php', 2, _EPROFILE_AM_FIELDNOTCONFIGURABLE);
		exit();
    }
    if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            redirect_header('admin_field.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
			exit();
        }
        if ($profilefield_handler->delete($obj)) {
        	redirect_header('admin_field.php', 3, sprintf(_EPROFILE_AM_DELETEDSUCCESS, _EPROFILE_AM_FIELD));
			exit();
        } else {
            echo $obj->getHtmlErrors();
        }
    } else {
        xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_EPROFILE_AM_RUSUREDEL, $obj->getVar('field_title')));
    }
    break;
}

if (isset($template_main)) {
    $xoopsTpl->display("db:{$template_main}");
}
xoops_cp_footer();
?>