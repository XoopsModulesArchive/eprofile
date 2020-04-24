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
 * @version         $Id: index.php 2020 2008-08-31 01:54:14Z phppp $
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

if ( !$xoopsUser || !$xoopsUser->isadmin()) {
  redirect_header("index.php", 1, _NOPERM);
  exit();
}

$xoopsOption['template_main'] = 'profile_breadcrumbs.html';
include $GLOBALS['xoops']->path('header.php'); 
$xoopsTpl->assign('section_name', _PROFILE_MA_USERCHECK);
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'social.php';
$xoBreadcrumbs[] = array('title' => _PROFILE_MA_USERCHECK);

$online_handler =& xoops_gethandler('online');
$criteria = new Criteria('online_uid',$uid);
$onlineuser = $online_handler->getAll($criteria);
if (count($onlineuser) > 0) {
  $checker_onlineip = $onlineuser[0]['online_ip'];
} else {
  $checker_onlineip = '';
}

echo '<table style="width:99%">';
echo '<tr><th>Datum</th><th>IP-Adresse</th><th>'._PROFILE_MA_USERCHECK.'</th></tr>';
$class ='odd';
$sql = "SELECT post_time,poster_ip FROM ".$xoopsDB->prefix('bb_posts')." WHERE uid='".$uid."' GROUP BY poster_ip ORDER BY post_time DESC LIMIT 0,20";
$res = $xoopsDB->query($sql);
$sum=0;
if ($res) {
  while ($row = $xoopsDB->fetcharray($res)) {
     $sql = "SELECT post_time,uid,poster_name  FROM ".$xoopsDB->prefix('bb_posts')." WHERE poster_ip='".$row['poster_ip']."' AND uid!=".$uid." GROUP BY uid LIMIT 0,20";
	 $res1 = $xoopsDB->query($sql);
     $wuid=array();
	 while ($row1 = $xoopsDB->fetcharray($res1)) {
	    if (intval($row1['uid']) > 0) {
            $nu = new XoopsUser($row1['uid']);
            if ($nu && $nu->isActive())
                $wuid[] = '<a href="userinfo.php?uid='.$nu->uid().'">'.$nu->uname().'</a>';
            else
                $wuid[]=$row1['poster_name'];
            unset($nu);
		} else {
            $wuid[]=$row1['poster_name'];
		}
	 }
	 if (count($wuid)>0) {
	   $class = ($class=='odd') ? 'even':'odd';
	   echo '<tr>';
       echo '<td class='.$class.'>'.formatTimestamp($row['post_time']). '</td>';
	   echo '<td class='.$class.'>'.long2ip($row['poster_ip']).'</td>';
	   echo '<td class='.$class.'>'. implode(", ",$wuid)."</td></tr>";
	   $sum ++;
	 }
  }
}
if ($sum==0) {
  echo '<tr><td class='.$class.' colspan="3">Keine weiteren User gefunden</td></tr>';
}
echo '</table>';
echo '<br style="clear: both;" />
  <div class="profile-footer">';
echo $xoopsTpl->get_template_vars('eprofile_version');
echo ' @ <a href="http://www.myxoops.org" target="_blank">myXOOPS.org</a>
  </div>
</div>
<div style="clear: both;"></div>';
include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
?>