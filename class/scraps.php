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
 * @version         $Id: scraps.php 2 2012-08-16 08:20:47Z alfred $
 */
 
class EprofileScraps extends XoopsObject  
{
    public function __construct() 
    {
      $this->initVar("scrap_id",XOBJ_DTYPE_INT,null,false,10);
      $this->initVar("scrap_text",XOBJ_DTYPE_TXTAREA, null, false);
      $this->initVar("scrap_from",XOBJ_DTYPE_INT,null,false,10);
      $this->initVar("scrap_to",XOBJ_DTYPE_INT,null,false,10);
      $this->initVar("private",XOBJ_DTYPE_INT,null,false,10);
	}
		
}

class EprofileScrapsHandler extends XoopsPersistableObjectHandler 
{
    public function __construct($db) 
    {
        parent::__construct($db, 'profile_scraps', 'Eprofilescraps', 'scrap_id', 'scrap_from');
    }
	
    public function getScraps($criteria) {
      $myts = MyTextSanitizer::getInstance();
      $ret = array();
      $sql = 'SELECT scrap_id, uid, uname, user_avatar, scrap_from, scrap_text, date FROM '.$this->db->prefix('profile_scraps').' ,'.$this->db->prefix('users')."";
      if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
        $sql .= ' '.$criteria->renderWhere();
		    //attention here this is kind of a hack
		    $sql .= " AND uid = scrap_to" ;
		    if ($criteria->getSort() != '') {
			    $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		    }
		   $limit = $criteria->getLimit();
		   $start = $criteria->getStart();
		
		   $result = $this->db->query($sql, $limit, $start);
		   $vetor = array();
		   $i=0;
		   $member_handler =& xoops_gethandler('member');				
		
		   while ($myrow = $this->db->fetchArray($result)) {
				if ($myrow['scrap_from'] > 0) {
          $scrapUser =& $member_handler->getUser($myrow['scrap_from']);
          if ($scrapUser->isActive() ) {
            $vetor[$i]['uid']         = $scrapUser->uid();
            $vetor[$i]['uname']       = $scrapUser->uname();
            $vetor[$i]['user_avatar'] = $scrapUser->getVar("user_avatar");
          } else {
            $vetor[$i]['uid']         = 0;
            $vetor[$i]['uname']       = $GLOBALS['xoopsConfig']['anonymous'];
            $vetor[$i]['user_avatar'] = 'blank.gif';
          }
				} else {
					$vetor[$i]['uid']         = 0;
					$vetor[$i]['uname']       = $GLOBALS['xoopsConfig']['anonymous'];
					$vetor[$i]['user_avatar'] = 'blank.gif';
				}
				$vetor[$i]['text']  = $myts->displayTarea($myrow['scrap_text'],0,1,1,1,1);        
				$vetor[$i]['id']    = $myrow['scrap_id'];		
				$vetor[$i]['date']  = formatTimestamp(strtotime($myrow['date']),'m');	
				$i++;
		  }		
		  $ret = $vetor;
		}
		return $ret;
	}
	
}
?>