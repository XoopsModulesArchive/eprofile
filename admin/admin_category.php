<?php
// $Id: admin_category.php 35 2014-02-08 17:37:13Z alfred $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
// Author: XOOPS Foundation                                                  //
// URL: http://www.xoops.org/                                                //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //
include 'admin_header.php';
xoops_cp_header();


$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'list';
$id = isset($_REQUEST['id']) ? intval($_REQUEST['id']) : 0;

if ( !in_array( $op,array('list', 'new', 'edit', 'save', 'delete') ) ) $op = "list";

$handler =& xoops_getmodulehandler('category');

switch($op) {
default:
case "list":
  $indexAdmin->addItemButton(_EPROFILE_AM_ADDCATEGORY, 'admin_category.php?op=new', $icon = 'add');
	echo $indexAdmin->renderButton();
	$criteria = new CriteriaCompo();
  $criteria->setSort('cat_weight');
  $criteria->setOrder('ASC');
  $xoopsTpl->assign('categories', $handler->getObjects($criteria, true, false));
	$xoopsTpl->assign('edit_link', XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') . "/admin/admin_category.php?op=edit&amp;id=");
	$xoopsTpl->assign('delete_link', XOOPS_URL . "/modules/". $xoopsModule->getVar('dirname') . "/admin/admin_category.php?op=delete&amp;id=");
	$template_main = "profile_admin_categorylist.html";
  break;

case "new":	
  $obj =& $handler->create();
	$obj->setVar('cat_weight',$handler->getNewId());
  $form = $obj->getForm();
  $form->display();
  break;

case "edit":
	if ($id > 0) {
		$obj = $handler->get($id);
		$form = $obj->getForm();
		$form->display();
	} else {
		redirect_header('admin_category.php', 3, _EPROFILE_AM_FIELDNOTCONFIGURABLE);
		exit();
	}
  break;

case "save":
    if (!$GLOBALS['xoopsSecurity']->check()) {
        redirect_header('category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
    }
    if ($id > 0) {
        $obj =& $handler->get($id);
    } else {
        $obj =& $handler->create();
    }
    $obj->setVar('cat_title', 		$myts->previewTarea($_REQUEST['cat_title'],0,0,0,0,0));
    $obj->setVar('cat_description', $myts->previewTarea($_REQUEST['cat_description'],0,0,0,0,0));
    $obj->setVar('cat_weight', 		intval($_REQUEST['cat_weight']));
    if ($handler->insert($obj)) {
        redirect_header('admin_category.php', 3, sprintf(_EPROFILE_AM_SAVEDSUCCESS, _EPROFILE_AM_CATEGORY));
		exit();
    }
    include_once '../include/forms.php';
    echo $obj->getHtmlErrors();
    $form =& $obj->getForm();
    $form->display();
    break;

case "delete":
	if ($id > 0) {
		$obj =& $handler->get($id);
		if (isset($_REQUEST['ok']) && $_REQUEST['ok'] == 1) {
			if (!$GLOBALS['xoopsSecurity']->check()) {
				redirect_header('admin_category.php', 3, implode(',', $GLOBALS['xoopsSecurity']->getErrors()));
				exit();
			}
			if ($handler->delete($obj)) {
				redirect_header('admin_category.php', 3, sprintf(_EPROFILE_AM_DELETEDSUCCESS, _EPROFILE_AM_CATEGORY));
				exit();
			} else {
				echo $obj->getHtmlErrors();
			}
        } else {
			xoops_confirm(array('ok' => 1, 'id' => $_REQUEST['id'], 'op' => 'delete'), $_SERVER['REQUEST_URI'], sprintf(_EPROFILE_AM_RUSUREDEL, $obj->getVar('cat_title')));
		}
  }
  break;
}

if (isset($template_main)) {
    $xoopsTpl->display("db:{$template_main}");
}
xoops_cp_footer();
?>