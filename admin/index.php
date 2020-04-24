<?php
// $Id: index.php 28 2013-09-06 21:58:22Z alfred $
// ------------------------------------------------------------------------ //
// XOOPS - PHP Content Management System                      //
// Copyright (c) 2000 XOOPS.org                           //
// <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //
// //
// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //
// //
// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

include('admin_header.php');

xoops_cp_header();
echo "<fieldset>";

$member_handler =& xoops_gethandler('member');

$indexAdmin->addInfoBox(_EPROFILE_AM_PREFERENCES) ;
$indexAdmin->addInfoBoxLine(_EPROFILE_AM_PREFERENCES, "<infotext>" . _EPROFILE_AM_ALLREGISTERUSER . "</infotext>",  $member_handler->getUserCount(), 'Green') ;
$indexAdmin->addInfoBoxLine(_EPROFILE_AM_PREFERENCES, "<infotext>" . _EPROFILE_AM_INACTIVEUSER . "</infotext>",  $member_handler->getUserCount(new Criteria('level', 0)), 'Red') ;

echo $indexAdmin->addNavigation('index.php');
echo $indexAdmin->renderIndex();

echo "</fieldset>";
xoops_cp_footer();
?>