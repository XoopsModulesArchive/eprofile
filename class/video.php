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
 * @version         $Id: video.php 2 2012-08-16 08:20:47Z alfred $
 */
 
class EprofileVideo extends XoopsObject  
{
    var $upload_path 	= "";
	var $upload_url 	= "";	
    
	function __construct() 
    {
        parent::__construct();
		$this->initVar("video_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_owner",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("video_desc",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("youtube_code",XOBJ_DTYPE_TXTBOX, null, false);
		$this->initVar("main_video",XOBJ_DTYPE_TXTBOX, null, false);		
	}

}

class EprofileVideoHandler extends XoopsPersistableObjectHandler 
{
    function __construct($db) 
    {
        parent::__construct($db, 'profile_videos', 'Eprofilevideo', 'video_id', 'uid_owner');
    }
    
    function assignVideoContent($nbVideos, $videos)	
    {
    	if ($nbVideos==0)
        {
			return false;
		} 
        else 
        {
			$i = 0;
			foreach ($videos as $video)
            {
				$videos_array[$i]['url']      = str_replace("http://www.youtube.com/watch?v=","",$video->getVar("youtube_code","s"));
				$videos_array[$i]['desc']     = $video->getVar("video_desc","s");
				$videos_array[$i]['id']  	  = $video->getVar("video_id","s");				
				$i++;
			}
		   return $videos_array;
		}
	}
	
}
?>