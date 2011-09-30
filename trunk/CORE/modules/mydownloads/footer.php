<?php
// $Id: footer.php,v 1.14 2008/01/25 17:14:10 yoshis Exp $
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
$xoopsTpl->assign("mod_name",$xoopsModule->name());
$xoopsTpl->assign("sub_menu",$xoopsModule->subLink());
$xoopsTpl->assign("show_option",$xoopsModuleConfig['show_option']);
$xoopsTpl->assign("xoops_module_header",
	'<link rel="stylesheet" href="'.XOOPS_URL.'/modules/'.$xoopsModule->dirname().'/style.css" type="text/css">'
	. $xoopsTpl->get_template_vars( "xoops_module_header" ) ) ;
include_once "../../footer.php";
?>