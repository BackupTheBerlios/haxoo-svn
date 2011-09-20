<?php
/**
 * $Id: brokenfile.php,v 1.6 2006/05/03 14:59:08 mithyt2 Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';

if (!empty($_POST['submit'])) {

    global $xoopsModule, $xoopsModuleConfig, $xoopsUser;

    $sender = (is_object($xoopsUser)) ? $xoopsUser->getVar('uid') : 0;
    $ip = getenv("REMOTE_ADDR");
    $lid = intval($_POST['lid']);
    $time = time();

    $download_handler = xoops_getmodulehandler('download');
    $download = $download_handler->get($lid);

    if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == 1 || ($download->getVar('expired') != 0 && $download->getVar('expired') < time()) || $download->getVar('status') == 0) {
        //Download not published, expired or taken offline - redirect
        redirect_header("index.php", 3, _MD_WFD_NODOWNLOAD);
    }
    /*
    *  Check if REG user is trying to report twice.
    */
    $criteria = new Criteria("lid", $lid);
    $report_handler = xoops_getmodulehandler('report');
    $count = $report_handler->getCount($criteria);
    if ($count > 0) {
        redirect_header('index.php', 2, _MD_WFD_ALREADYREPORTED);
    } else {

        $report = $report_handler->create();
        $report->setVar('lid', $lid);
        $report->setVar('sender', $sender);
        $report->setVar('ip', $ip);
        $report->setVar('date', time());
        $report->setVar('confirmed', 0);
        $report->setVar('acknowledged', 0);
        $report_handler->insert($report);

        $tags = array();
        $tags['BROKENREPORTS_URL'] = XOOPS_URL . '/modules/wfdownloads/admin/index.php?op=listBrokenDownloads';
        $notification_handler = &xoops_gethandler('notification');
        $notification_handler->triggerEvent('global', 0, 'file_broken', $tags);

        /**
         * Send email to the owner of the download stating that it is broken
         */

        $member_handler = xoops_gethandler('member');
        $user = $member_handler->getUser($download->getVar('submitter'));
        $subdate = formatTimestamp($download->getVar('published'), $xoopsModuleConfig['dateformat']);
        $cid = $download->getVar('cid');
        $title = $download->getVar('title');
        $subject = _MD_WFD_BROKENREPORTED;

        $xoopsMailer = &getMailer();
        $xoopsMailer->useMail();
        $template_dir = XOOPS_ROOT_PATH . "/modules/wfdownloads/language/" . $xoopsConfig['language'] . "/mail_template";

        $xoopsMailer->setTemplateDir($template_dir);
        $xoopsMailer->setTemplate('filebroken_notify.tpl');
        $xoopsMailer->setToEmails($user->email());
        $xoopsMailer->setFromEmail($xoopsConfig['adminmail']);
        $xoopsMailer->setFromName($xoopsConfig['sitename']);
        $xoopsMailer->assign("X_UNAME", $user->uname());
        $xoopsMailer->assign("SITENAME", $xoopsConfig['sitename']);
        $xoopsMailer->assign("X_ADMINMAIL", $xoopsConfig['adminmail']);
        $xoopsMailer->assign('X_SITEURL', XOOPS_URL . '/');
        $xoopsMailer->assign("X_TITLE", $title);
        $xoopsMailer->assign("X_SUB_DATE", $subdate);
        $xoopsMailer->assign('X_DOWNLOAD', XOOPS_URL . '/modules/wfdownloads/singlefile.php?cid=' . $cid . '&lid=' . $lid);
        $xoopsMailer->setSubject($subject);
        $xoopsMailer->send();
        redirect_header('index.php', 2, _MD_WFD_BROKENREPORTED);
        exit();
    }
} else {
    /**
     * Begin Main page Heading etc
     */
    $catarray['imageheader'] = wfd_imageheader();

    $lid = (isset($_GET['lid']) && $_GET['lid'] > 0) ? intval($_GET['lid']) : 0;

    $download_handler = xoops_getmodulehandler('download');
    $download = $download_handler->get($lid);

    if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == 1 || ($download->getVar('expired') != 0 && $download->getVar('expired') < time())) {
        //Download not published, expired or taken offline - redirect
        redirect_header("index.php", 3, _MD_WFD_NODOWNLOAD);
    }

    $xoopsOption['template_main'] = 'wfdownloads_brokenfile.html';
    include XOOPS_ROOT_PATH . '/header.php';

    $xoopsTpl->assign('catarray', $catarray);

    $report_handler = xoops_getmodulehandler('report');
    $report = $report_handler->getObjects(new Criteria('lid', $lid));
    if (isset($report[0])) {
        $report_obj = $report[0];
        global $xoopsModuleConfig;

        $broken['title'] = trim($download->getVar('title'));
        $broken['id'] = $report_obj->getVar('reportid');
        $broken['reporter'] = xoops_getLinkedUnameFromId(intval($report_obj->getVar('sender')));
        $broken['date'] = formatTimestamp($report_obj->getVar('published'), $xoopsModuleConfig['dateformat']);
        $broken['acknowledged'] = ($report_obj->getVar('acknowledged') == 1) ? _YES : _NO ;
        $broken['confirmed'] = ($report_obj->getVar('confirmed') == 1) ? _YES : _NO ;

        $xoopsTpl->assign('broken', $broken);
        $xoopsTpl->assign('brokenreport', true);
    } else {
        /**
         * file info
         */
        $down['title'] = trim($download->getVar('title'));
        $down['homepage'] = $myts->makeClickable(formatURL(trim($download->getVar('homepage'))));
        $time = ($download->getVar('updated') != 0) ? $download->getVar('updated') : $download->getVar('published');
        $down['updated'] = formatTimestamp($time, $xoopsModuleConfig['dateformat']);
        $is_updated = ($download->getVar('updated') != 0) ? _MD_WFD_UPDATEDON : _MD_WFD_SUBMITDATE;
        $down['publisher'] = xoops_getLinkedUnameFromId(intval($download->getVar('submitter')));

        $xoopsTpl->assign('file_id', $lid);
        $xoopsTpl->assign('lang_subdate' , $is_updated);
        $xoopsTpl->assign('down', $down);
    }
    include_once XOOPS_ROOT_PATH . '/footer.php';
}

?>