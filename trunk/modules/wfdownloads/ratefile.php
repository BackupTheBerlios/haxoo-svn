<?php
/* $Id: ratefile.php,v 1.5 2006/04/13 00:34:57 malanciault Exp $
* Module: WF-Downloads
* Version: v2.0.5a
* Release Date: 26 july 2004
* Author: WF-Sections
* Licence: GNU
*/

include 'header.php';

global $myts;

$download_handler = xoops_getmodulehandler('download');
if (!empty($_POST['submit']))
{
    $lid = intval($_POST['lid']);
    $download = $download_handler->get($lid);
    if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == 1 || ($download->getVar('expired') !=0 && $download->getVar('expired') < time()) || $download->getVar('status') == 0) {
        //Download not published, expired or taken offline - redirect
        redirect_header("index.php", 3, _MD_WFD_NODOWNLOAD);
    }
    $rating_handler = xoops_getmodulehandler('rating');
    if (empty($xoopsUser))
    {
        $ratinguser = 0;
    }
    else
    {
        $ratinguser = $xoopsUser -> getVar('uid');
    }
    // Make sure only 1 anonymous from an IP in a single day.
    $anonwaitdays = 1;
    $ip = getenv("REMOTE_ADDR");
    $cid = intval($_POST['cid']);
    $filerating = intval($_POST['rating']);
    // Check if Rating is Null
    if ($filerating == "--")
    {
        redirect_header("ratefile.php?cid=" . $cid . "&amp;lid=" . $lid . "", 4, _MD_WFD_NORATING);
        exit();
    }
    // Check if Download POSTER is voting (UNLESS Anonymous users allowed to post)
    if ($ratinguser != 0)
    {
        if ($download->getVar('submitter') == $ratinguser)
        {
            redirect_header("index.php", 4, _MD_WFD_CANTVOTEOWN);
            exit();
        }
        // Check if REG user is trying to vote twice.
        $criteria = new CriteriaCompo(new Criteria("lid", $lid));
        $criteria->add(new Criteria("ratinguser", $ratinguser));
        $ratingcount = $rating_handler->getCount($criteria);
        if ($ratingcount > 0)
        {
            redirect_header('index.php', 4, _MD_WFD_VOTEONCE);
            exit();
        }
    }
    else
    {
        // Check if ANONYMOUS user is trying to vote more than once per day.
        $yesterday = (time() - (86400 * $anonwaitdays));
        $criteria = new CriteriaCompo(new Criteria("lid", $lid));
        $criteria->add(new Criteria("ratinguser", 0));
        $criteria->add(new Criteria("ratinghostname", $ip));
        $criteria->add(new Criteria("ratingtimestamp", $yesterday, ">"));
        $anonvotecount = $rating_handler->getCount($criteria);
        if ($anonvotecount > 0)
        {
            redirect_header("index.php", 4, _MD_WFD_VOTEONCE);
            exit();
        }
    }
    // All is well.  Add to Line Item Rate to DB.
    $rating = $rating_handler->create();
    $rating->setVar("lid", $lid);
    $rating->setVar("ratinguser", $ratinguser);
    $rating->setVar("rating", $filerating);
    $rating->setVar("ratinghostname", $ip);
    $rating->setVar("ratingtimestamp", time());
    if ($rating_handler->insert($rating)) {
        // All is well.  Calculate Score & Add to Summary (for quick retrieval & sorting) to DB.
        wfd_updaterating($lid);
        $ratemessage = _MD_WFD_VOTEAPPRE . "<br />" . sprintf(_MD_WFD_THANKYOU, $xoopsConfig['sitename']);
        redirect_header('index.php', 4, $ratemessage);
    }
    else {
        echo $rating->getHtmlErrors();
    }
}
else
{
    $xoopsOption['template_main'] = 'wfdownloads_ratefile.html';
    include XOOPS_ROOT_PATH . '/header.php';
    $lid = intval($_GET['lid']);
    $cid = intval($_GET['cid']);
    $imageheader = wfd_imageheader();

    $download = $download_handler->get($lid);
    if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == 1 || ($download->getVar('expired') != 0 && $download->getVar('expired') < time())) {
        //Download not published, expired or taken offline - redirect
        redirect_header("index.php", 3, _MD_WFD_NODOWNLOAD);
    }

    $xoopsTpl -> assign('file', array('id' => $lid, 'cid' => $cid, 'title' => $download->getVar('title'), 'imageheader' => $imageheader));
    include XOOPS_ROOT_PATH . '/footer.php';
}
include 'footer.php';

?>