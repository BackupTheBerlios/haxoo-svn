<?php
// $Id: viewcat.php,v 1.6 2006/05/01 02:37:28 onokazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
include 'header.php';
$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object

include_once XOOPS_ROOT_PATH . '/class/tree.php';
$mylinksCatHandler =& xoops_getmodulehandler('category', $xoopsModule->getVar('dirname'));
$catObjs           = $mylinksCatHandler->getAll();
$myCatTree         = new XoopsObjectTree($catObjs, 'cid', 'pid');

xoops_load('mylinksUtility', $xoopsModule->getVar('dirname'));

$cid   = mylinksUtility::mylinks_cleanVars($_GET, 'cid', 0, 'int', array('min'=>0));
$catid = $cid;

$xoopsOption['template_main'] = 'mylinks_viewcat.html';
include XOOPS_ROOT_PATH . '/header.php';

//wanikoo
$xoTheme->addStylesheet('browse.php?' . mylinksGetStylePath('mylinks.css', 'include'));
$xoTheme->addScript('browse.php?' . mylinksGetStylePath('mylinks.js', 'include'));
//
$xoopsTpl->assign('show_nav', false);  //set to not show nav bar

$show    = mylinksUtility::mylinks_cleanVars($_GET, 'show', $xoopsModuleConfig['perpage'], 'int');
$min     = mylinksUtility::mylinks_cleanVars($_GET, 'min', 0, 'int');
$max     = (!isset($max)) ? $min + $show : $max;
$orderby = mylinksUtility::mylinks_cleanVars($_GET, 'orderby', 'title ASC', 'string');

