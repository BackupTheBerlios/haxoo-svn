<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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

$module_handler =& xoops_gethandler('module');
$xoopsModule =& XoopsModule::getByDirname('contact');
$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
//$pathImageAdmin = XOOPS_URL .'/'. $moduleInfo->getInfo('dirmoduleadmin').'/images/admin';
$pathImageAdmin = $moduleInfo->getInfo('icons32');

$adminmenu = array();

$i = 1;
$adminmenu[$i]["title"] = _MI_CONTACT_MENU_00;
$adminmenu[$i]["link"]  = "admin/index.php";
$adminmenu[$i]["desc"] = _MI_CONTACT_ADMIN_HOME_DESC;
$adminmenu[$i]["icon"] =  '../../'.$pathImageAdmin.'/home.png';
$i++;
$adminmenu[$i]["title"] = _MI_CONTACT_ADMIN_ABOUT;
$adminmenu[$i]["link"]  = "admin/about.php";
$adminmenu[$i]["desc"] = _MI_CONTACT_ADMIN_ABOUT_DESC;
$adminmenu[$i]["icon"] =  '../../'.$pathImageAdmin.'/about.png';

