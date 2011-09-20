<?php
/**
 * $Id: votedata.php,v 1.4 2006/04/07 00:11:36 malanciault Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';

$op = "";

if (isset($_REQUEST['op'])) $op = $_REQUEST['op'];
$rating_handler = xoops_getmodulehandler('rating');
switch ($op)
{
    case "delVote":
        $rid = intval($_GET['rid']);
        $lid = intval($_GET['lid']);
        $rating_handler->deleteAll(new Criteria("ratingid", $rid), true);
        wfd_updaterating($lid);
        redirect_header("votedata.php", 1, _AM_WFD_VOTEDELETED);
        break;

    case 'main':
    default:

        global $xoopsDB, $imagearray;

		$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
        $useravgrating = '0';
        $uservotes = '0';

        $criteria = new CriteriaCompo();
        $criteria->setSort('ratingtimestamp');
        $criteria->setOrder("DESC");
        $criteria->setLimit(20);
        $criteria->setStart($start);;
        $ratings = $rating_handler->getObjects($criteria);
		$votes = count($ratings);

        $useravgrating = $rating_handler->getUserAverage();
        $useravgrating = number_format($useravgrating["avg"], 2);

        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(8, _AM_WFD_VOTE_RATINGINFOMATION);

	echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_VOTE_DISPLAYVOTES . "</legend>\n
		<div style='padding: 8px;'>\n
		<div><b>" . _AM_WFD_VOTE_USERAVG . ": </b>$useravgrating</div>\n
		<div><b>" . _AM_WFD_VOTE_TOTALRATE . ": </b>$uservotes</div>\n
		<div style='padding: 8px;'>\n
		<li>" . $imagearray['deleteimg'] . " " . _AM_WFD_VOTE_DELETEDSC . "</li>
		<div>\n
		</fieldset>\n
		<br />\n

		<table width='100%' cellspacing='1' cellpadding='2' class='outer'>\n
		<tr>\n
		<th align='center'>" . _AM_WFD_VOTE_ID . "</th>\n
		<th align='center'>" . _AM_WFD_VOTE_USER . "</th>\n
		<th align='center'>" . _AM_WFD_VOTE_IP . "</th>\n
		<th align='center'>" . _AM_WFD_VOTE_FILETITLE . "</th>\n
		<th align='center'>" . _AM_WFD_VOTE_RATING . "</th>\n
		<th align='center'>" . _AM_WFD_VOTE_DATE . "</th>\n
		<th align='center'>" . _AM_WFD_MINDEX_ACTION . "</th></tr>\n";

        if ($votes == 0)
        {
            echo "<tr><td align='center' colspan='7' class='head'>" . _AM_WFD_VOTE_NOVOTES . "</td></tr>";
        }
        else {
            foreach (array_keys($ratings) as $i) {
                $lids[] = $ratings[$i]->getVar('lid');
            }
            $download_handler = xoops_getmodulehandler('download');
            $downloads = $download_handler->getObjects(new Criteria("lid", "(".implode(',', array_unique($lids)).")", "IN"), true);
            foreach (array_keys($ratings) as $i) {
                $formatted_date = formatTimestamp($ratings[$i]->getVar('ratingtimestamp'), $xoopsModuleConfig['dateformat']);
                $ratinguname = XoopsUser::getUnameFromId($ratings[$i]->getVar('ratinguser'));
                echo "
            		<tr>\n
            		<td class='head' align='center'>".$ratings[$i]->getVar('ratingid')."</td>\n
            		<td class='even' align='center'>".$ratings[$i]->getVar('ratinguname')."</td>\n
            		<td class='even' align='center'>".$ratings[$i]->getVar('ratinghostname')."</td>\n
            		<td class='even' align='center'>".$downloads[$ratings[$i]->getVar('lid')]->getVar('title')."</td>\n
            		<td class='even' align='center'>".$ratings[$i]->getVar('rating')."</td>\n
            		<td class='even' align='center'>$formatted_date</td>\n
            		<td class='even' align='center'><b><a href='votedata.php?op=delVote&amp;lid=".$ratings[$i]->getVar('lid')."&amp;rid=".$ratings[$i]->getVar('ratingid')."'>".$imagearray['deleteimg']."</a></b></td>\n
            		</tr>\n";
            }
        }
        echo "</table>";
		//Include page navigation
		include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $page = ($votes > 20) ? _AM_WFD_MINDEX_PAGE : '';
        $pagenav = new XoopsPageNav($page, 20, $start, 'start');
        echo '<div align="right" style="padding: 8px;">' . $page . '' . $pagenav->renderNav() . '</div>';
        break;
}
xoops_cp_footer();

?>