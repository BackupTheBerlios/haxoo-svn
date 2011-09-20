<?php
/**
 * $Id: review.php,v 1.8 2006/04/13 00:34:57 malanciault Exp $
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

    $xoopsOption['template_main'] = 'wfdownloads_reviews.html';
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

    $review_handler = xoops_getmodulehandler('review');
    $criteria = new CriteriaCompo(new Criteria("lid", $lid));
    $criteria->add(new Criteria("submit", 1));
    $review_amount = $review_handler->getCount($criteria);
    $criteria->setSort("date");
    $criteria->setLimit(5);
    $criteria->setStart($start);
    $reviews = $review_handler->getObjects($criteria);


    $down_arr = $download->toArray();
    $xoopsTpl->assign('down_arr', $down_arr);

    foreach (array_keys($reviews) as $i)
    {
        $down_review = $reviews[$i]->toArray();
        $down_review['date'] = formatTimestamp($down_review['date'], $xoopsModuleConfig['dateformat']);
        $down_review['submitter'] = xoops_getLinkedUnameFromId(intval($down_review['uid']));
        $review_rating = round(number_format($down_review['rated'], 0) / 2);
        $rateimg = "rate$review_rating.gif";
        $down_review['rated_img'] = $rateimg;
        $xoopsTpl->append('down_review', $down_review);
    }
    $xoopsTpl->assign('lang_review_found', sprintf(_MD_WFD_REVIEWTOTAL, $review_amount));

    include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
    $pagenav = new XoopsPageNav($review_amount, 5 , $start, 'start', 'op=list&amp;cid=' . $cid . '&amp;lid=' . $lid . '', 1);
    $navbar['navbar'] = $pagenav->renderNav();
    $xoopsTpl->assign('navbar', $navbar);

    include 'footer.php';
    break;

    case "default";
    default:
        if (!is_object($xoopsUser) && !$xoopsModuleConfig['rev_anonpost'])
        {
            redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFD_MUSTREGFIRST);
            exit();
        }

        if (!empty($_POST['submit']))
        {
            $uid = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

            $review_handler = xoops_getmodulehandler('review');
            $review = $review_handler->create();
            $review->setVar('title', trim($_POST["title"]));
            $review->setVar('review', trim($_POST["review"]));
            $review->setVar('lid', intval($_POST["lid"]));
            $review->setVar('rated', intval($_POST["rated"]));
            $review->setVar('date', time());
            $review->setVar('uid', $uid);
            $submit = ($xoopsModuleConfig['rev_approve']) ? 1 : 0 ;
            $review->setVar('submit', $submit);

            if (!$review_handler->insert($review))
            {
                redirect_header('index.php', 3, _MD_WFD_ERROR_CREATEREVIEW);
            }
            else
            {
                $database_mess = ($xoopsModuleConfig['rev_approve']) ? _MD_WFD_ISAPPROVED : _MD_WFD_ISNOTAPPROVED;
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
				<div>" . _MD_WFD_REV_SNEWMNAMEDESC . "</div>\n";

            $sform = new XoopsThemeForm(_MD_WFD_REV_SUBMITREV, "reviewform", xoops_getenv('PHP_SELF'));
            $sform->addElement(new XoopsFormText(_MD_WFD_REV_TITLE, 'title', 50, 255), true);
            $rating_select = new XoopsFormSelect(_MD_WFD_REV_RATING, "rated", '10');
            $rating_select->addOptionArray(array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10));
            $sform->addElement($rating_select);
            $sform->addElement(new XoopsFormDhtmlTextArea(_MD_WFD_REV_DESCRIPTION, 'review', '', 15, 60), true);
            $sform->addElement(new XoopsFormHidden("lid", $_GET['lid']));
            $sform->addElement(new XoopsFormHidden("uid", $uid));
            $button_tray = new XoopsFormElementTray('', '');
            $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
            $sform->addElement($button_tray);
            $sform->display();
            include 'footer.php';
        }
}
?>