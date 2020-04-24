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
 * @version         $Id: tribes.php 35 2014-02-08 17:37:13Z alfred $
 */

include 'header.php';

if ( !$profile_permission['tribes'] ) {
  redirect_header(XOOPS_URL , 2, _EPROFILE_MA_NOPERM);
}

  //ToDo
  $maxfilesize = 1024;
  $maxwidth = 150;
  $maxhight = 150;

$xoopsOption['template_main'] = 'profile_tribes.html';
include $GLOBALS['xoops']->path('header.php');      

$xoopsTpl->assign('section_name', _EPROFILE_MA_TRIBES);
include_once "include/themeheader.php";
$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname')) . " :: "._EPROFILE_MA_TRIBES;

$valid_op_requests = array('add', 'addnew');
$op = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : '';

if ($isOwner && ($op == 'add' || $op == ''))
{
  $tribes_anzahl = intval($xoopsModuleConfig['profile_tribes_max']);  
  $tribes_anzahl = $tribes_anzahl - $tribes_factory->getCountTribes($uid);  
} 
else 
{
  $tribes_anzahl = 0;
}
if ($tribes_anzahl <= 0) $tribes_anzahl = null;
$xoopsTpl->assign('allow_add_tribes', $tribes_anzahl);

if ($op == 'add' && $tribes_anzahl > 0)
{
  $tribes = $tribes_factory->create();
  tribesform($tribes);
}
elseif ($op == 'addnew' && $isOwner)
{
  $tribes = $tribes_factory->create();
  $tribes->setVars($_POST);
  if ($tribes_factory->getTribesCheck($tribes->getVar('tribes_desc')))
  {
    tribesform($tribes, _MA_EPROFILE_TRIBESTITLEEXIST);
  }
  else
  {
    include_once XOOPS_ROOT_PATH.'/class/uploader.php';
    $uploader = new XoopsMediaUploader($tribes->upload_path, array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png', 'image/png'), $maxfilesize * 1024, 4000, 4000);
    
    if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) 
    {       
     	$uploader->setPrefix('tribes_'.$xoopsUser->uid()."_"); 
      if ($uploader->upload()) 
      {	
        if (!is_writable($tribes->upload_path)) 
        {	   
          @unlink($tribes->upload_path."/".$uploader->getSavedFileName());
          $error = '<div class="errorMsg">'.sprintf(_EPROFILE_MA_DIRNOEXIST,str_replace(XOOPS_ROOT_PATH,"",$tribes->upload_path)).'<br />'.$tribes_factory->getHtmlErrors().'</div>';
          tribesform($tribes,$error);
        } 
        else 
        {
          $tribes_factory->resizeImg($tribes->upload_path."/".$uploader->getSavedFileName(), $maxwidth, $maxhight, $tribes->upload_path);
          $tribes->setVar('tribes_url',$uploader->getSavedFileName());
          $tribes->setVar('tribes_owner',$xoopsUser->uid());
          if ($tribes_factory->insert($tribes))
          {
            redirect_header(XOOPS_URL."/modules/".$xoopsModule->dirname()."/tribes.php?uid=".$uid , 2, _MA_EPROFILE_ADDGROUPSUCEESS);
          }
          else
          {
            @unlink($tribes->upload_path."/".$uploader->getSavedFileName());
            redirect_header(XOOPS_URL."/modules/".$xoopsModule->dirname()."/tribes.php?uid=".$uid , 2, $tribes->getHtmlErrors());
          }
          exit();
        }
      }
      else
      {
        $error = $uploader->getErrors();
        tribesform($tribes,$error);
      }
    }    
  }
}
else
{
  $criteria = new CriteriaCompo();
  $criteria->add(new Criteria('tribes_owner',$uid));
  $criteria->add(new Criteria('tribes_uid',0));
  $tribes = $tribes_factory->getObjects($criteria,false,false);
  if (count($tribes) > 0 )
  {
    $xoopsTpl->assign('tribes',$tribes);
    $_triebe = $tribes_factory->create();
    $xoopsTpl->assign('tribes_path',$_triebe->upload_path);
    $xoopsTpl->assign('tribes_url',$_triebe->upload_url);
    unset($_triebe);
  }
  //print_r($tribes);
}

include 'footer.php';

function tribesform($obj,$error=null)
{
  
  $form = new XoopsThemeForm(_MA_EPROFILE_ADDGROUP, 'tribesform', 'tribes.php?uid='.$GLOBALS['uid'], 'post', true);
  $form->setExtra('enctype="multipart/form-data"');
  if ($error != '')
  {
    $form->addElement(new XoopsFormLabel('', $error));
  }
  $form->addElement(new XoopsFormText(_MA_EPROFILE_TRIBESTITLE, 'tribes_desc', 30, 100, $obj->getVar('tribes_desc')), true);
  $form->addElement(new XoopsFormRadioYN(_MA_EPROFILE_TRIBESMODERATE, 'tribes_visible', $obj->getVar('tribes_visible')),true);
  $form->addElement(new XoopsFormFile(sprintf(_MA_EPROFILE_TRIBESPICTURE,$GLOBALS['maxfilesize'],$GLOBALS['maxwidth'],$GLOBALS['maxhight']), 'tribes_url', $GLOBALS['maxfilesize'] * 1024),true);
  $form->addElement(new XoopsFormHidden('op', "addnew"));
  $submit = new XoopsFormElementTray("", "");
  $submit->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
  $cancel_send = new XoopsFormButton('', 'cancel', _CANCEL, 'button');
  $cancel_send->setExtra("onclick='javascript:window.location.href =\"tribes.php?uid=".$GLOBALS["uid"]."\";'");
  $submit->addElement($cancel_send);
  $form->addElement($submit);  
  $GLOBALS['xoopsTpl']->assign('tribes_form',$form->render());
}
?>