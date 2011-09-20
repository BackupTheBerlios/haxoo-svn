<?php
/**
 * $Id: mirror.php,v 1.6 2006/05/18 00:33:31 m0nty_ Exp $
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
    $op = isset($_GET['op']) ? $_GET['op'] : 'mirrors';
}
else
{
    $op = $_POST['op'];
}

$lid = (isset($_GET['lid'])) ? intval($_GET['lid']) : 0;

switch ($op)
{
    case "del_mirror":

    $confirm = (isset($_REQUEST['confirm'])) ? 1 : 0;
    $mirror_handler = xoops_getmodulehandler('mirror');
    $mirror = $mirror_handler->get($_REQUEST['mirror_id']);
    if ($confirm)
    {
        $mirror_handler->delete($mirror);
        redirect_header("mirror.php?op=default", 1, sprintf(_AM_WFD_FILE_FILEWASDELETED, $mirror->getVar('title')));
        exit();
    }
    else
    {
        wfdownloads_xoops_cp_header();
        xoops_confirm(array('op' => 'del_mirror', 'mirror_id' => $mirror->getVar('mirror_id'), 'confirm' => 1), 'mirror.php?op=default', _AM_WFD_FILE_REALLYDELETEDTHIS . "<br /><br>" . $mirror->getVar('title'), _AM_WFD_BDELETE);
        xoops_cp_footer();
    }
    break;

    case "approve_mirror":

    $confirm = (isset($_REQUEST['confirm'])) ? 1 : 0;
    $mirror_handler = xoops_getmodulehandler('mirror');
    $mirror = $mirror_handler->get($_REQUEST['mirror_id']);
    if ($confirm)
    {
        $mirror->setVar('submit', 1);
		$mirror_handler->insert($mirror);
        redirect_header("review.php?op=default", 1, sprintf( _AM_WFD_MIRROR_MIRROR_UPDATED, $mirror->getVar('title')));
        exit();
    }
    else
    {
        wfdownloads_xoops_cp_header();
        xoops_confirm(array('op' => 'approve_mirror', 'mirror_id' => $mirror->getVar('mirror_id'), 'confirm' => 1), 'mirror.php?op=default', _AM_WFD_MIRROR_APPROVETHIS . "<br /><br>" . $mirror->getVar('title'), _AM_WFD_MIRROR_APPROVETHIS);
        xoops_cp_footer();
    }
    break; 

    case "edit_mirror":

    $confirm = (isset($_REQUEST['confirm'])) ? 1 : 0;
    $mirror_handler = xoops_getmodulehandler('mirror');
    $mirror = $mirror_handler->get($_REQUEST['mirror_id']);
    if ($confirm)
    {
        $mirror->setVar('title', trim($_POST['title']));
        $mirror->setVar('homeurl', formatURL(trim($_POST['homeurl'])));
        $mirror->setVar('location', trim($_POST['location']));
        $mirror->setVar('continent', trim($_POST['continent']));
        $mirror->setVar('downurl', formatURL(trim($_POST['downurl'])));
        $mirror->setVar('submit', intval($_POST['approve']));
        $mirror_handler->insert($mirror);
        redirect_header("mirror.php?op=default", 1, _AM_WFD_MIRROR_MIRROR_UPDATED);
        exit();
    }
    else
    {
        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(5, _AM_WFD_AMIRRORS);

        $sform = $mirror->getForm();
        $sform -> display();
        xoops_cp_footer();
    }
    break;

	default:
    case "mirrors":
    case "default":
    global $xoopsDB, $imagearray, $xoopsModule;

    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;
    $start2 = isset($_GET['start2']) ? intval($_GET['start2']) : 0;

    $mirror_handler = xoops_getmodulehandler('mirror');
    $criteria = new Criteria("submit", 0);
    $criteria2 = new Criteria("submit", 1);
    $num = $mirror_handler->getCount($criteria);
    $num2 = $mirror_handler->getCount($criteria2);
    $criteria->setSort("date");
    $criteria2->setSort("date");
    $criteria->setOrder("DESC");
    $criteria2->setOrder("DESC");
    $criteria->setLimit($xoopsModuleConfig['admin_perpage']);
    $criteria2->setLimit($xoopsModuleConfig['admin_perpage']);
    $criteria->setStart($start);
    $criteria2->setStart($start2);
    $mirrors = $mirror_handler->getObjects($criteria);
    $mirrors2 = $mirror_handler->getObjects($criteria2);

    wfdownloads_xoops_cp_header();
    wfdownloads_adminMenu(5, _AM_WFD_AMIRRORS);

    echo "
			<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_AMIRRORS_INFO . "</legend>\n
			<div style='padding: 8px;'>" . _AM_WFD_AMIRRORS_WAITING . "&nbsp;<b>$num</b><div>\n
			<div style='padding: 8px;'>" . _AM_WFD_MIRROR_MIRROR_TOTAL . "&nbsp;<b>$num2</b><div>\n
			<div style='padding: 8px;'>\n
			<li>" . $imagearray['approve'] . " " . _AM_WFD_AMIRRORS_APPROVE . "\n
			<li>" . $imagearray['editimg'] . " " . _AM_WFD_AMIRRORS_EDIT . "\n
			<li>" . $imagearray['deleteimg'] . " " . _AM_WFD_AMIRRORS_DELETE . "</div>\n
			</div>\n
			</fieldset><br />\n

		<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>\n
		<tr>\n
		<td class='head' align='center' width = '100%' colspan='6'><b>" . _AM_WFD_MIRROR_MIRROR_WAITING . "</b></td>\n
		</tr><br /><br />\n
		<tr>\n
		<td class='bg3' align='center' width = '3%'><b>" . _AM_WFD_MIRROR_ID . "</b></td>\n
		<td class='bg3' width = '30%'><b>" . _AM_WFD_MIRROR_TITLE . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_MIRROR_POSTER . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_MIRROR_SUBMITDATE . "</b></td>\n
		<td class='bg3' align='center' width = '7%'><b>" . _AM_WFD_MIRROR_ACTION . "</b></td>\n
		</tr>\n
		";
    if ($num > 0)
    {
        foreach (array_keys($mirrors) as $i)
        {
            $lids[] = $mirrors[$i]->getVar('lid');
            $uids[] = $mirrors[$i]->getVar('uid');
        }
        $download_handler = xoops_getmodulehandler('download');
        $member_handler = xoops_gethandler("member");

        $downloads = $download_handler->getList(new Criteria("lid", "(".implode(',', $lids).")", "IN"));
        $users = $member_handler->getUserList(new Criteria("uid", "(".implode(',', $uids).")"));

        foreach (array_keys($mirrors) as $i) {
            $mirror_id = $mirrors[$i]->getVar('mirror_id');
            $title = isset($downloads[$mirrors[$i]->getVar('lid')]) ? $downloads[$mirrors[$i]->getVar('lid')] : "";
            $submitter = xoops_getLinkedUnameFromId(intval($mirrors[$i]->getVar('uid')));
            $datetime = formatTimestamp($mirrors[$i]->getVar('date'), $xoopsModuleConfig['dateformat']);
            $status = "<a href='mirror.php?op=approve_mirror&mirror_id=" . $mirror_id . "'>" . $imagearray['approve'] . "</a>";
            $modify = "<a href='mirror.php?op=edit_mirror&mirror_id=" . $mirror_id . "'>" . $imagearray['editimg'] . "</a>";
            $delete = "<a href='mirror.php?op=del_mirror&mirror_id=" . $mirror_id . "'>" . $imagearray['deleteimg'] . "</a>";
            echo "
            		<tr>\n
            		<td class='head' align='center'>" . $mirror_id . "</td>\n
            		<td class='even' nowrap><a href='index.php?op=Download&amp;lid=" . $mirrors[$i]->getVar('lid') . "'>" . $title . "</a></td>\n
            		<td class='even' align='center' nowrap>$submitter</td>\n
            		<td class='even' align='center'>" . $datetime . "</td>\n
            		<td class='even' align='center' nowrap>$status $modify $delete</td>\n
            		</tr>\n
            		";
        }
    }
    else
    {
        echo "<tr ><td align = 'center' class='head' colspan = '6'>" . _AM_WFD_MIRROR_NOWAITINGMIRRORS . "</td></tr>";
    }
    echo "</table>\n";

    echo "
		<table width='100%' cellspacing='1' cellpadding='3' border='0' class='outer'>\n
		<tr>\n
		<td class='head' align='center' width = '100%' colspan='6'><b>" . _AM_WFD_MIRROR_MIRROR_PUBLISHED . "</b></td>\n
		</tr><br /><br />\n
		<tr>\n
		<td class='bg3' align='center' width = '3%'><b>" . _AM_WFD_MIRROR_ID . "</b></td>\n
		<td class='bg3' width = '20%'><b>" . _AM_WFD_MIRROR_TITLE . "</b></td>\n
		<td class='bg3' width = '30%'><b>" . _AM_WFD_MIRROR_MIRRORTITLE . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_MIRROR_POSTER . "</b></td>\n
		<td class='bg3' align='center' width = '15%'><b>" . _AM_WFD_MIRROR_SUBMITDATE . "</b></td>\n
		<td class='bg3' align='center' width = '7%'><b>" . _AM_WFD_MIRROR_ACTION . "</b></td>\n
		</tr>\n
		";
    if ($num2 > 0)
    {
        foreach (array_keys($mirrors2) as $i2)
        {
            $lids2[] = $mirrors2[$i2]->getVar('lid');
            $uids2[] = $mirrors2[$i2]->getVar('uid');
        }
        $download_handler = xoops_getmodulehandler('download');
        $member_handler = xoops_gethandler("member");

        $downloads = $download_handler->getList(new Criteria("lid", "(".implode(',', $lids2).")", "IN"));
        $users = $member_handler->getUserList(new Criteria("uid", "(".implode(',', $uids2).")"));

        foreach (array_keys($mirrors2) as $i2) {
            $mirror_id2 = $mirrors2[$i2]->getVar('mirror_id');
            $mirror_title2 = $mirrors2[$i2]->getVar('title');
            $title2 = isset($downloads[$mirrors2[$i2]->getVar('lid')]) ? $downloads[$mirrors2[$i2]->getVar('lid')] : "";
			$submitter2 = xoops_getLinkedUnameFromId(intval($mirrors2[$i2]->getVar('uid')));
            $datetime2 = formatTimestamp($mirrors2[$i2]->getVar('date'), $xoopsModuleConfig['dateformat']);
            $modify2 = "<a href='mirror.php?op=edit_mirror&mirror_id=" . $mirror_id2 . "'>" . $imagearray['editimg'] . "</a>";
            $delete2 = "<a href='mirror.php?op=del_mirror&mirror_id=" . $mirror_id2 . "'>" . $imagearray['deleteimg'] . "</a>";
            echo "
            		<tr>\n
            		<td class='head' align='center'>" . $mirror_id2 . "</td>\n
            		<td class='even' nowrap><a href='index.php?op=Download&amp;lid=" . $mirrors2[$i2]->getVar('lid') . "'>" . $title2 . "</a></td>\n
            		<td class='even' nowrap><a href='mirror.php?op=edit_mirror&amp;mirror_id=" . $mirror_id2 . "'>" . $mirror_title2 . "</a></td>\n
            		<td class='even' align='center' nowrap>$submitter2</td>\n
            		<td class='even' align='center'>" . $datetime2 . "</td>\n
            		<td class='even' align='center' nowrap>$modify2 $delete2</td>\n
            		</tr>\n
            		";
        }
    }
    else
    {
        echo "<tr><td align = 'center' class='head' colspan = '6'>" . _AM_WFD_MIRROR_NOPUBLISHEDMIRRORS . "</td></tr>";
    }
    echo "</table>\n";

    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $pagenav = new XoopsPageNav($num, $xoopsModuleConfig['admin_perpage'] , $start, 'start');
    echo "<div text-align='right'>" . $pagenav -> renderNav() . '</div>';
    xoops_cp_footer();
    break;

}

?>