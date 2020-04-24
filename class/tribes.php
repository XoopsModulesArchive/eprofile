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
 * @since           4.4.0
 * @author          Dirk Herrmann <dhcst@users.sourceforge.net>
 * @version         $Id: tribes.php 24 2013-05-24 19:31:14Z alfred $
 */
 
class EprofileTribes extends XoopsObject
{
    var $upload_path 	= "";
    var $upload_url 	= "";	
    
    public function __construct() 
    {
        $this->initVar("tribes_id",        XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("tribes_owner",     XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("tribes_desc",      XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("tribes_url",       XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("tribes_visible",   XOBJ_DTYPE_INT,1,false,1);
        $this->initVar("tribes_uid",       XOBJ_DTYPE_INT,null,false,11);
        $this->initVar("tribes_uidstatus", XOBJ_DTYPE_INT,0,false,11);
        $this->upload_path 	= XOOPS_UPLOAD_PATH . "/" . $GLOBALS['xoopsModule']->dirname();
        $this->upload_url 	= XOOPS_UPLOAD_URL  . "/" . $GLOBALS['xoopsModule']->dirname();
    }
}

class EprofileTribesHandler extends XoopsPersistableObjectHandler
{
    
    public function __construct(&$db)
    {
        parent::__construct($db, "profile_tribes", 'EprofileTribes', "tribes_id");
    }
    
    public function getCountTribes($uid=null)
    {
      if ($uid < 0) return false;
      $criteria = new CriteriaCompo();
      $criteria->add(new Criteria('tribes_owner',$uid));
      $ret = $this->getCount($criteria);
      return $ret;
    }
    
    public function getTribesCheck($title=null)
    {
      if ( trim($title) == '') return false;
      $criteria = new CriteriaCompo();
      $criteria->add(new Criteria('tribes_desc',$title));
      $ret = ( $this->getCount($criteria) > 0 ) ? 1 : 0;
      return $ret;     
    }
    
    public function resizeImg($picture, $maxwidth=100, $maxheight=100, $uploaddir) 
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
       	} 
        elseif ($breite > $maxwidth && $hoehe > $maxheight) 
        { // zu hoch und zu breit
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
?>