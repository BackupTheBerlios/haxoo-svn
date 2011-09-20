<?php
/**
 * $Id: topten.php,v 1.4 2006/04/10 00:02:35 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';

global $xoopsDB, $xoopsUser;

$xoopsOption['template_main'] = 'wfdownloads_topten.html';

$groups = (is_object($xoopsUser)) ? $xoopsUser -> getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id = $xoopsModule -> getVar('mid');
$gperm_handler = & xoops_gethandler('groupperm');

include XOOPS_ROOT_PATH . '/header.php';

$action_array = array('hit' => 0, 'rate' => 1);
$list_array = array('hits', 'rating');
$lang_array = array(_MD_WFD_HITS, _MD_WFD_RATING);

$sort = (isset($_GET['list']) && in_array($_GET['list'], $action_array)) ? $_GET['list'] : 'rate';
$thisselected = $action_array[$sort];
$sortDB = $list_array[$thisselected];

$catarray['imageheader'] = wfd_imageheader();
$catarray['letters'] = wfd_letters();
$catarray['toolbar'] = wfd_toolbar();
$xoopsTpl -> assign('catarray', $catarray);

$arr = array();

$download_handler = xoops_getmodulehandler('download');
$category_handler = xoops_getmodulehandler('category');
$categories = $category_handler->getObjects();

$mytree = new XoopsObjectTree($categories, "cid", "pid");
$top_cats = $mytree->getFirstChild(0);

$allowed_cats = $gperm_handler -> getItemIds('WFDownCatPerm', $groups, $module_id);
$e = 0;
$rankings = array();
foreach (array_keys($top_cats) as $i)
{
    $cid = $top_cats[$i]->getVar('cid');
    if (in_array($cid, $allowed_cats))
    {
        $subcats = $mytree->getAllChild($cid);
        $cids = array(); //initialise array
        if (count($subcats) > 0) {
            foreach (array_keys($subcats) as $k) {
                $cids[] = $subcats[$k]->getVar('cid');
            }
        }
        $cids[] = $cid;

        $criteria = new CriteriaCompo(new Criteria("cid", "(".implode(',', $cids).")", "IN"));
        $criteria->setSort($sortDB);
		$criteria->setOrder("DESC");
        $criteria->setLimit(10);
        $downloads = $download_handler->getActiveDownloads($criteria);
        $filecount = count($downloads);

        if ($filecount > 0)
        {
            $rankings[$e]['title'] = $top_cats[$i]->getVar('title');
            $rank = 1;

            foreach (array_keys($downloads) as $k) {
                $parent_cat_titles = array();
                $cats = $mytree->getAllParent($downloads[$k]->getVar('cid'));
                if (count($cats) > 0) {
                    foreach (array_keys($cats) as $j) {
                        $parent_cat_titles[] = $cats[$j]->getVar('title');
                    }
                }
                $thiscat = $mytree->getByKey($downloads[$k]->getVar('cid'));
                $parent_cat_titles[] = $thiscat->getVar('title');

                $catpath = implode('/', $parent_cat_titles);

                $rankings[$e]['file'][] = array('id' => $downloads[$k]->getVar('lid'),
                                                'cid' => $downloads[$k]->getVar('cid'),
                                                'rank' => $rank,
                                                'title' => $downloads[$k]->getVar('title'),
                                                'category' => $catpath,
                                                'hits' => $downloads[$k]->getVar('hits'),
                                                'rating' => number_format($downloads[$k]->getVar('rating'), 2),
                                                'votes' => $downloads[$k]->getVar('votes'));
                $rank++;
            }
            $e++;
        }
    }
}

$xoopsTpl -> assign('lang_sortby' , $lang_array[$thisselected]);

$xoopsTpl -> assign('rankings', $rankings);

if ($_GET['list'] == 'rate') {
	$xoopsTpl->assign('categoryPath',  _MD_WFD_DOWNLOAD_MOST_RATED);
} else {
	$xoopsTpl->assign('categoryPath',  _MD_WFD_DOWNLOAD_MOST_POPULAR);
}

$xoopsTpl->assign('module_home', wfdownloads_module_home(true));

include 'footer.php';

?>