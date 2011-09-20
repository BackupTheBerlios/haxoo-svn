<?php
/**
 * $Id: permissions.php,v 1.4 2006/04/07 00:11:36 malanciault Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';

wfdownloads_xoops_cp_header();

wfdownloads_adminMenu(9, _AM_WFD_PERM_MANAGEMENT);

	echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_PERM_CPERMISSIONS . "</legend>\n
		<div style='padding: 2px;'>\n";

$cat_form = new XoopsGroupPermForm('', $xoopsModule->getVar('mid'), 'WFDownCatPerm', _AM_WFD_PERM_CSELECTPERMISSIONS );

$category_handler = xoops_getmodulehandler('category');
$categories = $category_handler->getObjects();
if (count($categories) > 0)
{
    foreach (array_keys($categories) as $i)
    {
            $cat_form->addItem($categories[$i]->getVar('cid'), $categories[$i]->getVar('title'), $categories[$i]->getVar('pid'));
    }
    echo $cat_form->render();
}
else
{
    echo "<div><b>" . _AM_WFD_PERM_CNOCATEGORY . "</b></div>";
}
echo "</div></fieldset><br />";
unset ($cat_form);

echo _AM_WFD_PERM_PERMSNOTE;

xoops_cp_footer();

?>