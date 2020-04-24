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
 * @version         $Id: configs.php 32 2014-02-08 10:30:45Z alfred $
 */

class EprofileConfigs extends XoopsObject  
{
    public function __construct() 
    {
      global $xoopsModuleConfig;
      $this->initVar("config_id",			  XOBJ_DTYPE_INT,null,false,10);
      $this->initVar("config_uid",		  XOBJ_DTYPE_INT,null,false,10);
      $this->initVar("pictures",			  XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("audio",				    XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("videos",			    XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("tribes",			    XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("tribes_notify",	  XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("scraps",			    XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("scraps_notify",		XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("sendscraps",		  XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("friends",			    XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("friends_notify",	XOBJ_DTYPE_INT,0,false,1);		
      $this->initVar("emails",			    XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("profile_general",	XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("profile_stats",		XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("profile_messages",XOBJ_DTYPE_INT,0,false,1);
      $this->initVar("messages_notify",	XOBJ_DTYPE_INT,0,false,1);
    }

}


class EprofileConfigsHandler extends XoopsPersistableObjectHandler 
{
  public function __construct($db) 
  {
    parent::__construct($db, 'profile_configs', 'Eprofileconfigs', 'config_id', 'config_uid');
  }
	
	public function getperm($art=null, $uid=null, $accessgroup=0) 
	{
	  	global $xoopsUser,$xoopsModuleConfig;
	  	if( $art == null || $uid <= 0 ) return false;
           
      if (in_array($art,array("emails", "scraps", "pictures", "audio", "videos", "tribes", "friends"))) {
	    	if ( $xoopsModuleConfig["profile_".$art] != 1 ) return false;
	  	}
	  	$criteria = new Criteria('config_uid',$uid);
      $configs = $this->getObjects($criteria);
	  	$config = ($configs) ? $configs[0] : false;
	  	if ($art == "all") {
    
	    	$u = array( 'profile_general'  => $xoopsModuleConfig['profile_search'],
                    'profile_stats'    => $xoopsModuleConfig['profile_stats'],
                    'profile_messages' => $xoopsModuleConfig['profile_messages'],
                    'scraps'           => $xoopsModuleConfig['profile_scraps'],
                    'emails'           => $xoopsModuleConfig['profile_emails'],
                    'friends'          => $xoopsModuleConfig['profile_friends'],
                    'pictures'         => $xoopsModuleConfig['profile_pictures'],
                    'audio'            => $xoopsModuleConfig['profile_audio'],
                    //'tribes'           => $xoopsModuleConfig['profile_tribes'],
                    'videos'           => $xoopsModuleConfig['profile_videos'],
                    'scraps_notify'    => true,
                    'sendscraps'       => true,
                    'friends_notify'   => true,
                    'messages_notify'  => true,
                    'tribes_notify'    => true,
                    'isConfig'         => false
                  );
                  
	    	if ($config != false)	{ 
          $config = $config->toArray();                  
		   		foreach ($config as $v => $wert) { 
            if (in_array($v,array("emails", "scraps", "pictures", "audio", "videos", "friends"/*, "tribes"*/))) {
              if ( $GLOBALS["xoopsModuleConfig"]["profile_".$v] != 1 ) {
                $u[$v] = false;
              } elseif ( $wert <= $accessgroup && $wert > 0) {
                $u[$v] = true;
              } else {
                $u[$v] = false;
              }              
            } elseif (in_array($v,array("profile_stats", "profile_messages", "profile_general", "messages_notify", "scraps_notify", /*"tribes_notify", */"sendscraps"))) {
              if ( $wert <= $accessgroup && $wert > 0) {
                $u[$v] = true;
              } else {
                $u[$v] = false;
              }            
            }
          }
          $u['isConfig'] = true; 
        }         
        return $u;
        
      } else {
	    	$wert = ($config) ? $config->getVar($art) : 0;        
        if ( $wert == 1 ) return true;
	    	elseif ( $wert == 2 && $xoopsUser ) return true;
	    	elseif ( $wert == 3 ) return $this->isMyFriend($uid); 
	    	elseif ( $wert == 4 && ($xoopsUser && $xoopsUser->uid() == $uid)) return true;
	  	}
      return false;
    }
	
	public function getCount($table='',$art='',$uid=0,$zusatz=null) 
	{
	   	$ret = 0;
	   	if (intval($uid) > 0 && $table != '' && $art != '') {
	     	$sql_count = "SELECT COUNT(*) FROM " . $this->db->prefix($table) . " WHERE " . $art . "=".$uid;
        if (!empty($zusatz))  $sql_count .= " AND ".$zusatz;
        $result = $this->db->query($sql_count);
        list($count) = $this->db->fetchRow($result);
        $ret = intval($count);
       }
	   	return $ret;
	}
	
	public function getStat($art='',$uid=0) 
	{
	   	$ret = 0;
	   	if (intval($uid)>0 && $art!='') {
	     	$sql_count = "SELECT ".$art." FROM " . $this->db->prefix('profile_configs') . " WHERE config_uid=".$uid." LIMIT 0,1";
        $result = $this->db->query($sql_count);
        list($count) = $this->db->fetchRow($result);
	     	$ret = intval($count);
	   }
	   return $ret;
	}
	
	public function isMyFriend($uid=0) 
	{
	   	$self = (is_object($GLOBALS['xoopsUser'])) ? $GLOBALS['xoopsUser']->uid() : 0;
      if (intval($uid) > 0 && intval($self) > 0)
	   	{
	     	$sql = "SELECT friend_uid FROM ".$this->db->prefix('profile_friends')." WHERE (self_uid =".$self." AND friend_uid=".$uid.") or (friend_uid =".$self." AND self_uid=".$uid.") AND level=2 LIMIT 0,1";
        $res = $this->db->query($sql);
        if ($res && $this->db->getRowsNum($res) > 0) return true;
	   	}
	   	return false;	
	}
	
	public function selectFriendLevel($uid=0,$level=0) 
	{
	   global $xoopsUser;
	   if ($uid==0 || !is_object($xoopsUser) || $xoopsUser->uid() < 1 ) return false;
	   $self = $xoopsUser->uid();
	   $addlevel = (intval($level) > 0) ? " AND level=" . $level : "";
	   $sql = "SELECT level,self_uid FROM " . $this->db->prefix('profile_friends') . " WHERE (self_uid =" . $self . " AND friend_uid=" . $uid . ") or (friend_uid =" . $self . " AND self_uid=" . $uid . ")". $addlevel ." LIMIT 0,1";
     $res = $this->db->query($sql);
	   if ($res && $this->db->getRowsNum($res) > 0) 
	   {
	      list ($level,$suid) = $this->db->fetchRow($res);
        if ($suid == $self) $level = $level * -1;
        return $level;
	   }
	   return false;
	}
	
	public function readPreList($art="", $limit=0, $crit=array(), $sort = 'date')
	{
	  if (intval($limit) <= 0 || !in_array($art,array('scraps','pictures','video')) || !is_array($crit) || count($crit)==0) return false;
    
    $_handler = xoops_getmodulehandler($art);
		if (!$_handler) return false;
		$criteria = new CriteriaCompo();
		foreach ($crit as $k => $v) 
		{
  		$criteria->add(new Criteria($k,$v));
		}
  	$criteria->setLimit($limit);
  	$criteria->setSort($sort);
  	$criteria->setOrder('DESC');
  	$list = $_handler->getObjects($criteria);
	  return $list;
	}
	
	public function getLatestFriends($uid=0,$limit=0)
	{
		$self = (is_object($GLOBALS['xoopsUser'])) ? $GLOBALS['xoopsUser']->uid() : 0;
		if (intval($limit) <=0 || intval($uid) <= 0 ) return false;
		$_handler = xoops_getmodulehandler('friends');
		if (!$_handler) return false;
		$criteria = new CriteriaCompo();
    $crit1 = new CriteriaCompo();
		$crit1->add(new Criteria('self_uid',$uid),'OR');
		$crit1->add(new Criteria('friend_uid',$uid),'OR');
    $crit2 = new CriteriaCompo();
		$crit2->add(new Criteria('level','2','='));
    $criteria->add($crit1);
    $criteria->add($crit2);
		$criteria->setLimit($limit);
    $criteria->setSort('date');
  	$criteria->setOrder('DESC');
  	$list = $_handler->getObjects($criteria);
	  return $list;
	}
}
?>