<?php
/**
 * $Id: newdownloads.php,v 1.2 2005/12/07 17:11:32 malanciault Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'main';

switch ($op)
{
    case "approve":

    global $xoopsModule;

    $lid = intval($_GET['lid']);
    $download_handler = xoops_getmodulehandler('download');
    $download = $download_handler->get($lid);

    /**
         * Update the database
         */
    $download->setVar('published', time());
    $download->setVar('status', 1);
    $download_handler->insert($download, true);
    
    $title = $download->getVar('title');

    $tags = array();
    $tags['FILE_NAME'] = $title;
    $tags['FILE_URL'] = XOOPS_URL . '/modules/wfdownloads/singlefile.php?cid=' . $download->getVar('cid') . '&amp;lid=' . $lid;

    $cid = $download->getVar('cid');
    $category_handler = xoops_getmodulehandler('category');
    $category = $category_handler->get($cid);

    $tags['CATEGORY_NAME'] = $category->getVar('title');
    $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/wfdownloads/viewcat.php?cid=' . $cid;
    $notification_handler = &xoops_gethandler('notification');
    $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
    $notification_handler->triggerEvent('category', $cid, 'new_file', $tags);

    if ($download->getVar('notifypub')) {
        $notification_handler->triggerEvent('file', $lid, 'approve', $tags);
    }
    redirect_header('newdownloads.php', 1, _AM_WFD_SUB_NEWFILECREATED);
    break;

    // List downloads waiting for validation
    case 'main':
    default:

    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';

    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;

    $download_handler = xoops_getmodulehandler('download');
    $criteria = new Criteria("published", 0);
    $new_array_count = $download_handler->getCount($criteria);
    $criteria->setLimit($xoopsModuleConfig['admin_perpage']);
    $criteria->setStart($start);
    $new_array = $download_handler->getObjects($criteria, false, false);

    wfdownloads_xoops_cp_header();
    wfdownloads_adminMenu(3, _AM_WFD_SUB_SUBMITTEDFILES);

    echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_SUB_FILESWAITINGINFO . "</legend>\n
		<div style='padding: 8px;'>" . _AM_WFD_SUB_FILESWAITINGVALIDATION . "&nbsp;<b>$new_array_count</b><div>\n
		<div div style='padding: 8px;'>\n
		<li>" . $imagearray['approve'] . " " . _AM_WFD_SUB_APPROVEWAITINGFILE . "\n
		<li>" . $imagearray['editimg'] . " " . _AM_WFD_SUB_EDITWAITINGFILE . "\n
		<li>" . $imagearray['deleteimg'] . " " . _AM_WFD_SUB_DELETEWAITINGFILE . "</div>\n
		</fieldset><br />\n

		<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>\n
		<tr>\n
		<td class='bg3' align='center' width = '3%'><b>" . _AM_WFD_MINDEX_ID . "</b></td>\n
		<td class='bg3' width = '30%'><b>" . _AM_WFD_MINDEX_TITLE . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_MINDEX_POSTER . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_MINDEX_SUBMITTED . "</b></td>\n
		<td class='bg3' align='center' width = '7%'><b>" . _AM_WFD_MINDEX_ACTION . "</b></td>\n
		</tr>\n";
    if ($new_array_count > 0)
    {
        foreach (array_keys($new_array) as $i)
        {
            $new = $new_array[$i];
            $rating = number_format($new['rating'], 2);
            $title = $myts->htmlSpecialChars($new['title']);
            $url = $myts->htmlSpecialChars($new['url']);
            $url = urldecode($url);
            $homepage = $myts->htmlSpecialChars($new['homepage']);
            $version = $myts->htmlSpecialChars($new['version']);
            $size = $myts->htmlSpecialChars($new['size']);
            $platform = $myts->htmlSpecialChars($new['platform']);
            $logourl = $myts->htmlSpecialChars($new['screenshot']);
            $submitter = xoops_getLinkedUnameFromId($new['submitter']);
            $datetime = formatTimestamp($new['date'], $xoopsModuleConfig['dateformat']);
            $status = "<a href='newdownloads.php?op=approve&amp;lid=" . $new['lid'] . "'>" . $imagearray['approve'] . "</a>";
            $modify = "<a href='index.php?op=Download&amp;lid=" . $new['lid'] . "'>" . $imagearray['editimg'] . "</a>";
            $delete = "<a href='index.php?op=delDownload&amp;lid=" . $new['lid'] . "'>" . $imagearray['deleteimg'] . "</a>";

            echo "
        		<tr>\n
        		<td class='head' align='center'>" . $new['lid'] . "</td>\n
        		<td class='even' nowrap><a href='newdownloads.php?op=edit&lid=" . $new['lid'] . "'>" . $title . "</a></td>\n
        		<td class='even' align='center' nowrap>$submitter</td>\n
        		<td class='even' align='center'>" . $datetime . "</td>\n
        		<td class='even' align='center' nowrap>$status $modify $delete</td>\n
        		</tr>\n";
        }
    }
    else
    {
        echo "<tr ><td align='center' class='head' colspan='6'>" . _AM_WFD_SUB_NOFILESWAITING . "</td></tr>";
    }
    echo "</table>\n";
    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $page = ($new_array_count > $xoopsModuleConfig['admin_perpage']) ? _AM_WFD_MINDEX_PAGE : '';
    $pagenav = new XoopsPageNav($new_array_count, $xoopsModuleConfig['admin_perpage'], $start, 'start');
    echo '<div align="right" style="padding: 8px;">' . $page . '' . $pagenav->renderNav() . '</div>';
    xoops_cp_footer();
    break;
}

?>