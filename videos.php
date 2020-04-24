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
 * @since           2.3.3
 * @author          Dirk herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: videos.php 2 2012-08-16 08:20:47Z alfred $
 */

include 'header.php';

if ( !$profile_permission['videos'] ) {
  redirect_header(XOOPS_URL . '/modules/eprofile/userinfo.php?uid='.$uid, 2, _EPROFILE_MA_NOPERM);
}

$xoopsOption['template_main'] = 'profile_videos.html';
include $GLOBALS['xoops']->path('header.php');      

include_once "include/themeheader.php";
$xoopsTpl->assign('section_name', _EPROFILE_MA_VIDEOS);
$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname')) . " :: "._EPROFILE_MA_VIDEOS;

$start = (isset($_GET['start']))? intval($_GET['start']) : 0;
$valid_op_requests = array('add', 'deletevideo', 'editvideo','videoedit','videoadd');
$op = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : '' ;


if ($op=='add' && $isOwner) {
    $vid = $videos_factory->create();
  	video_form();
} elseif ($op=='deletevideo' && $isOwner) {
    $pid = intval($_REQUEST['video_id']);     
  	if(empty($_POST['confirm'])) {
     	$video = $videos_factory->get($pid);	 
      if (is_object($video) && $video->getVar('uid_owner') == $uid && $isOwner) {
		    xoops_confirm(array('uid'=>$uid,'video_id'=> $video->getVar("video_id"),'confirm'=>1,'op'=>'deletevideo'), 'videos.php', _EPROFILE_MA_ASKCONFIRMVIDEODELETION."<br />", _EPROFILE_MA_CONFIRMVIDEODELETION);
	   		include XOOPS_ROOT_PATH.'/footer.php';
	   		exit();
      }  
    } else {
    	$video = $videos_factory->get($pid);
      if (is_object($video) && $video->getVar('uid_owner') == $uid && $isOwner) {
        $videos_factory->delete($video);
        redirect_header('videos.php?uid='.$uid,2,_EPROFILE_MA_VIDEODELETED);
      } else {
        redirect_header('videos.php?uid='.$uid,2,_EPROFILE_MA_VIDEONOTDELETED);
      } 
    	exit();
  	}

} elseif ($op=='editvideo' && $isOwner) {
    $pid = intval($_REQUEST['video_id']);
    if ($pid>0) {
    	$vid = $videos_factory->get($pid);
        if (is_object($vid) && $vid->getVar('uid_owner') == $uid && $isOwner) {
            video_form(true);
        }
  	}
} elseif ($op=='videoedit' && $isOwner) {
    $pid = intval($_POST['video_id']);
    if ($pid>0) {
    	$vid = $videos_factory->get($pid); 
        if (is_object($vid) && $vid->getVar('uid_owner') == $uid && $isOwner) {
            $video_desc = $myts->stripSlashesGPC($_POST['video_desc']); 
            $youtube_code = $myts->stripSlashesGPC($_POST['youtube_code']);
            $youtube_code = str_replace("http://www.youtube.com/watch?v=","",$youtube_code);   
            $vid->setVar('video_desc',$video_desc);
            $vid->setVar('youtube_code',$youtube_code);
            if (!$videos_factory->insert($vid)) 
            {
                $xoopsTpl->assign('err_video', '<div class="errorMsg">'._EPROFILE_MA_DATANOTSENDET.'<br />'.$videos_factory->getHtmlErrors().'</div>');
                video_form();
            } 
            else 
            {
                redirect_header('videos.php?uid='.$uid, 1, _EPROFILE_MA_VIDEOSAVED);
                exit();
            }
        }
    }
} elseif ($op=='videoadd' && $isOwner) {
    $video_desc = $myts->stripSlashesGPC($_POST['video_desc']);  
    $youtube_code = $myts->stripSlashesGPC($_POST['youtube_code']);
    $youtube_code = str_replace("http://www.youtube.com/watch?v=","",$youtube_code);        
    $vid = $videos_factory->create();
    $vid->setVar('uid_owner',$xoopsUser->uid());
  	$vid->setVar('video_desc',$video_desc);
    $vid->setVar('youtube_code',$youtube_code);    
    if (!$videos_factory->insert($vid)) {
	   $xoopsTpl->assign('err_video', '<div class="errorMsg">'._EPROFILE_MA_DATANOTSENDET.'<br />'.$videos_factory->getHtmlErrors().'</div>');
	   video_form();
    } else 	{
        redirect_header('videos.php?uid='.$uid, 1, _EPROFILE_MA_VIDEOSAVED);
        exit();
	}
} else {
    $scountmax = (!empty($xoopsModuleConfig['profile_videos_perpage'])) ? intval($xoopsModuleConfig['profile_videos_perpage']) : 1;
        
    $criteriaUidVideo  = new criteria('uid_owner',$uid);
    $criteriaUidVideo->setOrder('DESC');
    $criteriaUidVideo->setSort('video_id');
    $criteriaUidVideo->setStart($start);
    $criteriaUidVideo->setLimit($scountmax);

    $videos = $videos_factory->getObjects($criteriaUidVideo);
    $videos_array = $videos_factory->assignVideoContent($videos_count,$videos);

    if(is_array($videos_array) && count($videos_array) > 0 ) {
        $xoopsTpl->assign('videos', $videos_array);
    } else {
        $xoopsTpl->assign('lang_novideoyet',_EPROFILE_MA_NOVIDEOSYET);
    }
    $xoopsTpl->assign('width',$xoopsModuleConfig['width_tube']);
    $xoopsTpl->assign('height',$xoopsModuleConfig['height_tube']);
    
    if ($videos_count > $scountmax) 
    {
        include_once XOOPS_ROOT_PATH."/class/pagenav.php";
        $pagenav = new XoopsPageNav($videos_count, $scountmax, $start, "start", "uid=".$uid);
        $xoopsTpl->assign('pageNav',$pagenav->renderImageNav());
    }
    
} 
include 'footer.php';

