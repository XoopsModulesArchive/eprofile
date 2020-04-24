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
 
class ProfileScraps extends XoopsObject  
{
    function __construct() 
    {
        $this->initVar("scrap_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("scrap_text",XOBJ_DTYPE_TXTAREA, null, false);
		$this->initVar("scrap_from",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("scrap_to",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("private",XOBJ_DTYPE_INT,null,false,10);
	}
	
	function ProfileScraps()
    {
        $this->__construct();
    }
	
}

class ProfileScrapsHandler extends XoopsPersistableObjectHandler 
{
    function __construct($db) 
    {
        parent::__construct($db, 'profile_scraps', 'profilescraps', 'scrap_id', 'scrap_from');
    }
	
	function getScraps($criteria)
	{
		$myts = new MyTextSanitizer();
		$ret = array();
		$sql = 'SELECT scrap_id, uid, uname, user_avatar, scrap_from, scrap_text, date FROM '.$this->db->prefix('profile_scraps').', '.$this->db->prefix('users');
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
			$sql .= ' '.$criteria->renderWhere();
		    //attention here this is kind of a hack
		    $sql .= " AND uid = scrap_from" ;
		    if ($criteria->getSort() != '') {
			    $sql .= ' ORDER BY '.$criteria->getSort().' '.$criteria->getOrder();
		    }
		   $limit = $criteria->getLimit();
		   $start = $criteria->getStart();
		
		   $result = $this->db->query($sql, $limit, $start);
		   $vetor = array();
		   $i=0;
		
		   while ($myrow = $this->db->fetchArray($result)) {			
			  $vetor[$i]['uid']= $myrow['uid'];
			  $vetor[$i]['uname']= $myrow['uname'];
			  $vetor[$i]['user_avatar']= $myrow['user_avatar'];
              $vetor[$i]['text'] = $myts->displayTarea($myrow['scrap_text']);
			  $vetor[$i]['id']= $myrow['scrap_id'];		
			  $vetor[$i]['date']= formatTimestamp(strtotime($myrow['date']),'m');	
			  $i++;
		  }		
		  $ret = $vetor;
		}
		return $ret;
	}
	
}
?>