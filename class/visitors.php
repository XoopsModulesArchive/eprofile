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
 * @version         $Id: visitors.php 7 2012-10-07 15:38:55Z alfred $
 */
 
class EprofileVisitors extends XoopsObject  
{
    public function __construct() 
    {
        $this->initVar("visit_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_owner",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("uid_visitor",XOBJ_DTYPE_INT,null,false,10);
        $this->initVar("uname_visitor",XOBJ_DTYPE_TXTAREA, null, false);
        $this->initVar("datetime",XOBJ_DTYPE_TXTAREA, null, false);
	}
	
}

class EprofileVisitorsHandler extends XoopsPersistableObjectHandler 
{
    public function __construct($db) 
    {
        parent::__construct($db, 'profile_visitors', 'Eprofilevisitors', 'visit_id', 'uid_owner');
    }
	
	public function setvisit($uid=0)
    {
        global $xoopsUser;
        $selfuser = ($xoopsUser) ? $xoopsUser->uid() : 0;
        if ($selfuser<1 || $uid <1) return;
        if ($selfuser == $uid ) return;
        $criteria = new CriteriaCompo();
		$criteria->add(new Criteria('uid_owner',$uid));
		$criteria->add(new Criteria('uid_visitor',$selfuser));
        $criteria->setSort('visit_id');
  		$criteria->setOrder('DESC');
        $vluser = $this->getObjects($criteria);
        $i=0;
        foreach ($vluser as $vluser2) {
          if ($i == 0) 
            $vuser = $this->get($vluser2->getVar('visit_id'));
          else 
            $this->delete($vluser2,true); 
          $i++;
        }
        $time = formatTimestamp(time()-(60*10),'mysql');
        if (!isset($vuser)) $vuser = $this->create();
        if ( $time > $vuser->getVar('datetime')) {
          $vuser->setVar('uid_owner',$uid);
          $vuser->setVar('uid_visitor',$selfuser);
          $vuser->setVar('uname_visitor',$xoopsUser->uname());
          $time = formatTimestamp(time(),'mysql');
          $vuser->setVar('datetime',$time);
          $this->insert($vuser);
        }
    }
    
    public function getvisit()
    {
        global $xoopsUser;
        $ret = array();
        $selfuser = ($xoopsUser) ? $xoopsUser->uid() : 0;
        if ($selfuser<1) return $ret;
        $sql = "SELECT visit_id, uid_visitor, uname_visitor, UNIX_TIMESTAMP(datetime) AS datetime FROM " . $this->table . " WHERE uid_owner=".$selfuser . " ORDER BY datetime DESC LIMIT 10";
        $res = $this->db->query($sql);
        while ($row = $this->db->fetcharray($res) ) {
          $ret[] = array('uid' => $row['uid_visitor'], 'name' => $row['uname_visitor'], 'time' => formattimestamp($row['datetime']));
        }
        return $ret;
	}
}
?>