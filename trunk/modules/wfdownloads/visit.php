<?php
/**
 * $Id: visit.php,v 1.8 2006/04/24 02:23:33 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';

global $xoopsUser, $xoopsModuleConfig, $myts;

if (is_object($xoopsUser)) {
    if ($xoopsUser->getVar('posts') < $xoopsModuleConfig['download_minposts'] && !$xoopsUser->isAdmin()) {
        redirect_header(XOOPS_URL.'/modules/wfdownloads/index.php',5,_MD_WFD_DOWNLOADMINPOSTS);
        exit();
    }
}
elseif (!is_object($xoopsUser) && ($xoopsModuleConfig['download_minposts'] > 0)) {
    redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFD_MUSTREGFIRST);
    exit();
}

$agreed = (isset($_GET['agree'])) ? intval($_GET['agree']) : 0;

$lid = intval($_GET['lid']);
$download_handler = xoops_getmodulehandler('download');
$download = $download_handler->get($lid);
$cid = $download->getVar('cid');

if ($download->isNew()) {
    redirect_header("index.php", 1, _MD_WFD_NODOWNLOAD);
    exit();
}
if ($download->getVar('published') == 0 || $download->getVar('published') > time() || $download->getVar('offline') == 1 || ($download->getVar('expired') != 0 && $download->getVar('expired') < time()) || $download->getVar('status') == 0) {
    //Download not published, expired or taken offline - redirect
    redirect_header("index.php", 3, _MD_WFD_NODOWNLOAD);
}

$gperm_handler =& xoops_gethandler('groupperm');
$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;

if (!$gperm_handler->checkRight("WFDownCatPerm", $cid,$groups, $xoopsModule->getVar('mid'))) {
    redirect_header(XOOPS_URL.'/modules/wfdownloads/index.php',3, _NOPERM);
    exit();
}

function reportBroken($lid)
{
    echo "
		<h4>" . _MD_WFD_BROKENFILE . "</h4>\n
		<div>" . _MD_WFD_PLEASEREPORT . "\n
		<a href='" . XOOPS_URL . "/modules/wfdownloads/brokenfile.php?lid=$lid'>" . _MD_WFD_CLICKHERE . "</a>\n
		</div>\n";
}

if ($agreed == 0)
{
    if ($xoopsModuleConfig['check_host'])
    {
        $goodhost = 0;
        $referer = parse_url(xoops_getenv('HTTP_REFERER'));
        $referer_host = $referer['host'];
        foreach ($xoopsModuleConfig['referers'] as $ref)
        {
            if (!empty($ref) && preg_match("/" . $ref . "/i", $referer_host))
            {
                $goodhost = "1";
                break;
            }
        }
        if (!$goodhost)
        {
            redirect_header(XOOPS_URL . "/modules/wfdownloads/singlefile.php?cid=$cid&amp;lid=$lid", 20, _MD_WFD_NOPERMISETOLINK);
            exit();
        }
    }
}

if ($xoopsModuleConfig['showDowndisclaimer'] && $agreed == 0)
{
    include XOOPS_ROOT_PATH . '/header.php';
    echo "
		<div align='center'>" . wfd_imageheader() . "</div>\n
		<h4>" . _MD_WFD_DISCLAIMERAGREEMENT . "</h4>\n
		<div>" . $myts -> displayTarea($xoopsModuleConfig['downdisclaimer'], 1, 1, 1, 1, 1) . "</div><br />\n
		<form action='visit.php' method='post'>\n
		<div align='center'><b>" . _MD_WFD_DOYOUAGREE . "</b><br /><br />\n
		<input type='button' onclick='location=\"visit.php?agree=1&amp;lid=$lid&amp;cid=$cid\"' class='formButton' value='" . _MD_WFD_AGREE . "' alt='" . _MD_WFD_AGREE . "' />\n
		&nbsp;\n
		<input type='button' onclick='location=\"index.php\"' class='formButton' value='" . _CANCEL . "' alt='" . _CANCEL . "' />\n
		<input type='hidden' name='lid' value='1' />\n
		<input type='hidden' name='cid' value='1' />\n
		</div></form>\n<br /><br />";
    include XOOPS_ROOT_PATH . '/footer.php';
    exit();
}
else
{
    $isadmin = (!empty($xoopsUser) && $xoopsUser -> isAdmin($xoopsModule -> mid())) ? true : false;
    if ($isadmin == false)
    {
        $download_handler->incrementHits($lid);
    }

    $full_name = trim($download->getVar('filename'));
    if ((!$download->getVar('url') == "" && !$download->getVar('url') == 'http://') || $full_name == '')
    {
        include XOOPS_ROOT_PATH . '/header.php';
        echo "<div align='center'>" . wfd_imageheader() . "</div>";
        $url = $myts -> htmlSpecialChars(preg_replace('/javascript:/si' , 'javascript:', $download->getVar('url')), ENT_QUOTES);

        echo "
  <h4><img src='" . XOOPS_URL . "/modules/wfdownloads/images/icon/downloads.gif' align='middle' alt='' /> " . _MD_WFD_DOWNINPROGRESS . "</h4>\n
  <div>" . _MD_WFD_DOWNSTARTINSEC . "</div><br />\n
  <div>" . _MD_WFD_DOWNNOTSTART . "\n
  <a href='$url' target='_blank'>" . _MD_WFD_CLICKHERE . "</a>.\n
  </div>\n";

        header("Cache-Control: no-store, no-cache, must-revalidate");
        header("Cache-Control: post-check=0, pre-check=0", false);
        // HTTP/1.0
        header("Pragma: no-cache");
        // Date in the past
        header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
        // always modified
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Refresh: 3; url=$url");
    }
    elseif (!empty($full_name))
    {
        $mimeType = $download->getVar('filetype');

        $file = strrev($full_name);
        $temp_name = strtolower(strrev(substr($file,0,strpos($file,"--"))) );
		if ($temp_name == '') {
			$file_name = $full_name;
		} else {
			$file_name = $temp_name;
		}
        $filePath = $xoopsModuleConfig['uploaddir'].'/'.stripslashes(trim($full_name));

        if(ini_get('zlib.output_compression')) {
            ini_set('zlib.output_compression', 'Off');
        }

        header("Pragma: public");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-Control: private",false);
        header("Content-Transfer-Encoding: binary");
        if(isset($mimeType))
        {
            header("Content-Type: " . $mimeType);
        }

        header("Content-Disposition: attachment; filename=" . $file_name);

        if(isset($mimeType) && strstr($mimeType, "text/"))
        {
            $fp = fopen($filePath, "r");
        }
        else
        {
            $fp = fopen($filePath, "rb");
        }
        fpassthru($fp);
        exit();
    }
    else
    {
        include XOOPS_ROOT_PATH . '/header.php';
        echo "<br /><div align='center'>" . wfd_imageheader() . "</div>";
        reportBroken($lid);
    }

    include XOOPS_ROOT_PATH . '/footer.php';
}

?>