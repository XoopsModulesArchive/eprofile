ALTER TABLE `profile_configs` ADD `messages_notify` tinyint(1) NOT NULL default '0';
ALTER TABLE `profile_configs` ADD `tribes_notify` tinyint(1) NOT NULL default '0';
ALTER TABLE `profile_configs` ADD `user_module` tinyint(1) NOT NULL default '0';
ALTER TABLE `priv_msgs` ADD  `from_save` INT( 2 ) NOT NULL;
ALTER TABLE `priv_msgs` ADD  `from_delete` INT( 2 ) NOT NULL;
ALTER TABLE `priv_msgs` ADD  `to_delete` INT( 2 ) NOT NULL;
ALTER TABLE `priv_msgs` ADD  `to_save` INT( 2 ) NOT NULL;
ALTER TABLE `users` CHANGE  `user_avatar`  `user_avatar` VARCHAR( 250 );
ALTER TABLE `profile_friends` ADD `date` timestamp NOT NULL default CURRENT_TIMESTAMP;

CREATE TABLE `profile_tribes` (
  `tribes_id` int(11) NOT NULL auto_increment,
  `tribes_owner` int(11) NOT NULL,
  `tribes_desc` varchar(255) NOT NULL,
  `tribes_url` varchar(255) NOT NULL,
  `tribes_visible` int(1) NOT NULL,
  `tribes_uid` int(11) NOT NULL,
  `tribes_uidstatus` int(1) NOT NULL,
  PRIMARY KEY  (`tribes_id`)
) ENGINE=MyISAM;
