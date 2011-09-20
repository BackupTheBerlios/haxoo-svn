<?php
/**
 * $Id: admin_header.php,v 1.4 2006/04/24 17:15:11 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include '../../../include/cp_header.php';
include_once(XOOPS_ROOT_PATH . '/modules/wfdownloads/include/functions.php');
include_once(XOOPS_ROOT_PATH . '/modules/wfdownloads/include/common.php');

include_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

$myts = &MyTextSanitizer::getInstance();

$imagearray = array(
	'editimg' => "<img src='../images/icon/edit.gif' alt='" . _AM_WFD_ICO_EDIT . "' align='middle'>",
    'deleteimg' => "<img src='../images/icon/delete.gif' alt='" . _AM_WFD_ICO_DELETE . "' align='middle'>",
    'online' => "<img src='../images/icon/on.gif' alt='" . _AM_WFD_ICO_ONLINE . "' align='middle'>",
    'offline' => "<img src='../images/icon/off.gif' alt='" . _AM_WFD_ICO_OFFLINE . "' align='middle'>",
    'approved' => "<img src='../images/icon/on.gif' alt=''" . _AM_WFD_ICO_APPROVED . "' align='middle'>",
    'notapproved' => "<img src='../images/icon/off.gif' alt='" . _AM_WFD_ICO_NOTAPPROVED . "' align='middle'>",
    'relatedfaq' => "<img src='../images/icon/link.gif' alt='" . _AM_WFD_ICO_LINK . "' align='absmiddle'>",
    'relatedurl' => "<img src='../images/icon/urllink.gif' alt='" . _AM_WFD_ICO_URL . "' align='middle'>",
    'addfaq' => "<img src='../images/icon/add.gif' alt='" . _AM_WFD_ICO_ADD . "' align='middle'>",
    'approve' => "<img src='../images/icon/approve.gif' alt='" . _AM_WFD_ICO_APPROVE . "' align='middle'>",
    'statsimg' => "<img src='../images/icon/stats.gif' alt='" . _AM_WFD_ICO_STATS . "' align='middle'>",
	'ignore' => "<img src='../images/icon/ignore.gif' alt='" . _AM_WFD_ICO_IGNORE . "' align='middle'>",
    'ack_yes' => "<img src='../images/icon/on.gif' alt='" . _AM_WFD_ICO_ACK . "' align='middle'>",
	'ack_no' => "<img src='../images/icon/off.gif' alt='" . _AM_WFD_ICO_REPORT . "' align='middle'>",
    'con_yes' => "<img src='../images/icon/on.gif' alt='" . _AM_WFD_ICO_CONFIRM . "' align='middle'>",
	'con_no' => "<img src='../images/icon/off.gif' alt='" . _AM_WFD_ICO_CONBROKEN . "' align='middle'>"
	);

?>