// list
//TODO: need to sanitize $_GET['list']
if (!isset($_GET['list'])) {
    //wanikoo
    $catObj = $mylinksCatHandler->get($cid);
    $imgurl = '';
    if (is_object($catObj) && !empty($catObj)) {
        $thisCatTitle = $myts->htmlSpecialChars($catObj->getVar('title'));
        if ( $catObj->getVar('imgurl') && ($catObj->getVar('imgurl') != "http://") ) {
            $imgurl = $myts->htmlSpecialChars($catObj->getVar('imgurl'));
        }
    } else {
        $thisCatTitle = '';
    }
    $thisPageTitle = $thisCatTitle;
    $xoopsTpl->assign('thiscategorytitle', $thisCatTitle);
    $xoopsTpl->assign('moremetasearch', '');
/*
    if (file_exists(XOOPS_ROOT_PATH."/include/moremetasearch.php")&&$mylinks_show_externalsearch) {
      include_once XOOPS_ROOT_PATH."/include/moremetasearch.php";
      $_REQUEST['query']= $thisCatTitle;
      $engineblocktitle = _MD_MYLINKS_EXTERNALSEARCH;
      $engineblocktitle .= sprintf(_MD_MYLINKS_EXTERNALSEARCH_KEYWORD, _MD_MYLINKS_CATEGORY, $thisCatTitle);
      $location_list=moremeta("meta_page","on");
      $metaresult = more_meta_page($location_list, $target="_blank", $display = false, $engineblocktitle);
      $xoopsTpl->assign('moremetasearch', "<br /><br />".$metaresult);
    } else {
      $xoopsTpl->assign('moremetasearch', '');
    }
*/
    //feed
    $xoopsTpl->assign('category_id', $cid);
    $xoopsTpl->assign('lang_categoryfeed', _MD_MYLINKS_FEED_CAT);

    //$thisCatObj = $mylinksCatHandler->get($cid);
    $homePath   = "<a href='" . XOOPSMYLINKURL . "/index.php'>" . _MD_MYLINKS_MAIN . "</a>&nbsp;:&nbsp;";
    $itemPath   = $catObj->getVar('title');
    $path       = '';
    $myParent = $catObj->getVar('pid');
    while ( $myParent != 0 ) {
        $ancestorObj = $myCatTree->getByKey($myParent);
        $path  = "<a href='" . XOOPSMYLINKURL . "/viewcat.php?cid=" . $ancestorObj->getVar('cid') . "'>" . $ancestorObj->getVar('title') . "</a>&nbsp;:&nbsp;{$path}";
        $myParent = $ancestorObj->getVar('pid');
    }

    $path = "{$homePath}{$path}{$itemPath}";
    $path = str_replace("&nbsp;:&nbsp;", " <img src='" . mylinksGetIconURL('arrow.gif') . "' style='border-width: 0px;' alt='' /> ", $path);

    $xoopsTpl->assign('category_path', $path);
    $xoopsTpl->assign('category_id', $cid);

    $subCatLimit = 5;

    // get all the subcats for this category
    $subCatObjs  = $myCatTree->getFirstChild($cid);

    $count = 1;
    foreach ($subCatObjs as $subCatObj) {
        // get 3rd level cats
        $gchildCatObjs = $myCatTree->getFirstChild($subCatObj->getVar('cid'));
        $gchildCategories = '';
        $subCatCount = count($gchildCatObjs);
        $lpLimit = min( array( $subCatLimit , $subCatCount ) );
        $i = 0;
        foreach ($gchildCatObjs as $gchildCatObj) {
            $gchtitle = $myts->htmlSpecialChars($gchildCatObj->getVar('title'));
            $gchildCategories .= ($i>0) ? ', ' : '';
            $gchildCategories .= "<a href='" . XOOPSMYLINKURL . "/viewcat.php?cid=" . $gchildCatObj->getVar('cid') . "'>{$gchtitle}</a>";
            if ($i < $lpLimit) {
                $i++;
            } else {
                break;
            }
        }
        $gchildCategories = ($subCatCount > $subCatLimit) ? $gchildCategories . '...' : $gchildCategories;
        $totalLinks = getTotalItems($subCatObj->getVar('cid'), 1);
        $xoopsTpl->append('subcategories', array('image'           => '',
                                                 'id'              => $subCatObj->getVar('cid'),
                                                 'title'           => $myts->htmlSpecialChars($subCatObj->getVar('title')),
                                                 'infercategories' => $gchildCategories,
                                                 'totallinks'      => $totalLinks,
                                                 'count'           => $count));
        $count++;
    }

    $LinkCountResult=$xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("mylinks_links") . " WHERE cid='{$cid}' AND status>0");
} else {
    $list    = $_GET['list'];
    $orderby = "title ASC";

    $xoopsTpl->assign('list_mode', true);
    //TODO:  need to filter $_GET['list'] input var
    $categoryPath = sprintf(_MD_MYLINKS_LINKS_LIST, $myts->htmlSpecialChars($_GET['list']));
    $thisPageTitle = $categoryPath;
    $xoopsTpl->assign('category_path', $categoryPath );

    $LinkCountResult=$xoopsDB->query("SELECT COUNT(*) FROM " . $xoopsDB->prefix("mylinks_links") . " WHERE title LIKE '" . $myts->addSlashes($_GET['list']) . "%' AND status>0");
}

if (1 == $xoopsModuleConfig['useshots']) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('lang_noscreenshot', _MD_MYLINKS_NOSHOTS);
}

$page_nav = '';
list($numrows) = $xoopsDB->fetchRow($LinkCountResult);

