<?php
// $Id: modinfo.php 2 2012-08-16 08:20:47Z alfred $
// _LANGCODE: de
// _CHARSET : UTF-8
// Translator: XOOPS Translation Team

define("_EPROFILE_MI_NAME", "User Profiles");
define("_EPROFILE_MI_DESC", "Module to manage extended user profile fields");

//Main menu links
define("_EPROFILE_MI_EDITACCOUNT", "Edit Account");
define("_EPROFILE_MI_CHANGEPASS", "Change Password");
define("_EPROFILE_MI_CHANGEMAIL", "Change E-mail");

//Admin links
define("_EPROFILE_MI_INDEX", "Index");
define("_EPROFILE_MI_CATEGORIES", "Categories");
define("_EPROFILE_MI_FIELDS", "Fields");
define("_EPROFILE_MI_USERS", "User");
define("_EPROFILE_MI_STEPS", "Registration Steps");
define("_EPROFILE_MI_PERMISSIONS", "Permissions");

//User Profile Category
define("_EPROFILE_MI_CATEGORY_TITLE", "User Profile");
define("_EPROFILE_MI_CATEGORY_DESC", "For these user fields");

//Configuration categories
define("_EPROFILE_MI_CAT_SETTINGS", "General Settings");
define("_EPROFILE_MI_CAT_SETTINGS_DSC", "");
define("_EPROFILE_MI_CAT_USER", "User Settings");
define("_EPROFILE_MI_CAT_USER_DSC", "");

//Configuration items
define("_EPROFILE_MI_EPROFILE_SEARCH", "Latest entries in the user view his profile");
define("_EPROFILE_MI_EPROFILE_SEARCH_DESC", "");

//Pages
define("_EPROFILE_MI_PAGE_INFO", "User Info");
define("_EPROFILE_MI_PAGE_EDIT", "Edit User");
define("_EPROFILE_MI_PAGE_SEARCH", "Search");

define("_EPROFILE_MI_STEP_BASIC", "Default");
define("_EPROFILE_MI_STEP_COMPLEMENTARY", "Advanced");

define("_EPROFILE_MI_CATEGORY_PERSONAL", "Personally");
define("_EPROFILE_MI_CATEGORY_MESSAGING", "News");
define("_EPROFILE_MI_CATEGORY_SETTINGS", "Preferences");
define("_EPROFILE_MI_CATEGORY_COMMUNITY", "Community");

define("_EPROFILE_MI_NEVER_LOGED_IN", "so far no login");

//---------------------------------------
// new Profilesystem with integrated yogurt
//--------------------------------------

define("_EPROFILE_MI_EPROFILE_EMAILS", "Enable email system");
define("_EPROFILE_MI_EPROFILE_EMAILS_DESC", "It allows users to send each other emails without having to exchange e mail addresses");

