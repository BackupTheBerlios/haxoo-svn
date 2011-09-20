<?php
/**
 * $Id: category.php,v 1.8 2006/04/29 08:00:19 mithyt2 Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */
include 'admin_header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopsform/grouppermform.php';
include_once XOOPS_ROOT_PATH . '/class/tree.php';

$op = '';

function createcat($cid = 0)
{
    include_once '../class/wfd_lists.php';
    include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

    $category_handler = xoops_getmodulehandler('category');

    $heading = _AM_WFD_CCATEGORY_CREATENEW;

    if ($cid > 0)
    {
        $category = $category_handler->get($cid);
        $heading = _AM_WFD_CCATEGORY_MODIFY;
    } else {
        $category = $category_handler->create();
	}

	$sform = $category->getForm($heading);
	$sform -> display();
}

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : 'main';

switch ($op)
{
    case "move":
        if (!isset($_POST['ok']))
        {
            $cid = (isset($_POST['cid'])) ? intval($_POST['cid']) : intval($_GET['cid']);

            wfdownloads_xoops_cp_header();
            wfdownloads_adminMenu(2, _AM_WFD_MCATEGORY);

            include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $sform = new XoopsThemeForm(_AM_WFD_CCATEGORY_MOVE, "move", xoops_getenv('PHP_SELF'));

            $category_handler = xoops_getmodulehandler('category');
            $categories = $category_handler->getObjects();
            $mytree = new XoopsObjectTree($categories, "cid", "pid");
            $sform->addElement(new XoopsFormLabel(_AM_WFD_BMODIFY, $mytree->makeSelBox('target', 'title')));

            $create_tray = new XoopsFormElementTray('', '');
            $create_tray -> addElement(new XoopsFormHidden('source', $cid));
            $create_tray -> addElement(new XoopsFormHidden('ok', 1));
            $create_tray -> addElement(new XoopsFormHidden('op', 'move'));
            $butt_save = new XoopsFormButton('', '', _AM_WFD_BMOVE, 'submit');
            $butt_save -> setExtra('onclick="this.form.elements.op.value=\'move\'"');
            $create_tray -> addElement($butt_save);
            $butt_cancel = new XoopsFormButton('', '', _AM_WFD_BCANCEL, 'submit');
            $butt_cancel -> setExtra('onclick="this.form.elements.op.value=\'cancel\'"');
            $create_tray -> addElement($butt_cancel);
            $sform -> addElement($create_tray);
            $sform -> display();
            xoops_cp_footer();
        }
        else
        {
            global $xoopsDB;

            $source = intval($_POST['source']);
            $target = intval($_POST['target']);
            if ($target == $source)
            {
                redirect_header("category.php?op=move&amp;ok=0&amp;cid=$source", 5, _AM_WFD_CCATEGORY_MODIFY_FAILED);
            }

            if (!$target)
            {
                redirect_header("category.php?op=move&amp;ok=0&amp;cid=$source", 5, _AM_WFD_CCATEGORY_MODIFY_FAILEDT);
            }
            $download_handler = xoops_getmodulehandler('download');
            $result = $download_handler->updateAll("cid", $target, new Criteria("cid", intval($source)), true);
            if (!$result)
            {
                $error = _AM_WFD_DBERROR;
                trigger_error($error, E_USER_ERROR);
            }
            redirect_header("category.php?op=default", 1, _AM_WFD_CCATEGORY_MODIFY_MOVED);
            exit();
        }
        break;

    case "addCat":

        global $xoopsDB, $myts, $_FILES, $xoopsModuleConfig;

        $cid = (isset($_POST["cid"])) ? intval($_POST["cid"]) : 0;
        $groups = isset($_POST['groups']) ? $_POST['groups'] : array();
        $pid = (isset($_POST["pid"])) ? intval($_POST["pid"]) : 0;
        $weight = (isset($_POST["weight"]) && $_POST["weight"] > 0) ? intval($_POST["weight"]) : 0;
        $spotlighthis = (isset($_POST["lid"])) ? intval($_POST["lid"]) : 0;
        $spotlighttop = (isset($_POST["spotlighttop"]) && ($_POST["spotlighttop"] == 1)) ? 1 : 0;
        $imgurl = ($_POST["imgurl"] && $_POST["imgurl"] != "blank.png") ? $myts -> addslashes($_POST["imgurl"]) : "";

        $dohtml = isset($_POST['dohtml']);
        $dosmiley = isset($_POST['dosmiley']);
        $doxcode = isset($_POST['doxcode']);
        $doimage = isset($_POST['doimage']);
        $dobr = isset($_POST['dobr']);

        $category_handler = xoops_getmodulehandler('category');
        if (!$cid)
        {
            $category = $category_handler->create();
        }
        else {
            $category = $category_handler->get($cid);
            $childcats = $category_handler->getChildCats($category);
            if ($pid == $cid || in_array($pid, array_keys($childcats))) {
                $category->setErrors(_AM_WFD_CCATEGORY_CHILDASPARENT);
            }
        }

        $category->setVar('title', $_POST["title"]);
        $category->setVar('description', $_POST["description"]);
        $category->setVar('summary', $_POST["summary"]);
        $category->setVar('dohtml', $dohtml);
        $category->setVar('dosmiley', $dosmiley);
        $category->setVar('doxcode', $doxcode);
        $category->setVar('dobr', $dobr);
        $category->setVar('doimage', $doimage);
        $category->setVar('pid', $pid);
        $category->setVar('weight', $weight);
        $category->setVar('spotlighthis', $spotlighthis);
        $category->setVar('spotlighttop', $spotlighttop);
        $category->setVar('imgurl', $imgurl);
        $result = $category_handler->insert($category);
        if (!$result) {
            echo $category->getHtmlErrors();
        }
        if (!$cid)
        {
            if ($cid == 0) {
                $newid = $category->getVar('cid');
            }
            wfd_save_Permissions($groups, $newid, 'WFDownCatPerm');
            /**
             * Notify of new category
             */
            global $xoopsModule;
            $tags = array();
            $tags['CATEGORY_NAME'] = $_POST['title'];
            $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/wfdownloads/viewcat.php?cid=' . $newid;
            $notification_handler = & xoops_gethandler('notification');
            $notification_handler -> triggerEvent('global', 0, 'new_category', $tags);
            $database_mess = _AM_WFD_CCATEGORY_CREATED;
        }
        else
        {
            $database_mess = _AM_WFD_CCATEGORY_MODIFIED;
            wfd_save_Permissions($groups, $cid, 'WFDownCatPerm');
        }
        redirect_header("category.php", 1, $database_mess);
        break;

    case "del":

        global $xoopsDB, $xoopsModule;

        $cid = (isset($_POST['cid']) && is_numeric($_POST['cid'])) ? intval($_POST['cid']) : intval($_GET['cid']);
        $ok = (isset($_POST['ok']) && $_POST['ok'] == 1) ? intval($_POST['ok']) : 0;
        $category_handler = xoops_getmodulehandler('category');
        $categories = $category_handler->getObjects();
        $mytree = new XoopsObjectTree($categories, "cid", "pid");

        if ($ok == 1)
        {
            // get all subcategories under the specified category
            $arr = $mytree -> getAllChild($cid);

           foreach($arr as $child)
            {
                // get all category ids
                $cids[] = $child->getVar('cid');
            }
            $cids[] = $cid;

            $criteria = new Criteria("cid", "(".implode(',', $cids).")", "IN");

            $download_handler = xoops_getmodulehandler('download');
            //get list of downloads in these subcategories
            $downloads = $download_handler->getList($criteria);

            $download_criteria = new Criteria("lid", "(".implode(',', array_keys($downloads)).")", "IN");

            $rating_handler = xoops_getmodulehandler('rating');
            $report_handler = xoops_getmodulehandler('report');

            // now for each download, delete the text data and vote data associated with the download
            $rating_handler->deleteAll($download_criteria);
            $report_handler->deleteAll($download_criteria);
            $download_handler->deleteAll($download_criteria);
            foreach (array_keys($downloads) as $lid) {
                xoops_comment_delete($xoopsModule -> getVar('mid'), $lid);
            }

            // all downloads for each category is deleted, now delete the category data
            $category_handler->deleteAll($criteria);
            $error = _AM_WFD_DBERROR;

            foreach ($cids as $cid) {
                xoops_groupperm_deletebymoditem ($xoopsModule -> getVar('mid'), 'WFDownCatPerm', $cid);
            }

            redirect_header("category.php", 1, _AM_WFD_CCATEGORY_DELETED);
            exit();
        }
        else
        {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'del', 'cid' => $cid, 'ok' => 1), 'category.php', _AM_WFD_CCATEGORY_AREUSURE);
            xoops_cp_footer();
        }
        break;

    case "modCat":
        $cid = (isset($_POST['cid'])) ? $_POST['cid'] : 0;
        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(2, _AM_WFD_MCATEGORY);
        createcat($cid);
        xoops_cp_footer();
        break;

    case 'main':
    default:

        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(2, _AM_WFD_MCATEGORY);

        include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        $totalcats = wfd_totalcategory();

        if ($totalcats > 0)
        {
            $sform = new XoopsThemeForm(_AM_WFD_CCATEGORY_MODIFY, "category", $_SERVER['REQUEST_URI']);
            $category_handler = xoops_getmodulehandler('category');
            $categories = $category_handler->getObjects();
            $mytree = new XoopsObjectTree($categories, "cid", "pid");
            $sform->addElement(new XoopsFormLabel(_AM_WFD_CCATEGORY_MODIFY_TITLE, $mytree->makeSelBox('cid', 'title')));

            $dup_tray = new XoopsFormElementTray('', '');
            $dup_tray -> addElement(new XoopsFormHidden('op', 'modCat'));
            $butt_dup = new XoopsFormButton('', '', _AM_WFD_BMODIFY, 'submit');
            $butt_dup -> setExtra('onclick="this.form.elements.op.value=\'modCat\'"');
            $dup_tray -> addElement($butt_dup);
            $butt_move = new XoopsFormButton('', '', _AM_WFD_BMOVE, 'submit');
            $butt_move -> setExtra('onclick="this.form.elements.op.value=\'move\'"');
            $dup_tray -> addElement($butt_move);
            $butt_dupct = new XoopsFormButton('', '', _AM_WFD_BDELETE, 'submit');
            $butt_dupct -> setExtra('onclick="this.form.elements.op.value=\'del\'"');
            $dup_tray -> addElement($butt_dupct);
            $sform -> addElement($dup_tray);
            $sform -> display();
        }

        createcat(0);
        xoops_cp_footer();
        break;
}

?>
