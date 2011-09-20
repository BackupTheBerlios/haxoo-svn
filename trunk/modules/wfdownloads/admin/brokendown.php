<?php
/**
 * $Id: brokendown.php,v 1.5 2006/05/06 13:22:27 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';

$op = '';

if (!isset($_POST['op']))
{
    $op = isset($_GET['op']) ? $_GET['op'] : 'listBrokenDownloads';
}
else
{
    $op = $_POST['op'];
}

$lid = (isset($_GET['lid'])) ? intval($_GET['lid']) : 0;

switch ($op)
{
    case "updateNotice":
    $report_handler = xoops_getmodulehandler('report');
    if (isset($_GET['ack']))
    {
        $acknowledged = (isset($_GET['ack']) && $_GET['ack'] == 0) ? 1 : 0;
        $report_handler->updateAll("acknowledged", $acknowledged, new Criteria("lid", $lid), true);
        $update_mess = _AM_WFD_BROKEN_NOWACK;
    }

    if (isset($_GET['con']))
    {
        $confirmed = (isset($_GET['con']) && $_GET['con'] == 0) ? 1 : 0;
        $report_handler->updateAll("confirmed", $confirmed, new Criteria("lid", $lid), true);
        $update_mess = _AM_WFD_BROKEN_NOWCON;
    }
    redirect_header("brokendown.php?op=default", 1, $update_mess);
    break;

    case "delBrokenDownloads":
    $criteria = new Criteria('lid', intval($lid));
    $report_handler = xoops_getmodulehandler('report');
    $download_handler = xoops_getmodulehandler('download');
    $report_handler->deleteAll($criteria, true);
    $download_handler->deleteAll($criteria, true);
    redirect_header("brokendown.php?op=default", 1, _AM_WFD_BROKENFILEDELETED);
    break;

    case "ignoreBrokenDownloads":
    $criteria = new Criteria('lid', intval($lid));
    $report_handler = xoops_getmodulehandler('report');
    $report_handler->deleteAll($criteria, true);

    redirect_header("brokendown.php?op=default", 1, _AM_WFD_BROKEN_FILEIGNORED);
    break;

    default:
    case "listBrokenDownloads":
    case "default":

    global $xoopsDB, $imagearray, $xoopsModule;
    $report_handler = xoops_getmodulehandler('report');
    $reports = $report_handler->getObjects();
    $totalbrokendownloads = count($reports);

    wfdownloads_xoops_cp_header();

    wfdownloads_adminMenu(3, _AM_WFD_BROKEN_FILE);

    echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_BROKEN_REPORTINFO . "</legend>\n
		<div style='padding: 8px;'>" . _AM_WFD_BROKEN_REPORTSNO . "&nbsp;<b>$totalbrokendownloads</b><div>\n
		<div style='padding: 8px;'>\n
		<ul><li>" . $imagearray['ignore'] . " " . _AM_WFD_BROKEN_IGNOREDESC . "</li>\n
		<li>" . $imagearray['editimg'] . " " . _AM_WFD_BROKEN_EDITDESC . "</li>
		<li>" . $imagearray['deleteimg'] . " " . _AM_WFD_BROKEN_DELETEDESC . "</li>\n
		<li>" . $imagearray['ack_yes'] . " " . _AM_WFD_BROKEN_ACKDESC . "</li>
		<li>" . $imagearray['con_yes'] . " " . _AM_WFD_BROKEN_CONFIRMDESC . "</li>
		</ul></div>\n
		</fieldset><br />\n

		<table width='100%' border='0' cellspacing='1' cellpadding = '2' class='outer'>\n
		<tr align = 'center'>\n
		<th width = '3%' align = 'center'>" . _AM_WFD_BROKEN_ID . "</th>\n
		<th width = '35%' align = 'left'>" . _AM_WFD_BROKEN_TITLE . "</th>\n
		<th>" . _AM_WFD_BROKEN_REPORTER . "</th>\n
		<th>" . _AM_WFD_BROKEN_FILESUBMITTER . "</th>\n
		<th>" . _AM_WFD_BROKEN_DATESUBMITTED . "</th>\n
		<th align='center'>" . _AM_WFD_BROKEN_ACTION . "</th>\n
		</tr>\n
		";

    if ($totalbrokendownloads == 0)
    {
        echo "<tr align = 'center'><td align = 'center' class='head' colspan = '6'>" . _AM_WFD_BROKEN_NOFILEMATCH . "</td></tr>";
    }
    else
    {
        foreach (array_keys($reports) as $i) {
            $lids[] = $reports[$i]->getVar('lid');
            $uids[] = $reports[$i]->getVar('sender');
        }
        $download_handler = xoops_getmodulehandler('download');
        $downloads = $download_handler->getObjects(new Criteria('lid', "(".implode(',', array_unique($lids)).")", "IN"), true);

        foreach (array_keys($downloads) as $i) {
            $uids[] = $downloads[$i]->getVar('submitter');
        }

        $member_handler = xoops_gethandler('member');
        $users = $member_handler->getUsers(new Criteria("uid", "(".implode(',', array_unique($uids)).")", "IN"), true);

        foreach (array_keys($reports) as $i) {
        	// Does the download exists ?
        	if (isset($downloads[$reports[$i]->getVar('lid')])) {
	        	$submitter = isset($users[$downloads[$reports[$i]->getVar('lid')]->getVar('submitter')]) ? $users[$downloads[$reports[$i]->getVar('lid')]->getVar('submitter')] : false;
	            $download = $downloads[$reports[$i]->getVar('lid')];
	            $download_link = "<a href='" . XOOPS_URL . "/modules/wfdownloads/singlefile.php?cid=" . $download->getVar('cid') . "&amp;lid=" . $download->getVar('lid') . "' target='_blank'>" . $download->getVar('title') . "</a>";
	            $submitteremail = is_object($submitter) ? $submitter->getVar('email') : '';
	            $submitteruname = is_object($submitter) ? $submitter->getVar('uname') : $xoopsConfig['anonymous'];
        	} else {
	        	$submitter = '';
	            $download_link = _AM_WFD_BROKEN_DOWNLOAD_DONT_EXISTS;
	            $submitteremail = '';
	            $submitteruname = $xoopsConfig['anonymous'];
        	}
        	
            $sender = isset($users[$reports[$i]->getVar('sender')]) ? $users[$reports[$i]->getVar('sender')] : "";
            $senderemail = isset($users[$reports[$i]->getVar('sender')]) ? $users[$reports[$i]->getVar('sender')]->getVar('email') : "";
            $sendername = isset($users[$reports[$i]->getVar('sender')]) ? $users[$reports[$i]->getVar('sender')]->getVar('uname') : $xoopsConfig['anonymous'];

            $ingnore_link = "<a href='brokendown.php?op=ignoreBrokenDownloads&amp;lid=".$download->getVar('lid')."' alt='" . _AM_WFD_BROKEN_IGNORE_ALT . "' title='" . _AM_WFD_BROKEN_IGNORE_ALT . "'>" . $imagearray['ignore'] . "</a>";
			$edit_link = "<a href='index.php?op=Download&amp;lid=".$download->getVar('lid')."' alt='" . _AM_WFD_BROKEN_EDIT_ALT . "' title='" . _AM_WFD_BROKEN_EDIT_ALT . "'> " . $imagearray['editimg'] . " </a>";
			$delete_link = "<a href='brokendown.php?op=delBrokenDownloads&amp;lid=".$download->getVar('lid')."' alt='" . _AM_WFD_BROKEN_DELETE_ALT . "' title='" . _AM_WFD_BROKEN_DELETE_ALT . "'>" . $imagearray['deleteimg'] . "</a>";
			$ack_image = ($reports[$i]->getVar('acknowledged')) ? $imagearray['ack_yes'] : $imagearray['ack_no'];
			$ack_link = "<a href='brokendown.php?op=updateNotice&amp;lid=".$download->getVar('lid')."&amp;ack=".$reports[$i]->getVar('acknowledged')."' alt='" . _AM_WFD_BROKEN_ACK_ALT . "' title='" . _AM_WFD_BROKEN_ACK_ALT . "'>" . $ack_image . "</a>";
			$con_image = ($reports[$i]->getVar('confirmed')) ? $imagearray['con_yes'] : $imagearray['con_no'];
			$con_link = "<a href='brokendown.php?op=updateNotice&amp;lid=".$download->getVar('lid')."&amp;con=".$reports[$i]->getVar('confirmed')."' alt='" . _AM_WFD_BROKEN_CONFIRM_ALT . "' title='" . _AM_WFD_BROKEN_CONFIRM_ALT . "'>" . $con_image . " </a>\n";
            
            echo "
		<tr align = 'center'>\n
		<td class = 'head'>".$reports[$i]->getVar('reportid')."</td>\n
		<td class = 'even' align = 'left'>$download_link</td>\n
		";
            if ($senderemail == "")
            {
                echo "<td class = 'even'>".$sendername." (".$reports[$i]->getVar('ip').")";
            }
            else
            {
                echo "<td class = 'even'><a href='mailto:".$senderemail."'>".$sendername."</a> (".$reports[$i]->getVar('ip').")";
            }
            if ($submitteremail == '')
            {
                echo "<td class = 'even'>".$submitteruname;
            }
            else
            {
                echo "<td class = 'even'><a href='mailto:".$submitteremail."'>".$submitteruname."</a>";
            }
            echo "
		</td>\n
		<td class='even' align='center'>" . formatTimestamp($reports[$i]->getVar('date'), $xoopsModuleConfig['dateformat']) . "</td>\n
		<td align='center' class = 'even' nowrap>\n
		$ingnore_link $edit_link $delete_link $ack_link $con_link
		</td></tr>\n
		";
        }
    }
    echo"</table>";
}
xoops_cp_footer();

?>