<?php
// $Id: review.php,v 1.4 2006/01/13 07:03:51 m0nty_ Exp $
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
CREATE TABLE wfdownloads_reviews (
  review_id int(11) unsigned NOT NULL auto_increment,
  lid int(11) NOT NULL default '0',
  title varchar(255) default NULL,
  review text,
  submit int(11) NOT NULL default '0',
  date int(11) NOT NULL default '0',
  uid int(10) NOT NULL default '0',
  rated int(11) NOT NULL default '0',
  PRIMARY KEY  (review_id),
  KEY categoryid (lid)
) TYPE=MyISAM;

*/
class WfdownloadsReview extends XoopsObject {
	function WfdownloadsReview() {
		$this->initVar('review_id', XOBJ_DTYPE_INT);
		$this->initVar('lid', XOBJ_DTYPE_INT);
		$this->initVar('title', XOBJ_DTYPE_TXTBOX);
		$this->initVar('review', XOBJ_DTYPE_TXTAREA);
		$this->initVar('submit', XOBJ_DTYPE_INT);
		$this->initVar('date', XOBJ_DTYPE_INT);
		$this->initVar('uid', XOBJ_DTYPE_INT);
		$this->initVar('rated', XOBJ_DTYPE_INT);
	}

	function getForm() {
	    include_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
        $uid = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

	    $sform = new XoopsThemeForm(_AM_WFD_REV_SNEWMNAMEDESC, "reviewform", $_SERVER['REQUEST_URI']);
	    $sform -> addElement(new XoopsFormText(_AM_WFD_REV_FTITLE, 'title', 30, 40, $this->getVar('title', 'e')), true);
	    $rating_select = new XoopsFormSelect(_AM_WFD_REV_FRATING, "rated", $this->getVar('rated'));
	    $rating_select -> addOptionArray(array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10));
	    $sform -> addElement($rating_select);
	    $sform -> addElement(new XoopsFormDhtmlTextArea(_AM_WFD_REV_FDESCRIPTION, 'review', $this->getVar('review', 'e'), 15, 60), true);

	    $approved = ($this->getVar('submit') == 0) ? 0 : 1;
	    $approve_checkbox = new XoopsFormCheckBox(_AM_WFD_REV_FAPPROVE, "approve", $approved);
	    $approve_checkbox -> addOption(1, " ");
	    $sform -> addElement($approve_checkbox);

	    $sform -> addElement(new XoopsFormHidden("lid", $this->getVar('lid')));
	    $sform -> addElement(new XoopsFormHidden("uid", $uid));
	    $sform -> addElement(new XoopsFormHidden("review_id", $this->getVar('review_id')));
	    $sform -> addElement(new XoopsFormHidden("confirm", 1));
	    $button_tray = new XoopsFormElementTray('', '');
	    $hidden = new XoopsFormHidden('op', 'save');
	    $button_tray -> addElement($hidden);

	    if ($this->isNew())
	    {
	        $butt_create = new XoopsFormButton('', '', _AM_WFD_BSAVE, 'submit');
	        $butt_create -> setExtra('onclick="this.form.elements.op.value=\'edit_review\'"');
	        $button_tray -> addElement($butt_create);

	        $butt_clear = new XoopsFormButton('', '', _AM_WFD_BRESET, 'reset');
	        $button_tray -> addElement($butt_clear);

	        $butt_cancel = new XoopsFormButton('', '', _AM_WFD_BCANCEL, 'button');
	        $butt_cancel -> setExtra('onclick="history.go(-1)"');
	        $button_tray -> addElement($butt_cancel);
	    }
	    else
	    {
	        $butt_create = new XoopsFormButton('', '', _AM_WFD_BSAVE, 'submit');
	        $butt_create -> setExtra('onclick="this.form.elements.op.value=\'edit_review\'"');
	        $button_tray -> addElement($butt_create);

	        $butt_delete = new XoopsFormButton('', '', _AM_WFD_BDELETE, 'submit');
	        $butt_delete -> setExtra('onclick="this.form.elements.op.value=\'del_review\'"');
	        $button_tray -> addElement($butt_delete);

	        $butt_cancel = new XoopsFormButton('', '', _AM_WFD_BCANCEL, 'button');
	        $butt_cancel -> setExtra('onclick="history.go(-1)"');
	        $button_tray -> addElement($butt_cancel);
	    }
	    $sform -> addElement($button_tray);
	    return $sform;
	}
    /**
    * Returns an array representation of the object
    *
    * @return array
    */
    function toArray() {
        $ret = array();
        $vars = $this->getVars();
        foreach (array_keys($vars) as $i) {
            $ret[$i] = $this->getVar($i);
        }
        return $ret;
    }
}

class WfdownloadsReviewHandler extends XoopsPersistableObjectHandler {
	function WfdownloadsReviewHandler($db) {
		$this->XoopsPersistableObjectHandler($db, 'wfdownloads_reviews', 'WfdownloadsReview', 'review_id', 'title');
	}
}
?>