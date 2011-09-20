<?php
// $Id: category.php,v 1.6 2006/04/15 12:14:20 mithyt2 Exp $
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
CREATE TABLE wfdownloads_cat (
cid int(5) unsigned NOT NULL auto_increment,
pid int(5) unsigned NOT NULL default '0',
title varchar(50) NOT NULL default '',
imgurl varchar(150) NOT NULL default '',
description varchar(255) NOT NULL default '',
total int(11) NOT NULL default '0',
summary text NOT NULL,
spotlighttop int(11) NOT NULL default '0',
spotlighthis int(11) NOT NULL default '0',
dohtml int(1) NOT NULL default '0',
dosmiley int(1) NOT NULL default '0',
doxcode int(1) NOT NULL default '0',
doimage int(1) NOT NULL default '0',
dobr int(1) NOT NULL default '1',
weight int(11) NOT NULL default '0',
PRIMARY KEY  (cid),
KEY pid (pid)
) TYPE=MyISAM;
*/
class WfdownloadsCategory extends XoopsObject {
    function WfdownloadsCategory() {
        $this->initVar('cid', XOBJ_DTYPE_INT);
        $this->initVar('pid', XOBJ_DTYPE_INT, 0);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('imgurl', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('total', XOBJ_DTYPE_INT, 0);
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('spotlighttop', XOBJ_DTYPE_INT, 0);
        $this->initVar('spotlighthis', XOBJ_DTYPE_INT, 0);
        $this->initVar('dohtml', XOBJ_DTYPE_INT, 0);
        $this->initVar('dosmiley', XOBJ_DTYPE_INT, 1);
        $this->initVar('doxcode', XOBJ_DTYPE_INT, 1);
        $this->initVar('doimage', XOBJ_DTYPE_INT, 1);
        $this->initVar('dobr', XOBJ_DTYPE_INT, 1);
        $this->initVar('weight', XOBJ_DTYPE_INT, 0);
    }

    function getForm($heading) {
        global $xoopsModule, $xoopsModuleConfig ;
        $sform = new XoopsThemeForm($heading, "op", $_SERVER['REQUEST_URI']);
        $sform -> setExtra('enctype="multipart/form-data"');

        $member_handler = & xoops_gethandler('member');
        $group_list = & $member_handler -> getGroupList();

        $gperm_handler = & xoops_gethandler('groupperm');
        $groups = $gperm_handler -> getGroupIds('WFDownCatPerm', $this->getVar('cid'), $xoopsModule -> getVar('mid'));
        $groups = $groups;
        $sform -> addElement(new XoopsFormSelectGroup(_AM_WFD_FCATEGORY_GROUPPROMPT, "groups", true, $groups, 5, true));

        $totalcats = wfd_totalcategory();
        if ($totalcats > 0)
        {
            $category_handler = xoops_getmodulehandler('category');
            $categories = $category_handler->getObjects();
            $mytree = new XoopsObjectTree($categories, "cid", "pid");
            $sform->addElement(new XoopsFormLabel(_AM_WFD_FCATEGORY_SUBCATEGORY, $mytree->makeSelBox('pid', 'title', "-", $this->getVar('pid', 'e'),true)));
    	}
        $sform -> addElement(new XoopsFormText(_AM_WFD_FCATEGORY_TITLE, 'title', 50, 80, $this->getVar('title', 'e')), true);
        $sform -> addElement(new XoopsFormText(_AM_WFD_FCATEGORY_WEIGHT, 'weight', 10, 80, $this->getVar('weight')), false);

        $graph_array = & WfsLists :: getListTypeAsArray(XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['catimage'], $type = "images");
        $indeximage_select = new XoopsFormSelect('', 'imgurl', $this->getVar('imgurl'));
        $indeximage_select -> addOptionArray($graph_array);
        $indeximage_select -> setExtra("onchange='showImgSelected(\"image\", \"imgurl\", \"" . $xoopsModuleConfig['catimage'] . "\", \"\", \"" . XOOPS_URL . "\")'");
        $indeximage_tray = new XoopsFormElementTray(_AM_WFD_FCATEGORY_CIMAGE, '&nbsp;');
        $indeximage_tray -> addElement($indeximage_select);
        if ($this->getVar('imgurl') != "")
        {
            $indeximage_tray -> addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['catimage'] . "/" . $this->getVar('imgurl') . "' name='image' id='image' alt='' />"));
        }
        else
        {
            $indeximage_tray -> addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
        }
        $sform -> addElement($indeximage_tray);
        $sform -> addElement(new XoopsFormDhtmlTextArea(_AM_WFD_FCATEGORY_DESCRIPTION, 'description', $this->getVar('description', 'e'), 15, 60), true);
        $sform -> addElement(new XoopsFormTextArea(_AM_WFD_FCATEGORY_SUMMARY, 'summary', $this->getVar('summary'), 10, 60));

