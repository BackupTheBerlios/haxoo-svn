<?php
// $Id: singlelink.php,v 1.5 2005/09/04 20:46:10 onokazu Exp $
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
$myts =& MyTextSanitizer::getInstance();// MyTextSanitizer object

include_once XOOPS_ROOT_PATH . '/class/tree.php';
$mylinksCatHandler =& xoops_getmodulehandler('category', $xoopsModule->getVar('dirname'));
$catObjs           = $mylinksCatHandler->getAll();
$myCatTree         = new XoopsObjectTree($catObjs, 'cid', 'pid');

xoops_load('mylinksUtility', $xoopsModule->getVar('dirname'));

$lid   = mylinksUtility::mylinks_cleanVars($_GET, 'lid', 0, 'int', array('min'=>0));
$cid   = mylinksUtility::mylinks_cleanVars($_GET, 'cid', 0, 'int', array('min'=>0));

$xoopsOption['template_main'] = 'mylinks_singlelink.html';
include XOOPS_ROOT_PATH . '/header.php';

//wanikoo
$xoTheme->addStylesheet('browse.php?' . mylinksGetStylePath('mylinks.css', 'include'));
$xoTheme->addScript('browse.php?' . mylinksGetStylePath('mylinks.js', 'include'));
//

$result = $xoopsDB->query("SELECT l.lid, l.cid, l.title, l.url, l.logourl, l.status, l.date, l.hits, l.rating, l.votes, l.comments, t.description FROM " . $xoopsDB->prefix("mylinks_links") . " l, " . $xoopsDB->prefix("mylinks_text") . " t WHERE l.lid={$lid} AND l.lid=t.lid AND status>0");
list($lid, $cid, $ltitle, $url, $logourl, $status, $time, $hits, $rating, $votes, $comments, $description) = $xoopsDB->fetchRow($result);

$thisCatObj     = $mylinksCatHandler->get($cid);
$homePath       = "<a href='" . XOOPSMYLINKURL . "/index.php'>" . _MD_MYLINKS_MAIN . "</a>&nbsp;:&nbsp;";
$niceItemPath   = "<a href='" . XOOPSMYLINKURL . "/viewcat.php?cid={$cid}'>" . $thisCatObj->getVar('title') . "</a>";
$itemPath       = $thisCatObj->getVar('title');
$nicePathFromId = '';
$pathFromId     = '';
$myParent = $thisCatObj->getVar('pid');
while ( $myParent != 0 ) {
    $ancestorObj = $myCatTree->getByKey($myParent);
    $nicePathFromId  = "<a href='" . XOOPSMYLINKURL . "/viewcat.php?cid=" . $ancestorObj->getVar('cid') . "'>" . $ancestorObj->getVar('title') . "</a>&nbsp;:&nbsp;{$nicePathFromId}";
    $pathFromId  = $ancestorObj->getVar('title') . "/{$pathFromId}";
    $myParent = $ancestorObj->getVar('pid');
}

$nicePathFromId = "{$homePath}{$nicePathFromId}{$niceItemPath}";
$nicePathFromId = str_replace("&nbsp;:&nbsp;", " <img src='" . mylinksGetIconURL('arrow.gif') . "' style='border-width: 0px;' alt='' /> ", $nicePathFromId);
$pathFromId     = _MD_MYLINKS_MAIN . "/{$pathFromId}{$itemPath}";
$pathFromId     = str_replace("/", " <img src='" . mylinksGetIconURL('arrow.gif') . "' style='border-width: 0px;' alt='' /> ", $pathFromId);
$xoopsTpl->assign('category_path', $nicePathFromId);

if ($xoopsUser && $xoopsUser->isAdmin($xoopsModule->mid())) {
    $adminlink = "<a href='" . XOOPSMYLINKURL . "/admin/index.php?op=modLink&amp;lid={$lid}'><img src='" . mylinksGetIconURL("edit.png") . "' style='border-width: 0px;' alt='" . _MD_MYLINKS_EDITTHISLINK . "' /></a>";
} else {
    $adminlink = '';
}
$votestring = (1 == $votes) ? _MD_MYLINKS_ONEVOTE : sprintf(_MD_MYLINKS_NUMVOTES, $votes);

if ( 1 == $xoopsModuleConfig['useshots'] ) {
    $xoopsTpl->assign('shotwidth', $xoopsModuleConfig['shotwidth']);
    $xoopsTpl->assign('tablewidth', $xoopsModuleConfig['shotwidth'] + 10);
    $xoopsTpl->assign('show_screenshot', true);
    $xoopsTpl->assign('lang_noscreenshot', _MD_MYLINKS_NOSHOTS);
}

$new = newlinkgraphic($time, $status);
$pop = popgraphic($hits);

