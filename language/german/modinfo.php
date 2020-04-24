<?php
// $Id: modinfo.php 35 2014-02-08 17:37:13Z alfred $
// _LANGCODE: EN
// _CHARSET : UTF-8
// Translator: XOOPS Translation Team

define("_EPROFILE_MI_NAME", "Userprofile");
define("_EPROFILE_MI_DESC", "Modul um erweiterte Userprofilfelder zu verwalten");

//Main menu links
define("_EPROFILE_MI_EDITACCOUNT", "Konto bearbeiten");
define("_EPROFILE_MI_CHANGEPASS", "Passwort ändern");
define("_EPROFILE_MI_CHANGEMAIL", "E-Mail ändern");

//Admin links
define("_EPROFILE_MI_INDEX", "Index");
define("_EPROFILE_MI_CATEGORIES", "Kategorien");
define("_EPROFILE_MI_FIELDS", "Felder");
define("_EPROFILE_MI_USERS", "User");
define("_EPROFILE_MI_STEPS", "Registrations<br />schritte");
define("_EPROFILE_MI_PERMISSIONS", "Berechtigungen");

//User Profile Category
define("_EPROFILE_MI_CATEGORY_TITLE", "Userprofil");
define("_EPROFILE_MI_CATEGORY_DESC", "Für diese Userfelder");

//Configuration categories
define("_EPROFILE_MI_CAT_SETTINGS", "Allgemeine Einstellungen");
define("_EPROFILE_MI_CAT_SETTINGS_DSC", "");
define("_EPROFILE_MI_CAT_USER", "Usereinstellungen");
define("_EPROFILE_MI_CAT_USER_DSC", "");

//Configuration items
define("_EPROFILE_MI_EPROFILE_SEARCH", "Letzte Eintragungen des Users in seinem Profil anzeigen");
define("_EPROFILE_MI_EPROFILE_SEARCH_DESC", "");

//Pages
define("_EPROFILE_MI_PAGE_INFO", "Userinfo");
define("_EPROFILE_MI_PAGE_EDIT", "User bearbeiten");
define("_EPROFILE_MI_PAGE_SEARCH", "Suche");

define("_EPROFILE_MI_STEP_BASIC", "Grundeinstellung");
define("_EPROFILE_MI_STEP_COMPLEMENTARY", "Erweitert");

define("_EPROFILE_MI_CATEGORY_PERSONAL", "Persönlich");
define("_EPROFILE_MI_CATEGORY_MESSAGING", "Nachrichten");
define("_EPROFILE_MI_CATEGORY_SETTINGS", "Einstellungen");
define("_EPROFILE_MI_CATEGORY_COMMUNITY", "Community");

define("_EPROFILE_MI_NEVER_LOGED_IN", "bisher kein Login");

//---------------------------------------
// new Profilesystem with integrated yogurt
//--------------------------------------

define("_EPROFILE_MI_EPROFILE_EMAILS","Emailsystem aktivieren");
define("_EPROFILE_MI_EPROFILE_EMAILS_DESC", "ermöglicht es Usern untereinander Emails zu schicken ohne die Mailadressen auszutauschen");

