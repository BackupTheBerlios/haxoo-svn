<?php

/**
* $Id: partner.php,v 1.17 2005/05/30 17:37:09 fx2024 Exp $
* Module: SmartPartner
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include_once("header.php");
error_reporting(E_ALL);
global $xoopsDB;
$id = ($_GET['id']);

	if ($_GET['title']!=''){
		$ret = $xoopsDB->query("SELECT a.id FROM ".$xoopsDB->prefix("smartpartner_partner")." a WHERE a.title LIKE '".xoops_sef($_GET['title'],'_')."'");
		list($id) = $xoopsDB->fetchRow($ret);
	} else {
		$ret = $xoopsDB->query("SELECT a.title FROM ".$xoopsDB->prefix("smartpartner_partner")." a WHERE a.id = '$id'");
		list($title) = $xoopsDB->fetchRow($ret);
		
		header( "HTTP/1.1 301 Moved Permanently" ); 
		header( "Location: ".XOOPS_URL."/associates/".xoops_sef($title)."/".$id);
		exit;
	}
	
$xoopsOption['template_main'] = 'smartpartner_partner.html';
include_once(XOOPS_ROOT_PATH . "/header.php");
include_once("footer.php");

global $xoopsUser, $xoopsConfig, $xoopsModuleConfig, $xoopsModule;


If ($id == 0) {
	redirect_header("javascript:history.go(-1)", 2, _MD_SPARTNER_NOPARTNERSELECTED);
	exit();
}

// Creating the Partner object for the selected FAQ
$partnerObj = new SmartpartnerPartner($id);

// If the selected partner was not found, exit
If ($partnerObj->notLoaded()) {
	redirect_header("javascript:history.go(-1)", 2, _MD_SPARTNER_NOPARTNERSELECTED);
	exit();
}

// Chech the status
If ($partnerObj->status() != _SPARTNER_STATUS_ACTIVE) {
	redirect_header("javascript:history.go(-1)", 2, _NOPERM);
	exit();
}

// Updating the counter
$partnerObj->updateHits_page();

// Populating the smarty variables with informations related to the selected Partner
$partner = $partnerObj->toArray();
$xoopsTpl->assign('partner', $partner);

if (file_exists(XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php")) {
	include_once XOOPS_ROOT_PATH."/modules/tag/include/tagbar.php";
	$xoopsTpl->assign('tagbar', tagBar($partnerObj->id(), $catid = 0));
}

// Lanugage constants
$xoopsTpl->assign('lang_contact', _CO_SPARTNER_CONTACT);
$xoopsTpl->assign('lang_email', _CO_SPARTNER_EMAIL);
$xoopsTpl->assign('lang_adress', _CO_SPARTNER_ADRESS);
$xoopsTpl->assign('lang_phone', _CO_SPARTNER_PHONE);
$xoopsTpl->assign('lang_website', _CO_SPARTNER_WEBSITE);
$xoopsTpl->assign('lang_times', _CO_SPARTNER_TIMES);
$xoopsTpl->assign('lang_stats', _CO_SPARTNER_STATS);
$xoopsTpl->assign('lang_partner_informations', _CO_SPARTNER_PARTNER_INFORMATIONS);
$xoopsTpl->assign('lang_page_been_seen', _CO_SPARTNER_PAGE_BEEN_SEEN);
$xoopsTpl->assign('lang_url_been_visited', _CO_SPARTNER_URL_BEEN_VISITED);
$xoopsTpl->assign('backtoindex', $xoopsModuleConfig['backtoindex']);
$xoopsTpl->assign('lang_backtoindex', _MD_SPARTNER_BACKTOINDEX);
$xoopsTpl->assign('modulepath', SMARTPARTNER_URL);

$show_stats_block = false;
if ($xoopsUser) {
	foreach($xoopsModuleConfig['stats_group'] as $group) {
		if (in_array($group, $xoopsUser->getGroups()))	{
			$show_stats_block = true;
		}
	}
} else {
	$show_stats_block = in_array(XOOPS_GROUP_ANONYMOUS, $xoopsModuleConfig['stats_group']);	
}

$xoopsTpl->assign('show_stats_block', $show_stats_block);

// MetaTag Generator
smartpartner_createMetaTags($partnerObj->title(), '', $partnerObj->summary());
if (file_exists(XOOPS_ROOT_PATH . '/modules/smarttie/smarttie_links.php')) {
	include_once XOOPS_ROOT_PATH . '/modules/smarttie/smarttie_links.php';
	$xoopsTpl->assign('smarttie',1);
}
include_once XOOPS_ROOT_PATH . '/footer.php';

?>