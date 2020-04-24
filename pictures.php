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
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: pictures.php 2 2012-08-16 08:20:47Z alfred $
 */
 
include 'header.php';

if ( !$profile_permission['pictures'] || $uid <= 0 ) 
{
  redirect_header(XOOPS_URL . '/modules/eprofile/userinfo.php?uid='.$uid, 2, _EPROFILE_MA_NOPERM);
  exit();
}
xoops_loadLanguage('modinfo', 'eprofile');
$valid_op_requests = array('add', 'picedit', 'picdelete','picprivate','picavatar','pictureedit','pictureupload');
$op = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : '' ;
$start = !empty($_GET['start']) ? intval($_GET['start']) : 0;

$xoopsOption['template_main'] = 'profile_pictures.html';
include $GLOBALS['xoops']->path('header.php');  
include_once "include/themeheader.php";
$xoopsTpl->assign('section_name', _EPROFILE_MI_EPROFILE_PICTURESTITLE);
$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname')) . " :: "._EPROFILE_MI_EPROFILE_PICTURESTITLE;
$xoTheme->addStylesheet(XOOPS_URL . "/modules/eprofile/js/lightbox.css");
$xoTheme->addScript(XOOPS_URL . '/modules/eprofile/js/jquery-1.7.2.min.js');
$xoTheme->addScript(XOOPS_URL . '/modules/eprofile/js/lightbox.js');
    
$pic = $pictures_factory->create();