if ( $numrows > 0 ) {
    $xoopsTpl->assign('lang_description', _MD_MYLINKS_DESCRIPTIONC);
    $xoopsTpl->assign('lang_lastupdate', _MD_MYLINKS_LASTUPDATEC);
    $xoopsTpl->assign('lang_hits', _MD_MYLINKS_HITSC);
    $xoopsTpl->assign('lang_rating', _MD_MYLINKS_RATINGC);
    $xoopsTpl->assign('lang_ratethissite', _MD_MYLINKS_RATETHISSITE);
    $xoopsTpl->assign('lang_reportbroken', _MD_MYLINKS_REPORTBROKEN);
    $xoopsTpl->assign('lang_tellafriend', _MD_MYLINKS_TELLAFRIEND);
    $xoopsTpl->assign('lang_modify', _MD_MYLINKS_MODIFY);
    $xoopsTpl->assign('lang_category', _MD_MYLINKS_CATEGORYC);
    $xoopsTpl->assign('lang_visit', _MD_MYLINKS_VISIT);
    $xoopsTpl->assign('show_links', true);
    $xoopsTpl->assign('lang_comments', _COMMENTS);

    //if 2 or more items in result, show the sort menu
    if ( $numrows > 1 ) {
        $xoopsTpl->assign('show_nav', true);
        $xoopsTpl->assign('lang_sortby', _MD_MYLINKS_SORTBY);
        $xoopsTpl->assign('lang_title', _MD_MYLINKS_TITLE);
        $xoopsTpl->assign('lang_date', _MD_MYLINKS_DATE);
        $xoopsTpl->assign('lang_rating', _MD_MYLINKS_RATING);
        $xoopsTpl->assign('lang_popularity', _MD_MYLINKS_POPULARITY);
        $xoopsTpl->assign('lang_cursortedby', sprintf(_MD_MYLINKS_CURSORTEDBY, convertorderbytrans($orderby)));
    }

    if (!isset($_GET['list'])) {
        $sql = "SELECT l.lid, l.cid, l.title, l.url, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description FROM "
              . $xoopsDB->prefix("mylinks_links") . " l, "
              . $xoopsDB->prefix("mylinks_text") . " t "
              ."WHERE cid='{$cid}' AND l.lid=t.lid AND status>0 "
              ."ORDER BY {$orderby}";
    } else {
        $sql = "SELECT l.lid, l.cid, l.title, l.url, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description FROM "
              . $xoopsDB->prefix("mylinks_links") . " l, "
              . $xoopsDB->prefix("mylinks_text") . " t "
              ."WHERE l.title LIKE '" . $myts->addSlashes($_GET['list']) . "%' AND l.lid=t.lid AND status>0 "
              ."ORDER BY {$orderby}";
    }
    $result=$xoopsDB->query($sql, $show, $min);
    while (list($lid, $cid, $ltitle, $url, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description) = $xoopsDB->fetchRow($result)) {
        if (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) {
            $isadmin = true;
            $adminlink = "<a href='" . XOOPSMYLINKURL . "/admin/index.php?op=modLink&amp;lid={$lid}'><img src='" . mylinksGetIconURL('edit.png') . "' style='border-width: 0px;' alt='" . _MD_MYLINKS_EDITTHISLINK . "' /></a>";
        } else {
            $isadmin = false;
            $adminlink = '';
        }
        $votestring = (1 == $votes) ? _MD_MYLINKS_ONEVOTE : sprintf(_MD_MYLINKS_NUMVOTES, $votes);
        $thisCatObj = $mylinksCatHandler->get($cid);
        $homePath   = "<a href='" . XOOPSMYLINKURL . "/index.php'>" . _MD_MYLINKS_MAIN . "</a>&nbsp;:&nbsp;";
        $itemPath   = $thisCatObj->getVar('title');
        $path       = '';
        $myParent = $thisCatObj->getVar('pid');
        while ( $myParent != 0 ) {
            $ancestorObj = $myCatTree->getByKey($myParent);
            $path  = "<a href='" . XOOPSMYLINKURL . "/viewcat.php?cid=" . $ancestorObj->getVar('cid') . "'>" . $ancestorObj->getVar('title') . "</a>&nbsp;:&nbsp;{$path}";
            $myParent = $ancestorObj->getVar('pid');
        }

        $path = "{$homePath}{$path}{$itemPath}";
        $path = str_replace("&nbsp;:&nbsp;", " <img src='" . mylinksGetIconURL('arrow.gif') . "' style='border-width: 0px;' alt='' /> ", $path);
        $new = newlinkgraphic($time, $status);
        $pop = popgraphic($hits);
        //by wanikoo
        $xoopsTpl->append('links', array('url' => $myts->htmlSpecialChars($url), 'id' => $lid, 'cid' => $cid, 'rating' => number_format($rating, 2), 'ltitle' => $myts->htmlSpecialChars($ltitle), 'title' => $myts->htmlSpecialChars($ltitle).$new.$pop, 'category' => $path, 'logourl' => $myts->htmlSpecialChars(trim($logourl)), 'updated' => formatTimestamp($time, "m"), 'description' => $myts->displayTarea($description, 0), 'adminlink' => $adminlink, 'hits' => $hits, 'comments' => $comments, 'votes' => $votestring, 'mail_subject' => rawurlencode(sprintf(_MD_MYLINKS_INTRESTLINK, $xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_MD_MYLINKS_INTLINKFOUND, $xoopsConfig['sitename']).':  '.XOOPSMYLINKURL.'/singlelink.php?cid='.$cid.'&lid='.$lid)));
    }
    $orderby = convertorderbyout($orderby);
    // for navi in case of list
    $cid = $catid;
    // new navi
    include_once XOOPSMYLINKPATH . '/class/mylinkspagenav.php';
    if (!isset($_GET['list'])) {
        $mylinksnav = new MylinksPageNav($numrows, $show, $min, 'min', "cid={$cid}&amp;orderby={$orderby}&amp;show={$show}");
    } else {
        $mylinksnav = new MylinksPageNav($numrows, $show, $min, 'min', "list={$list}&amp;orderby={$orderby}&amp;show={$show}");
    }
    $page_nav = $mylinksnav->renderNav($offset = 5);
}
$xoopsTpl->assign('page_nav', $page_nav);

