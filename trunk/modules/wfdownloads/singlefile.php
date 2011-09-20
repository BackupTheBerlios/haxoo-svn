<?php
/**
 * $Id: singlefile.php,v 1.8 2006/04/12 01:34:32 malanciault Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

$lid = intval($_GET['lid']);
$xoopsOption['template_main'] = 'wfdownloads_singlefile.html';

$download_handler = xoops_getmodulehandler('download');
$download = $download_handler->get($lid);
$cid = $download->getVar('cid');
if ($download->isNew()) {
   redirect_header("index.php", 1, _MD_WFD_NODOWNLOAD);
   exit();
}
if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == 1 || ($download->getVar('expired') != 0 && $download->getVar('expired') < time()) || $download->getVar('status') == 0) {
	//Download not published, expired or taken offline - redirect
    redirect_header("index.php", 3, _MD_WFD_NODOWNLOAD);
}

$gperm_handler =& xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

if (!$gperm_handler->checkRight("WFDownCatPerm", $cid,$groups, $xoopsModule->getVar('mid'))) {
redirect_header(XOOPS_URL.'/modules/wfdownloads/index.php',3, _NOPERM);
exit();
}

include XOOPS_ROOT_PATH . '/header.php';

$use_mirrors = $xoopsModuleConfig['enable_mirrors'];
$add_mirror = 0;
if (!is_object($xoopsUser) && $use_mirrors == 1 && ($xoopsModuleConfig['anonpost'] == 3 || $xoopsModuleConfig['anonpost'] == 4) && ($xoopsModuleConfig['submissions'] == 3 || $xoopsModuleConfig['submissions'] == 4))
{
	$add_mirror = 1;
}
elseif (is_object($xoopsUser) && $use_mirrors == 1 && ($xoopsModuleConfig['submissions'] == 3 || $xoopsModuleConfig['submissions'] == 4 || $xoopsUser->isAdmin()))
{
	$add_mirror = 1;
}

$down = $download->getDownloadInfo();
$xoopsTpl->assign('categoryPath', $down['path'] . " > " . $down['title']);
$xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFD_DLTIMES, $down['hits']));
$xoopsTpl->assign('lang_subdate' , $down['is_updated']);
$xoopsTpl->append('file', $down);

$xoopsTpl->assign('show_screenshot', false);
if (isset($xoopsModuleConfig['screenshot']) && $xoopsModuleConfig['screenshot'] == 1)
{
    $xoopsTpl->assign('shots_dir', $xoopsModuleConfig['screenshots']);
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('shotheight', $xoopsModuleConfig['shotheight']);
    $xoopsTpl->assign('show_screenshot', true);
}
/**
 * Show other author downloads
 */

$criteria = new CriteriaCompo(new Criteria("submitter", $download->getVar('submitter')));
$criteria->add(new Criteria('lid', $lid, '!='));
$criteria->setLimit(20);
$criteria->setSort("published");
$criteria->setOrder("DESC");

$downloads = $download_handler->getActiveDownloads($criteria);
foreach (array_keys($downloads) as $i)
{
    $downuid['title'] = $downloads[$i]->getVar('title');
    $downuid['lid'] = $downloads[$i]->getVar('lid');
    $downuid['cid'] = $downloads[$i]->getVar('cid');
    $downuid['published'] = formatTimestamp($downloads[$i]->getVar('published'), $xoopsModuleConfig['dateformat']);
    $xoopsTpl->append('down_uid', $downuid);
}
/**
 * User reviews
 */
$review_handler = xoops_getmodulehandler('review');
$criteria = new CriteriaCompo(new Criteria("lid", $download->getVar('lid')));
$criteria->add(new Criteria("submit", 1));
$review_amount = $review_handler->getCount($criteria);

if ($review_amount > 0)
{
    $user_reviews = "op=list&amp;cid=" . $download->getVar('cid') . "&amp;lid=" . $download->getVar('lid') . "\">" . _MD_WFD_USERREVIEWS;
}
else
{
    $user_reviews = "cid=" . $download->getVar('cid') . "&amp;lid=" . $download->getVar('lid') . "\">" . _MD_WFD_NOUSERREVIEWS;
}
$xoopsTpl->assign('lang_user_reviews', $xoopsConfig['sitename'] . " " . _MD_WFD_USERREVIEWSTITLE);
$xoopsTpl->assign('lang_UserReviews', sprintf($user_reviews, $download->getVar('title')));

/**
 * User mirrors
 */
$down['add_mirror'] = $add_mirror;

$mirror_handler = xoops_getmodulehandler('mirror');
$criteria = new CriteriaCompo(new Criteria("lid", $download->getVar('lid')));
$criteria->add(new Criteria("submit", 1));
$mirror_amount = $mirror_handler->getCount($criteria);

if ($mirror_amount > 0)
{
    $user_mirrors = "op=list&amp;cid=" . $download->getVar('cid') . "&amp;lid=" . $download->getVar('lid') . "\">" . _MD_WFD_USERMIRRORS;
}
else
{
    $user_mirrors = "cid=" . $download->getVar('cid') . "&amp;lid=" . $download->getVar('lid') . "\">" . _MD_WFD_NOUSERMIRRORS;
}
$xoopsTpl->assign('lang_user_mirrors', $xoopsConfig['sitename'] . " " . _MD_WFD_USERMIRRORSTITLE);
$xoopsTpl->assign('lang_UserMirrors', sprintf($user_mirrors, $download->getVar('title')));

if (isset($xoopsModuleConfig['copyright']) && $xoopsModuleConfig['copyright'] == 1)
{
    $xoopsTpl->assign('lang_copyright', "" . $download->getVar('title') . " © " . _MD_WFD_COPYRIGHT . " " . date("Y") . " " . XOOPS_URL);
}
$xoopsTpl->assign('down', $down);

include XOOPS_ROOT_PATH . '/include/comment_view.php';

$xoopsTpl->assign('com_rule', $xoopsModuleConfig['com_rule']);
$xoopsTpl->assign('module_home', wfdownloads_module_home(true));
include 'footer.php';
?>