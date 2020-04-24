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
 * @author          Dirk Herrmann <myxoops@t-online.de>
 * @version         $Id: audio.php 24 2013-05-24 19:31:14Z alfred $
 */

 
 class EprofileAudio extends XoopsObject  
{
 
  var $upload_path 	= "";
	var $upload_url 	= "";	
 
  function __construct($id=null) 
    {
        $this->initVar("audio_id",XOBJ_DTYPE_INT,null,false,10);
        $this->initVar("audio_uid",XOBJ_DTYPE_INT,null,false,10);
        $this->initVar("title",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("author",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("url",XOBJ_DTYPE_TXTBOX, null, false);
        $this->initVar("audio_size",XOBJ_DTYPE_INT,null,false,10);
        $this->initVar("data_creation",XOBJ_DTYPE_TXTBOX,null,false);
        $this->initVar("data_update",XOBJ_DTYPE_TXTBOX,null,false);
        $this->upload_path 	= XOOPS_UPLOAD_PATH."/".$GLOBALS['xoopsModule']->dirname()."/music";
        $this->upload_url 	= XOOPS_UPLOAD_URL."/".$GLOBALS['xoopsModule']->dirname()."/music";
    }
     
} 

class EprofileAudioHandler extends XoopsPersistableObjectHandler 
{
    function __construct($db) 
    {
        parent::__construct($db, 'profile_audio', 'Eprofileaudio', 'audio_id', 'audio_uid');
    }
    
    function getAudio($criteria)
    {
        $audios = $this->getObjects($criteria);
        return $audios;
    }
    
    function readSpace($uid=0) {
        if (empty($uid)) return -1;
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('o.audio_uid',$uid));
        $this->table_link = $this->db->prefix('profile_audio');
        $audios = $this->getByLink($criteria,array('o.audio_size'),false,'audio_size');
        $max = 0;
        foreach ($audios as $p) {
            $max += intval($p['audio_size']);
        }
        if ($max > 0) $max = round(($max / 1024),0);
        else $max = 0;
        return $max;
	}
  
  function id3($filename = "") {
    if (!is_file($filename) or filesize($filename) < 128) {    
      return false;
      break;
    }
    $fp = fopen($filename, "r");
    fseek($fp, filesize($filename) - 128);
    $id3 = fread($fp, 128);        
    $arr = array();    
    if (strtoupper(substr($id3, 0, 3)) == "TAG") {
      $arr["song"] = trim(substr($id3, 3, 30));
      $arr["artist"] = trim(substr($id3, 33, 30));
      $arr["album"] = trim(substr($id3, 63, 30));
      $arr["year"] = trim(substr($id3, 93, 4));
      fclose($fp);
      return $arr;
    } 
    fclose($fp);
    return false;  
  }
    
  function assignAudioContent($nbAudios, $audios)
    {
	    if ($nbAudios==0) {
        return false;
      } else {            
        $i = 0;
        $audios_array = array();
        foreach ($audios as $audio){
          $audios_array[$i]['url']      = $audio->getVar("url");
          $audios_array[$i]['title']    = $audio->getVar("title");
          $audios_array[$i]['id']       = $audio->getVar("audio_id");
          $audios_array[$i]['author']   = $audio->getVar("author");
          $audio_path = $audio->upload_path . '/' . $audio->getVar("url");
          $idv_data = $this->id3($audio_path);          
          if ( is_array($idv_data) && count($idv_data) > 0) {
            $filemeta = array();
            $filemeta['Title']  = $idv_data['song'];
            $filemeta['Artist'] = $idv_data['artist'];
            $filemeta['Album']  = $idv_data['album'];
            $filemeta['Year']   = $idv_data['year'];
            $audios_array[$i]['meta'] = $filemeta;
          } else {
            $audios_array[$i]['nometa'] = 1;
          }                 
          $i++;
        }
        return $audios_array;
      }
    }    
}

?>