//$xoopsTpl->assign('link', array('id' => $lid, 'cid' => $cid, 'rating' => number_format($rating, 2), 'title' => $myts->htmlSpecialChars($ltitle).$new.$pop, 'category' => $path, 'logourl' => $myts->htmlSpecialChars($logourl), 'updated' => formatTimestamp($time,"m"), 'description' => $myts->displayTarea($description,0), 'adminlink' => $adminlink, 'hits' => $hits, 'votes' => $votestring, 'comments' => $comments, 'mail_subject' => rawurlencode(sprintf(_MD_MYLINKS_INTRESTLINK,$xoopsConfig['sitename'])), 'mail_body' => rawurlencode(sprintf(_MD_MYLINKS_INTLINKFOUND,$xoopsConfig['sitename']).':  '.XOOPSMYLINKURL.'/singlelink.php?lid='.$lid)));
//by wanikoo
$xoopsTpl->assign('link', array('url' => $myts->htmlSpecialChars($url),
                                'id' => $lid,
                                'cid' => $cid,
                                'rating' => number_format($rating, 2),
                                'ltitle' => $myts->htmlSpecialChars($ltitle),
                                'title' => $myts->htmlSpecialChars($ltitle).$new.$pop,
                                'category' => $pathFromId,
                                'logourl' => $myts->htmlSpecialChars(trim($logourl)),
                                'updated' => formatTimestamp($time, "m"),
                                'description' => $myts->displayTarea($description, 0),
                                'adminlink' => $adminlink,
                                'hits' => $hits,
                                'votes' => $votestring,
                                'comments' => $comments,
                                'mail_subject' => rawurlencode(sprintf(_MD_MYLINKS_INTRESTLINK, $xoopsConfig['sitename'])),
                                'mail_body' => rawurlencode(sprintf(_MD_MYLINKS_INTLINKFOUND, $xoopsConfig['sitename']) . ":  " . XOOPSMYLINKURL . "/singlelink.php?lid={$lid}")));
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
$xoopsTpl->assign('lang_comments', _COMMENTS);
//wanikoo
/*
if (file_exists(XOOPS_ROOT_PATH."/include/moremetasearch.php")&&$mylinks_show_externalsearch) {
  include_once XOOPS_ROOT_PATH."/include/moremetasearch.php";
  $thisltitle = $myts->htmlSpecialChars($ltitle);
  $_REQUEST['query']= $thisltitle;
  $engineblocktitle = _MD_MYLINKS_EXTERNALSEARCH;
  $engineblocktitle .= sprintf(_MD_MYLINKS_EXTERNALSEARCH_KEYWORD, _MD_MYLINKS_TITLE, $thisltitle);
  $location_list=moremeta("meta_page","on");
  $metaresult = more_meta_page($location_list, $target="_blank", $display = false, $engineblocktitle);
  $xoopsTpl->assign('moremetasearch', "<br /><br />".$metaresult);
} else {
  $xoopsTpl->assign('moremetasearch', '');
}
*/
$xoopsTpl->assign('moremetasearch', '');

//wanikoo theme changer
$xoopsTpl->assign("lang_themechanger", _MD_MYLINKS_THEMECHANGER);
$mymylinkstheme_options = '';

foreach ($GLOBALS['mylinks_allowed_theme'] as $mymylinkstheme) {
    $mymylinkstheme_options .= "<option value='{$mymylinkstheme}'";
    if ($mymylinkstheme == $GLOBALS['mylinks_theme']) {
        $mymylinkstheme_options .= ' selected="selected"';
    }
    $mymylinkstheme_options .= ">{$mymylinkstheme}</option>";
}

$mylinkstheme_select = "<select name='mylinks_theme_select' onchange='submit();' size='1'>{$mymylinkstheme_options}</select>";

$xoopsTpl->assign("mylinksthemeoption", $mylinkstheme_select);
//wanikoo end

//wanikoo search
if (file_exists(XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/search.php")) {
   include_once XOOPS_ROOT_PATH."/language/".$xoopsConfig['language']."/search.php";
} else {
   include_once XOOPS_ROOT_PATH."/language/english/search.php";
}
$xoopsTpl->assign('lang_all', _SR_ALL);
$xoopsTpl->assign('lang_any', _SR_ANY);
$xoopsTpl->assign('lang_exact', _SR_EXACT);
$xoopsTpl->assign('lang_search', _SR_SEARCH);
$xoopsTpl->assign('module_id', $xoopsModule->getVar('mid'));
//
//category head
$catarray = array();
if ($mylinks_show_letters) {
    $catarray['letters'] = ml_wfd_letters();
}
if ($mylinks_show_toolbar) {
    $catarray['toolbar'] = ml_wfd_toolbar();
}
$xoopsTpl->assign('catarray', $catarray);
//pagetitle (module name - singlelink)
$xoopsTpl->assign('xoops_pagetitle', $xoopsModule->getVar('name') . ' - ' . $myts->htmlSpecialChars($ltitle));
//category jump box
$catjumpbox = "<form name='catjumpbox' method='get' action='viewcat.php'>\n"
             ."  <strong>" . _MD_MYLINKS_CATEGORYC . "</strong>&nbsp;\n"
             ."  " . $myCatTree->makeSelBox("title", "title", $cid) . "&nbsp;\n"
             ."  <input type='submit' value='" . _SUBMIT . "' />\n"
             ."</form>\n";
$xoopsTpl->assign('mylinksjumpbox', $catjumpbox);

include XOOPS_ROOT_PATH . '/include/comment_view.php';
include_once XOOPSMYLINKPATH . '/footer.php';