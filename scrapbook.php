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
 * @version         $Id: scrapbook.php 2 2012-08-16 08:20:47Z alfred $
 */

include 'header.php';

if ( !$profile_permission['scraps'] ) {
  redirect_header(XOOPS_URL . '/modules/eprofile/userinfo.php?uid='.$uid, 2, _EPROFILE_MA_NOPERM);
}

$xoopsOption['template_main'] = 'profile_scrapbook.html';
include $GLOBALS['xoops']->path('header.php');      

include_once "include/themeheader.php";
$xoopsTpl->assign('section_name', _EPROFILE_MA_SCRAPBOOK);
$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname'))." :: "._EPROFILE_MA_SCRAPBOOK;        
	
$xoopsTpl->assign('allow_send_scraps',($profileconfigs_handler->getperm('sendscraps', $uid, $muid) ? 1:0));
$start = !empty($_GET['start']) ? intval($_GET['start']) : 0;
$stop = '';

if ( !empty($_REQUEST['mainscrap']) ) {
  if ( !in_array($_REQUEST['mainscrap'],array('edit','insert','edit','edit_ok','delete','delete_ok'))  ) {
      redirect_header(XOOPS_URL . '/modules/eprofile/scrapbook.php?uid='.$uid, 2, _EPROFILE_MA_NOPERM);
  }	
      
  if ( $_REQUEST['mainscrap'] == 'insert' ) {  //submit
      if ( !empty($_POST['text']) && $_POST['text'] == _EPROFILE_MA_ENTERTEXTSCRAP  ) {
        redirect_header(XOOPS_URL . '/modules/eprofile/scrapbook.php?uid='.$uid."&op=add", 2, _EPROFILE_MA_NOTEXT);
      }
      xoops_load("Xoopscaptcha");
      $xoopsCaptcha = XoopsCaptcha::getInstance();
			if (! $xoopsCaptcha->verify() ) {
				redirect_header(XOOPS_URL . '/modules/eprofile/scrapbook.php?uid='.$uid."&op=add", 2, $xoopsCaptcha->getMessage());
			}
      $scrapbook_uid 	= intval($uid);
      $scrap_text    	= $myts->stripSlashesGPC($_POST['text']);
      $mainform	   	  = (!empty($_POST['mainform'])) ? 1 : 0;
      $scrap = $scraps_factory->create();
      $scrap->setVar('scrap_text',$scrap_text);
      $scrap->setVar('scrap_from',(($xoopsUser) ? $xoopsUser->getVar('uid') : 0));
      $scrap->setVar('scrap_to',$scrapbook_uid);
      if ($scraps_factory->insert($scrap)){
        if (!empty($profile_permission['scraps_notify']))	{
          $toUser =& $member_handler->getUser($uid);
          $xoopsMailer = xoops_getMailer();
          $xoopsMailer->useMail();
          if (file_exists(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/language/".$xoopsConfig['language']."/mail_template/scrap_notify.tpl"))
			  		$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/language/".$xoopsConfig['language']."/mail_template");
          else
			    	$xoopsMailer->setTemplateDir(XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/language/english/mail_template");
          $xoopsMailer->setTemplate('scrap_notify.tpl');
          $xoopsMailer->assign('X_UNAME', $toUser->uname());
          $xoopsMailer->assign('X_MESSAGE', strip_tags(str_replace("<br />"," ",$myts->displayTarea($scrap_text))));
          if ($xoopsUser)
            $xoopsMailer->assign('X_NAME', $xoopsUser->uname());
          else
            $xoopsMailer->assign('X_NAME', _GUEST);
          $xoopsMailer->assign('SITENAME', $xoopsConfig['sitename']);
          $xoopsMailer->assign('ADMINMAIL', $xoopsConfig['adminmail']);
          $xoopsMailer->assign('SITEURL', XOOPS_URL."/");                    
          $xulink = XOOPS_URL."/modules/".$xoopsModule->dirname()."/scrapbook.php?uid=".$toUser->uid();
          $xoopsMailer->assign('X_ULINK',$xulink);
          $xoopsMailer->setToUsers($toUser);
          $xoopsMailer->setSubject(_EPROFILE_MA_MAILSUBJECTNEWSCRAP);
          if ( !$xoopsMailer->send(true) ) {
        			//xoops_result($xoopsMailer->getErrors());    
    			} 
	   		}
      }
  } elseif ( $_REQUEST['mainscrap'] == 'edit' ) {  //answer
      $scrap_id = (!empty($_GET['scrap_id'])) ? intval($_GET['scrap_id']) : 0;
      $scrap = $scraps_factory->get($scrap_id);
	  	if ($scrap->getVar('scrap_to') == $uid && $isOwner) {            
        $xoopsTpl->assign('add_scraps', 1);
        $form = new XoopsThemeForm(_EPROFILE_MA_SCRAPEDIT, 'scrapedit', 'scrapbook.php?uid='.$uid, 'post', true);
        $form->setExtra('enctype="multipart/form-data"');
        $form->addElement(new XoopsFormHidden('mainscrap','edit_ok'));
        $form->addElement(new XoopsFormHidden('mainform','1'));
        $form->addElement(new XoopsFormHidden('scrap_id',$scrap->getVar('scrap_id','n')));
        
        //$editor = xoops_getModuleOption('general_editor', 'system');
        $editor = "textarea";
        $editor_configs["name"]     = "text";
        $editor_configs["value"]    = $scrap->getVar('scrap_text');
        $editor_configs["rows"]     = 10;
        $editor_configs["cols"]     = 50;
        $editor_configs["width"]    = "100%";
        $editor_configs["height"]   = "400px";    
        $_editor = new XoopsFormEditor(_DESCRIPTION, $editor, $editor_configs);
        $form->addElement($_editor, true);
        $form->addElement(new XoopsFormButton('', 'post_scrap', _SUBMIT, 'submit'));
        $xoopsTpl->assign('sendscraps_text',$form->render());
	  	}
  } elseif ( $_REQUEST['mainscrap'] == 'edit_ok' ) { 
      if (!($GLOBALS['xoopsSecurity']->check())  ){
        redirect_header(XOOPS_URL . '/modules/eprofile/scrapbook.php?uid='.$uid, 0, _EPROFILE_MA_NOPERM);
      }        
     	$scrap_id = (!empty($_POST['scrap_id'])) ? intval($_POST['scrap_id']) : 0;
      $scrap = $scraps_factory->get($scrap_id);
      if ($scrap_id > 0 && $isOwner) {
	      $scrap_text  = $myts->stripSlashesGPC($_POST['text']);
        $scrap->setVar('scrap_text',$scrap_text);
        if ($scraps_factory->insert($scrap))
			    redirect_header('scrapbook.php?uid='.$uid, 1, _EPROFILE_MA_DATASENDET);
        else 
          redirect_header('scrapbook.php?uid='.$uid, 2, _EPROFILE_MA_DATANOTSENDET);
        exit();
      } 
  } elseif ( $_REQUEST['mainscrap'] == 'delete_ok' ) {  //delete
      if (!($GLOBALS['xoopsSecurity']->check())  ){
        redirect_header(XOOPS_URL . '/modules/eprofile/scrapbook.php?uid='.$uid, 0, _EPROFILE_MA_NOPERM);
      } 
      $xoopsTpl->assign('add_scraps', 1);
      $scrap_id = (!empty($_POST['scrap_id'])) ? intval($_POST['scrap_id']) : 0;
      $scrap = $scraps_factory->get($scrap_id);
      if ($scrap->getVar('scrap_to') == $uid && $isOwner) {
        $criteria_scrap_id = new Criteria ('scrap_id',$scrap_id);	   
        $criteria_uid = new Criteria ('scrap_to',$uid);
        $criteria = new CriteriaCompo ($criteria_scrap_id);
        $criteria->add($criteria_uid);	
        if($scraps_factory->deleteAll($criteria)) {
          redirect_header(XOOPS_URL . '/modules/eprofile/scrapbook.php?uid='.$uid, 2, _EPROFILE_MA_SCRAPDELETED);
        } else {
          redirect_header('scrapbook.php?uid='.$uid, 2, _EPROFILE_MA_NOCACHACA);
        }
      } 
  } elseif ( $_REQUEST['mainscrap'] == 'delete' ) {  //delete
      $xoopsTpl->assign('add_scraps', 1);
     	$scrap_id = (!empty($_GET['scrap_id'])) ? intval($_GET['scrap_id']) : 0;
      $scrap = $scraps_factory->get($scrap_id);
      if ($scrap->getVar('scrap_to') == $uid && $isOwner) {
        $GLOBALS['xoopsTpl']->assign('sendscraps_text',$scrap->getVar('scrap_text'));	
        xoops_confirm(array('uid'=>$uid,'scrap_id'=> $scrap_id,'confirm'=>1,'mainscrap'=>'delete_ok'), 'scrapbook.php', _EPROFILE_MA_ASKCONFIRMSCRAPDELETION,_EPROFILE_MA_CONFIRMSCRAPDELETION);
      }
  }
} elseif (!empty($_REQUEST['op']) && $_REQUEST['op'] == 'add') {
  $xoopsTpl->assign('add_scraps', 1);
  $form = new XoopsThemeForm(_EPROFILE_MA_CONFIGSSENDSCRAPS, 'scrapedit', 'scrapbook.php?uid='.$uid, 'post', true);
  $form->setExtra('enctype="multipart/form-data"');
  $form->addElement(new XoopsFormHidden('mainscrap','insert'));
  $form->addElement(new XoopsFormHidden('mainform','1'));
  //$editor = xoops_getModuleOption('general_editor', 'system');
  $editor = "textarea";
  $editor_configs["name"]     = "text";
  $editor_configs["value"]    = _EPROFILE_MA_ENTERTEXTSCRAP;
  $editor_configs["rows"]     = 10;
  $editor_configs["cols"]     = 50;
  $editor_configs["width"]    = "100%";
  $editor_configs["height"]   = "400px";    
  $_editor = new XoopsFormEditor(_DESCRIPTION, $editor, $editor_configs);
  $form->addElement($_editor, true);
  $form->addElement(new XoopsFormCaptcha());
  $form->addElement(new XoopsFormButton('', 'post_scrap', _SUBMIT, 'submit'));
  $xoopsTpl->assign('sendscraps_text',$form->render());
}

if ($stop != '') $xoopsTpl->assign('err_message', xoops_error($stop));

$scountmax = (!empty($xoopsModuleConfig['profile_scraps_perpage'])) ? intval($xoopsModuleConfig['profile_scraps_perpage']) : 5;
if ($scraps_count > $scountmax) {
  include_once XOOPS_ROOT_PATH."/class/pagenav.php";
  $pagenav = new XoopsPageNav($scraps_count, $scountmax, $start, "start", "uid=".$uid);
  $xoopsTpl->assign('pageNav',$pagenav->renderImageNav());
}
//$xoopsTpl->assign('add_scraps', 1);
$criteria_uid = new Criteria('scrap_to',$uid);
$criteria_uid->setOrder('DESC');
$criteria_uid->setSort('date');
$criteria_uid->setStart($start);
$criteria_uid->setLimit($scountmax);
$scraps = $scraps_factory->getScraps($criteria_uid);
$GLOBALS['xoopsTpl']->assign('scraps',$scraps);	
include 'footer.php';
?>