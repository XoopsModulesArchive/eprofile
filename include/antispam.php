<?php

if (!defined("XOOPS_ANTISPAM_INCLUDED")) {
    define("XOOPS_ANTISPAM_INCLUDED", 1);
    
    //Globale Konfiguration
    define("ANTISPAM_APIKEY","");               // Your Key from www.stopforumspam.com
    define("ANTISPAM_AUTOSUBMIT",	"1");         // add in Database 0 -> disabled or 1 -> enabled
    define("ANTISPAM_SENDMAIL",		"1");         // send mail to notifyregisterGroup 0 -> disabled or 1 -> enabled
    define("BLOCK_IP"			  , 5);			          // max. List IP to block
    define("BLOCK_EMAIL"		, 5);			          // max. List IP to email
    define("BLOCK_USERNAME"	,	10);		          // max. List IP to username
    define("ANTISPAM_URL"   , "http://www.stopforumspam.com");

    function check_spam( $uname=null, $email=null ) {
        if ( !uname || !$email ) return false;        
        $member_handler = xoops_gethandler('member');
        $ip = xoops_getenv('REMOTE_ADDR');
        $submitted = false;
        $url = ANTISPAM_URL . "/api?";
        $url .= "ip="         . $ip;    // IP-Adresse
        $url .= "&username="  . $uname; // username
        $url .= "&email="     . $email; //emailadress
        $url .= "&f=serial";            //apicheck
        $data = file_get_contents($url);
        $data = unserialize($data);
        if ($data['success'] == 1) {
            if ($data['username']['appears'] == 1 || $data['email']['appears'] == 1 || $data['ip']['appears'] == 1) {                
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
                    $xoopsMailer->setSubject(_US_USERISSPAMSUBJECT );
                    $body = sprintf(_US_USERISSPAMBODY,$uname);
                    $body .= "\nUsername: ".$uname;
                    $body .= "\nEMail: ".$email;
                    $body .= "\nIP: ".$ip;
                    $body .= "\n\nListed on: ";
                    if ($data['username']['appears'] == 1) $body .= "\nUsername => " . $data['username']['frequency'];
                    if ($data['email']['appears'] == 1) $body .= "\nEmail => " . $data['email']['frequency'];
                    if ($data['ip']['appears'] == 1) $body .= "\nIP  => " . $data['ip']['frequency'];                    
                    if ( $submitted == true ) {
                        $body .= "\n\n------------\n";
                        $body .= "Data added to " . ANTISPAM_URL . "\n";
                        $body .= $result;
                    }
                    $xoopsMailer->setBody($body);
                    $xoopsMailer->send();
                    return $submitted; 
                }
              return $dopost;
            }
        }
        // Check is failed !!!
        return false;
    }

    function submit_spam($uname, $ip, $em) {
        $postdata = http_build_query(
                array(  
                        'username' 	=> $urlencode($uname),
                        'ip_addr' 	=> $ip,
                        'email' 	  => $urlencode($em),
                        'api_key' 	=> ANTISPAM_APIKEY)
        );
        $opts = array(
                'http' => array(
                        'method'  => 'POST',
                        'header'  => 'Content-type: application/x-www-form-urlencoded',
                        'content' => $postdata
                )
        );
        $context  = stream_context_create($opts);
        $result = file_get_contents(ANTISPAM_URL . '/post.php', false, $context);
        return $result;
    }
}
?>