<?php
// $Id$
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

include_once '../../../include/cp_header.php';
xoops_cp_header();
include_once XOOPS_ROOT_PATH.'/modules/news/include/functions.php';

if (is_object($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())){
	$errors=0;
	// 1) Create, if it does not exists, the stories_files table
	if(!TableExists($xoopsDB->prefix('mydownloads_files'))){
		$sql = "CREATE TABLE ".$xoopsDB->prefix('mydownloads_files')." (
			fid int(11) NOT NULL auto_increment,
			  lid int(11) NOT NULL default '0',
			  filename varchar(250) NOT NULL default '',
			  submitdate datetime default NULL,
  			  PRIMARY KEY  (fid),
			  KEY lid (lid),
			  KEY submitdate (submitdate)
			) TYPE=MyISAM;";
		if (!$xoopsDB->queryF($sql)) {
	    	echo '<br />' . _AM_MYDL_UPGRADEFAILED.' '._AM_MYDL_UPGRADEFAILED1;
	    	$errors++;
		}
	}
    // At the end, if there was errors, show them or redirect user to the module's upgrade page
	if($errors) {
		echo "<H1>" . _AM_MYDL_UPGRADEFAILED . "</H1>";
		echo "<br />" . _AM_MYDL_UPGRADEFAILED0;
	} else {
		echo _AM_MYDL_UPGRADECOMPLETE." - <a href='".XOOPS_URL."/modules/system/admin.php?fct=modulesadmin&op=update&module=mydownloads'>"._AM_MYDL_UPDATEMODULE."</a>";
	}
} else {
	printf("<H2>%s</H2>\n",_AM_MYDL_UPGR_ACCESS_ERROR);
}
xoops_cp_footer();
?>
