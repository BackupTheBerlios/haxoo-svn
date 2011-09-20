<?php
// $Id: rating.php,v 1.1 2005/12/03 16:34:43 malanciault Exp $
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
CREATE TABLE wfdownloads_votedata (
  ratingid int(11) unsigned NOT NULL auto_increment,
  lid int(11) unsigned NOT NULL default '0',
  ratinguser int(11) NOT NULL default '0',
  rating tinyint(3) unsigned NOT NULL default '0',
  ratinghostname varchar(60) NOT NULL default '',
  ratingtimestamp int(10) NOT NULL default '0',
  PRIMARY KEY  (ratingid),
  KEY ratinguser (ratinguser),
  KEY ratinghostname (ratinghostname),
  KEY lid (lid)
) TYPE=MyISAM;
*/
class WfdownloadsRating extends XoopsObject {
	function WfdownloadsRating() {
		$this->initVar('ratingid', XOBJ_DTYPE_INT);
		$this->initVar('lid', XOBJ_DTYPE_INT);
		$this->initVar('ratinguser', XOBJ_DTYPE_INT);
		$this->initVar('rating', XOBJ_DTYPE_INT);
		$this->initVar('ratinghostname', XOBJ_DTYPE_TXTBOX);
		$this->initVar('ratingtimestamp', XOBJ_DTYPE_INT);
	}
}

class WfdownloadsRatingHandler extends XoopsPersistableObjectHandler {
	function WfdownloadsRatingHandler($db) {
		$this->XoopsPersistableObjectHandler($db, 'wfdownloads_votedata', 'WfdownloadsRating', 'ratingid');
	}

	/**

     * Get average ratings of users matching a condition
     *
     * @param object $criteria {@link CriteriaElement} to match
     * @return array/int
     */
	function getUserAverage($criteria = null) {
	    $groupby = false;
	    $field = "";
        if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->groupby != "") {
                $groupby = true;
                $field = $criteria->groupby.", "; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
        $sql = 'SELECT '.$field.'AVG(rating), count(*) FROM '.$this->table;
	    if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            $sql .= ' '.$criteria->renderWhere();
            if ($criteria->groupby != "") {
                $sql .= $criteria->getGroupby();
            }
        }
        $result = $this->db->query($sql);
        if (!$result) {
            return 0;
        }
        if ($groupby == false) {
            list($average, $count) = $this->db->fetchRow($result);
            return array("avg" => $average, "count" => $count);
        }
        else {
            $ret = array();
            while (list($id, $average, $count) = $this->db->fetchRow($result)) {
                $ret[$id] = array("avg" => $average, "count" => $count);
            }
            return $ret;
        }
	}
}
?>