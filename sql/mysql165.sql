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

ALTER TABLE `priv_msgs`  
  ADD `from_delete` tinyint(1) unsigned     NOT NULL default '1',
  ADD `from_save`   tinyint(1) unsigned     NOT NULL default '0',
  ADD `to_delete`   tinyint(1) unsigned     NOT NULL default '0',
  ADD `to_save`     tinyint(1) unsigned     NOT NULL default '0',  
  ADD INDEX `prune` (`msg_time`, `read_msg`, `from_save`, `to_delete`);