define("_EPROFILE_MI_EPROFILE_MESSAGES", "Enable Private Messages");
define("_EPROFILE_MI_EPROFILE_MESSAGES_DESC", "allows users to send each other private messages");
define("_EPROFILE_MI_EPROFILE_SCRAPS", "Enable Guest Book");
define("_EPROFILE_MI_EPROFILE_SCRAPS_DESC", "Guest book for each user to enable or disable");
define("_EPROFILE_MI_EPROFILE_FRIENDS", "Enable friends list");
define("_EPROFILE_MI_EPROFILE_FRIENDS_DESC", "Users can create a friends list");
define("_EPROFILE_MI_EPROFILE_PICTURES", "Enable Image Gallery");
define("_EPROFILE_MI_EPROFILE_PICTURES_DESC", "allows users uploading pictures and display as gallery");
define("_EPROFILE_MI_EPROFILE_VIDEOS", "Activate videos");
define("_EPROFILE_MI_EPROFILE_VIDEOS_DESC", "Setting User Videos");
define("_EPROFILE_MI_EPROFILE_AUDIOS", "Enable Music");
define("_EPROFILE_MI_EPROFILE_AUDIOS_DESC", "Users can upload their music available");
define("_EPROFILE_MI_EPROFILE_TRIBES", "Enable Group System");
define("_EPROFILE_MI_EPROFILE_TRIBES_DESC", "allows users to group together in separate groups");
define("_EPROFILE_MI_EPROFILE_PREVIEW", "Number of objects Preview");
define("_EPROFILE_MI_EPROFILE_PREVIEW_DESC", "specifies the number of objects that should appear on the overview <br /> 0 = disabled");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSPACE", "Max storage per user");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSPACE_DESC", "This space is available to any user to upload images <br /> Data in kilobytes (kb), this is 1MB = 1024 kB");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSOLOSPACE", "max. Uplodgröße per image");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSOLOSPACE_DESC", "that is the size of an image can have a maximum. <br /> Data in kilobytes (kb), this is 1MB = 1024 kB");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXHEIGTH", "max. Height of an image");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXHEIGTH_DESC", "This value specifies the maximum height of the image, which can upload one. <br > Data in pixels");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXWIDTH", "max. Width of an image");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXWIDTH_DESC", "This value specifies the maximum width of the image, which can upload one. <br > Data in pixels");
define("_EPROFILE_MI_EPROFILE_PERPAGE", "Number of entries per page");
define("_EPROFILE_MI_EPROFILE_PERPAGE_DESC", "Number of objects on the category page jeweilgen");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBHEIGTH", "max. Height of the preview image");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBHEIGTH_DESC", "This value specifies the maximum height of the preview image, the image is larger it will be automatically resized. <br > Data in pixels");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBWIDTH", "max. Width of the thumbnail");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBWIDTH_DESC", "This value specifies the maximum width of the preview image, the image is larger it will be automatically resized. <br > Data in pixels");
define("_EPROFILE_MI_EPROFILE_AUDIOMAXSPACE", "max. Space for audio files per user");
define("_EPROFILE_MI_EPROFILE_AUDIOMAXSPACE_DESC", "This is the maximum size that may be occupied by music. <br /> Data in kilobytes (kb), this is 1MB = 1024 kB");
define("_EPROFILE_MI_EPROFILE_WITHTUBE", "Width of the video in the view");
define("_EPROFILE_MI_EPROFILE_WITHTUBE_DESC","");
define("_EPROFILE_MI_EPROFILE_HEIGTHTUBE", "Height of the video in the view");
define("_EPROFILE_MI_EPROFILE_HEIGTHTUBE_DESC","");
define("_EPROFILE_MI_EPROFILE_TRIBESMAX", "max. Number of groups per user");
//ADD in 1.69
define("_EPROFILE_MI_LEER","");
define("_EPROFILE_MI_EPROFILE_SCRAPSTITLE","Guest book");
define("_EPROFILE_MI_EPROFILE_FRIENDSTITLE","friends list");
define("_EPROFILE_MI_EPROFILE_PICTURESTITLE","Image Gallery");
define("_EPROFILE_MI_EPROFILE_AUDIOSTITLE","Music");
define("_EPROFILE_MI_EPROFILE_VIDEOSTITLE","User Videos");
define("_EPROFILE_MI_EPROFILE_GROUPSTITLE","Groups");
//Add in 1.70
define("_EPROFILE_MI_EPROFILE_AUDIOPLAYER","audio player");
define("_EPROFILE_MI_EPROFILE_AUDIOPLAYER_DESC","the audio player used to");
//Add in 1.71
define("_EPROFILE_MI_ABOUT","About");
define("_EPROFILE_MI_EPROFILE_AVATARSEARCH","Show avatar in search");
define("_EPROFILE_MI_EPROFILE_AVATARSEARCH_desc","Shows in the results with the avatar of the user to");
//Add in 1.72
define("_EPROFILE_MI_BLOCK_ONLINE","Wer ist online");
define("_EPROFILE_MI_BLOCK_AVATAR","Avatar anzeigen");
define("_EPROFILE_MI_BLOCK_MNAME","Richtigen Namen statt Usernamen anzeigen");
define("_EPROFILE_MI_BLOCK_MCOUNT","Anzahl User die direkt angezeigt werden");
define("_EPROFILE_MI_BLOCK_VERTICAL","User untereinander anzeigen");
define("_EPROFILE_MI_BLOCK_HORZONTAL","User nebeneinander anzeigen");

define("_EPROFILE_MI_EPROFILE_STATS","Besucherstatistiken anzeigen");
define("_EPROFILE_MI_EPROFILE_STATS_DESC","User können in Ihrem Profil sehen, wer sie besucht hat.");

// add 1.73
define("_EPROFILE_MI_BLOCK_POPULAR","most popular users");
define("_EPROFILE_MI_BLOCK_COUNTER","Posting(s)");

define("_EPROFILE_MI_EPROFILE_ANTISPAM","Anti-spam settings");
define("_EPROFILE_MI_ANTISPAM_APIKEY","API-KEY");
define("_EPROFILE_MI_ANTISPAM_APIKEY_DESC","Enter the key for your page to be able to send a data. <br /> For this key see http://http://www.stopforumspam.com/");
define("_EPROFILE_MI_ANTISPAM_SUBMIT","Send Data?");
define("_EPROFILE_MI_ANTISPAM_SUBMIT_DESC","Send spam registrations automatically to www.stopforumspam.com");
define("_EPROFILE_MI_ANTISPAM_SENDMAIL","Report Logon attempt by mail?");
define("_EPROFILE_MI_ANTISPAM_SENDMAIL_DESC","For each failed message the administrator group will receive an email with the data.");
define("_EPROFILE_MI_ANTISPAM_IP","Number of attempts by the IP address");
define("_EPROFILE_MI_ANTISPAM_IP_DESC","Denied the registration from the same number IP");
define("_EPROFILE_MI_ANTISPAM_EMAIL","Number of attempts by the Email address");
define("_EPROFILE_MI_ANTISPAM_EMAIL_DESC","Denied the registration from the same number Email");
define("_EPROFILE_MI_ANTISPAM_UNAME","Number of attempts by the username");
define("_EPROFILE_MI_ANTISPAM_UNAME_DESC","Denied the registration From the same number usernames");

define("_EPROFILE_MI_BLOCK_NEIGTHBAR","Umkreissuche von Usern");
define("_EPROFILE_MI_BLOCK_NEIGTHBAR_SIZE","im Umkreis von:");
define("_EPROFILE_MI_BLOCK_NEIGTHBAR_COUNTRY","Land:");
define("_EPROFILE_MI_BLOCK_NEIGTHBAR_PLZ","Postleitzahl:");
define("_EPROFILE_MI_BLOCK_APIUSER","Username für API bei http://geonames.org");
?>