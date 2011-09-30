<?php
// $Id: visit.php,v 1.1 2004/09/09 05:15:10 onokazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
ini_set("memory_limit","80M");
include "../../mainfile.php";
include_once "./fileup.ini.php";
include_once "./include/browser.php";
include_once "./class/mbfunction.class.php";
include_once "./class/download.class.php";

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : NULL;
$cid = isset($_GET['cid']) ? intval($_GET['cid']) : NULL;
$filename = isset($_GET['filename']) ? rawurldecode(strip_tags($_GET['filename'])) : NULL;
/*
** Check referer
*/
if ( $xoopsModuleConfig['check_host'] ) {
	$goodhost      = 0;
	$referer       = parse_url(xoops_getenv('HTTP_REFERER'));
	$referer_host  = $referer['host'];
	foreach ( $xoopsModuleConfig['referers'] as $ref ) {
		if ( !empty($ref) && preg_match("/".$ref."/i", $referer_host) ) {
			$goodhost = "1";
			break;
		}
	}
	if (!$goodhost) {
		redirect_header(XOOPS_URL . "/modules/mydownloads/singlefile.php?cid=$cid&amp;lid=$lid", 20, _MD_NOPERMISETOLINK);
		exit();
	}
}
if( !$lid && $filename ){
	$url = XOOPS_URL . UPLOADS . "/" . $filename;
	$sql = "SELECT lid FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE url='$url' AND status>0";
	$result = $xoopsDB->query($sql);
	list($lid) = $xoopsDB->fetchRow($result);
}
/*
** Count up
*/
if( $lid ){
	$sql = sprintf("UPDATE %s SET hits = hits+1 WHERE lid = %u AND status > 0", $xoopsDB->prefix("mydownloads_downloads"), $lid);
	$xoopsDB->queryF($sql);
	$result = $xoopsDB->query("SELECT url FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE lid=$lid AND status>0");
	list($url) = $xoopsDB->fetchRow($result);
}
//
// From eMule server
//
if (preg_match("/^ed2k*:\/\//i", $url)) {
	echo "<html><head><meta http-equiv=\"Refresh\" content=\"0; URL=".$myts->oopsHtmlSpecialChars($url)."\"></meta></head><body></body></html>";
}
//
// From outside server
//
$xoopsurl = preg_replace("/\//","\\\/" , XOOPS_URL );
if (!preg_match("/^" . $xoopsurl . "/i", $url)) {
	Header("Location: $url");
}
//
// From my server
//
$mb = new mb_func();
$down = new download($url);
$filename = $down->fnameOnServer();
$ctype = $down->contentType() ;
$fpathname = XOOPS_ROOT_PATH . "/uploads/mydownloads/" . $down->fnameOnServer();
$fpathname = $mb->internal2x( $fpathname, $xoopsModuleConfig['filename_code'] );
if(!file_exists($fpathname)){
	redirect_header("brokenfile.php?lid=$lid",3,sprintf(_MD_FILEPATHNOTEXIST,$filename) );
	exit();
}
ob_clean();
$browser = $version =0;
UsrBrowserAgent($browser,$version);
@ignore_user_abort();
@set_time_limit(0);
if ($browser == 'IE' && (ini_get('zlib.output_compression')) ) {
    ini_set('zlib.output_compression', 'Off');
}
header("Content-Transfer-Encoding: binary");
header("Content-Length: " . filesize($fpathname) );
header("Content-type: " . $ctype);
header('Expires: ' . gmdate('D, d M Y H:i:s') . ' GMT');
header('Last-Modified: ' . date("D M j G:i:s T Y"));
header("Content-Disposition: attachment; " . $mb->cnv_mbstr4browser($filename,$browser));
if ($browser == 'IE') {
    header('Pragma: public');
	header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
} else {
    header('Pragma: no-cache');
}

$fp=fopen($fpathname,'r');
while(!feof($fp)) {
	$buffer = fread($fp, 1024*6); //speed-limit 64kb/s
	print $buffer;
	flush();
	ob_flush();
	usleep(10000); 
}
fclose($fp);

exit();
?>
