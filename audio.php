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
 * @version         $Id: audio.php 2 2012-08-16 08:20:47Z alfred $
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'header.php';

if ( !$profile_permission['audio'] ) {
  redirect_header(XOOPS_URL . '/modules/eprofile/userinfo.php?uid='.$uid, 2, _EPROFILE_MA_NOPERM);
}

$xoopsOption['template_main'] = 'profile_audios.html';
include $GLOBALS['xoops']->path('header.php');   
include_once "include/themeheader.php";
$xoopsTpl->assign('section_name', _EPROFILE_MA_AUDIOS);
$xoopsOption['xoops_pagetitle'] = sprintf(_US_ALLABOUT, $thisUser->getVar('uname'))." :: "._EPROFILE_MA_AUDIOS;

$aud = $audio_factory->create();

$start = (isset($_GET['start']))? intval($_GET['start']) : 0;
$valid_op_requests = array('add', 'audiodelete', 'audioupload');
$op = !empty($_REQUEST['op']) && in_array($_REQUEST['op'], $valid_op_requests) ? $_REQUEST['op'] : '' ;


if ($op=='add' && ($isOwner || $isAdmin)) {
  	audio_form();
} elseif ($op=='audiodelete' && ($isOwner || $isAdmin)) {
  $pid = intval($_POST['audio_id']);  
  if(empty($_POST['confirm'])) {
    $aud = $audio_factory->get($pid);	    
	 	if (is_object($aud)) {
		    xoops_confirm(array('uid'=>$uid,'audio_id'=> $aud->getVar("audio_id"),'confirm'=>1,'op'=>'audiodelete'), 'audio.php', _EPROFILE_MA_ASKCONFIRMAUDIOSDELETION."<br />", _EPROFILE_MA_CONFIRMAUDIOSDELETION);
	   		include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'footer.php';
	   		exit();
	 	}  
  } else {
   	$aud = $audio_factory->get($pid);
		$audio_factory->delete($aud);
		@unlink($aud->upload_path."/".$aud->getVar('url'));
		redirect_header('audio.php?uid='.$uid,2,_EPROFILE_MA_DATASENDET);
    exit();
  }
   
} elseif ($op=='audioupload' && ($isOwner || $isAdmin)) {
    if (!$GLOBALS['xoopsSecurity']->check()) {
     	redirect_header('userinfo.php?uid='.$uid, 3, _US_NOEDITRIGHT . "<br />" . implode('<br />', $GLOBALS['xoopsSecurity']->getErrors()));
     	exit();
  	}
  	$xoops_upload_file = array();
  	if (!empty($_POST['xoops_upload_file']) && is_array($_POST['xoops_upload_file'])) {
      	$xoops_upload_file = $_POST['xoops_upload_file'];
  	}
  	if (!is_object($xoopsUser) || $xoopsUser->uid()!=$uid ) {
        redirect_header('userinfo.php?uid='.$uid, 3, _US_NOEDITRIGHT);
        exit();
  	}
  	$picture_maxsize = 0;
  	if (!empty($_POST['MAX_FILE_SIZE'])) {
    	$picture_maxsize = $_POST['MAX_FILE_SIZE'];
  	}
    $title = $myts->stripSlashesGPC($_POST['title']);  
  	
    $aud->setVar('audio_uid',$xoopsUser->uid());
  	$aud->setVar('title',$title);
  	include_once XOOPS_ROOT_PATH.'/class/uploader.php';
    $uploader = new XoopsMediaUploader($aud->upload_path, array('audio/mpeg'), $picture_maxsize);
  	if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {       
     	$uploader->setPrefix('aud_'.$xoopsUser->uid()."_");
      if ($uploader->upload()) {
        $aud->setVar('url',$uploader->getSavedFileName());
	   		$aud->setVar('audio_size',$uploader->getMediaSize());
        $time = formatTimestamp(time(),'mysql');
        $aud->setVar('data_creation',$time);
        $aud->setVar('data_update',$time);
        $audio_path = $aud->upload_path.'/'.$aud->getVar("url");
        $mp3filemetainfo = $audio_factory->id3($audio_path);
        if ($mp3filemetainfo["song"] != '') $aud->setVar('title',$mp3filemetainfo["song"]);
        if ($mp3filemetainfo["artist"] != '') $aud->setVar('author',$mp3filemetainfo["artist"]);        
        if (!$audio_factory->insert($aud)) {
	        @unlink($aud->upload_path."/".$uploader->getSavedFileName());
          $xoopsTpl->assign('err_audio', '<div class="errorMsg">'._EPROFILE_MA_DATANOTSENDET.'<br />'.$audio_factory->getHtmlErrors().'</div>');
		     	audio_form();
		   	} else {
		    	redirect_header('audio.php?uid='.$uid, 3, _EPROFILE_MA_DATASENDET);
          exit();
		   	}
      } else {
	   		$xoopsTpl->assign('err_audio', '<div class="errorMsg">'.$uploader->getErrors().'</div>');
	   		audio_form();
      }
  	} else {
    	if ($uploader->mediaError == 2) {
	  		$xoopsTpl->assign('err_audio', '<div class="errorMsg">'._EPROFILE_MA_FILETOLARGE.'</div>');
		} 
		else 
		{
		  	$xoopsTpl->assign('err_audio', '<div class="errorMsg">'.$uploader->getErrors().'</div>');
		} 
		audio_form();
  	}
} else {
  $scountmax = (!empty($xoopsModuleConfig['profile_audios_perpage'])) ? intval($xoopsModuleConfig['profile_audios_perpage']) : 1;
  $player = (!empty($xoopsModuleConfig['profile_audioplayer'])) ? intval($xoopsModuleConfig['profile_audioplayer']) : 0;
  $criteriaUidAudio = new criteria('audio_uid',$uid);
  $criteriaUidAudio->setStart($start);
  $criteriaUidAudio->setOrder('DESC');
  $criteriaUidAudio->setSort('audio_uid');
  $criteriaUidAudio->setLimit($scountmax);
  $audio_player = "";
  $playlist = "";
  $xmldatei   = XOOPS_UPLOAD_PATH. "/eprofile/music/mp3list_".$uid.".xml";
  $xmlurl     = XOOPS_UPLOAD_URL. "/eprofile/music/mp3list_".$uid.".xml";     
        
  if ($player == 1) {
    $audio_player ='
      <object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000"
        codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,19,0" width="301" height="212">
        <param name="movie" value="audioplayers/musicplayer.swf?playlist='.$xmlurl.'" />
        <param name="quality" value="high" />
        <param name="wmode" value="transparent" />
        <embed src="audioplayers/musicplayer.swf?playlist='.$xmlurl.'" quality="high" wmode="transparent" pluginspage="http://www.macromedia.com/go/getflashplayer"
        type="application/x-shockwave-flash" width="301" height="212">
        </embed>
      </object>';
    $playlist .= "<player showDisplay=\"yes\" showPlaylist=\"yes\" autoStart=\"no\">\n";
  } elseif ($player == 2) {
    $audio_player ='
      <object data="audioplayers/flashmp3player.swf?playlist='.$xmlurl.'" type="application/x-shockwave-flash" width="300" height="200">
        <param name="movie" value="audioplayers/flashmp3player.swf?playlist='.$xmlurl.'" />
        <param name="quality" value="high" />
        <param name="wmode" value="transparent" />
      </object>';
      $playlist .= '<?xml version="1.0" encoding="UTF-8"?>
                      <playlist version="1" xmlns="http://xspf.org/ns/0/">
                    <trackList>'; 
  } else {
    $audio_player = '
      <object type="application/x-shockwave-flash" data="audioplayers/dewplayer.swf" width="250" height="230" id="dewplayer" name="dewplayer">
        <param name="movie" value="audioplayers/dewplayer.swf" />
        <param name="flashvars" value="xml='.$xmlurl.'" />
        <param name="wmode" value="transparent">
        <param name="allowScriptAccess" value="sameDomain" />
      </object>';        
    $playlist .="<?xml version='1.0' encoding='utf-8'?>\n";
    $playlist .= "<playlist version='1' xmlns='http://xspf.org/ns/0/'>\n";
    $playlist .= "  <title>"._EPROFILE_MA_MYAUDIOS."</title>\n";
    $playlist .= "  <info>" . XOOPS_URL . "</info>\n";
    $playlist .= "  <trackList>\n";   
  }
    
  $audios = $audio_factory->getAudio($criteriaUidAudio);
  $audios_array = $audio_factory->assignAudioContent($audio_count,$audios);
  if(is_array($audios_array)) {
    $xoopsTpl->assign('audios', $audios_array);
    foreach($audios_array as $audio_item) {
      if ($player == 1) {
        // for the title it filters the directory and the .mp3 extension out
        $title = $audio_item['title'];   
        $playlist .= "  <song path=\"" . $aud->upload_url.'/'.$audio_item['url'] . "\" title=\"$title\" />";
            
      } else {
        // ...then we loop through the array...
        $playlist .= "    <track>\n"; 
        $playlist .= "      <creator>&nbsp;" . $audio_item['author'] . "</creator>\n";  
        $playlist .= "      <title>&nbsp;" . $audio_item['title'] . "</title>\n";  
        $playlist .= "      <location>" . $aud->upload_url.'/'.$audio_item['url'] . "</location>\n";
        $playlist .= "    </track>\n";
      }
    } 
    
    if ($player == 1) {
      $playlist .= "</player>";        
    } else {
      // ...and last we add the closing tags
      $playlist .= "  </trackList>\n";
      $playlist .= "</playlist>\n";                    
    }
    if ($fp = fopen($xmldatei, 'w')) {        
      fputs($fp, $playlist);
      fclose($fp);
    }     
        
    $xoopsTpl->assign('player',$audio_player);
  } 
  $xoopsTpl->assign('nb_audio',count($audios_array));
  $xoopsTpl->assign('player_url',$aud->upload_url);
    
  if ($audio_count > $scountmax) {
        include_once XOOPS_ROOT_PATH."/class/pagenav.php";
        $pagenav = new XoopsPageNav($audio_count, $scountmax, $start, "start", "uid=".$uid);
        $xoopsTpl->assign('pageNav',$pagenav->renderImageNav());
  }
  
}
include 'footer.php';

