<?php
// $Id: modification.php,v 1.4 2006/04/08 01:18:29 m0nty_ Exp $
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

require_once XOOPS_ROOT_PATH."/modules/wfdownloads/class/download.php";
class WfdownloadsModification extends WfdownloadsDownload  {
	function WfdownloadsModification() {
	    $this->WfdownloadsDownload();
		$this->initVar('requestid', XOBJ_DTYPE_INT);

		$this->initVar('modifysubmitter', XOBJ_DTYPE_INT, 0);
		$this->initVar('requestdate', XOBJ_DTYPE_INT, 0);
		//Obsolete
		unset($this->vars['ipaddress']);
		unset($this->vars['notifypub']);
	}
}

class WfdownloadsModificationHandler extends XoopsPersistableObjectHandler {
	function WfdownloadsModificationHandler($db) {
		$this->XoopsPersistableObjectHandler($db, 'wfdownloads_mod', 'WfdownloadsModification', 'requestid', 'title');
	}

	function approveModification($requestid) {
	    $download_handler = xoops_getmodulehandler('download');

	    $sql = "UPDATE " .$this->table." m, ". $download_handler->table . " d SET d.cid = m.cid, d.title = m.title,
			d.url = m.url, d.filename = m.filename, d.filetype = m.filetype, d.mirror = m.mirror, d.license = m.license, d.features = m.features, d.homepage = m.homepage, d.version = m.version, d.size = m.size, d.platform = m.platform,
			d.screenshot = m.screenshot, d.publisher = m.publisher, d.status = '2', d.price = m.price, d.requirements = m.requirements,
			d.homepagetitle = m.homepagetitle, d.limitations = m.limitations, d.dhistory = m.dhistory, d.updated = m.updated,
			d.summary = m.summary, d.description = m.description WHERE d.lid = m.lid AND m.requestid=".intval($requestid);
	    if ($this->db->query($sql)) {
	        return $this->deleteAll(new Criteria("requestid", intval($requestid)));
	    }
	    return false;
	}
}
?>