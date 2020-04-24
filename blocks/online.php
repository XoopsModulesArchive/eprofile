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
 * @author          Dirk Herrmann
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: online.php 31 2014-02-08 10:06:07Z alfred $
 */
 
xoops_loadLanguage("modinfo","eprofile");

function eprofile_online_edit($options) {
    $form = _EPROFILE_MI_BLOCK_AVATAR . "&nbsp;";
    if ( $options[0] == 1 ) {
      $chk  = " checked='checked'";
      $chk1 = "";
    } else {
      $chk1 = " checked='checked'";
      $chk  = "";
    }
    $form .= "<input type='radio' name ='options[0]' value='1'".$chk." />&nbsp;"._YES."";	
    $form .= "&nbsp;<input type='radio' name ='options[0]' value='0'".$chk1." />"._NO."<br />";
    
    $form .= _EPROFILE_MI_BLOCK_MNAME."&nbsp"; 
    if ( $options[1] == 1 ) {
      $chk  = " checked='checked'";
      $chk1 = "";
    } else {
      $chk1 = " checked='checked'";
      $chk  = "";
    }
    $form .= "<input type='radio' name ='options[1]' value='1'".$chk." />&nbsp;"._YES."";	
    $form .= "&nbsp;<input type='radio' name ='options[1]' value='0'".$chk1." />"._NO."<br />";
      
    $form .= _EPROFILE_MI_BLOCK_MCOUNT."&nbsp;";
    $form .= "<input type='text' name='options[2]' value='".$options[2]."' /><br />";    
    
    return $form;
}

function eprofile_online_show($options)
{
    global $xoopsUser, $xoopsModule;
    $online_handler =& xoops_gethandler('online');
    $member_handler =& xoops_gethandler('member');
    xoops_load('XoopsUserUtility');
    mt_srand((double)microtime()*1000000);
    // set gc probabillity to 10% for now..
    if (mt_rand(1, 100) < 11) {
        $online_handler->gc(100);
    }
    if (is_object($xoopsUser)) {
      $uid = $xoopsUser->getVar('uid');
      $uname = $xoopsUser->getVar('uname');
    } else {
      $uid = 0;
      $uname = '';
    }
    if (is_object($xoopsModule)) {
      $online_handler->write($uid, $uname, time(), $xoopsModule->getVar('mid'), XoopsUserUtility::getIP(true));
    } else {
      $online_handler->write($uid, $uname, time(), 0, XoopsUserUtility::getIP(true));
    }
    $total = $online_handler->getCount();
    
    if ($total > 0) {
      $onlines = $online_handler->getAll();    
      $block = array();
      $guests = 0;
      $zaehler = ($options[2] > $total) ? $total : $options[2]; 
      $user = array();
      $uname = intval($options[1]); 
      for ($i = 0; $i < $zaehler; $i++) {              
        if ($onlines[$i]['online_uid'] > 0) {
          $users = array();          
          $profil = $member_handler->getUser($onlines[$i]['online_uid']);
          if ( $options[0] == 1 ) {
            $users['avatar'] = XOOPS_UPLOAD_URL . "/" . $profil->getVar('user_avatar');
          } else {
            $users['avatar'] = $users['avatar'] = XOOPS_UPLOAD_URL . "/blank.gif";
          }          
          $users['link'] = XoopsUserUtility::getUnameFromId($profil->getVar('uid'),$uname,true);
          $user[] = $users;
          unset($users);
          unset($profil);
        } else {
          $guests++;
        }
      }
      $block['online_total'] = sprintf(_ONLINEPHRASE, $total);
      if (is_object($xoopsModule)) {
        $mytotal = $online_handler->getCount(new Criteria('online_module', $xoopsModule->getVar('mid')));
        $block['online_total'] .= ' ('.sprintf(_ONLINEPHRASEX, $mytotal, $xoopsModule->getVar('name')).')';
      }
      $block['lang_members'] = _MEMBERS;
      $block['lang_guests'] = _GUESTS;
      $block['online_names'] = $user;
      $block['online_members'] = $total - $guests;
      $block['online_guests'] = $guests;
      $block['lang_more'] = _MORE;  
      return $block;
    } else {
      return false;
    }
}

function eprofile_popular_edit($options) {
  $form = _EPROFILE_MI_BLOCK_AVATAR . "&nbsp;";
    if ( $options[0] == 1 ) {
      $chk  = " checked='checked'";
      $chk1 = "";
    } else {
      $chk1 = " checked='checked'";
      $chk  = "";
    }
    $form .= "<input type='radio' name ='options[0]' value='1'".$chk." />&nbsp;"._YES."";	
    $form .= "&nbsp;<input type='radio' name ='options[0]' value='0'".$chk1." />"._NO."<br />";
    
    $form .= _EPROFILE_MI_BLOCK_MNAME."&nbsp"; 
    if ( $options[1] == 1 ) {
      $chk  = " checked='checked'";
      $chk1 = "";
    } else {
      $chk1 = " checked='checked'";
      $chk  = "";
    }
    $form .= "<input type='radio' name ='options[1]' value='1'".$chk." />&nbsp;"._YES."";	
    $form .= "&nbsp;<input type='radio' name ='options[1]' value='0'".$chk1." />"._NO."<br />";
      
    $form .= _EPROFILE_MI_BLOCK_MCOUNT."&nbsp;";
    $form .= "<input type='text' name='options[2]' value='".$options[2]."' /><br />";  
    
    return $form;
}

function eprofile_popular_show($options)
{
    global $xoopsUser, $xoopsModule;
    $member_handler =& xoops_gethandler('member');
    
    $criteria = new Criteria('posts', "0", ">");
    $criteria->setLimit($options[2]);  
    $criteria->setOrder('DESC');
    $criteria->setSort('posts');
    
    $members = $member_handler->getUsers($criteria);
    xoops_load('XoopsUserUtility');    
        
    $block = array();
    $block['avatar'] = $options[0];
    $user = array();
    foreach ($members as $member) {
      $users = array();  
      if ( $member->getVar('user_avatar') == '' || $member->getVar('user_avatar') == 'blank.gif' ) {
        $users['avatar'] = XOOPS_UPLOAD_URL . "/avatars/blank.gif";
      } else {
        $users['avatar'] = XOOPS_UPLOAD_URL . "/" . $member->getVar('user_avatar');
      }
      $users['link'] = XoopsUserUtility::getUnameFromId($member->getVar('uid'),$options[1],true);
      $users['count'] = $member->getVar('posts');
      $user[] = $users;
      unset($users);
    }
    $block['count_name'] = _EPROFILE_MI_BLOCK_COUNTER;
    $block['users'] = $user;       
    return $block;
}

?>