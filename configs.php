<?php
// $Id: configs.php,v 1.9 2009/02/01 22:34:08 alfred Exp $
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
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

if ( !is_object($GLOBALS['xoopsUser']) ) {
    redirect_header(XOOPS_URL, 3, _NOPERM);
    exit();
}

$profileconfigs_handler = xoops_getmodulehandler('configs');
$criteria = new Criteria('config_uid',$xoopsUser->uid());
$configs = $profileconfigs_handler->getObjects($criteria);
$config = ($configs) ? $configs[0] : null;

if (!isset($_POST['button'])) {
    $xoopsOption['template_main'] = 'profile_configs.html';
    include $GLOBALS['xoops']->path('header.php');  
    include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'social.php';
    $xoBreadcrumbs[] = array('title' => _PROFILE_MA_CONFIGS);
    $xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $GLOBALS['xoopsUser']->getVar('uname'))." :: "._PROFILE_MA_CONFIGS;        
    $xoopsTpl->assign('user_ownpage', true);
	$xoopsTpl->assign('user_uid', $xoopsUser->uid());
	$xoopsTpl->assign('user_candelete', ($xoopsConfigUser['self_delete'] == 1) ? true:false);
	$xoopsTpl->assign('user_changeemail', $xoopsConfigUser['allow_chgmail']);
	$xoopsTpl->assign('section_name', _PROFILE_MA_CONFIGS);
	
	$xoopsTpl->assign('pgen', 	(($config) ? $config->getVar('profile_general') : 1));
	$xoopsTpl->assign('psta', 	(($config) ? $config->getVar('profile_stats')   : 1));
	$xoopsTpl->assign('scr' , 	(($config) ? $config->getVar('scraps') 			: 0));
	$xoopsTpl->assign('scrnot', (($config) ? $config->getVar('scraps_notify')	: 0));
	$xoopsTpl->assign('sscr', 	(($config) ? $config->getVar('sendscraps')   	: 0)); 
	$xoopsTpl->assign('mail', 	(($config) ? $config->getVar('emails') 			: 0)); 
	$xoopsTpl->assign('fri',  	(($config) ? $config->getVar('friends') 		: 0)); 
	$xoopsTpl->assign('frinot', (($config) ? $config->getVar('friends_notify')	: 0)); 
	$xoopsTpl->assign('pic', 	(($config) ? $config->getVar('pictures')		: 0));
	$xoopsTpl->assign('vid', 	(($config) ? $config->getVar('videos')			: 0));
	$xoopsTpl->assign('aud', 	(($config) ? $config->getVar('audio')			: 0));
	$xoopsTpl->assign('trib', 	(($config) ? $config->getVar('tribes')			: 0));
    $xoopsTpl->assign('mess', 	(($config) ? $config->getVar('profile_messages'): 1));
    $xoopsTpl->assign('fb', 	(($config) ? $config->getVar('profile_facebook'): 0));
	if (!$config) $xoopsTpl->assign('newconfig',_PROFILE_MA_NOCONFIGS);
	include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
} else {
  if (!($GLOBALS['xoopsSecurity']->check())){
	redirect_header('configs.php', 3, _PROFILE_MA_EXPIRED);
  }
  if ($config) {
    $config->unsetNew();
  } else {
    $config = $profileconfigs_handler->create();	
  }
  $config->setVar('config_uid',$xoopsUser->getVar("uid"));
  if (isset($_POST['gen'])) $config->setVar('profile_general',$_POST['gen']);
  if (isset($_POST['stat'])) $config->setVar('profile_stats',$_POST['stat']);
  if (isset($_POST['scraps'])) $config->setVar('scraps',$_POST['scraps']);
  $config->setVar('scraps_notify',(isset($_POST['scrapsnotify'])) ? 1:0);
  if (isset($_POST['sendscraps'])) $config->setVar('sendscraps',$_POST['sendscraps']);
  if (isset($_POST['emails'])) $config->setVar('emails',$_POST['emails']);
  if (isset($_POST['friends'])) $config->setVar('friends',$_POST['friends']);
  $config->setVar('friends_notify',(isset($_POST['friendsnotify'])) ? 1:0);  
  if (isset($_POST['pic'])) $config->setVar('pictures',$_POST['pic']);
  if (isset($_POST['vid'])) $config->setVar('videos',$_POST['vid']);
  if (isset($_POST['aud'])) $config->setVar('audio',$_POST['aud']);
  if (isset($_POST['trib'])) $config->setVar('tribes',$_POST['trib']);
  if (isset($_POST['mess'])) $config->setVar('profile_messages',intval($_POST['mess']));
  if (isset($_POST['fb'])) $config->setVar('profile_facebook',intval($_POST['fb']));
  if (!$profileconfigs_handler->insert($config)) {
     redirect_header("configs.php",3,_PROFILE_MA_DATANOTSENDET);
  }
  redirect_header("configs.php",1,_PROFILE_MA_CONFIGSSAVE);
} 
?>