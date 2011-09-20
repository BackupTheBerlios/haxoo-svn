<?php
/**
 * $Id: mirror.php,v 1.7 2006/04/13 00:34:57 malanciault Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

global $xoopsModuleConfig, $myts, $xoopsUser;
$gperm_handler =& xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

$cid = isset($_REQUEST['cid']) ? $_REQUEST['cid'] : 0;

if (!$gperm_handler->checkRight("WFDownCatPerm", $cid,$groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL.'/modules/wfdownloads/index.php',3, _NOPERM);
    exit();
}

$use_mirrors = $xoopsModuleConfig['enable_mirrors'];
if ($use_mirrors !== 1 && ((is_object($xoopsUser) && !$xoopsUser->isAdmin()) || !is_object($xoopsUser))) {
    redirect_header(XOOPS_URL.'/modules/wfdownloads/index.php',3, _NOPERM);
    exit();
}

$op = '';

switch (isset($_REQUEST['op']) && !empty($_REQUEST['op']))
{
    case "list";

    $cid = isset($_GET['cid']) ? intval($_GET['cid']) : 0;
    $lid = isset($_GET['lid']) ? intval($_GET['lid']) : 0;
    $start = isset($_GET['start']) ? intval($_GET['start']) : 0;

    $download_handler = xoops_getmodulehandler('download');
    $download = $download_handler->get($lid);

    if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == 1 || ($download->getVar('expired') != 0 && $download->getVar('expired') < time()) || $download->getVar('status') == 0) {
        //Download not published, expired or taken offline - redirect
        redirect_header("index.php", 3, _MD_WFD_NODOWNLOAD);
    }

    $xoopsOption['template_main'] = 'wfdownloads_mirrors.html';
    include XOOPS_ROOT_PATH . '/header.php';

    $sql = "SELECT * FROM " . $xoopsDB->prefix('wfdownloads_indexpage') . " ";
    $head_arr = $xoopsDB->fetchArray($xoopsDB->query($sql));

    $catarray['imageheader'] = wfd_imageheader();
    $catarray['letters'] = wfd_letters();
    $catarray['toolbar'] = wfd_toolbar();
    $xoopsTpl->assign('catarray', $catarray);

    $category_handler = xoops_getmodulehandler('category');
    $category = $category_handler->get($cid);
    $pathstring = $category_handler->getNicePath($cid);
    $xoopsTpl->assign('category_path', $pathstring);
    $xoopsTpl->assign('category_id', $cid);

    $mirror_handler = xoops_getmodulehandler('mirror');
    $criteria = new CriteriaCompo(new Criteria("lid", $lid));
    $criteria->add(new Criteria("submit", 1));
    $mirror_amount = $mirror_handler->getCount($criteria);
    $criteria->setSort("date");
    $criteria->setLimit(5);
    $criteria->setStart($start);
    $mirrors = $mirror_handler->getObjects($criteria);

    $down_arr = $download->toArray();
    $xoopsTpl->assign('down_arr', $down_arr);

    $add_mirror = 0;
    if (!is_object($xoopsUser) && ($xoopsModuleConfig['anonpost'] == 3 || $xoopsModuleConfig['anonpost'] == 4) && ($xoopsModuleConfig['submissions'] == 3 || $xoopsModuleConfig['submissions'] == 4))
    {
        $add_mirror = 1;
    }
    elseif (is_object($xoopsUser) && ($xoopsModuleConfig['submissions'] == 3 || $xoopsModuleConfig['submissions'] == 4 || $xoopsUser->isAdmin()))
    {
        $add_mirror = 1;
    }

    foreach (array_keys($mirrors) as $i)
    {
        $down_mirror = $mirrors[$i]->toArray();
        if ($xoopsModuleConfig['enable_onlinechk'] == 1) {
            $serverurl = str_replace('http://', '', trim($down_mirror['homeurl']));
            $down_mirror['isonline'] = intval(wfd_mirroronline($serverurl));
        } else {
            $down_mirror['isonline'] = 2;
        }
        $down_mirror['add_mirror'] = $add_mirror;
        $down_mirror['date'] = formatTimestamp($down_mirror['date'], $xoopsModuleConfig['dateformat']);
        $down_mirror['submitter'] = xoops_getLinkedUnameFromId(intval($down_mirror['uid']));
        $xoopsTpl->append('down_mirror', $down_mirror);
    }
    $xoopsTpl->assign('lang_mirror_found', sprintf(_MD_WFD_MIRROR_TOTAL, $mirror_amount));

    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $pagenav = new XoopsPageNav($mirror_amount, 5 , $start, 'start', 'op=list&amp;cid=' . $cid . '&amp;lid=' . $lid . '', 1);
    $navbar['navbar'] = $pagenav->renderNav();
    $xoopsTpl->assign('navbar', $navbar);

    $xoopsTpl->assign('categoryPath', $pathstring . " > " . $down_arr['title']);
    $xoopsTpl->assign('module_home', wfdownloads_module_home(true));

    include 'footer.php';
    break;

    case "default";
    default:
        if (!is_object($xoopsUser) && ($xoopsModuleConfig['anonpost'] == 1 || $xoopsModuleConfig['anonpost'] == 2))
        {
            redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFD_MUSTREGFIRST);
            exit();
        }
        elseif (is_object($xoopsUser) && ($xoopsModuleConfig['submissions'] == 1 || $xoopsModuleConfig['submissions'] == 2) && !$xoopsUser->isAdmin())
        {
            redirect_header("index.php", 1, _MD_WFD_MIRROR_NOTALLOWESTOSUBMIT);
            exit();
        }

        if (!empty($_POST['submit']))
        {
            $uid = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

            $mirror_handler = xoops_getmodulehandler('mirror');
            $mirror = $mirror_handler->create();
            $mirror->setVar('title', trim($_POST["title"]));
            $mirror->setVar('homeurl', formatURL(trim($_POST["homeurl"])));
            $mirror->setVar('location', trim($_POST["location"]));
            $mirror->setVar('continent', trim($_POST["continent"]));
            $mirror->setVar('downurl', formatURL(trim($_POST["downurl"])));
            $mirror->setVar('lid', intval($_POST["lid"]));
            $mirror->setVar('uid', $uid);
            $mirror->setVar('date', time());
            if (($xoopsModuleConfig['autoapprove'] == 1 || $xoopsModuleConfig['autoapprove'] == 2) && !$wfdownloads_isAdmin)
            {
                $approve = 0;
            }
            else
            {
                $approve = 1;
            }
            $submit = ($approve) ? 1 : 0 ;
            $mirror->setVar('submit', $submit);

            if (!$mirror_handler->insert($mirror))
            {
                redirect_header('index.php', 3, _MD_WFD_ERROR_CREATEMIRROR);
            }
            else
            {
                $database_mess = ($approve) ? _MD_WFD_ISAPPROVED : _MD_WFD_ISNOTAPPROVED;
                redirect_header('index.php', 2, $database_mess);
            }
        }
        else
        {
            include XOOPS_ROOT_PATH . '/header.php';
            include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
            $uid = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

            echo "
				<div align='center'>" . wfd_imageheader() . "</div><br />\n
				<div>" . _MD_WFD_MIRROR_SNEWMNAMEDESC . "</div>\n";

            $sform = new XoopsThemeForm(_MD_WFD_MIRROR_SUBMITMIRROR, "mirrorform", xoops_getenv('PHP_SELF'));
            $sform->addElement(new XoopsFormText(_MD_WFD_MIRROR_HOMEURLTITLE, 'title', 50, 255), true);
            $sform->addElement(new XoopsFormText(_MD_WFD_MIRROR_HOMEURL, 'homeurl', 50, 255), true);
            $sform->addElement(new XoopsFormText(_MD_WFD_MIRROR_LOCATION, 'location', 50, 255), true);
            $continent_select = new XoopsFormSelect(_MD_WFD_MIRROR_CONTINENT, "continent", _MD_WFD_CONT4);
            $continent_select->addOptionArray(array(_MD_WFD_CONT1 => _MD_WFD_CONT1, _MD_WFD_CONT2 => _MD_WFD_CONT2, _MD_WFD_CONT3 => _MD_WFD_CONT3, _MD_WFD_CONT4 => _MD_WFD_CONT4, _MD_WFD_CONT5 => _MD_WFD_CONT5, _MD_WFD_CONT6 => _MD_WFD_CONT6, _MD_WFD_CONT7 => _MD_WFD_CONT7));
            $sform->addElement($continent_select);
            $sform->addElement(new XoopsFormText(_MD_WFD_MIRROR_DOWNURL, 'downurl', 50, 255), true);
            $sform->addElement(new XoopsFormHidden("lid", $_GET['lid']));
            $sform->addElement(new XoopsFormHidden("cid", $_GET['cid']));
            $sform->addElement(new XoopsFormHidden("uid", $uid));
            $button_tray = new XoopsFormElementTray('', '');
            $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
            $sform->addElement($button_tray);
            $sform->display();
            include 'footer.php';
        }
}
?>