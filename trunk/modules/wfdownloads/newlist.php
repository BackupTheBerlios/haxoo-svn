<?php
/**
 * $Id: newlist.php,v 1.5 2006/05/25 08:09:57 mithyt2 Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

$xoopsOption['template_main'] = 'wfdownloads_newlistindex.html';
include XOOPS_ROOT_PATH . '/header.php';

global $xoopsDB, $xoopsModule, $xoopsUser, $xoopsModuleConfig;

$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id = $xoopsModule->getVar('mid');
$gperm_handler = &xoops_gethandler('groupperm');

$imageheader = wfd_imageheader();
$xoopsTpl->assign('imageheader', $imageheader);

//Get number of downloads the last week
$one_week_ago = time() - 3600*24*7; //@TODO: Change to strtotime (TODAY-1week);
$download_handler = xoops_getmodulehandler('download');
$allowed_cats = $gperm_handler->getItemIds("WFDownCatPerm", $groups, $xoopsModule->getVar('mid'));
$criteria = new Criteria("published", $one_week_ago, ">=");
$allweekdownloads = $download_handler->getActiveCount($criteria);

//Get number of downloads the last month
$one_month_ago = time() - 3600*24*7; //@TODO: Change to strtotime (TODAY-1month);
$criteria = new Criteria("published", $one_month_ago, ">=");
$allmonthdownloads = $download_handler->getActiveCount($criteria);

$xoopsTpl->assign('allweekdownloads', $allweekdownloads);
$xoopsTpl->assign('allmonthdownloads', $allmonthdownloads);

//Get latest downloads
$criteria = new CriteriaCompo(new Criteria("offline", 0));
if(isset($_GET['newdownloadshowdays'])) {
	$days = intval($_GET['newdownloadshowdays']);
	$days_limit = array(7, 14, 30);
	if(in_array($days, $days_limit)) {
		$downloadshowdays = time() - (3600 * 24 * $days);
		$criteria->add(new Criteria("published",$downloadshowdays, '>='), 'AND');
    }
}
$criteria->setSort("published");
$criteria->setOrder("DESC");
$criteria->setLimit($xoopsModuleConfig['perpage']);
$criteria->setStart(0);
$downloads = $download_handler->getActiveDownloads($criteria);
foreach (array_keys($downloads) as $i)
{
    $down = $downloads[$i]->getDownloadInfo();
    $xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFD_DLTIMES, $down['hits']));
    $xoopsTpl->assign('lang_subdate' , $down['is_updated']);
    $xoopsTpl->append('file', $down);
}

/**
 * Screenshots display
 */
$xoopsTpl->assign('show_screenshot', false);
if (isset($xoopsModuleConfig['screenshot']) && $xoopsModuleConfig['screenshot'] == 1)
{
    $xoopsTpl->assign('shots_dir', $xoopsModuleConfig['screenshots']);
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('shotheight', $xoopsModuleConfig['shotheight']);
    $xoopsTpl->assign('show_screenshot', true);
}
if (isset($days)) {
	$which_new_downloads = " > " . sprintf(_MD_WFD_NEWDOWNLOADS_INTHELAST, $days);
	$xoopsTpl->assign('categoryPath', '<a href="' . WFDOWNLOADS_URL . 'newlist.php">' . _MD_WFD_NEWDOWNLOADS . '</a>' . $which_new_downloads);
} else {
	$xoopsTpl->assign('categoryPath',  _MD_WFD_NEWDOWNLOADS);
}
$xoopsTpl->assign('module_home', wfdownloads_module_home(true));
include 'footer.php';

?>