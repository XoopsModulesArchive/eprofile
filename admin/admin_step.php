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
 * @version         $Id: admin_step.php 28 2013-09-06 21:58:22Z alfred $
 */
include 'admin_header.php';
xoops_cp_header();

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;
if ( !in_array( $op,array('list', 'new', 'edit', 'save', 'delete') ) ) $op = "list";

$handler =& xoops_getmodulehandler('regstep');
switch ($op) {
    case "list":
		$indexAdmin->addItemButton(_EPROFILE_AM_ADDREGISTRY, 'admin_step.php?op=new', $icon = 'add');
		echo $indexAdmin->renderButton();
        $xoopsTpl->assign('steps', $handler->getObjects(null, true, false));
		$xoopsTpl->assign('edit_link', XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') . "/admin/admin_step.php?op=edit&amp;id=");
		$xoopsTpl->assign('delete_link', XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') . "/admin/admin_step.php?op=delete&amp;id=");
		$xoopsTpl->assign('yes_pic', XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') . "/images/yes.png");
		$xoopsTpl->assign('no_pic', XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') . "/images/no.png");
		$template_main = "profile_admin_steplist.html";
        break;

    case "new":
        $obj =& $handler->create();
        include_once "../include/forms.php";
        $form = profile_getStepForm($obj);;
        $form->display();
        break;

    case "edit":
        $obj =& $handler->get($id);
        include_once "../include/forms.php";
        $form = profile_getStepForm($obj);
        $form->display();
        break;

    case "save":
        if (isset($_REQUEST['id'])) {
            $obj =& $handler->get($_REQUEST['id']);
        } else {
            $obj =& $handler->create();
        }
        $obj->setVar('step_name', $_REQUEST['step_name']);
        $obj->setVar('step_order', $_REQUEST['step_order']);
        $obj->setVar('step_desc', $_REQUEST['step_desc']);
        $obj->setVar('step_save', $_REQUEST['step_save']);
        if ($handler->insert($obj)) {
            redirect_header('admin_step.php', 3, sprintf(_EPROFILE_AM_SAVEDSUCCESS, _EPROFILE_AM_STEP));
			exit();
        }
        echo $obj->getHtmlErrors();
        $form = $obj->getForm();
        $form->display();
        break;

    case "delete":
        $obj =& $handler->get($_REQUEST['id']);
        if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
            if ($handler->delete($obj)) {
                redirect_header('admin_step.php', 3, sprintf(_EPROFILE_AM_DELETEDSUCCESS, _EPROFILE_AM_STEP));
				exit();
            } else {
                echo $obj->getHtmlErrors();
            }
        } else {
            xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_EPROFILE_AM_RUSUREDEL, $obj->getVar('step_name')));
        }
        break;
}

if (!empty($template_main)) {
    $xoopsTpl->display("db:{$template_main}");
}

xoops_cp_footer();
?>