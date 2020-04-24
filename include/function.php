<?php
// $Id:$
//  ------------------------------------------------------------------------ //
//                SIMPLE-XOOPS - PHP Content Management System               //
//                    Copyright (c) 2014 www.simple-xoops.de                 //
//                       <http://www.simple-xoops.de/>                       //
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

function getConfig($uid=null) 
{
  global $xoopsModuleConfig;
  $profileconfigs_handler = xoops_getmodulehandler('configs');
  $criteria = new Criteria('config_uid',$uid);
  $configs = $profileconfigs_handler->getObjects($criteria);
  $config = ($configs) ? $configs[0] : null;
  if (!$config) {
    $config = array();
    $config['profile_general']  = $xoopsModuleConfig['profile_search'];
    $config['profile_stats']    = $xoopsModuleConfig['profile_stats'];
    $config['profile_messages'] = $xoopsModuleConfig['profile_messages'];
    $config['scraps']           = $xoopsModuleConfig['profile_scraps'];
    $config['emails']           = $xoopsModuleConfig['profile_emails'];
    $config['friends']          = $xoopsModuleConfig['profile_friends'];
    $config['pictures']         = $xoopsModuleConfig['profile_pictures'];
    $config['audio']            = $xoopsModuleConfig['profile_audio'];
    //$config['tribes']           = $xoopsModuleConfig['profile_tribes'];
    $config['videos']           = $xoopsModuleConfig['profile_videos'];
    $config['scraps_notify']    = true;
    $config['sendscraps']       = true;
    $config['friends_notify']   = true;
    $config['messages_notify']  = true;
    $config['tribes_notify']    = true;
    $config['isConfig']         = false;
  } else {
    $config = $config->toArray();
    $config['isConfig'] = true;
  }  
  return $config;
}