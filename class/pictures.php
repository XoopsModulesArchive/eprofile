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
 * @version         $Id: pictures.php 11 2013-02-24 19:57:08Z alfred $
 */
 
class EprofilePictures extends XoopsObject  
{
    var $upload_path 	= "";
    var $thumb_path 	= "";
    var $upload_url 	= "";	
    var $thumb_url      = "";	
	
    public function __construct() 
    {
      $this->initVar("pic_id",XOBJ_DTYPE_INT,null,false,10);
      $this->initVar("pic_uid",XOBJ_DTYPE_INT,null,false,10);
      $this->initVar('pic_title', XOBJ_DTYPE_TXTBOX, null, true, 255);
      $this->initVar("pic_desc",XOBJ_DTYPE_TXTAREA, null, false);
      $this->initVar("pic_size",XOBJ_DTYPE_INT,null,false,10);
      $this->initVar('pic_url', XOBJ_DTYPE_TXTBOX, null, true, 255);
      $this->initVar("private",XOBJ_DTYPE_INT,null,false,10);
      $this->initVar("date",XOBJ_DTYPE_OTHER);
      $this->upload_path 	= XOOPS_UPLOAD_PATH . "/" . $GLOBALS['xoopsModule']->dirname();
      $this->upload_url 	= XOOPS_UPLOAD_URL  . "/" . $GLOBALS['xoopsModule']->dirname();
      $this->thumb_path 	= XOOPS_UPLOAD_PATH . "/" . $GLOBALS['xoopsModule']->dirname() . "/thumbs";
      $this->thumb_url 	  = XOOPS_UPLOAD_URL  . "/" . $GLOBALS['xoopsModule']->dirname() . "/thumbs";
    }
	
	
	function resizeImg($picture,$maxwidth=100,$maxheight=100,$uploaddir) 
	{
	   	if ($picture=="") return false;
	   	$bild 		= basename($picture);
	   	$size 		= getimagesize($picture); 
      $breite 	= $size[0]; 
      $hoehe 		= $size[1]; 
	   	$sollHoehe  = (!empty($maxheight)) ? $maxheight : 100; 
      $sollBreite = (!empty($maxwidth))  ? $maxwidth  : 100; 
		
       	// Breite ok, zu hoch
       	if ($breite <= $maxwidth && $hoehe > $maxheight) 
       	{
         	$neueHoehe = $sollHoehe;
            $neueBreite = intval($breite * $sollHoehe/$hoehe); 
            if ($neueBreite > $sollBreite) 
            {
                $neueBreite = $sollBreite;
                $neueHoehe = intval($neueHoehe * $neueBreite/$sollBreite);                
            }
       	}
       	elseif ($breite > $maxwidth && $hoehe <= $maxheight) // Höhe OK zu breit
       	{
            $neueBreite = $sollBreite;
            $neueHoehe  = intval($hoehe * $sollBreite/$breite); 
            if ($neueHoehe > $sollHoehe) 
            {
                $neueHoehe  = $sollHoehe;
                $neueBreite = intval($neueBreite * $neueHoehe/$sollHoehe);
                
            }
       	} elseif ($breite > $maxwidth && $hoehe > $maxheight) { // zu hoch und zu breit
            $neueBreite = $sollBreite;
            $neueHoehe = intval($hoehe * $neueBreite/$breite); 
            if ($neueHoehe > $sollHoehe) 
            {
                $neueBreite =  intval($neueBreite * $sollHoehe/$neueHoehe);
                $neueHoehe = $sollHoehe;
            }
       	} 
       	else 
       	{
         	$neueBreite = $breite;
         	$neueHoehe  = $hoehe;
       	}
       
       	switch($size[2])
       	{
            case "1": $altesBild = imagecreatefromgif($picture); break;
            case "3": $altesBild = imagecreatefrompng($picture); break;
            default:  $altesBild = imagecreatefromjpeg($picture);
       	}
       
       	$neuesBild=imagecreatetruecolor($neueBreite,$neueHoehe);        
		
       	if (imagecopyresampled($neuesBild,$altesBild,0,0,0,0,$neueBreite,$neueHoehe,$breite,$hoehe)) {
		    $ret = false;
		    switch($size[2])
       		{
            case "1": 
				if (imagegif($neuesBild, $uploaddir."/".$bild)) $ret = true;
				break;
            case "3": 
			    if (imagepng($neuesBild, $uploaddir."/".$bild)) $ret = true;
				break;
            default:  
			    if (imagejpeg($neuesBild, $uploaddir."/".$bild)) $ret = true;
       		}
           	return $ret;
       	}
 
	   	return false;
	}
	
 
}

class EprofilePicturesHandler extends XoopsPersistableObjectHandler 
{
    function __construct($db) 
    {
        parent::__construct($db, 'profile_pictures', 'Eprofilepictures', 'pic_id', 'pic_uid');
    }
	
	function readSpace($uid=0) {
        if (empty($uid)) return -1;
        $criteria = new CriteriaCompo();
        $criteria->add(new Criteria('o.pic_uid',$uid));
        $this->table_link = $this->db->prefix('profile_pictures');
        $pictures = $this->getByLink($criteria,array('o.pic_size'),false,'pic_size');
        $max = 0;
        foreach ($pictures as $p) {
            $max += intval($p['pic_size']);
        }
        if ($max > 0) $max = round(($max / 1024),0);
        else $max = 0;
        return $max;
	}
}
?>