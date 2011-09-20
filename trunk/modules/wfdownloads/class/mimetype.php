<?php
// $Id: mimetype.php,v 1.1 2005/12/03 16:34:43 malanciault Exp $
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

CREATE TABLE wfdownloads_mimetypes (
  mime_id int(11) NOT NULL auto_increment,
  mime_ext varchar(60) NOT NULL default '',
  mime_types text NOT NULL,
  mime_name varchar(255) NOT NULL default '',
  mime_admin int(1) NOT NULL default '1',
  mime_user int(1) NOT NULL default '0',
  KEY mime_id (mime_id)
) TYPE=MyISAM;

*/
class WfdownloadsMimetype extends XoopsObject {
	function WfdownloadsMimetype() {
		$this->initVar('mime_id', XOBJ_DTYPE_INT);
		$this->initVar('mime_ext', XOBJ_DTYPE_TXTBOX, "");
		$this->initVar('mime_types', XOBJ_DTYPE_TXTAREA, "");
		$this->initVar('mime_name', XOBJ_DTYPE_TXTBOX, "");
		$this->initVar('mime_admin', XOBJ_DTYPE_INT, 1);
		$this->initVar('mime_user', XOBJ_DTYPE_INT, 0);
	}
}

class WfdownloadsMimetypeHandler extends XoopsPersistableObjectHandler {
	function WfdownloadsMimetypeHandler($db) {
		$this->XoopsPersistableObjectHandler($db, 'wfdownloads_mimetypes', 'WfdownloadsMimetype', 'mime_id', 'mime_ext');
	}
}
?>