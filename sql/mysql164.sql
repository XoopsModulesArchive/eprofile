CREATE TABLE `profile_pictures` (
  `pic_id` int(11) NOT NULL auto_increment,
  `pic_uid` int(11) NOT NULL,
  `pic_title` varchar(255) NOT NULL,
  `pic_desc` text,
  `pic_size` int(11) NOT NULL,
  `pic_url` varchar(255) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  `private` tinyint(1) NOT NULL,
  PRIMARY KEY  (`pic_id`)
) TYPE=MyISAM;

CREATE TABLE `profile_visitors` (
  `visit_id` int(11) NOT NULL auto_increment,
  `uid_owner` int(11) NOT NULL,
  `uid_visitor` int(11) NOT NULL,
  `uname_visitor` varchar(30) NOT NULL,
  `datetime` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`visit_id`)
) TYPE=MyISAM;

