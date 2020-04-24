<?php
// $Id: configs.php 2 2012-08-16 08:20:47Z alfred $
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
include 'header.php';

if ( !$isOwner ) {
  redirect_header(XOOPS_URL, 3, _NOPERM);
  exit();
}

$config = getConfig($uid);

if (!isset($_POST['button'])) {
  $xoopsOption['template_main'] = 'profile_configs.html';
  include $GLOBALS['xoops']->path('header.php');  
  $xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $GLOBALS['xoopsUser']->getVar('uname'))." :: "._EPROFILE_MA_CONFIGS;        
	$xoopsTpl->assign('section_name', _EPROFILE_MA_CONFIGS);	
  include_once "include/themeheader.php";
	$xoopsTpl->assign('permsm',	$xoopsModuleConfig);
	$xoopsTpl->assign('pgen', 	$config['profile_general']);
	$xoopsTpl->assign('pmod', 	$config['user_module']);
	$xoopsTpl->assign('scr' , 	$config['scraps']);
	$xoopsTpl->assign('scrnot', $config['scraps_notify']);
	$xoopsTpl->assign('sscr', 	$config['sendscraps']);
	$xoopsTpl->assign('mail', 	$config['emails']); 
	$xoopsTpl->assign('fri',  	$config['friends']); 
	$xoopsTpl->assign('frinot', $config['friends_notify']); 
	$xoopsTpl->assign('pic', 	  $config['pictures']);
	$xoopsTpl->assign('vid', 	  $config['videos']);
	$xoopsTpl->assign('aud', 	  $config['audio']);
	//$xoopsTpl->assign('trib', 	$config['tribes']);
  $xoopsTpl->assign('mess',   $config['profile_messages']);
	$xoopsTpl->assign('messnot',$config['messages_notify']);
  $xoopsTpl->assign('tribnot',$config['tribes_notify']);
	if (!$config['isConfig']) $xoopsTpl->assign('newconfig',_EPROFILE_MA_NOCONFIGS);
	include 'footer.php';
  exit();
} else {

	if (!($GLOBALS['xoopsSecurity']->check())){
		redirect_header('configs.php', 3, _EPROFILE_MA_EXPIRED);
	}

  if (!$config['isConfig']) {
    $configs = $profileconfigs_handler->create();    
  } else {
    $criteria = new Criteria('config_uid',$uid);
    $configs = $profileconfigs_handler->getObjects($criteria);
    $configs = $configs[0];
  }

	$configs->setVar('config_uid',$uid);
	$configs->setVar('messages_notify',	(isset($_POST['messagenotify'])) 	? 1 : 0);  
	$configs->setVar('scraps_notify',	  (isset($_POST['scrapsnotify'])) 	? 1 : 0);
	$configs->setVar('friends_notify',	(isset($_POST['friendsnotify'])) 	? 1 : 0); 
  $configs->setVar('tribes_notify',	  (isset($_POST['tribesnotify'])) 	? 1 : 0);
	if (isset($_POST['gen'])) 			    $configs->setVar('profile_general',	intval($_POST['gen']));
	if (isset($_POST['pmod'])) 			    $configs->setVar('user_module', 	  intval($_POST['pmod']));
	if (isset($_POST['scraps'])) 		    $configs->setVar('scraps',			    intval($_POST['scraps']));	
	if (isset($_POST['sendscraps'])) 	  $configs->setVar('sendscraps',		  intval($_POST['sendscraps']));
	if (isset($_POST['emails'])) 		    $configs->setVar('emails',			    intval($_POST['emails']));
	if (isset($_POST['friends'])) 		  $configs->setVar('friends',			    intval($_POST['friends']));
	if (isset($_POST['pic'])) 			    $configs->setVar('pictures',			  intval($_POST['pic']));
	if (isset($_POST['vid'])) 			    $configs->setVar('videos',			    intval($_POST['vid']));
	if (isset($_POST['aud'])) 			    $configs->setVar('audio',			      intval($_POST['aud']));
	if (isset($_POST['trib'])) 			    $configs->setVar('tribes',			    intval($_POST['trib']));
	if (isset($_POST['mess'])) 			    $configs->setVar('profile_messages',intval($_POST['mess']));
  
	if (!$profileconfigs_handler->insert($configs)) {
		redirect_header("configs.php",3,_EPROFILE_MA_DATANOTSENDET);
	}
	redirect_header("configs.php",1,_EPROFILE_MA_CONFIGSSAVE);
} 
?>