function video_form($edit=false) 
{
  	$GLOBALS['xoopsTpl']->assign('add_video', 1);
  	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
  	if (!$edit)
    	$form = new XoopsThemeForm(_EPROFILE_MA_ADDVIDEO, 'uploadvideo', XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/videos.php?uid='.$GLOBALS['uid'], 'post', true);
  	else
    	$form = new XoopsThemeForm(_EPROFILE_MA_EDITVIDEO, 'uploadvideo', XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/videos.php?uid='.$GLOBALS['uid'], 'post', true);
  	$form->setExtra('enctype="multipart/form-data"');
  	$form->insertBreak('<script language="JavaScript" type="text/javascript" src="' . XOOPS_URL . '/include/formdhtmltextarea.js"></script>'._EPROFILE_MA_ADDVIDEOSHELP);
  	$form->addElement(new XoopsFormText(_EPROFILE_MA_VIDEOTITLE, "video_desc", 80, 255,$GLOBALS['vid']->getVar('video_desc','n')),true); 
  	$form->addElement(new XoopsFormText(_EPROFILE_MA_YOUTUBECODE."<br />http://www.youtube.com/watch?v=", "youtube_code", 80, 255,$GLOBALS['vid']->getVar('youtube_code','n')),true); 
  	$form->addElement(new XoopsFormHidden('uid', $GLOBALS['xoopsUser']->getVar('uid')));
    if (!$edit) {
      $form->addElement(new XoopsFormHidden('op','videoadd'));
    } else {
      $form->addElement(new XoopsFormHidden('op','videoedit'));
      $form->addElement(new XoopsFormHidden('video_id',$GLOBALS['vid']->getVar('video_id','n')));
    }
  	$submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
  	$form->addElement($submit_button);
  	$form->assign($GLOBALS['xoopsTpl']);  
}
?>