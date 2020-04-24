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
 * @version         $Id$
 */

 
 class ProfileAudio extends XoopsObject  
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
        if ( !empty($id) ) 
        {
			if ( is_array($id) ) 
            {
				$this->assignVars($id);
			} 
            else 
            {
				$this->load(intval($id));
			}
		} else {
			$this->setNew();
		}
        $this->upload_path 	= XOOPS_UPLOAD_PATH."/".$GLOBALS['xoopsModule']->dirname()."/music";
		$this->upload_url 	= XOOPS_UPLOAD_URL."/".$GLOBALS['xoopsModule']->dirname()."/music";
    }
    
    function ProfileAudio($id=null)
    {
        $this->__construct($id);
    }
    
 
} 

class ProfileAudioHandler extends XoopsPersistableObjectHandler 
{
    function __construct($db) 
    {
        parent::__construct($db, 'profile_audio', 'profileaudio', 'audio_id', 'audio_uid');
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
    
    function assignAudioContent($nbAudios, $audios)
    {
	
        if ($nbAudios==0) {
            return false;
        } else {            
            $i = 0;
            foreach ($audios as $audio){
                $audios_array[$i]['url']      = $audio->getVar("url","s");
                $audios_array[$i]['title']    = $audio->getVar("title","s");
                $audios_array[$i]['id']       = $audio->getVar("audio_id","s");
                $audios_array[$i]['author']   = $audio->getVar("author","s");
                if ( (str_replace('.', '', PHP_VERSION)) > 499 ){  
                    $audio_path = $audio->upload_path.'/'.$audio->getVar("url","s");
                    $id3v1 = new Id3v1($audio_path, true);
                    $filemeta = array();
                    $filemeta['Title']  = $id3v1->getTitle();
                    $filemeta['Artist'] = $id3v1->getArtist();
                    $filemeta['Album']  = $id3v1->getAlbum();
                    $filemeta['Year']   = $id3v1->getYear();
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