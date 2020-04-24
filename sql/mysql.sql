CREATE TABLE `profile_category` (
  `cat_id`          smallint(5) unsigned    NOT NULL auto_increment,
  `cat_title`       varchar(255)            NOT NULL default '',
  `cat_description` text,
  `cat_weight`      smallint(5) unsigned    NOT NULL default '0',
  
  PRIMARY KEY  (`cat_id`)
) TYPE=MyISAM;

CREATE TABLE `profile_field` (
  `field_id`            int(12) unsigned        NOT NULL auto_increment,
  `cat_id`              smallint(5) unsigned    NOT NULL default '0',
  `field_type`          varchar(30)             NOT NULL default '',
  `field_valuetype`     tinyint(2) unsigned     NOT NULL default '0',
  `field_name`          varchar(255)            NOT NULL default '',
  `field_title`         varchar(255)            NOT NULL default '',
  `field_description`   text,
  `field_required`      tinyint(1) unsigned     NOT NULL default '0',
  `field_maxlength`     smallint(6) unsigned    NOT NULL default '0',
  `field_weight`        smallint(6) unsigned    NOT NULL default '0',
  `field_default`       text,
  `field_notnull`       tinyint(1) unsigned     NOT NULL default '0',
  `field_edit`          tinyint(1) unsigned     NOT NULL default '0',
  `field_show`          tinyint(1) unsigned     NOT NULL default '0',
  `field_config`        tinyint(1) unsigned     NOT NULL default '0',
  `field_options`       text,
  `step_id`             smallint(3) unsigned    NOT NULL default '0',
  
  PRIMARY KEY  (`field_id`),
  UNIQUE KEY `field_name` (`field_name`),
  KEY `step` (`step_id`, `field_weight`)
) TYPE=MyISAM;

CREATE TABLE `profile_visibility` (
  `field_id`        int(12) unsigned        NOT NULL default '0',
  `user_group`      smallint(5) unsigned    NOT NULL default '0',
  `profile_group`   smallint(5) unsigned    NOT NULL default '0',
  
  PRIMARY KEY (`field_id`, `user_group`, `profile_group`),
  KEY `visible` (`user_group`, `profile_group`)
) TYPE=MyISAM;

CREATE TABLE `profile_regstep` (
  `step_id`         smallint(3) unsigned    NOT NULL auto_increment,
  `step_name`       varchar(255)            NOT NULL DEFAULT '',
  `step_desc`       text,
  `step_order`      smallint(3) unsigned    NOT NULL default '0',
  `step_save`       tinyint(1) unsigned     NOT NULL default '0',
  
  PRIMARY KEY (`step_id`),
  KEY `sort` (`step_order`, `step_name`)
) Type=MyISAM;

CREATE TABLE `profile_profile` (
  `profile_id`      int(12) unsigned        NOT NULL default '0',  
  PRIMARY KEY  (`profile_id`)
) TYPE=MyISAM;

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
  `profile_messages` tinyint(1) NOT NULL default '1',
  `profile_facebook` tinyint(1) NOT NULL default '0',
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

CREATE TABLE `profile_pictures` (
  `pic_id` int(11) NOT NULL auto_increment,
  `pic_uid` int(11) NOT NULL,
  `pic_title` varchar(255) NOT NULL,
  `pic_desc` text,
  `pic_size` int(11) NOT NULL,
  `pic_url` VARCHAR( 255 ) NOT NULL,
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

CREATE TABLE `profile_audio` (
  `audio_id` int(11) NOT NULL auto_increment,
  `audio_uid` int(11) NOT NULL,
  `title` varchar(256) NOT NULL,
  `author` varchar(256) NOT NULL,
  `url` varchar(256) NOT NULL,
  `audio_size` int(11) NOT NULL,
  `data_creation` date NOT NULL,
  `data_update` date NOT NULL,
  PRIMARY KEY  (`audio_id`)
) TYPE=MyISAM;

CREATE TABLE `profile_videos` (
  `video_id` int(11) NOT NULL auto_increment,
  `uid_owner` int(11) NOT NULL,
  `video_desc` text NOT NULL,
  `youtube_code` text NOT NULL,
  `main_video` varchar(1) NOT NULL,
  PRIMARY KEY  (`video_id`)
) TYPE=MyISAM;