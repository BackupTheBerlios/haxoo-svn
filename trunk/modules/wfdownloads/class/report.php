<?php
// $Id: report.php,v 1.1 2005/12/03 16:34:43 malanciault Exp $
// ------------------------------------------------------------------------ //
// 				 XOOPS - PHP Content Management System                      //
//					 Copyright (c) 2000 XOOPS.org                           //
// 						<http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //

// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //

// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// URL: http://www.xoops.org/												//
// Project: The XOOPS Project                                               //
// -------------------------------------------------------------------------//
if (!class_exists("XoopsPersistableObjectHandler")) {
	include_once XOOPS_ROOT_PATH."/modules/wfdownloads/class/object.php";
}
/*
CREATE TABLE wfdownloads_broken (
  reportid int(5) NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  sender int(11) NOT NULL default '0',
  ip varchar(20) NOT NULL default '',
  date varchar(11) NOT NULL default '0',
  confirmed enum('0','1') NOT NULL default '0',
  acknowledged enum('0','1') NOT NULL default '0',
  PRIMARY KEY  (reportid),
  KEY lid (lid),
  KEY sender (sender),
  KEY ip (ip)
) TYPE=MyISAM;
*/
class WfdownloadsReport extends XoopsObject {
	function WfdownloadsReport() {
		$this->initVar('reportid', XOBJ_DTYPE_INT);
		$this->initVar('lid', XOBJ_DTYPE_INT);
		$this->initVar('sender', XOBJ_DTYPE_INT);
		$this->initVar('date', XOBJ_DTYPE_INT);
		$this->initVar('ip', XOBJ_DTYPE_TXTBOX);
		$this->initVar('confirmed', XOBJ_DTYPE_INT);
		$this->initVar('acknowledged', XOBJ_DTYPE_INT);
	}
}

class WfdownloadsReportHandler extends XoopsPersistableObjectHandler {
	function WfdownloadsReportHandler($db) {
		$this->XoopsPersistableObjectHandler($db, 'wfdownloads_broken', 'WfdownloadsReport', 'reportid');
	}
}
?>