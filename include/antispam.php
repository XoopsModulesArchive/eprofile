<?php

if (!defined("XOOPS_ANTISPAM_INCLUDED")) {
    define("XOOPS_ANTISPAM_INCLUDED", 1);
    
    //Globale Konfiguration
    define("ANTISPAM_APIKEY"      , xoops_getModuleOption('profile_antispam_apikey', 'eprofile')); 
    define("ANTISPAM_AUTOSUBMIT"  ,	xoops_getModuleOption('profile_antispam_submit', 'eprofile'));
    define("ANTISPAM_SENDMAIL"    ,	xoops_getModuleOption('profile_antispam_sendmail', 'eprofile'));
    $as_ip = (xoops_getModuleOption('profile_antispam_ip', 'eprofile') < 1 ) ? 1 : xoops_getModuleOption('profile_antispam_ip', 'eprofile');
    define("BLOCK_IP"			  , $as_ip);
    $as_email = (xoops_getModuleOption('profile_antispam_email', 'eprofile') < 1 ) ? 1 : xoops_getModuleOption('profile_antispam_email', 'eprofile');
    define("BLOCK_EMAIL"		, $as_email);
    $as_uname = (xoops_getModuleOption('profile_antispam_uname', 'eprofile') < 1 ) ? 1 : xoops_getModuleOption('profile_antispam_uname', 'eprofile');
    define("BLOCK_USERNAME"	,	$as_uname);		          

    function check_spam( $uname=null, $email=null ) {
		if ( !$uname || !$email ) return false;
        $member_handler = xoops_gethandler('member');
        $ip = xoops_getenv('REMOTE_ADDR');
        $submitted = false;
        $url = "http://www.stopforumspam.com/api?";
        $url .= "ip="         . $ip;    // IP-Adresse
        $url .= "&username="  . $uname; // username
        $url .= "&email="     . $email; //emailadress
        $url .= "&f=serial";            //apicheck
        $data = file_get_contents($url);
        $data = unserialize($data);        
        if ($data['success'] == 1) {
            if ($data['username']['appears'] == 1 || $data['email']['appears'] == 1 || $data['ip']['appears'] == 1) { 
                $result = false;
                if ( $data['email']['appears'] == 1 ) {
                  if ($data['email']['frequency'] > BLOCK_EMAIL) $submitted = true;
                }
                if ($data['ip']['appears'] == 1 ) {                    
                    if ($data['ip']['frequency'] > BLOCK_IP) $submitted = true;
                }
                if ($data['username']['appears'] == 1 && $data['username']['frequency'] > 1 ) {
                    if ($data['username']['frequency'] > BLOCK_USERNAME) $submitted = true;
                }
                if ( ANTISPAM_AUTOSUBMIT == 1 && ANTISPAM_APIKEY !='' ) {                    
                    if ( $submitted == true ) {
                        //send spammer to www.stopforumspam.com
                        $result = submit_spam($uname, $ip, $email); 
                    }
                }
                
                if ( ANTISPAM_SENDMAIL == 1 && $submitted == true) {
                    $xoopsMailer = xoops_getMailer();
                    $xoopsMailer->reset();
                    $xoopsMailer->useMail();
                    $xoopsMailer->setToGroups($member_handler->getGroup($GLOBALS['xoopsConfigUser']['new_user_notify_group']) );
                    $xoopsMailer->setSubject(_EPROFILE_MA_USERISSPAMSUBJECT);
                    $body = _EPROFILE_MA_USERISSPAMBODY;
                    $body .= "\nUsername: ".$uname;
                    $body .= "\nEMail: ".$email;
                    $body .= "\nIP: ".$ip;
                    $body .= "\n\nListed on: ";
                    if ($data['username']['appears'] == 1) $body .= "\nUsername => " . $data['username']['frequency'];
                    if ($data['email']['appears'] == 1) $body .= "\nEmail => " . $data['email']['frequency'];
                    if ($data['ip']['appears'] == 1) $body .= "\nIP  => " . $data['ip']['frequency'];                    
                    if ( $result == true ) {
                        $body .= "\n\n------------\n";
                        $body .= "Data added to http://www.stopforumspam.com\n";
                    }
                    $xoopsMailer->setBody($body);
                    $xoopsMailer->send();
                    return $submitted; 
                }              
            }
        }
        
        return $submitted;
    }
    
    
    function submit_spam($uname, $ip, $em) {
    
      $data = "username=" . urlencode($uname);
      $data .= "&ip_addr=" . $ip;
      $data .= "&email=" . urlencode($em);
      $data .= "&api_key=" . ANTISPAM_APIKEY;
      
      $ret = false;
      $fp = fsockopen("www.stopforumspam.com",80);
      if ($fp) {
        fputs($fp, "POST /add.php HTTP/1.1\n" );
        fputs($fp, "Host: www.stopforumspam.com\n" );
        fputs($fp, "Content-type: application/x-www-form-urlencoded\n" );
        fputs($fp, "Content-length: " . strlen($data)."\n" );
        fputs($fp, "Connection: close\n\n" );
        if (fputs($fp, $data)) $ret = true;
        fclose($fp);
        return $ret;
      } 
    }
}
?>