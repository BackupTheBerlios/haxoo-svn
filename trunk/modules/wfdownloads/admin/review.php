<?php
/**
 * $Id: review.php,v 1.6 2006/04/26 21:02:35 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v3.1
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';

$op = '';

if (!isset($_POST['op']))
{
    $op = isset($_GET['op']) ? $_GET['op'] : 'reviews';
}
else
{
    $op = $_POST['op'];
}

$lid = (isset($_GET['lid'])) ? intval($_GET['lid']) : 0;

switch ($op)
{
    case "del_review":

    $confirm = (isset($_REQUEST['confirm'])) ? 1 : 0;
    $review_handler = xoops_getmodulehandler('review');
    $review = $review_handler->get($_REQUEST['review_id']);
    if ($confirm)
    {
        $review_handler->delete($review);
        redirect_header("review.php?op=default", 1, sprintf(_AM_WFD_FILE_FILEWASDELETED, $review->getVar('title')));
        exit();
    }
    else
    {
        wfdownloads_xoops_cp_header();
        xoops_confirm(array('op' => 'del_review', 'review_id' => $review->getVar('review_id'), 'confirm' => 1), 'review.php?op=default', _AM_WFD_FILE_REALLYDELETEDTHIS . "<br /><br>" . $review->getVar('title'), _AM_WFD_BDELETE);
        xoops_cp_footer();
    }
    break;

    case "approve_review":

    $confirm = (isset($_REQUEST['confirm'])) ? 1 : 0;
    $review_handler = xoops_getmodulehandler('review');
    $review = $review_handler->get($_REQUEST['review_id']);
    if ($confirm)
    {
        $review->setVar('submit', 1);
		$review_handler->insert($review);
        redirect_header("review.php?op=default", 1, sprintf( _AM_WFD_REV_REVIEW_UPDATED, $review->getVar('title')));
        exit();
    }
    else
    {
        wfdownloads_xoops_cp_header();
        xoops_confirm(array('op' => 'approve_review', 'review_id' => $review->getVar('review_id'), 'confirm' => 1), 'review.php?op=default', _AM_WFD_REVIEW_APPROVETHIS . "<br /><br>" . $review->getVar('title'), _AM_WFD_REVIEW_APPROVETHIS);
        xoops_cp_footer();
    }
    break; 

    case "edit_review":

    $confirm = (isset($_REQUEST['confirm'])) ? 1 : 0;
    $review_handler = xoops_getmodulehandler('review');
    $review = $review_handler->get($_REQUEST['review_id']);
    if ($confirm)
    {
        $review->setVar('title', trim($_POST['title']));
        $review->setVar('review', trim($_POST['review']));
        $review->setVar('rated', intval($_POST['rated']));
        $review->setVar('submit', intval($_POST['approve']));
        $review_handler->insert($review);
        redirect_header("review.php?op=default", 1, _AM_WFD_REV_REVIEW_UPDATED);
        exit();
    }
    else
    {
        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(4, _AM_WFD_AREVIEWS);

        $sform = $review->getForm();
        $sform -> display();
        xoops_cp_footer();
    }
    break;

	default:
    case "reviews":
	case "default":
    global $xoopsDB, $imagearray, $xoopsModule;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $start2 = isset($_GET['start2']) ? intval($_GET['start2']) : 0;

    $review_handler = xoops_getmodulehandler('review');
    $criteria = new Criteria("submit", 0);
    $criteria2 = new Criteria("submit", 1);
    $num = $review_handler->getCount($criteria);
    $num2 = $review_handler->getCount($criteria2);
    $criteria->setSort("date");
    $criteria2->setSort("date");
    $criteria->setOrder("DESC");
    $criteria2->setOrder("DESC");
    $criteria->setLimit($xoopsModuleConfig['admin_perpage']);
    $criteria2->setLimit($xoopsModuleConfig['admin_perpage']);
    $criteria->setStart($start);
    $criteria2->setStart($start2);
    $reviews = $review_handler->getObjects($criteria);
    $reviews2 = $review_handler->getObjects($criteria2);

    wfdownloads_xoops_cp_header();
    wfdownloads_adminMenu(4, _AM_WFD_AREVIEWS);

    echo "
			<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_AREVIEWS_INFO . "</legend>\n
			<div style='padding: 8px;'>" . _AM_WFD_AREVIEWS_WAITING . "&nbsp;<b>$num</b><div>\n
			<div style='padding: 8px;'>" . _AM_WFD_REV_REVIEW_TOTAL . "&nbsp;<b>$num2</b><div>\n
			<div style='padding: 8px;'>\n
			<li>" . $imagearray['approve'] . " " . _AM_WFD_AREVIEWS_APPROVE . "\n
			<li>" . $imagearray['editimg'] . " " . _AM_WFD_AREVIEWS_EDIT . "\n
			<li>" . $imagearray['deleteimg'] . " " . _AM_WFD_AREVIEWS_DELETE . "</div>\n
			</div>\n
			</fieldset><br />\n

		<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>\n
		<tr>\n
		<td class='head' align='center' width = '100%' colspan='6'><b>" . _AM_WFD_REV_REVIEW_WAITING . "</b></td>\n
		</tr><br /><br />\n
		<tr>\n
		<td class='bg3' align='center' width = '3%'><b>" . _AM_WFD_REV_ID . "</b></td>\n
		<td class='bg3' width = '20%'><b>" . _AM_WFD_REV_TITLE . "</b></td>\n
		<td class='bg3' width = '30%'><b>" . _AM_WFD_REV_REVIEWTITLE . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_REV_POSTER . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_REV_SUBMITDATE . "</b></td>\n
		<td class='bg3' align='center' width = '7%'><b>" . _AM_WFD_REV_ACTION . "</b></td>\n
		</tr>\n
		";
    if ($num > 0)
    {
        foreach (array_keys($reviews) as $i)
        {
            $lids[] = $reviews[$i]->getVar('lid');
            $uids[] = $reviews[$i]->getVar('uid');
        }
        $download_handler = xoops_getmodulehandler('download');
        $member_handler = xoops_gethandler("member");

        $downloads = $download_handler->getList(new Criteria("lid", "(".implode(',', $lids).")", "IN"));
        $users = $member_handler->getUserList(new Criteria("uid", "(".implode(',', $uids).")"));

        foreach (array_keys($reviews) as $i) {
            $review_id = $reviews[$i]->getVar('review_id');
            $review_title = $reviews[$i]->getVar('title');
            $title = isset($downloads[$reviews[$i]->getVar('lid')]) ? $downloads[$reviews[$i]->getVar('lid')] : "";
			$submitter = xoops_getLinkedUnameFromId(intval($reviews[$i]->getVar('uid')));
            $datetime = formatTimestamp($reviews[$i]->getVar('date'), $xoopsModuleConfig['dateformat']);
            $status = "<a href='review.php?op=approve_review&review_id=" . $review_id . "'>" . $imagearray['approve'] . "</a>";
            $modify = "<a href='review.php?op=edit_review&review_id=" . $review_id . "'>" . $imagearray['editimg'] . "</a>";
            $delete = "<a href='review.php?op=del_review&review_id=" . $review_id . "'>" . $imagearray['deleteimg'] . "</a>";
            echo "
            		<tr>\n
            		<td class='head' align='center'>" . $review_id . "</td>\n
            		<td class='even' nowrap><a href='index.php?op=Download&amp;lid=" . $reviews[$i]->getVar('lid') . "'>" . $title . "</a></td>\n
            		<td class='even' nowrap><a href='review.php?op=edit_review&amp;review_id=" . $review_id . "'>" . $review_title . "</a></td>\n
            		<td class='even' align='center' nowrap>$submitter</td>\n
            		<td class='even' align='center'>" . $datetime . "</td>\n
            		<td class='even' align='center' nowrap>$status $modify $delete</td>\n
            		</tr>\n
            		";
        }
    }
    else
    {
        echo "<tr><td align = 'center' class='head' colspan = '6'>" . _AM_WFD_REV_NOWAITINGREVIEWS . "</td></tr>";
    }
    echo "</table>\n";

    echo "
		<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>\n
		<tr>\n
		<td class='head' align='center' width = '100%' colspan='6'><b>" . _AM_WFD_REV_REVIEW_PUBLISHED . "</b></td>\n
		</tr><br /><br />\n
		<tr>\n
		<td class='bg3' align='center' width = '3%'><b>" . _AM_WFD_REV_ID . "</b></td>\n
		<td class='bg3' width = '20%'><b>" . _AM_WFD_REV_TITLE . "</b></td>\n
		<td class='bg3' width = '30%'><b>" . _AM_WFD_REV_REVIEWTITLE . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_REV_POSTER . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_REV_SUBMITDATE . "</b></td>\n
		<td class='bg3' align='center' width = '7%'><b>" . _AM_WFD_REV_ACTION . "</b></td>\n
		</tr>\n
		";
    if ($num2 > 0)
    {
        foreach (array_keys($reviews2) as $i2)
        {
            $lids2[] = $reviews2[$i2]->getVar('lid');
            $uids2[] = $reviews2[$i2]->getVar('uid');
        }
        $download_handler = xoops_getmodulehandler('download');
        $member_handler = xoops_gethandler("member");

        $downloads = $download_handler->getList(new Criteria("lid", "(".implode(',', $lids2).")", "IN"));
        $users = $member_handler->getUserList(new Criteria("uid", "(".implode(',', $uids2).")"));

        foreach (array_keys($reviews2) as $i2) {
            $review_id2 = $reviews2[$i2]->getVar('review_id');
            $review_title2 = $reviews2[$i2]->getVar('title');
            $title2 = isset($downloads[$reviews2[$i2]->getVar('lid')]) ? $downloads[$reviews2[$i2]->getVar('lid')] : "";
			$submitter2 = xoops_getLinkedUnameFromId(intval($reviews2[$i2]->getVar('uid')));
            $datetime2 = formatTimestamp($reviews2[$i2]->getVar('date'), $xoopsModuleConfig['dateformat']);
            $modify2 = "<a href='review.php?op=edit_review&review_id=" . $review_id2 . "'>" . $imagearray['editimg'] . "</a>";
            $delete2 = "<a href='review.php?op=del_review&review_id=" . $review_id2 . "'>" . $imagearray['deleteimg'] . "</a>";
            echo "
            		<tr>\n
            		<td class='head' align='center'>" . $review_id2 . "</td>\n
            		<td class='even' nowrap><a href='index.php?op=Download&amp;lid=" . $reviews2[$i2]->getVar('lid') . "'>" . $title2 . "</a></td>\n
            		<td class='even' nowrap><a href='review.php?op=edit_review&amp;review_id=" . $review_id2 . "'>" . $review_title2 . "</a></td>\n
            		<td class='even' align='center' nowrap>$submitter2</td>\n
            		<td class='even' align='center'>" . $datetime2 . "</td>\n
            		<td class='even' align='center' nowrap>$modify2 $delete2</td>\n
            		</tr>\n
            		";
        }
    }
    else
    {
        echo "<tr><td align = 'center' class='head' colspan = '6'>" . _AM_WFD_REV_NOPUBLISHEDREVIEWS . "</td></tr>";
    }
    echo "</table>\n";

    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $pagenav = new XoopsPageNav($num, $xoopsModuleConfig['admin_perpage'] , $start, 'start');
    echo "<div text-align='right'>" . $pagenav -> renderNav() . '</div>';
    xoops_cp_footer();
    break;

}

?>