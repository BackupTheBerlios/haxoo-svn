<?php
/**
 * $Id: menu.php,v 1.6 2006/04/10 18:39:34 malanciault Exp $
 * Module: WF-Downloads
 * Version: v2.0.4
 * Release Date: 11 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

$i = 0;

$adminmenu[$i]['title'] = _MI_WFD_BINDEX;
$adminmenu[$i]['link'] = "admin/index.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_INDEXPAGE;
$adminmenu[$i]['link'] = "admin/indexpage.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_MCATEGORY;
$adminmenu[$i]['link'] = "admin/category.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_MDOWNLOADS;
$adminmenu[$i]['link'] = "admin/index.php?op=Download";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_REVIEWS;
$adminmenu[$i]['link'] = "admin/review.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_MMIRRORS;
$adminmenu[$i]['link'] = "admin/mirror.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_MUPLOADS;
$adminmenu[$i]['link'] = "admin/upload.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_MMIMETYPES;
$adminmenu[$i]['link'] = "admin/mimetypes.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_MVOTEDATA;
$adminmenu[$i]['link'] = "admin/votedata.php";
$i++;

$adminmenu[$i]['title'] = _MI_WFD_PERMISSIONS;
$adminmenu[$i]['link'] = "admin/permissions.php";
$i++;

if (isset($xoopsModule) && ($xoopsModule->getVar('dirname') == 'wfdownloads')) {

	$adminmenu[$i]['title'] = _AM_WFD_MCOMMENTS;
	$adminmenu[$i]['link'] = "../system/admin.php?module=" . $xoopsModule->getVar('mid') . "&status=0&limit=100&fct=comments&selsubmit=Go";
	$i++;

	$i=0;
	$headermenu[$i]['title'] = _PREFERENCES;
	$headermenu[$i]['link'] = '../../system/admin.php?fct=preferences&amp;op=showmod&amp;mod=' . $xoopsModule->getVar('mid');
	$i++;
	
	$headermenu[$i]['title'] = _AM_WFD_GOMOD;
	$headermenu[$i]['link'] = WFDOWNLOADS_URL;
	$i++;
	
	$headermenu[$i]['title'] = _AM_WFD_UPDATE_MODULE;
	$headermenu[$i]['link'] = XOOPS_URL . "/modules/system/admin.php?fct=modulesadmin&op=update&module=" . $xoopsModule->getVar('dirname');
	$i++;
	
	$headermenu[$i]['title'] = _AM_WFD_DB_IMPORT;
	$headermenu[3]['link'] = WFDOWNLOADS_URL . "admin/import.php";
	$i++;
	// Remove temporarily until content is updated... // bender
	//$headermenu[$i]['title'] = _AM_WFD_BHELP;
	//$headermenu[$i]['link'] = "http://wfsections.xoops2.com/uploads/readme/wfd/readme.html";
	//$i++;

	$headermenu[$i]['title'] = _AM_WFD_HELP;
	$headermenu[$i]['link'] = WFDOWNLOADS_URL . "docs/english/readme.html";
	$i++;
	
	$headermenu[$i]['title'] = _AM_WFD_ABOUT;
	$headermenu[$i]['link'] = WFDOWNLOADS_URL . "admin/about.php";
	$i++;
}

?>