//wanikoo theme changer
$xoopsTpl->assign('lang_themechanger', _MD_MYLINKS_THEMECHANGER);
$mymylinkstheme_options = '';

foreach ($GLOBALS['mylinks_allowed_theme'] as $mymylinkstheme) {
    $mymylinkstheme_options .= "<option value='{$mymylinkstheme}'";
    if ($mymylinkstheme == $GLOBALS['mylinks_theme']) {
        $mymylinkstheme_options .= " selected='selected'";
    }
    $mymylinkstheme_options .= ">{$mymylinkstheme}</option>";
}

$mylinkstheme_select = "<select name='mylinks_theme_select' onchange='submit();' size='1'>{$mymylinkstheme_options}</select>";

$xoopsTpl->assign('mylinksthemeoption', $mylinkstheme_select);
//wanikoo end

//wanikoo search
if ( file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/search.php") ) {
   include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/search.php";
} else {
   include_once XOOPS_ROOT_PATH."/language/english/search.php";
}
$xoopsTpl->assign('lang_all', _SR_ALL);
$xoopsTpl->assign('lang_any', _SR_ANY);
$xoopsTpl->assign('lang_exact', _SR_EXACT);
$xoopsTpl->assign('lang_search', _SR_SEARCH);
$xoopsTpl->assign('module_id', $xoopsModule->getVar('mid'));
//category head
$catarray = array();
if ( $mylinks_show_letters ) {
    $catarray['letters'] = ml_wfd_letters();
}
if ( $mylinks_show_toolbar ) {
    $catarray['toolbar'] = ml_wfd_toolbar();
}
$xoopsTpl->assign('catarray', $catarray);
//pagetitle (module name - category)
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name').' - '.$thisPageTitle);
//category jump box
$catjumpbox = "<form name='catjumpbox' method='get' action='viewcat.php'>\n"
       ."  <strong>"._MD_MYLINKS_CATEGORYC."</strong>&nbsp;\n"
       ."  " . $myCatTree->makeSelBox('cid', 'title', ' - ', $cid) . "\n"
       ."  &nbsp;<input type='submit' value='" . _SUBMIT . "' />\n</form>\n";
$xoopsTpl->assign('mylinksjumpbox', $catjumpbox);

include_once XOOPSMYLINKPATH . '/footer.php';