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
 * @version         $Id$
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

$xoopsOption['template_main'] = 'profile_videos.html';
include $GLOBALS['xoops']->path('header.php');      
$video_handler = xoops_getmodulehandler('video');
$xoopsTpl->assign('section_name', _PROFILE_MA_VIDEOS);
include_once dirname(__FILE__) . DIRECTORY_SEPARATOR . 'social.php';
$xoBreadcrumbs[] = array('title' => _PROFILE_MA_VIDEOS);

if (empty($allow_videos) || empty($uid) )
{
  redirect_header('userinfo.php?uid='.$uid,2,_PROFILE_MA_NOPERM);
  exit();
}

$start = (isset($_GET['start']))? intval($_GET['start']) : 0;
$valid_op_requests = array('add', 'deletevideo', 'editvideo','videoedit','videoadd');
$op = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : '' ;


if ($op=='add') {
    $vid = $video_handler->create();
  	video_form();
} elseif ($op=='deletevideo') {
    $pid = intval($_REQUEST['video_id']);     
  	if(empty($_POST['confirm'])) {
     	$video = $video_handler->get($pid);	 
	 	if (is_object($video) && $video->getVar('uid_owner') == $uid && $isOwner) {
		    xoops_confirm(array('uid'=>$uid,'video_id'=> $video->getVar("video_id"),'confirm'=>1,'op'=>'deletevideo'), 'videos.php', _PROFILE_MA_ASKCONFIRMVIDEODELETION."<br />", _PROFILE_MA_CONFIRMVIDEODELETION);
	   		include XOOPS_ROOT_PATH.'/footer.php';
	   		exit();
	 	}  
  	} else {
    	$video = $video_handler->get($pid);
        if (is_object($video) && $video->getVar('uid_owner') == $uid && $isOwner) {
            $video_handler->delete($video);
            redirect_header('videos.php?uid='.$uid,2,_PROFILE_MA_VIDEODELETED);
        } else {
            redirect_header('videos.php?uid='.$uid,2,_PROFILE_MA_VIDEONOTDELETED);
        } 
    	exit();
  	}

} elseif ($op=='editvideo') {
    $pid = intval($_REQUEST['video_id']);
    if ($pid>0) {
    	$vid = $video_handler->get($pid);
        if (is_object($vid) && $vid->getVar('uid_owner') == $uid && $isOwner) {
            video_form(true);
        }
  	}
} elseif ($op=='videoedit') {
    $pid = intval($_POST['video_id']);
    if ($pid>0) {
    	$vid = $video_handler->get($pid); 
        if (is_object($vid) && $vid->getVar('uid_owner') == $uid && $isOwner) {
            $video_desc = $myts->stripSlashesGPC($_POST['video_desc']); 
            $youtube_code = $myts->stripSlashesGPC($_POST['youtube_code']);
            $vid->setVar('video_desc',$video_desc);
            $vid->setVar('youtube_code',$youtube_code);
            if (!$video_handler->insert($vid)) 
            {
                $xoopsTpl->assign('err_video', '<div class="errorMsg">'._PROFILE_MA_DATANOTSENDET.'<br />'.$video_handler->getHtmlErrors().'</div>');
                video_form();
            } 
            else 
            {
                redirect_header('videos.php?uid='.$uid, 1, _PROFILE_MA_VIDEOSAVED);
                exit();
            }
        }
    }
} elseif ($op=='videoadd') {
    $video_desc = $myts->stripSlashesGPC($_POST['video_desc']);  
    $youtube_code = $myts->stripSlashesGPC($_POST['youtube_code']);
    $vid = $video_handler->create();
    $vid->setVar('uid_owner',$xoopsUser->uid());
  	$vid->setVar('video_desc',$video_desc);
    $vid->setVar('youtube_code',$youtube_code);
    if (!$video_handler->insert($vid)) 
	{
	   $xoopsTpl->assign('err_video', '<div class="errorMsg">'._PROFILE_MA_DATANOTSENDET.'<br />'.$video_handler->getHtmlErrors().'</div>');
	   video_form();
    } 
	else 
	{
        redirect_header('videos.php?uid='.$uid, 1, _PROFILE_MA_VIDEOSAVED);
        exit();
	}
} else {
    $scountmax = (!empty($xoopsModuleConfig['profile_videos_perpage'])) ? intval($xoopsModuleConfig['profile_videos_perpage']) : 1;
        
    $criteriaUidVideo  = new criteria('uid_owner',$uid);
    $criteriaUidVideo->setOrder('DESC');
    $criteriaUidVideo->setSort('video_id');
    $criteriaUidVideo->setStart($start);
    $criteriaUidVideo->setLimit($scountmax);

    $videos = $video_handler->getObjects($criteriaUidVideo);
    $videos_array = $video_handler->assignVideoContent($videos_count,$videos);

    if(is_array($videos_array) && count($videos_array) > 0 ) {
        $xoopsTpl->assign('videos', $videos_array);
    } else {
        $xoopsTpl->assign('lang_novideoyet',_PROFILE_MA_NOVIDEOSYET);
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
    	$form = new XoopsThemeForm(_PROFILE_MA_ADDVIDEO, 'uploadvideo', XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/videos.php?uid='.$GLOBALS['uid'], 'post', true);
  	else
    	$form = new XoopsThemeForm(_PROFILE_MA_EDITVIDEO, 'uploadvideo', XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/videos.php?uid='.$GLOBALS['uid'], 'post', true);
  	$form->setExtra('enctype="multipart/form-data"');
  	$form->insertBreak('<script language="JavaScript" type="text/javascript" src="' . XOOPS_URL . '/include/formdhtmltextarea.js"></script>'._PROFILE_MA_ADDVIDEOSHELP);
  	$form->addElement(new XoopsFormText(_PROFILE_MA_VIDEOTITLE, "video_desc", 80, 255,$GLOBALS['vid']->getVar('video_desc','n')),true); 
  	$form->addElement(new XoopsFormText(_PROFILE_MA_YOUTUBECODE."<br />http://www.youtube.com/watch?v=", "youtube_code", 80, 255,$GLOBALS['vid']->getVar('youtube_code','n')),true); 
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