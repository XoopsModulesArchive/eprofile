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
 * @version         $Id: checker.php 35 2014-02-08 17:37:13Z alfred $
 */

include 'header.php';

if ( !$isAdmin ) {
  redirect_header("index.php", 1, _NOPERM);
  exit();
}

$xoopsOption['template_main'] = 'profile_header.html';
include $GLOBALS['xoops']->path('header.php'); 
$xoopsTpl->assign('section_name', _EPROFILE_MA_USERCHECK);
include_once "include/themeheader.php";

xoops_load("xoopsuserutility");
$online_handler =& xoops_gethandler('online');
$criteria = new Criteria('online_uid',$uid);
$onlineuser = $online_handler->getAll($criteria);
if (count($onlineuser) > 0) {
  $checker_onlineip = $onlineuser[0]['online_ip'];
  $checker_onlinename = $onlineuser[0]['online_uname'];
} else {
  $checker_onlineip = '';
  $checker_onlinename = XoopsUserUtility::getUnameFromId( $uid );
}

echo '<table style="width:99%">';
echo '<tr><th>Datum</th><th>IP-Adresse</th><th>'._EPROFILE_MA_USERCHECK.'</th></tr>';
$class ='odd';
$class = ($class=='odd') ? 'even':'odd';
echo '<tr>';
echo '<td class='.$class.' colspan="3" text-align="center">ONLINE [ USER: '.$checker_onlinename.' ]</td>';
echo '</tr>';
$sum = 0;

if ( $checker_onlineip != '' ) {
  xoops_load("xoopsuserutility");
  $checker_onlinename = XoopsUserUtility::getUnameFromId( $uid );
  unset($onlineuser);

  $criteria = new CriteriaCompo(new Criteria('online_ip',$checker_onlineip));
  $criteria->add(new Criteria('online_uid',$uid,"<>"));
  $onlineuser = $online_handler->getAll($criteria);  
  foreach ($onlineuser as $ouser) {
    $class = ($class=='odd') ? 'even':'odd';
    echo '<tr>';
    echo '<td class='.$class.'>'.formatTimestamp($ouser['online_updated']). '</td>';
    echo '<td class='.$class.'>'.$ouser['online_ip'].'</td>';
    echo '<td class='.$class.'>'. XoopsUserUtility::getUnameFromId( $ouser['online_uid'], false, true )."</td></tr>";
    $sum ++;
  }
}

if ($sum==0) {
  echo '<tr><td class='.$class.' colspan="3">Keine Online User gefunden</td></tr>';
}

$class = ($class=='odd') ? 'even':'odd';
echo '<tr>';
echo '<td class='.$class.' colspan="3"></td>';
echo '</tr>';

if (xoops_isActiveModule('newbb')) {

  echo '<tr>';
  echo '<td class='.$class.' colspan="3" text-align="center">FORUM</td>';
  echo '</tr>';

  $sum = 0;

  $criteria = new CriteriaCompo(new Criteria('uid',$uid));
  $criteria->setSort('post_time');
  $criteria->setOrder('DESC');
  $criteria->setLimit(20);
  $lastpost_handler = xoops_getmoduleHandler('post','newbb');
  $last_user_post = $lastpost_handler->getAll($criteria, 'poster_ip', false, false);
  $last_user_ip = array();
  foreach ($last_user_post as $last_ip) {
    $last_user_ip[] = $last_ip['poster_ip'];
  }
  unset($criteria);
  
  if (count($last_user_ip) > 0) {
    $criteria = new CriteriaCompo();
    foreach ($last_user_ip as $last_ip) {
      $criteria->add(new Criteria('poster_ip',$last_ip),'OR');
    }
    $criteria->setGroupby('uid');
    $criteria->setSort('post_time');
    $criteria->setOrder('DESC');
    $criteria->setLimit(20);
    $last_user = $lastpost_handler->getAll($criteria, '', false, false);
    //print_r($last_user);
    
    foreach ($last_user as $user) {
      if ($user['uid'] == $uid) continue;
      $class = ($class=='odd') ? 'even':'odd';
      echo '<tr>';
      echo '<td class='.$class.'>'.formatTimestamp($user['post_time']). '</td>';
      echo '<td class='.$class.'>'.long2ip($user['poster_ip']).'</td>';
      echo '<td class='.$class.'>'. XoopsUserUtility::getUnameFromId( $user['uid'], false, true )."</td></tr>";
      $sum ++;
    }
  }

  if ($sum==0) {
    echo '<tr><td class='.$class.' colspan="3">Keine Forum User gefunden</td></tr>';
  }
}

$eprofile_version = 'eProfile '.XoopsLocal::number_format(($xoopsModule->getVar('version') / 100));
echo '</table>';
echo '<br style="clear: both;" />
  <div class="profile-footer">
  <br style="clear: both;" />
  <div class="profile-footer">
    '.$eprofile_version.' @ <a href="http://www.simple-xoops.de" target="_blank">SIMPLE-XOOPS</a>
  </div>
</div>
<div style="clear: both;"></div>';
include 'footer.php';
?>