define("_EPROFILE_MI_EPROFILE_MESSAGES","Private Nachrichten aktivieren");
define("_EPROFILE_MI_EPROFILE_MESSAGES_DESC", "ermöglicht es Usern untereinander Private Nachrichten zu schicken");
define("_EPROFILE_MI_EPROFILE_SCRAPS","Gästebuch aktivieren");
define("_EPROFILE_MI_EPROFILE_SCRAPS_DESC","Gästebuch für jeden User aktivieren oder deaktivieren");
define("_EPROFILE_MI_EPROFILE_FRIENDS","Freundesliste aktivieren");
define("_EPROFILE_MI_EPROFILE_FRIENDS_DESC","User können sich eine Freundesliste anlegen");
define("_EPROFILE_MI_EPROFILE_PICTURES","Bildergalerie aktivieren");
define("_EPROFILE_MI_EPROFILE_PICTURES_DESC", "ermöglicht es Usern Bilder upzuloaden und als Galerie darzustellen");
define("_EPROFILE_MI_EPROFILE_VIDEOS","Videos aktivieren");
define("_EPROFILE_MI_EPROFILE_VIDEOS_DESC","einstellen von Uservideos");
define("_EPROFILE_MI_EPROFILE_AUDIOS","Musik aktivieren");
define("_EPROFILE_MI_EPROFILE_AUDIOS_DESC","User können ihre Musikstücke zur Verfügung stellen");
define("_EPROFILE_MI_EPROFILE_TRIBES","Gruppensystem aktivieren");
define("_EPROFILE_MI_EPROFILE_TRIBES_DESC", "ermöglicht es Usern sich in eigene Gruppen zusammenzufassen");
define("_EPROFILE_MI_EPROFILE_PREVIEW","Anzahl der Vorschauobjekte");
define("_EPROFILE_MI_EPROFILE_PREVIEW_DESC","legt die Anzahl der Objekte fest die auf der Übersichtsseite erscheinen sollen<br />0 = deaktiviert");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSPACE","max Speicherplatz je User");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSPACE_DESC","dieser Speicherplatz steht jedem User zur Verfügung um Bilder hochzuladen<br /> Angaben in Kilobyte (kb), dabei gilt 1MB = 1024 kB");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSOLOSPACE","max. Uplodgröße je Bild");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXSOLOSPACE_DESC"," das ist die Größe die ein Bild maximal haben darf.<br /> Angaben in Kilobyte (kb), dabei gilt 1MB = 1024 kB");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXHEIGTH","max. Höhe eines Bildes");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXHEIGTH_DESC","dieser Wert gibt die maximale Höhe des Bildes an, das man uploaden kann.<br >Angaben in Pixel");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXWIDTH","max. Breite eines Bildes");
define("_EPROFILE_MI_EPROFILE_PICTURESMAXWIDTH_DESC","dieser Wert gibt die maximale Breite des Bildes an, das man uploaden kann.<br >Angaben in Pixel");
define("_EPROFILE_MI_EPROFILE_PERPAGE","Anzahl der Einträge je Seite");
define("_EPROFILE_MI_EPROFILE_PERPAGE_DESC","Anzahl der Objekte auf der jeweilgen Kategorieseite");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBHEIGTH","max. Höhe des Vorschaubildes");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBHEIGTH_DESC","dieser Wert gibt die maximale Höhe des Vorschaubildes an, ist das Bild größer wird es automatisch verkleinert.<br >Angaben in Pixel");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBWIDTH","max. Breite des Vorschaubildes");
define("_EPROFILE_MI_EPROFILE_PICTURESTHUMBWIDTH_DESC","dieser Wert gibt die maximale Breite des Vorschaubildes an, ist das Bild größer wird es automatisch verkleinert.<br >Angaben in Pixel");
define("_EPROFILE_MI_EPROFILE_AUDIOMAXSPACE","max. Speicherplatz für Audiodateien je User");
define("_EPROFILE_MI_EPROFILE_AUDIOMAXSPACE_DESC","das ist die Größe die mit Musik maximal belegt werden darf.<br />Angaben in Kilobyte (kb), dabei gilt 1MB = 1024 kB");
define("_EPROFILE_MI_EPROFILE_WITHTUBE","Breite des Videos in der Ansicht");
define("_EPROFILE_MI_EPROFILE_WITHTUBE_DESC","");
define("_EPROFILE_MI_EPROFILE_HEIGTHTUBE","Höhe des Videos in der Ansicht");
define("_EPROFILE_MI_EPROFILE_HEIGTHTUBE_DESC","");
define("_EPROFILE_MI_EPROFILE_TRIBESMAX","max. Anzahl der Gruppen je User");
//ADD in 1.69
define("_EPROFILE_MI_LEER","");
define("_EPROFILE_MI_EPROFILE_SCRAPSTITLE","Gästebuch");
define("_EPROFILE_MI_EPROFILE_FRIENDSTITLE","Freundesliste");
define("_EPROFILE_MI_EPROFILE_PICTURESTITLE","Bilder");
define("_EPROFILE_MI_EPROFILE_AUDIOSTITLE","Musik");
define("_EPROFILE_MI_EPROFILE_VIDEOSTITLE","Youtube-Videos");
define("_EPROFILE_MI_EPROFILE_GROUPSTITLE","Gruppensystem");
//Add in 1.70
define("_EPROFILE_MI_EPROFILE_AUDIOPLAYER","Audioplayer");
define("_EPROFILE_MI_EPROFILE_AUDIOPLAYER_DESC","der zu verwendete Audioplayer");
//Add in 1.71
define("_EPROFILE_MI_ABOUT","Über");
define("_EPROFILE_MI_EPROFILE_AVATARSEARCH","Avatar bei Suche anzeigen");
define("_EPROFILE_MI_EPROFILE_AVATARSEARCH_desc","Zeigt in der Ergebnisliste den Avatar des Users mit an");
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
define("_EPROFILE_MI_BLOCK_POPULAR","populärste User");
define("_EPROFILE_MI_BLOCK_COUNTER","Posting(s)");

?>