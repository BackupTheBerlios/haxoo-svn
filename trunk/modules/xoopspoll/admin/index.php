<?php
// $Id: index.php,v 1.18 2005/06/26 15:38:29 mithyt2 Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
include '../../../include/cp_header.php';
include XOOPS_ROOT_PATH."/modules/xoopspoll/include/constants.php";
include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once XOOPS_ROOT_PATH."/class/xoopsblock.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspoll.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolloption.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspolllog.php";
include_once XOOPS_ROOT_PATH."/modules/xoopspoll/class/xoopspollrenderer.php";

include 'admin_header.php';
xoops_cp_header();

$indexAdmin = new ModuleAdmin();

$result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("xoopspoll_desc") . "") ;
list($totalPolls) = $xoopsDB->fetchRow($result) ;

$result = $xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("xoopspoll_desc") . " WHERE end_time < UNIX_TIMESTAMP()") ;
list($totalNonActivePolls) = $xoopsDB->fetchRow($result) ;

$totalActivePolls =  $totalPolls - $totalNonActivePolls;

$indexAdmin->addInfoBox(_MD_XOOPSPOLL_DASHBOARD) ;
$indexAdmin->addInfoBoxLine(_MD_XOOPSPOLL_DASHBOARD, "<infolabel>" ._MD_XOOPSPOLL_TOTALACTIVE. "</infolabel>", $totalActivePolls, 'Green') ;
$indexAdmin->addInfoBoxLine(_MD_XOOPSPOLL_DASHBOARD, "<infolabel>" ._MD_XOOPSPOLL_TOTALNONACTIVE. "</infolabel>", $totalNonActivePolls, 'Red') ;
$indexAdmin->addInfoBoxLine(_MD_XOOPSPOLL_DASHBOARD, "<infolabel>" ._MD_XOOPSPOLL_TOTALPOLLS."</infolabel><infotext>", $totalPolls."</infotext>") ;
	

    echo $indexAdmin->addNavigation('index.php');
	echo $indexAdmin->renderIndex();

include "admin_footer.php";