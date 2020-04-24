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
 
class ProfileFriends extends XoopsObject  
{
    function __construct() 
    {
        $this->initVar("friend_id",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("self_uid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("friend_uid",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("level",XOBJ_DTYPE_INT,null,false,10);
		$this->initVar("date",XOBJ_DTYPE_OTHER);
	}
	
	function ProfileFriends()
    {
        $this->__construct();
    }
	
}

class ProfileFriendsHandler extends XoopsPersistableObjectHandler 
{
    function __construct($db) 
    {
        parent::__construct($db, 'profile_friends', 'profilefriends', 'friend_id', 'self_uid');
    }	
	
	function getFriends($uid=0,$level=2) 
	{
	  $member_handler = xoops_gethandler('member');
	  //eigene Antraege
	  $criteria = new CriteriaCompo();
      $crit1 = new CriteriaCompo();
	  $crit1->add(new Criteria('self_uid',$uid),'OR');
	  $crit1->add(new Criteria('friend_uid',$uid),'OR');
      $criteria->add($crit1);
      $criteria->add(new Criteria('level',$level));
      $friends = $this->getObjects($criteria,false,false);
	  $vetor = array();
	  $i=0;
	  foreach ($friends as $myrow) 
	  {
	        $friend = ($myrow['friend_uid']==$uid) ? $myrow['self_uid'] : $myrow['friend_uid'];
			$fr = $member_handler->getUser($friend);
			if (is_object($fr) && $fr->uid()>0 && $fr->isactive())
			{
	          	$vetor[$i]['id']			= $myrow['friend_id'];
				$vetor[$i]['uid']			= $fr->uid();
				$vetor[$i]['uname']			= $fr->uname();
				$vetor[$i]['user_avatar']	= $fr->user_avatar();
				$vetor[$i]['date']			= $myrow['date'];
				$i++;
			}
			else
			{
			    // delete inactive users
			    $delcon = $this->get($myrow['friend_id']);
		        if (is_object($delcon)) $this->delete($delcon,true);
			}
	  }
	  return $vetor;
    }
	
	function isFriend($uid=0) 
	{
	  	global $xoopsUser;
	  	if (!$xoopsUser || $uid<1) return false;
	  	$criteria = new CriteriaCompo();
      	$criteria->add(new Criteria('self_uid',$xoopsUser->uid()));
      	$criteria->add(new Criteria('friend_uid',$uid));
	  	$friends = $this->getObjects($criteria);
	  	return (isset($friends[0]))? $friends[0]:false;
	}
	
	function countNewFriends() 
	{
	  	global $xoopsUser;
	  	if (!$xoopsUser) return false;
	  	$criteria = new CriteriaCompo();
      	$criteria->add(new Criteria('friend_uid',$xoopsUser->uid()));
      	$criteria->add(new Criteria('level',1));
	  	$friends = $this->getCount($criteria);
	  	return $friends;
	}
	
	function getLastFriends($uid=0,$limit=5) 
	{
	  	$ret = $this->create();
	  	if (intval($uid)>0 && intval($limit)>0)
	  	{
	    	
	  	}
	  	return $ret;
	}
}
?>