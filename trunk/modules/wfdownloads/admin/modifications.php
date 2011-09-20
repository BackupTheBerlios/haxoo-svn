<?php
/**
 * $Id: modifications.php,v 1.4 2006/05/23 01:46:50 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';

if (!isset($_POST['op']))
{
    $op = isset($_GET['op']) ? $_GET['op'] : 'main';
}
else
{
    $op = $_POST['op'];
}

switch ($op)
{
    case "listModReqshow":

        include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        global $xoopsDB, $myts, $mytree, $xoopsModuleConfig, $xoopsUser;

        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(3, _AM_WFD_MOD_MODREQUESTS);

        $requestid = intval($_GET['requestid']);
        $modification_handler = xoops_getmodulehandler('modification');
        $download_handler = xoops_getmodulehandler('download');
        $category_handler = xoops_getmodulehandler('category');

        $modification = $modification_handler->get($requestid);
        $download = $download_handler->get($modification->getVar('lid'));

        $orig_user = new XoopsUser($download->getVar('submitter'));
        $submittername = xoops_getLinkedUnameFromId($download->getVar('submitter')); // $orig_user->getvar("uname");
        $submitteremail = $orig_user->getVar("email");

        echo "<div><b>" . _AM_WFD_MOD_MODPOSTER . "</b> $submittername</div>";
        $not_allowed = array("lid", "submitter", "requestid", "modifysubmitter");

        $sform = new XoopsThemeForm(_AM_WFD_MOD_ORIGINAL, "storyform", "index.php");

        $keys = $download->getVars();
        foreach (array_keys($keys) as $key)
        {
            if (in_array($key , $not_allowed))
            {
                continue;
            }
            $lang_def = constant("_AM_WFD_MOD_" . strtoupper($key));

            $content = $download->getVar($key, 'e');
            switch ($key) {
                case "platform":
                case "license":
                case "limitations":
                $content = $xoopsModuleConfig[$key][$download->getVar($key)];
                break;

                case "cid":
                $category_list = $category_handler->getObjects(new Criteria("cid", $download->getVar($key)));
                if (!isset($category_list[0])) {
                    continue;
                }
                $content = $category_list[0]->getVar('title', 'e');
                break;

                case "screenshot":
                if ($content != "") {
                    $content = "<img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['screenshots'] . "/" . $content . "' width='" . $xoopsModuleConfig['shotwidth'] . "' alt='' />";
                }
                break;

                case "features":
                case "requirements":
                if ($content != '')
                {
                    $downrequirements = explode('|', trim($content));
                    foreach ($downrequirements as $bi)
                    {
                        $content = "<li>" . $bi;
                    }
                }
                break;

                case "dhistory":
	            $content = $myts->displayTarea($content, 1, 0, 0, 0, 1);
	            break;
            }
            $sform->addElement(new XoopsFormLabel($lang_def, $content));
        }
        $sform->display();

        $modify_user = new XoopsUser($modification->getVar('modifysubmitter'));
        $modifyname = xoops_getLinkedUnameFromId($modify_user->getVar('uid'));
        $modifyemail = $modify_user->getVar("email");

        echo "<div><b>" . _AM_WFD_MOD_MODIFYSUBMITTER . "</b> $modifyname</div>";
        $sform = new XoopsThemeForm(_AM_WFD_MOD_PROPOSED, "storyform", "modifications.php");
        $keys = $modification->getVars();
        foreach (array_keys($keys) as $key)
        {
            if (in_array($key , $not_allowed))
            {
                continue;
            }
            $lang_def = constant("_AM_WFD_MOD_" . strtoupper($key));

            $content = $modification->getVar($key, 'e');
            switch ($key) {
                case "platform":
                case "license":
                case "limitations":
                $content = $xoopsModuleConfig[$key][$modification->getVar($key)];
                break;

                case "cid":
                $category_list = $category_handler->getObjects(new Criteria("cid", $modification->getVar($key)));
                if (!isset($category_list[0])) {
                    continue;
                }
                $content = $category_list[0]->getVar('title', 'e');
                break;

                case "screenshot":
                if ($content != "") {
                    $content = "<img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['screenshots'] . "/" . $content . "' width='" . $xoopsModuleConfig['shotwidth'] . "' alt='' />";
                }
                break;

                case "features":
                case "requirements":
                if ($content != '')
                {
                    $downrequirements = explode('|', trim($content));
                    foreach ($downrequirements as $bi)
                    {
                        $content = "<li>" . $bi;
                    }
                }
                break;

                case "dhistory":
	            $content = $myts->displayTarea($content, 1, 0, 0, 0, 1);
	            break;
            }
            $sform->addElement(new XoopsFormLabel($lang_def, $content));
        }

        $button_tray = new XoopsFormElementTray('', '');
        $button_tray->addElement(new XoopsFormHidden('requestid', $requestid));
        $button_tray->addElement(new XoopsFormHidden('lid', $modification->getVar('lid')));
        $hidden = new XoopsFormHidden('op', 'changeModReq');
        $button_tray->addElement($hidden);
        if (!$modification->isNew())
        {
            $butt_dup = new XoopsFormButton('', '', _AM_WFD_BAPPROVE, 'submit');
            $butt_dup->setExtra('onclick="this.form.elements.op.value=\'changeModReq\'"');
            $button_tray->addElement($butt_dup);
        }
        $butt_dupct2 = new XoopsFormButton('', '', _AM_WFD_BIGNORE, 'submit');
        $butt_dupct2->setExtra('onclick="this.form.elements.op.value=\'ignoreModReq\'"');
        $button_tray->addElement($butt_dupct2);
        $sform->addElement($button_tray);
        $sform->display();

        xoops_cp_footer();
        exit();
        break;

    case "changeModReq":

        $modification_handler = xoops_getmodulehandler('modification');
        $modification_handler->approveModification($_POST['requestid']);

        redirect_header('index.php', 1, _AM_WFD_MOD_REQUPDATED);
        break;

    case "ignoreModReq":
	    $criteria = new Criteria('requestid', intval($_POST['requestid']));
	    $modification_handler = xoops_getmodulehandler('modification');
	    $modification_handler->deleteAll($criteria, true);
        redirect_header('index.php', 1, _AM_WFD_MOD_REQDELETED);
        break;

    case 'main':
    default:

        include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

        global $xoopsModuleConfig;
        $start = isset($_GET['start']) ? intval($_GET['start']) : 0;

        $modification_handler = xoops_getmodulehandler('modification');
        $criteria = new CriteriaCompo();
        $criteria->setLimit($xoopsModuleConfig['admin_perpage']);
        $criteria->setStart($start);
        $criteria->setSort("requestdate");
        $modifications = $modification_handler->getObjects($criteria);
        $totalmodrequests = $modification_handler->getCount();

        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(3, _AM_WFD_MOD_MODREQUESTS);
	echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_MOD_MODREQUESTSINFO . "</legend>\n
		<div style='padding: 8px;'>" . _AM_WFD_MOD_TOTMODREQUESTS . " <b>$totalmodrequests</></div>\n
		</fieldset><br />\n

		<table width='100%' cellspacing='1' cellpadding='2' border='0' class='outer'>\n
		<tr>\n
		<th align='center'><b>" . _AM_WFD_MOD_MODID . "</b></th>\n
		<th><b>" . _AM_WFD_MOD_MODTITLE . "</b></th>\n
		<th align='center'><b>" . _AM_WFD_MOD_MODIFYSUBMIT . "</b></th>\n
		<th align='center'><b>" . _AM_WFD_MOD_DATE . "</b></th>\n
		<th align='center'><b>" . _AM_WFD_MINDEX_ACTION . "</b></th>\n
		</tr>\n";
        if ($totalmodrequests > 0)
        {
            foreach ($modifications as $modification)
            {
                $submitter = xoops_getLinkedUnameFromId($modification->getVar('modifysubmitter'));
                $requestdate = formatTimestamp($modification->getVar('requestdate'), $xoopsModuleConfig['dateformat']);
	            echo "
            		<tr>\n
            		<td class='head' align='center'>" . $modification->getVar('requestid') . "</td>\n
            		<td class='even'>" . $modification->getVar('title') . "</td>\n
            		<td class='even' align='center'>" . $submitter . "</td>\n
            		<td class='even' align='center'>" . $requestdate . "</td>\n
            		<td class='even' align='center'> <a href='modifications.php?op=listModReqshow&amp;requestid=" . $modification->getVar('requestid'). "'>"._AM_WFD_MOD_VIEW."</a></td>\n
            		</tr>\n";
            }
        }
        else
        {
            echo "<tr><td class='head' align='center' colspan='7'>" . _AM_WFD_MOD_NOMODREQUEST . "</td></tr>";
        }
        echo "</table>\n";

        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $page = ($totalmodrequests > $xoopsModuleConfig['admin_perpage']) ? _AM_WFD_MINDEX_PAGE : '';
        $pagenav = new XoopsPageNav($totalmodrequests, $xoopsModuleConfig['admin_perpage'], $start, 'start');
        echo "<div align='right' style='padding: 8px;'>" . $page . '' . $pagenav->renderNav() . '</div>';
        xoops_cp_footer();
}

?>