<?php
/**
 * $Id: index.php,v 1.7 2006/05/21 20:55:45 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';

global $xoopsModuleConfig, $xoopsModule, $xoopsUser;

$xoopsOption['template_main'] = 'wfdownloads_index.html';
include XOOPS_ROOT_PATH . '/header.php';

$category_handler = xoops_getmodulehandler('category');
$cat_criteria = new CriteriaCompo();
$cat_criteria->setSort('weight ASC, title');
$categories = $category_handler->getObjects($cat_criteria);
unset($cat_criteria);

$mytree = new XoopsObjectTree($categories, "cid", "pid");

/**
 * Begin Main page Heading etc
 */
$sql = "SELECT * FROM " . $xoopsDB->prefix('wfdownloads_indexpage') . " ";
$head_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));
$catarray['imageheader'] = wfd_imageheader();
//$catarray['indexheading'] = $myts->displayTarea($head_arr['indexheading']);
$catarray['indexheaderalign'] = $head_arr['indexheaderalign'];
$catarray['indexfooteralign'] = $head_arr['indexfooteralign'];

$html = ($head_arr['nohtml']) ? 1 : 0;
$smiley = ($head_arr['nosmiley']) ? 1 : 0;
$xcodes = ($head_arr['noxcodes']) ? 1 : 0;
$images = ($head_arr['noimages']) ? 1 : 0;
$breaks = ($head_arr['nobreak']) ? 0 : 1;

$catarray['indexheader'] = $myts->displayTarea($head_arr['indexheader'], $html, $smiley, $xcodes, $images, $breaks);
$catarray['indexfooter'] = $myts->displayTarea($head_arr['indexfooter'], $html, $smiley, $xcodes, $images, $breaks);
$catarray['letters'] = wfd_letters();
$catarray['toolbar'] = wfd_toolbar();
$xoopsTpl->assign('catarray', $catarray);
/**
 * End main page Headers
 */

$chcount = 0;
$countin = 0;

$groups = (is_object($xoopsUser)) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
$module_id = $xoopsModule->getVar('mid');
$gperm_handler = &xoops_gethandler('groupperm');

/**
 * Begin Main page download info
 */
$allowed_cats = $gperm_handler->getItemIds("WFDownCatPerm", $groups, $xoopsModule->getVar('mid'));
$listings = wfd_getTotalItems(0, $allowed_cats);
/*
* get total amount of categories
*/
$total_cat = count($allowed_cats);
$cats = $mytree->getFirstChild(0);
$count = 0;
foreach (array_keys($cats) as $i) {
	if (in_array($cats[$i]->getVar('cid'), $allowed_cats)) {
		//Get category indicator image
		$publishdate = isset($listings['published'][$cats[$i]->getVar('cid')]) ? $listings['published'][$cats[$i]->getVar('cid')] : 0;
		$indicator = wfd_isnewimage($publishdate);
		if (($cats[$i]->getVar('imgurl') != "") && is_file(XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['catimage'] . "/" . $cats[$i]->getVar('imgurl')))
        {
            if ($xoopsModuleConfig['usethumbs'] && function_exists('gd_info'))
            {
                $imgurl = down_createthumb($cats[$i]->getVar('imgurl'), $xoopsModuleConfig['catimage'],
					"thumbs", $xoopsModuleConfig['shotwidth'], $xoopsModuleConfig['shotheight'],
					$xoopsModuleConfig['imagequality'], $xoopsModuleConfig['updatethumbs'], $xoopsModuleConfig['keepaspect']);
            }
            else
            {
                $imgurl = XOOPS_URL . "/" . $xoopsModuleConfig['catimage'] . "/" . $cats[$i]->getVar('imgurl');
            }
        }
        else
        {
            $imgurl = $indicator['image'];
        }

        $subcategories = array();
		//Get Subcategories
		// CONDITION REMOVED FROM THIS CODE SO THAT SUBCATEGORIES CAN BE USED TO GENERATE A MORE INSTRUCTIVE DOWNLOAD COUNT EVEN WHEN SUBCATEGORIES ARE NOT VISIBLE
//        if ($xoopsModuleConfig['subcats'] == 1) {
        	$subcats = $mytree->getFirstChild($cats[$i]->getVar('cid'));
        	if (count($subcats) > 0) {
        		foreach (array_keys($subcats) as $k) {
        			if (in_array($subcats[$k]->getVar('cid'), $allowed_cats)) {
        				$subcategories[] = array("id" => $subcats[$k]->getVar('cid'), "title" => $subcats[$k]->getVar('title'));
        			}
        		}
        	}
//        }

        $count++;
        $download_count = isset($listings['count'][$cats[$i]->getVar('cid')]) ? $listings['count'][$cats[$i]->getVar('cid')] : 0;
	  // modified May 5 2006 by Freeform Solutions (jwe)
	  // make download count recursive, to include all sub categories that the user has permission to view
	  // unset subcategories array if the subcats themselves are not to be listed.
 	  foreach($subcategories as $id=>$data) {
       	$download_count += isset($listings['count'][$data['id']]) ? $listings['count'][$data['id']] : 0;
        }
	  if($xoopsModuleConfig['subcats'] != 1) {
		unset($subcategories);
	  
		$xoopsTpl->append('categories', array('image' => $imgurl,
											'id' => $cats[$i]->getVar('cid'),
											'title' => $cats[$i]->getVar('title'),
											'summary' => $cats[$i]->getVar('summary'),
											'totaldownloads' => $download_count,
											'count' => $count,
											'alttext' => $indicator['alttext']));
	 } else {
		$xoopsTpl->append('categories', array('image' => $imgurl,
											'id' => $cats[$i]->getVar('cid'),
											'title' => $cats[$i]->getVar('title'),
											'summary' => $cats[$i]->getVar('summary'),
											'subcategories' => $subcategories,
											'totaldownloads' => $download_count,
											'count' => $count,
											'alttext' => $indicator['alttext']));
	 }
	}
}
$lang_ThereAre = $count != 1 ? _MD_WFD_THEREARE : _MD_WFD_THEREIS;

$xoopsTpl->assign('lang_thereare', sprintf($lang_ThereAre, $count, array_sum($listings['count'])));
$xoopsTpl->assign('module_home', wfdownloads_module_home(false));

include 'footer.php';
?>