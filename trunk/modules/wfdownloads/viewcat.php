<?php
/**
 * $Id: viewcat.php,v 1.11 2006/05/25 08:02:30 mithyt2 Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';

global $xoopsModuleConfig, $myts, $xoopsModule, $xoopsUser;

$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$orderby = isset($_GET['orderby']) ? convertorderbyin($_GET['orderby']) : $xoopsModuleConfig['filexorder'];
$cid = (isset($_GET['cid']) && $_GET['cid'] > 0) ? intval($_GET['cid']) : 0;

$gperm_handler =& xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

if (!$gperm_handler->checkRight("WFDownCatPerm", $cid,$groups, $xoopsModule->getVar('mid'))) {
redirect_header(XOOPS_URL.'/modules/wfdownloads/index.php',3, _NOPERM);
exit();
}

$xoopsOption['template_main'] = 'wfdownloads_viewcat.html';
include XOOPS_ROOT_PATH . '/header.php';

$catarray['imageheader'] = wfd_imageheader();
$catarray['letters'] = wfd_letters();
$catarray['toolbar'] = wfd_toolbar();
$xoopsTpl->assign('catarray', $catarray);

$category_handler = xoops_getmodulehandler('category');
$category = $category_handler->get($cid);

/**
 * Breadcrumb
 */
$pathstring = $category_handler->getNicePath($cid);
$xoopsTpl->assign('categoryPath', $pathstring);
$xoopsTpl->assign('category_id', $cid);

$criteria = new CriteriaCompo();
$criteria->setSort("weight ASC, title");
$categories = $category_handler->getObjects($criteria, true);
include_once XOOPS_ROOT_PATH."/class/tree.php";
$mytree = new XoopsObjectTree($categories, "cid", "pid");
$arr = $mytree->getFirstChild($cid);

/**
 * Display Sub-categories for selected Category
 */
if (is_array($arr) > 0 && !isset($_GET['list']) && !isset($_GET['selectdate']))
{
    $allowed_cats = $gperm_handler->getItemIds("WFDownCatPerm", $groups, $xoopsModule->getVar('mid'));
    $listings = wfd_getTotalItems(0, $allowed_cats);
    $scount = 1;
    foreach(array_keys($arr) as $i)
    {
        $ele = $arr[$i];
        if (!in_array($ele->getVar('cid'), $allowed_cats))
        {
            continue;
        }


        $infercategories = array();
        $catdowncount = isset($listings["count"][$ele->getVar('cid')]) ? $listings["count"][$ele->getVar('cid')] : 0;
        $subcats = $mytree->getFirstChild($ele->getVar('cid'));
        if (count($subcats) > 0) {
            foreach (array_keys($subcats) as $k) {
                if (in_array($subcats[$k]->getVar('cid'), $allowed_cats)) {
                    $download_count = isset($listings["count"][$subcats[$k]->getVar('cid')]) ? $listings["count"][$subcats[$k]->getVar('cid')] : 0;
                    $infercategories[] = array("id" => $subcats[$k]->getVar('cid'), "title" => $subcats[$k]->getVar('title'), "count" => $download_count);
                }
            }
        }
        else {
            $download_count = 0;
            $infercategories = array();
        }
        $catdowncount += $download_count;

        $xoopsTpl->append('subcategories', array('title' => $ele->getVar('title'),
                'id' => $ele->getVar('cid'), 'infercategories' => $infercategories, 'totallinks' => $catdowncount,
                'count' => $scount));
        $scount++;
    }
}
if (isset($cid) && $cid > 0 && isset($categories[$cid])) {
    $xoopsTpl->assign('description', $categories[$cid]->getVar('description'));
}

/**
 * Extract Download information from database
 */
$xoopsTpl->assign('show_categort_title', true);

$download_handler = xoops_getmodulehandler('download');
if (isset($_GET['selectdate']))
{
    $criteria->add(new Criteria('', "TO_DAYS(FROM_UNIXTIME(".intval($_GET['selectdate'])."))", "=", "", "TO_DAYS(FROM_UNIXTIME(published))"));

} elseif (isset($_GET['list']))
{
	$criteria->setSort($orderby.", title");
	$criteria->add(new Criteria("title", $myts->addSlashes($_GET['list'])."%", "LIKE"));
	$xoopsTpl->assign('categoryPath', sprintf(_MD_WFD_DOWNLOADS_LIST, htmlspecialchars($_GET['list'])));
}
else
{
	$criteria->setSort($orderby.", title");
	$criteria->add(new Criteria("cid", $cid));
    $xoopsTpl->assign('show_categort_title', false);
}
$total_numrows = $download_handler->getActiveCount($criteria);
$criteria->setLimit($xoopsModuleConfig['perpage']);
$criteria->setStart($start);
$downloads = $download_handler->getActiveDownloads($criteria);

/**
 * Show Downloads by file
 */
if ($total_numrows > 0)
{
	foreach (array_keys($downloads) as $i) {
		$down = $downloads[$i]->getDownloadInfo();
		$xoopsTpl->assign('lang_dltimes', sprintf(_MD_WFD_DLTIMES, $down['hits']));
		$xoopsTpl->assign('lang_subdate' , $down['is_updated']);
		$xoopsTpl->append('file', $down);
	}

    /**
     * Show order box
     */
    $xoopsTpl->assign('show_links', false);
    if ($total_numrows > 1 && $cid != 0)
    {
        $xoopsTpl->assign('show_links', true);
        $orderbyTrans = convertorderbytrans($orderby);
        $xoopsTpl->assign('lang_cursortedby', sprintf(_MD_WFD_CURSORTBY, convertorderbytrans($orderby)));
        $orderby = convertorderbyout($orderby);
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

    /**
     * Nav page render
     */
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    if (isset($_GET['selectdate']))
    {
        $pagenav = new XoopsPageNav($total_numrows, $xoopsModuleConfig['perpage'] , $start, 'start', 'list=' . urlencode($_GET['selectdate']));
    }
    elseif 	(isset($_GET['list']))
    {
    	$pagenav = new XoopsPageNav($total_numrows, $xoopsModuleConfig['perpage'] , $start, 'start', 'list=' . urlencode($_GET['list']));
    }
    else
    {
        $pagenav = new XoopsPageNav($total_numrows, $xoopsModuleConfig['perpage'] , $start, 'start', 'cid=' . $cid);
    }
    $page_nav = $pagenav->renderNav();
    $istrue = (isset($page_nav) && !empty($page_nav)) ? true : false;
    $xoopsTpl->assign('page_nav', $istrue);
    $xoopsTpl->assign('pagenav', $page_nav);
}

$xoopsTpl->assign('module_home', wfdownloads_module_home(true));
include 'footer.php';

?>