if ($op == 'add' && $isOwner) {
  	pic_form();
} elseif ($op == 'picedit' && $isOwner) {
  	$pid = intval($_REQUEST['cod_img']);
  	if ($pid > 0) {
    	$pic = $pictures_factory->get($pid);
      if (is_object($pic) && $pic->getVar('pic_uid') == $uid) {
        pic_form(true);
      } 
  	}
} elseif ($op == 'picdelete'  && $isOwner) {
  	$pid = intval($_REQUEST['cod_img']);     
  	if(empty($_POST['confirm'])) {
     	$picture = $pictures_factory->get($pid);	 
      if (is_object($picture) && $picture->getVar('pic_uid') == $uid) {
		    $pict = '<img src="'.$picture->thumb_url.'/'.$picture->getVar('pic_url').'" alt="'.$picture->getVar("pic_title").'" />';
	   		xoops_confirm(array('uid'=>$uid,'cod_img'=> $picture->getVar("pic_id"),'confirm'=>1,'op'=>'picdelete'), 'pictures.php', $pict."<br /><br />"._EPROFILE_MA_ASKCONFIRMPICTURESDELETION."<br />", _EPROFILE_MA_CONFIRMPICTURESDELETION);
	   		include XOOPS_ROOT_PATH.'/footer.php';
	   		exit();
      }  
  	} else {
    	$picture = $pictures_factory->get($pid);
      $pictures_factory->delete($picture);
      $uploadDir = XOOPS_UPLOAD_PATH."/".$xoopsModule->dirname()."/";
      @unlink($picture->upload_path."/".$picture->getVar('pic_url'));
      @unlink($picture->thumb_path."/".$picture->getVar('pic_url'));
      redirect_header('pictures.php?uid='.$uid,2,_EPROFILE_MA_DATASENDET);
    	exit();
  	}
} elseif ($op == 'picprivate'  && $isOwner) {
  	$pid = intval($_REQUEST['cod_img']);
  	$private = intval($_REQUEST['private']);
  	$picture = $pictures_factory->get($pid);
  	if (is_object($picture) && $picture->getVar('pic_uid') == $uid) 
    {
     	$picture->setVar('private',$private);
      if (!$pictures_factory->insert($picture)) 
      {
        redirect_header('pictures.php?uid='.$uid,1,_EPROFILE_MA_DATANOTSENDET);
      } else {
        redirect_header('pictures.php?uid='.$uid,1,_EPROFILE_MA_DATASENDET);
      }
      exit();
  	}
} elseif ($op == 'picavatar' && $isOwner) {
  	$is_allowed = false;
  	if ($isOwner && $xoopsConfigUser['avatar_allow_upload']) {
     	if ($xoopsConfigUser['avatar_minposts'] > 0) {
	   		if ($xoopsConfigUser['avatar_minposts'] <= $xoopsUser->getVar('posts')) {
	     		$is_allowed = true;
	   		} 
      } else {
	   		$is_allowed = true;
      } 
    }
  	$pid = intval($_REQUEST['cod_img']); 
  	if ($is_allowed && $pid>0) {
    	$picture = $pictures_factory->get($pid);
      if (is_object($picture)  && $picture->getVar('pic_uid') == $uid && $isOwner) {
        if(empty($_POST['confirm'])) {
          xoops_confirm(array('uid'=>$uid,'cod_img'=> $picture->getVar("pic_id"),'confirm'=>1,'op'=>'picavatar'), 'pictures.php', _EPROFILE_MA_ASKCONFIRMPICTURESASAVATAR."<br />", _YES);
          include 'footer.php';
          exit();
        } else { 
          $av = $picture->upload_path."/".$picture->getVar('pic_url');
          if (is_readable($av)) {	   					
            if ($picture->resizeImg($av,$xoopsConfigUser['avatar_width'],$xoopsConfigUser['avatar_height'],XOOPS_UPLOAD_PATH . "/avatars")) {
              $size = getimagesize($picture->upload_path."/".$picture->getVar('pic_url'));
              if ($size[2]==0) $size = "image/gif";
              elseif ($size[2]==3) $size = "image/png";
              else $size = "image/jpeg";
              $avt_handler =& xoops_gethandler('avatar');
              $avatar =& $avt_handler->create();                        
              $avatar->setVar('avatar_file', 'avatars/' . $picture->getVar('pic_url'));
              $avatar->setVar('avatar_name', $xoopsUser->getVar('uname'));
              $avatar->setVar('avatar_mimetype', $size);
              $avatar->setVar('avatar_display', 1);
              $avatar->setVar('avatar_type', 'C');
              if (!$avt_handler->insert($avatar)) {						
                @unlink(XOOPS_UPLOAD_PATH."/".$picture->getVar('pic_url'));
              } else {						
                $oldavatar = $xoopsUser->getVar('user_avatar');	
                if (!empty($oldavatar) && preg_match("/^cavt/", strtolower($oldavatar))) {							
                  $avatars = $avt_handler->getObjects(new Criteria('avatar_file', $oldavatar));								
                  if (!empty($avatars) && count($avatars) == 1 && is_object($avatars[0])) {
                    $avt_handler->delete($avatars[0]);
                    $oldavatar_path = str_replace("\\", "/", realpath(XOOPS_UPLOAD_PATH.'/'.$oldavatar));
                    if (0 === strpos($oldavatar_path, XOOPS_UPLOAD_PATH) && is_file($oldavatar_path)) {
                      unlink($oldavatar_path);
                    }
                  }
                }
                $sql = sprintf("UPDATE %s SET user_avatar = %s WHERE uid = %u", $xoopsDB->prefix('users'), $xoopsDB->quoteString('avatars/' . $picture->getVar('pic_url')), $xoopsUser->getVar('uid'));
                $xoopsDB->query($sql);
                $avt_handler->addUser($avatar->getVar('avatar_id'), $xoopsUser->getVar('uid'));
                redirect_header('pictures.php?uid='.$uid, 3, _US_PROFUPDATED); 
              }      
            } else {
              redirect_header('pictures.php?uid='.$uid,1,_EPROFILE_MA_NOTCREATEDTHUMB);
            } 
          } else {
            redirect_header('pictures.php?uid='.$uid,1,_EPROFILE_MA_NOPICTURES);
          } 
          exit();
        }
      }
    }
} elseif ($op == 'pictureedit'  && $isOwner) {
  	if (!$GLOBALS['xoopsSecurity']->check()) {
     	redirect_header('userinfo.php?uid='.$uid, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
     	exit();
  	}
  	$pid = intval($_POST['cod_img']);
  	$picture = $pictures_factory->get($pid);
  	$title = $myts->stripSlashesGPC($_POST['pic_title']);
  	$desc = $myts->stripSlashesGPC($_POST['pic_desc']);
  	if (is_object($picture)) {
     	$picture->setVar('pic_title',$title);
      $picture->setVar('pic_desc',$desc);
      if (!$pictures_factory->insert($picture)) {
	   		redirect_header('pictures.php?uid='.$uid,1,_EPROFILE_MA_DATANOTSENDET);
      } else {
	   		redirect_header('pictures.php?uid='.$uid,1,_EPROFILE_MA_DATASENDET);
      } 
      exit();
  	}
} elseif ($op == 'pictureupload'  && ($isOwner || $isAdmin)) {
  	if (!$GLOBALS['xoopsSecurity']->check()) {
     	redirect_header('userinfo.php?uid='.$uid, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
     	exit();
  	}
  	$xoops_upload_file = array();
  	if (!empty($_POST['xoops_upload_file']) && is_array($_POST['xoops_upload_file']))	{
      $xoops_upload_file = $_POST['xoops_upload_file'];
  	}
  	if (!is_object($xoopsUser) || $xoopsUser->uid() != $uid ) {
        redirect_header('userinfo.php?uid='.$uid, 3, _US_NOEDITRIGHT);
        exit();
  	}
  	$picture_maxsize = 0;
  	if (!empty($_POST['MAX_FILE_SIZE']))	{
    	$picture_maxsize = $_POST['MAX_FILE_SIZE'];
  	}
    $maxbreite = (!empty($xoopsModuleConfig['profile_pic_maxwidth'])) ? intval($xoopsModuleConfig['profile_pic_maxwidth']) : 1024;
    $maxhoehe = (!empty($xoopsModuleConfig['profile_pic_maxheight'])) ? intval($xoopsModuleConfig['profile_pic_maxheight']) : 768;
    $thumbbreite = (!empty($xoopsModuleConfig['profile_pic_thumbwidth'])) ? intval($xoopsModuleConfig['profile_pic_thumbwidth']) : 150;
    $thumbhoehe = (!empty($xoopsModuleConfig['profile_pic_thumbheigth'])) ? intval($xoopsModuleConfig['profile_pic_thumbheigth']) : 150;
    $title = $myts->stripSlashesGPC($_POST['pic_title']);  
  	$desc = $myts->stripSlashesGPC($_POST['pic_desc']);  
  	$pic->setVar('pic_uid',$xoopsUser->uid());
  	$pic->setVar('pic_title',$title);
  	$pic->setVar('pic_desc',$desc);
  	include_once XOOPS_ROOT_PATH.'/class/uploader.php';
  	$uploader = new XoopsMediaUploader($pic->upload_path, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $picture_maxsize, $maxbreite, $maxhoehe);
  	if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {       
     	$uploader->setPrefix('pic_'.$xoopsUser->uid()."_");
	 	if ($uploader->upload()) {	 
	   		$pic->setVar('pic_url',$uploader->getSavedFileName());
	   		$pic->setVar('pic_size',$uploader->getMediaSize());
	   		if (!is_writable($pic->thumb_path)) {	   
	     		@unlink($pic->upload_path."/".$uploader->getSavedFileName());
          $xoopsTpl->assign('err_picture', '<div class="errorMsg">'.sprintf(_EPROFILE_MA_DIRNOEXIST,str_replace(XOOPS_ROOT_PATH,"",$pic->thumb_path)).'<br />'.$pictures_factory->getHtmlErrors().'</div>');
          pic_form();
	   		} else {
	     		if (!$pic->resizeImg($pic->upload_path."/".$uploader->getSavedFileName(),$thumbbreite,$thumbhoehe,$pic->thumb_path)) {
		   			@unlink($pic->upload_path."/".$uploader->getSavedFileName());
		   			$xoopsTpl->assign('err_picture', '<div class="errorMsg">'._EPROFILE_MA_NOTCREATEDTHUMB.'<br />'.$pictures_factory->getHtmlErrors().'</div>');
		   			pic_form();
          } else {
	       			if (!$pictures_factory->insert($pic)) {
                @unlink($pic->upload_path."/".$uploader->getSavedFileName());
                @unlink($pic->thumb_path."/".$uploader->getSavedFileName());
                $xoopsTpl->assign('err_picture', '<div class="errorMsg">'._EPROFILE_MA_DATANOTSENDET.'<br />'.$pictures_factory->getHtmlErrors().'</div>');
                pic_form();
              } else {
                redirect_header('pictures.php?uid='.$uid, 1, _EPROFILE_MA_DATASENDET);
                exit();
              }
          }
        }
      } else {
	   		$xoopsTpl->assign('err_picture', '<div class="errorMsg">'.$uploader->getErrors().'</div>');
	   		pic_form();
      }
  	} else {
    	if ($uploader->mediaError == 2) {
	  		$xoopsTpl->assign('err_picture', '<div class="errorMsg">'._EPROFILE_MA_FILETOLARGE.'</div>');
      } else {
		  	$xoopsTpl->assign('err_picture', '<div class="errorMsg">'.$uploader->getErrors().'</div>');
      } 
      pic_form();
  	}
} else {
  if ($isOwner && $xoopsConfigUser['avatar_allow_upload']) {
   	if ($xoopsConfigUser['avatar_minposts']>0) {
	 		if ($xoopsConfigUser['avatar_minposts'] <= $xoopsUser->getVar('posts')) {
	   		$xoopsTpl->assign('is_allowed_avatar',1);
	 		} 
    } else {
	 		$xoopsTpl->assign('is_allowed_avatar',1);
    } 
  }
    
  //if ($isOwner) $GLOBALS['xoopsSecurity']->createToken(0,'PROFILE_PICTURE');
    
  $scountmax = (!empty($xoopsModuleConfig['profile_pictures_perpage'])) ? intval($xoopsModuleConfig['profile_pictures_perpage']) : 5;
  if ($pictures_count > $scountmax) {
    include_once XOOPS_ROOT_PATH."/class/pagenav.php";
    $pagenav = new XoopsPageNav($pictures_count, $scountmax, $start, "start", "uid=".$uid);
    $xoopsTpl->assign('pageNav',$pagenav->renderNav());
  }
  $criteria = new CriteriaCompo();
  $criteria->add(new Criteria('pic_uid',$uid));
  if (!is_object($xoopsUser) || $xoopsUser->uid()!=$uid)
  $criteria->add(new Criteria('private',0));
  $criteria->setSort('date');
  $criteria->setOrder('DESC');
  $criteria->setStart($start);
  $criteria->setLimit($scountmax);
  $pictures = $pictures_factory->getObjects($criteria);
		
  $pics = array();	
	  	
  foreach ($pictures as $p) {
    $title = $myts->displayTarea($p->getVar("pic_title",'n'),0,0,0,0,1);
    $desc = $myts->displayTarea($p->getVar("pic_desc",'n'),0,0,0,0,1);
      
	  $purl = '<a href="'.$p->upload_url."/".$p->getVar('pic_url').'" rel="lightbox['.$uid.']" title="'.$desc.'">
              <img src="'.$p->upload_url."/thumbs/".$p->getVar('pic_url').'" alt="'.$desc.'" title="'.$desc.'" />
            </a>';
           
   	$pics[]=array(  'url'   	=> $purl,
                    'title' 	=> $title,
                    'desc' 		=> $desc,
                    'id' 		  => $p->getVar("pic_id"),
                    'private'	=> $p->getVar("private"),
	             	);
  }
  $xoopsTpl->assign('pics_array',$pics);
}

include 'footer.php';

function pic_form($edit=false) {
  $restspace = (!empty($GLOBALS['xoopsModuleConfig']['profile_pic_max'])) ? $GLOBALS['xoopsModuleConfig']['profile_pic_max'] : 0; 
  $uploadspace = (!empty($GLOBALS['xoopsModuleConfig']['profile_pic_maxsolo'])) ? $GLOBALS['xoopsModuleConfig']['profile_pic_maxsolo'] : 0;
  $belegt = $GLOBALS['pictures_factory']->readSpace($GLOBALS['xoopsUser']->uid());
  $restspace = $restspace - $belegt;
  if ($restspace <0) $restspace = 0;
  $GLOBALS['xoopsTpl']->assign('add_picture', 1);
  include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
  $title = (!$edit) ? _EPROFILE_MA_ADDNEWPICTURE : _EPROFILE_MA_EDITPICTURE;
  $form = new XoopsThemeForm($title, 'uploadpicture', XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/pictures.php?uid='.$GLOBALS['uid'], 'post', true);
  $form->setExtra('enctype="multipart/form-data"');
  $form->insertBreak('<script language="JavaScript" type="text/javascript" src="' . XOOPS_URL . '/include/formdhtmltextarea.js"></script>');
  if (!$edit) {
    $form->insertBreak('<div class="confirmMsg">'. sprintf(_EPROFILE_MA_SPEICHERLIMIT,$restspace,$GLOBALS['xoopsModuleConfig']['profile_pic_max']).'</div>');
    if ($uploadspace > $restspace) $uploadspace = $restspace;
    $maxbreite = (!empty($GLOBALS['xoopsModuleConfig']['profile_pic_maxwidth'])) ? intval($GLOBALS['xoopsModuleConfig']['profile_pic_maxwidth']) : 1024;
    $maxhoehe = (!empty($GLOBALS['xoopsModuleConfig']['profile_pic_maxheight'])) ? intval($GLOBALS['xoopsModuleConfig']['profile_pic_maxheight']) : 768;
    $uploadspacetext = $uploadspace." kB<br />".$maxbreite." x ".$maxhoehe." Pixel<br />( jpg / jpeg / gif / png )";
    $form->addElement(new XoopsFormLabel(_EPROFILE_MA_MAXSPACEUSER, $uploadspacetext));
  }
  $form->addElement(new XoopsFormText(_EPROFILE_MA_TITLE, "pic_title", 80, 255,$GLOBALS['pic']->getVar('pic_title','n')),true); 
  //$form->addElement(new XoopsFormDhtmlTextArea(_EPROFILE_MA_DESCRIPTION, 'pic_desc', $GLOBALS['pic']->getVar('pic_desc','n')));
  $form->addElement(new XoopsFormText(_EPROFILE_MA_DESCRIPTION, 'pic_desc', 80, 255, $GLOBALS['pic']->getVar('pic_desc','n')));
  if (!$edit) {
    $formfile = new XoopsFormFile(_US_SELFILE, 'picturefile', ($uploadspace * 1024));
    $form->addElement($formfile, true);
    $form->addElement(new XoopsFormHidden('op', 'pictureupload'));
  } else {
   	$form->addElement(new XoopsFormHidden('op', 'pictureedit'));
		$form->addElement(new XoopsFormHidden('cod_img', $GLOBALS['pic']->getVar('pic_id')));
 	}
  $form->addElement(new XoopsFormHidden('uid', $GLOBALS['xoopsUser']->getVar('uid')));
  $submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
  $form->addElement($submit_button);
	$dir_exists=false;
  if (is_writable($GLOBALS['pic']->upload_path) && is_writable($GLOBALS['pic']->thumb_path)) $dir_exists=true;
  if (($restspace < 1 && !$edit) || !$dir_exists)	{
    $submit_button->setExtra('disabled="disabled"');
		$formfile->setExtra('disabled="disabled"');
		if ($dir_exists)
     	$form->insertBreak('<div class="errorMsg">'._EPROFILE_MA_NOSPACEUSER.'</div>');
		else
			$form->insertBreak('<div class="errorMsg">'.sprintf(_EPROFILE_MA_NODIR,str_replace(XOOPS_ROOT_PATH,"",$GLOBALS['pic']->upload_path),str_replace(XOOPS_ROOT_PATH,"",$GLOBALS['pic']->thumb_path)).'</div>');
  }
  $form->assign($GLOBALS['xoopsTpl']);  
}
?>