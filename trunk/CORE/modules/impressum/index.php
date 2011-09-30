<?php
// ------------------------------------------------------------------------- //
//                XOOPS - PHP Content Management System                      //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
// Based on:								     //
// myPHPNUKE Web Portal System - http://myphpnuke.com/	  		     //
// PHP-NUKE Web Portal System - http://phpnuke.org/	  		     //
// Thatware - http://thatware.org/					     //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------- //

include("header.php");

$xoopsOption['template_main'] = 'impressum_index.html';

if($xoopsConfig['startpage'] == "impressum")
{
	$xoopsOption['show_rblock'] =1;
	include(XOOPS_ROOT_PATH."/header.php");
	make_cblock();
}
else
{
	$xoopsOption['show_rblock'] =1;
	include(XOOPS_ROOT_PATH."/header.php");
}

$xoopsTpl->assign('imp_title', _IMP_NAMEMODULE);
$xoopsTpl->assign('imp_copyright', _IMP_COPYRIGHT);

$myts =& MyTextSanitizer::getInstance();
$impressum=$myts->sanitizeForDisplay($xoopsModuleConfig['cfgImpressum'], 1, 1, 1);

$xoopsTpl->assign('imp_impressum', $impressum);

include(XOOPS_ROOT_PATH."/footer.php");
?>