function audio_form($edit=false) 
{
  	$restspace = (isset($GLOBALS['xoopsModuleConfig']['profile_audio_max'])) ? intval($GLOBALS['xoopsModuleConfig']['profile_audio_max']) : 0; 
  	$belegt = $GLOBALS['audio_factory']->readSpace($GLOBALS['thisUser']->uid());
  	$restspace = $restspace - $belegt;
  	if ($restspace <0) $restspace = 0;
  	$GLOBALS['xoopsTpl']->assign('add_audio', 1);
  	include_once XOOPS_ROOT_PATH.'/class/xoopsformloader.php';
  	if (!$edit)
    	$form = new XoopsThemeForm(_EPROFILE_MA_ADDNEWAUDIO, 'uploadaudio', XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/audio.php?uid='.$GLOBALS['uid'], 'post', true);
  	else
    	$form = new XoopsThemeForm(_EPROFILE_MA_EDITAUDIO, 'uploadaudio', XOOPS_URL . '/modules/' . $GLOBALS['xoopsModule']->getVar('dirname', 'n') . '/audio.php?uid='.$GLOBALS['uid'], 'post', true);
  	$form->setExtra('enctype="multipart/form-data"');
  	$form->insertBreak('<script language="JavaScript" type="text/javascript" src="' . XOOPS_URL . '/include/formdhtmltextarea.js"></script>');
  	if (!$edit) 
	{
    	$form->insertBreak('<div class="confirmMsg">'. sprintf(_EPROFILE_MA_SPEICHERLIMIT,$restspace,$GLOBALS['xoopsModuleConfig']['profile_audio_max']).'</div>');
    	$uploadspacetext = $restspace." kB ( mp3 )";
    	$form->addElement(new XoopsFormLabel(_EPROFILE_MA_MAXSPACEUSER, $uploadspacetext));
  	}
  	$form->addElement(new XoopsFormText(_EPROFILE_MA_TITLEAUDIO, "title", 80, 255,$GLOBALS['aud']->getVar('title','n')),true); 
  	$form->addElement(new XoopsFormText(_EPROFILE_MA_AUDIOAUTOR, "author", 80, 255,$GLOBALS['aud']->getVar('author','n'))); 
  	
    if (!$edit) 
	{
    	$formfile = new XoopsFormFile(_US_SELFILE, 'audiofile', ($restspace * 1024));
    	$form->addElement($formfile, true);
    	$form->addElement(new XoopsFormHidden('op', 'audioupload'));
  	} else {
    	$form->addElement(new XoopsFormHidden('op', 'audioedit'));
		$form->addElement(new XoopsFormHidden('audio_id', $GLOBALS['aud']->getVar('audio_id')));
 	}
  	$form->addElement(new XoopsFormHidden('uid', $GLOBALS['xoopsUser']->getVar('uid')));
  	$submit_button = new XoopsFormButton('', 'submit', _SUBMIT, 'submit');
  	$form->addElement($submit_button);
	$dir_exists=false;
	if (is_writable($GLOBALS['aud']->upload_path)) $dir_exists=true;
  	if (($restspace < 1 && !$edit) || !$dir_exists)
  	{
    	$submit_button->setExtra('disabled="disabled"');
		$formfile->setExtra('disabled="disabled"');
		if ($dir_exists)
    	  	$form->insertBreak('<div class="errorMsg">'._EPROFILE_MA_NOSPACEUSER.'</div>');
		else
			$form->insertBreak('<div class="errorMsg">'.sprintf(_EPROFILE_MA_NODIR,str_replace(XOOPS_ROOT_PATH,"",$GLOBALS['aud']->upload_path),'').'</div>');
  	}
  	$form->assign($GLOBALS['xoopsTpl']);  
}
?>