        $options_tray = new XoopsFormElementTray(_AM_WFD_TEXTOPTIONS, '<br />');

        $html_checkbox = new XoopsFormCheckBox('', 'dohtml', $this->getVar('dohtml'));
        $html_checkbox -> addOption(1, _AM_WFD_ALLOWHTML);
        $options_tray -> addElement($html_checkbox);

        $smiley_checkbox = new XoopsFormCheckBox('', 'dosmiley', $this->getVar('dosmiley'));
        $smiley_checkbox -> addOption(1, _AM_WFD_ALLOWSMILEY);
        $options_tray -> addElement($smiley_checkbox);

        $xcodes_checkbox = new XoopsFormCheckBox('', 'doxcode', $this->getVar('doxcode'));
        $xcodes_checkbox -> addOption(1, _AM_WFD_ALLOWXCODE);
        $options_tray -> addElement($xcodes_checkbox);

        $noimages_checkbox = new XoopsFormCheckBox('', 'doimage', $this->getVar('doimage'));
        $noimages_checkbox -> addOption(1, _AM_WFD_ALLOWIMAGES);
        $options_tray -> addElement($noimages_checkbox);

        $breaks_checkbox = new XoopsFormCheckBox('', 'dobr', $this->getVar('dobr'));
        $breaks_checkbox -> addOption(1, _AM_WFD_ALLOWBREAK);
        $options_tray -> addElement($breaks_checkbox);
        $sform -> addElement($options_tray);

        $button_tray = new XoopsFormElementTray('', '');
        $hidden = new XoopsFormHidden('op', 'save');
        $button_tray -> addElement($hidden);

        if ($this->isNew())
        {
            $butt_create = new XoopsFormButton('', '', _AM_WFD_BSAVE, 'submit');
            $butt_create -> setExtra('onclick="this.form.elements.op.value=\'addCat\'"');
            $button_tray -> addElement($butt_create);

            $butt_clear = new XoopsFormButton('', '', _AM_WFD_BRESET, 'reset');
            $button_tray -> addElement($butt_clear);

            $butt_cancel = new XoopsFormButton('', '', _AM_WFD_BCANCEL, 'button');
            $butt_cancel -> setExtra('onclick="history.go(-1)"');
            $button_tray -> addElement($butt_cancel);
        }
        else
        {
            $sform->addElement(new XoopsFormHidden('cid', $this->getVar('cid')));
            $butt_create = new XoopsFormButton('', '', _AM_WFD_BMODIFY, 'submit');
            $butt_create -> setExtra('onclick="this.form.elements.op.value=\'addCat\'"');
            $button_tray -> addElement($butt_create);

            $butt_delete = new XoopsFormButton('', '', _AM_WFD_BDELETE, 'submit');
            $butt_delete -> setExtra('onclick="this.form.elements.op.value=\'delCat\'"');
            $button_tray -> addElement($butt_delete);

            $butt_cancel = new XoopsFormButton('', '', _AM_WFD_BCANCEL, 'button');
            $butt_cancel -> setExtra('onclick="history.go(-1)"');
            $button_tray -> addElement($butt_cancel);
        }
        $sform -> addElement($button_tray);
        return $sform;
    }
}

class WfdownloadsCategoryHandler extends XoopsPersistableObjectHandler {
    function WfdownloadsCategoryHandler($db) {
        $this->XoopsPersistableObjectHandler($db, 'wfdownloads_cat', 'WfdownloadsCategory', 'cid', 'title');
    }

    function getNicePath($cid, $root_filename = "index.php", $item_filename = "viewcat.php?op=") {
        include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';
        $mytree = new XoopsTree($this->table, $this->keyName, "pid");
        $pathstring = $mytree->getNicePathFromId($cid, $this->identifierName, $item_filename);

        /**
         * Replacing the " with ">" and deleteing the last ">" at the end
         */
        $pathstring = trim($pathstring);
        $pathstring = str_replace(':', '>', $pathstring);
        $pathstring = substr($pathstring, 0, strlen($pathstring) - 13);

        return $pathstring;
    }

    /**
     * Get categories that the current user has permissions for
     *
     * @param bool $id_as_key
     * @param bool $as_object
     * @return array
     */
    function getUserCategories($id_as_key = false, $as_object = true) {
        global $xoopsUser;
        $wfModule = wfdownloads_getModuleInfo();
        $gperm_handler = xoops_gethandler('groupperm');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $categoryids = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $wfModule->getVar('mid'));
        return $this->getObjects(new Criteria('cid', "(".implode(',', $categoryids).")", "IN"), $id_as_key, $as_object);
    }

    function getChildCats($category) {
        $allcats = $this->getObjects();
        include_once(XOOPS_ROOT_PATH."/class/tree.php");
        $tree = new XoopsObjectTree($allcats, $this->keyName, "pid");
        return $tree->getAllChild($category->getVar($this->keyName));
    }
}
?>