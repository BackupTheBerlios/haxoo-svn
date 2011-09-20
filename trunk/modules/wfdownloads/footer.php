<?php
// $Id: footer.php,v 1.2 2006/04/06 17:55:28 mithyt2 Exp $
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

global $xoopsModule, $xoopsModuleConfig;
$uid = ($xoopsUser) ? ($xoopsUser->getVar("uid")) : 0;

$xoopsTpl->assign("wfdownloads_adminpage", "<a href='" . WFDOWNLOADS_URL . "admin/index.php'>" ._MD_WFD_ADMIN_PAGE . "</a>");

$xoopsTpl->assign("isAdmin", $wfdownloads_isAdmin);
$xoopsTpl->assign('wfdownloads_url', WFDOWNLOADS_URL);

$xoopsTpl->assign("xoops_module_header", '<link rel="stylesheet" type="text/css" href="' . WFDOWNLOADS_URL . 'module.css" />');

$xoopsTpl->assign("ref_smartfactory", "WFDownloads is developed by The SmartFactory (http://www.smartfactory.ca), a division of InBox Solutions (http://www.inboxsolutions.net)");

include_once XOOPS_ROOT_PATH . '/footer.php';

?>