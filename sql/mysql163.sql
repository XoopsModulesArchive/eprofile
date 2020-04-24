CREATE TABLE `profile_configs` (
  `config_id` int(11) NOT NULL auto_increment,
  `config_uid` int(11) NOT NULL,
  `pictures` tinyint(1) NOT NULL,
  `audio` tinyint(1) NOT NULL,
  `videos` tinyint(1) NOT NULL,
  `tribes` tinyint(1) NOT NULL,
  `scraps` tinyint(1) NOT NULL,
  `scraps_notify` tinyint(1) NOT NULL default '1',
  `emails` tinyint(1) NOT NULL default '1',
  `sendscraps` tinyint(1) NOT NULL default '1',
  `friends` tinyint(1) NOT NULL,
  `friends_notify` tinyint(1) NOT NULL default '1',
  `profile_general` tinyint(1) NOT NULL,
  `profile_stats` tinyint(1) NOT NULL,
  PRIMARY KEY  (`config_id`),
  KEY `config_uid` (`config_uid`)
) TYPE=MyISAM;

CREATE TABLE `profile_scraps` (
  `scrap_id` int(11) NOT NULL auto_increment,
  `scrap_text` text NOT NULL,
  `scrap_from` int(11) NOT NULL,
  `scrap_to` int(11) NOT NULL,
  `private` tinyint(1) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`scrap_id`)
) TYPE=MyISAM;

CREATE TABLE `profile_friends` (
  `friend_id` int(11) NOT NULL auto_increment,
  `self_uid` int(11) NOT NULL,
  `friend_uid` int(11) NOT NULL,
  `level` int(11) NOT NULL,
  `date` timestamp NOT NULL default CURRENT_TIMESTAMP,
  PRIMARY KEY  (`friend_id`)
) TYPE=MyISAM;