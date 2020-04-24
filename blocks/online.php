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
 * @version         $Id: online.php 49 2014-05-31 22:58:46Z alfred $
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

function eprofile_neigthbar_edit($options) {
  $profile_handler =& xoops_getmodulehandler('profile','eprofile');
	$fields = $profile_handler->loadFields();
  $form = _EPROFILE_MI_BLOCK_NEIGTHBAR . "&nbsp;<br />";  
  $form .= _EPROFILE_MI_BLOCK_NEIGTHBAR_SIZE . "&nbsp;";
  $form .= "<select name='options[0]' size='1'>";
  $chk = ( $options[0] == 5 ) ? " selected" : "";
  $form .= "<option value='5'".$chk." />5 km</option>";
  $chk = ( $options[0] == 15 ) ? " selected" : "";
  $form .= "<option value='10'".$chk." />15 km</option>";
  $chk = ( $options[0] == 30 ) ? " selected" : "";
  $form .= "<option value='30'".$chk." />30 km</option>";
  //$chk = ( $options[0] == 50 ) ? " selected" : "";
  //$form .= "<option value='50'".$chk." />50 km</option>";
  //$chk = ( $options[0] == 100 ) ? " selected" : "";
  //$form .= "<option value='100'".$chk." />100 km</option>";
  //$chk = ( $options[0] == 160 ) ? " selected" : "";
  //$form .= "<option value='160'".$chk." />160 km</option>";
  $form .="</select><br />";
  
  $form .= _EPROFILE_MI_BLOCK_NEIGTHBAR_COUNTRY . "&nbsp;";  
  $form .= "<select name='options[1]' size='1'>";
  $form .= "<option value='"._NONE.$chk."' >"._NONE."</option>";	
  foreach (array_keys($fields) as $i) {
    $chk  = ( $options[1] == $fields[$i]->getVar('field_name') ) ? " selected" : "";
    $form .= "<option value='".$fields[$i]->getVar('field_name')."'".$chk." />".$fields[$i]->getVar('field_title')."</option>";
  }
  $form .="</select><br />";
  
  $form .= _EPROFILE_MI_BLOCK_NEIGTHBAR_PLZ . "&nbsp;";  
  $form .= "<select name='options[2]' size='1'>";
  $form .= "<option value='"._NONE.$chk."' >"._NONE."</option>";	
  foreach (array_keys($fields) as $i) {
    $chk  = ( $options[2] == $fields[$i]->getVar('field_name') ) ? " selected" : "";
    $form .= "<option value='".$fields[$i]->getVar('field_name')."'".$chk.">".$fields[$i]->getVar('field_title')."</option>";
  }
  $form .="</select><br />";
  
  $form .= _EPROFILE_MI_BLOCK_MCOUNT."&nbsp;";
  $form .= "<input type='text' name='options[3]' value='".$options[3]."' /><br />";
  
  $form .= _EPROFILE_MI_BLOCK_APIUSER."&nbsp;";
  $form .= "<input type='text' name='options[4]' value='".$options[4]."' /><br />";
  
  return $form;
}

function eprofile_neigthbar_show($options) {
  global $xoopsUser, $xoopsModule;  
  xoops_load('XoopsUserUtility');
  if (!$xoopsUser) return false;
  $profile_handler = xoops_getmodulehandler('profile','eprofile');
	$profile = $profile_handler->get($xoopsUser->uid());
  $block = array();
  $users = array();
  
  $user_field1 = $profile->getVar($options[1]);
  $user_field2 = $profile->getVar($options[2]);  
 
  $url = "http://api.geonames.org/findNearbyPostalCodes?postalcode=" . $user_field2 . "&country=" . $user_field1 . "&radius=" . $options[0] . "&username=" . $options[4];
  //echo $url;
  //die();
  if ($xml = simplexml_load_file($url)) {       
      $counter = 0;  
      $_plz = array();
      $_user = array();
      $_ort = array();
      $_users = array();
      if ($xoopsUser) $_user[] = $xoopsUser->uid();
      $_user_data = array();
      foreach ($xml as $_data) {             
        $_data = (array)$_data;        
        $_items = array(); 
        $_items['status'] = (@$_data['@attributes']) ? XoopsLocal::convert_encoding($_data['@attributes']['message'], _CHARSET, 'UTF-8') : '';
        if ($_items['status'] == '') { 
          $_items['plz']  = XoopsLocal::convert_encoding($_data['postalcode'], _CHARSET, 'UTF-8');          
          $_items['ort']  = XoopsLocal::convert_encoding($_data['name'], _CHARSET, 'UTF-8');
          if (!in_array($_items['plz'],$_plz) && !in_array($_items['ort'],$_ort)) {   
            $_plz[] = $_items['plz'];
            $_ort[] = $_items['ort'];
            $counter++;              
          }         
        } else {
          $users['status'] = $_items['status'];
        }
        unset($_items);
      } 
      if ($counter > 0) {
        $criteria = new CriteriaCompo(new Criteria($options[1],$user_field1));
        $_plz_str = implode(",", $_plz);
        $criteria2 = new CriteriaCompo(new Criteria($options[2],'(' . $_plz_str . ')', 'IN'));
        $criteria->add($criteria2);        
        $criteria->setLimit(intval($options[3]));        
        list($neights, $profiles, $total_users) = $profile_handler->search($criteria, array('uid', $options[1], $options[2])); 
        $member_handler =& xoops_gethandler('member');
        $profile_handler = xoops_getmodulehandler('profile','eprofile');
        if ($total_users > 0) {
          foreach ($neights as $neight) {
            if (!in_array($neight->getVar('uid'), $_user)) {            
              $_user[] = $neight->getVar('uid');
              $profile = $profile_handler->get($neight->getVar('uid'));
              $_users['user'] = XoopsUserUtility::getUnameFromId($neight->getVar('uid'),false,true);
              $_users['plz']  = $profile->getVar($options[2]);
              //$_users['ort']  = $_items['ort'];
              $_user_data[]   = $_users;
              unset($_users);
              unset($profile);
            }
          }              
        }  
        if (count($_user_data) > 0) {  
          $users['user']  = $_user_data;
          $users['count'] = $counter; 
        } else {
          $users['count']  = 0;
          $users['status'] = _EPROFILE_MI_BLOCK_ERRORAPI;
        }               
      } else {
        $users['count']  = 0;
      }
  } else {
      $users['count']  = 0;
      $users['status'] = _EPROFILE_MI_BLOCK_ERRORAPI;      
  } 
  //print_r($users);
  $block['neigthbar'] = $users;
  return $block;
}

?>