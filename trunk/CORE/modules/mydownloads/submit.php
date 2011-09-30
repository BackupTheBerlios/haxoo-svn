<?php
// $Id: submit.php,v 1.1.1 2005/07/17 12:52:12 yoshis Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
// ------------------------------------------------------------------------- //
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
include "header.php";
include_once XOOPS_ROOT_PATH."/class/xoopstree.php";
include_once XOOPS_ROOT_PATH."/class/module.errorhandler.php";
include_once XOOPS_ROOT_PATH."/include/xoopscodes.php";
include_once "./fileup.ini.php";
include_once "./class/fileup.class.php";

$myts =& MyTextSanitizer::getInstance(); // MyTextSanitizer object
$eh = new ErrorHandler; //ErrorHandler object
$mytree = new XoopsTree($xoopsDB->prefix("mydownloads_cat"),"cid","pid");

if (empty($xoopsUser) && !$xoopsModuleConfig['anonpost']) {
	redirect_header(XOOPS_URL."/user.php",2,_MD_MUSTREGFIRST);
	exit();
}
if (!empty($_POST['submit'])) {

	$submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;

	// Check if Title exist
	if ($_POST["title"]=="") {
		$eh->show("1001");
	}
	// Check if URL exist
	if (($_POST["url"]) || ($_POST["url"]!="")) {
		$url = $_POST["url"];
	}
	// For uploading file
    if ( array_intersect( $xoopsModuleConfig['uploadgroups'], $xoopsUser->getGroups() ) ){
		$fup = new fileUp(UPLOADS,$xoopsModuleConfig['maxuploadsize'], $xoopsModuleConfig['uploadtypes'],$xoopsModuleConfig['filename_code']);
		$fup->fetchfile();	
		if ( $fup->errmsg != "" ) {
			redirect_header("index.php",5,$fup->errmsg);
		}
		if ( $fup->upfile_url ) $url = $fup->upfile_url;
    } 
	if ($url=="") {
		$eh->show("1016");
	}
	// Check if HomePage exist
	/*if ($_POST["homepage"]=="") {
		$eh->show("1017");
	}*/
	// Check if Description exist
	if ($_POST['message']=="") {
		//$eh->show("1008");
		$_POST['message'] = "No description";
	}

	$notify = !empty($_POST['notify']) ? 1 : 0;

	if ( !empty($_POST['cid']) ) {
		$cid = intval($_POST['cid']);
	} else {
		$cid = 0;
	}
	$url = $myts->makeTboxData4Save(formatURL($url));
	$title = $myts->makeTboxData4Save($_POST["title"]);
	$homepage = $myts->makeTboxData4Save($_POST["homepage"]);
	$version = $myts->makeTboxData4Save($_POST["version"]);
	$size = intval($_POST["size"]);
	if ($fup->upfile_size>0) $size = $fup->upfile_size; // By Bluemooninc.biz
	$platform = $myts->makeTboxData4Save($_POST["platform"]);
	$description = $myts->makeTareaData4Save($_POST["message"]);
	$date = time();
	$newid = $xoopsDB->genId($xoopsDB->prefix("mydownloads_downloads")."_lid_seq");

	if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
		$status = $xoopsModuleConfig['autoapprove'];
	} else {
		$status = 0;
	}
	$sql = sprintf("INSERT INTO %s (lid, cid, title, url, homepage, version, size, platform, logourl, submitter, status, date, hits, rating, votes, comments) VALUES (%u, %u, '%s', '%s', '%s', '%s', %u, '%s', '%s', %u, %u, %u, %u, %u, %u, %u)", 
		$xoopsDB->prefix("mydownloads_downloads"), $newid, $cid, $title, $url, $homepage, $version, $size, $platform, '', $submitter, $status, $date, 0, 0, 0, 0);
	$xoopsDB->query($sql) or $eh->show("0013");
	if($newid == 0){
		$newid = $xoopsDB->getInsertId();
	}
	// Check existing record before insert for "mydownloads_text" table
	$sql = sprintf("SELECT COUNT(*) FROM %s WHERE lid=%d;", $xoopsDB->prefix("mydownloads_text") , $newid);
    list($lid_count) = $xoopsDB->fetchRow( $xoopsDB->query($sql) );
	if ($lid_count==0){
		$sql = sprintf("INSERT INTO %s (lid, description) VALUES (%u, '%s')", $xoopsDB->prefix("mydownloads_text"), $newid, $description);
		$xoopsDB->query($sql) or $eh->show("0013");
	}
	// Notify of new link (anywhere) and new link in category
	$notification_handler =& xoops_gethandler('notification');
	$tags = array();
	$tags['FILE_NAME'] = $title;
	$tags['FILE_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/singlefile.php?cid=' . $cid . '&lid=' . $newid;
	$sql = "SELECT title FROM " . $xoopsDB->prefix("mydownloads_cat") . " WHERE cid=" . $cid;
	$result = $xoopsDB->query($sql);
	$row = $xoopsDB->fetchArray($result);
	$tags['CATEGORY_NAME'] = $row['title'];
	$tags['CATEGORY_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/viewcat.php?cid=' . $cid;
	if ( $xoopsModuleConfig['autoapprove'] == 1 ) {
		$notification_handler->triggerEvent('global', 0, 'new_file', $tags);
		$notification_handler->triggerEvent('category', $cid, 'new_file', $tags);
		redirect_header("index.php",2,_MD_RECEIVED."<br />"._MD_ISAPPROVED."");
	}else{
		$tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/' . $xoopsModule->getVar('dirname') . '/admin/index.php?op=listNewDownloads';
		$notification_handler->triggerEvent('global', 0, 'file_submit', $tags);
		$notification_handler->triggerEvent('category', $cid, 'file_submit', $tags);
		if ($notify) {
			include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
			$notification_handler->subscribe('file', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
		}
		redirect_header("index.php",2,_MD_RECEIVED);
	}
	exit();

} else {

	$xoopsOption['template_main'] = 'mydownloads_submit.html';
	include XOOPS_ROOT_PATH."/header.php";
/* by makinosuke @ 2009.1.9
	ob_start();
	xoopsCodeTarea("message",37,8);
	$xoopsTpl->assign('xoops_codes', ob_get_contents());
	ob_end_clean();
	ob_start();
	xoopsSmilies("message");
	$xoopsTpl->assign('xoops_smilies', ob_get_contents());
	ob_end_clean();*/
	$xoopsTpl->assign('action', 'submit');
	$xoopsTpl->assign('xoops_module_header', '<script src="js/jquery-1.2.6.min.js" type="text/javascript"></script><script src="js/markitup/jquery.markitup.pack.js" type="text/javascript"></script><script src="js/markitup/sets/bbcode/set.js" type="text/javascript"></script><link href="js/markitup/skins/simple/style.css" type="text/css" rel="stylesheet" /><link href="js/markitup/sets/bbcode/style.css" type="text/css" rel="stylesheet" />');
//	$mustmark = "<font color='RED'>*</font>"; // by makinosuke @ 2009.1.9
	$mustmark = "<em class='mydl-submit-mustmark'>*</em>"; // by makinosuke @ 2009.1.9
	$xoopsTpl->assign('notify_show', !empty($xoopsUser) && !$xoopsModuleConfig['autoapprove'] ? 1 : 0);
	$xoopsTpl->assign('lang_submitonce', _MD_SUBMITONCE);
	$xoopsTpl->assign('lang_submitcath', _MD_SUBMITCATHEAD);
	$xoopsTpl->assign('lang_allpending', _MD_ALLPENDING);
	$xoopsTpl->assign('lang_dontabuse', _MD_DONTABUSE);
	$xoopsTpl->assign('lang_wetakeshot', _MD_TAKEDAYS);
	$xoopsTpl->assign('lang_category', _MD_CATEGORYC);
//	$xoopsTpl->assign('lang_sitetitle', _MD_FILETITLE . $mustmark); // by makinosuke @ 2009.1.9
	$xoopsTpl->assign('lang_filetitle', _MD_FILETITLE . $mustmark); // by makinosuke @ 2009.1.9
	$xoopsTpl->assign('lang_category', _MD_CATEGORYC);
	$xoopsTpl->assign('lang_homepage', _MD_HOMEPAGEC);
	$xoopsTpl->assign('lang_version', _MD_VERSIONC);
	$xoopsTpl->assign('lang_size', _MD_FILESIZEC);
	$xoopsTpl->assign('lang_bytes', _MD_BYTES);
	$xoopsTpl->assign('lang_platform', _MD_PLATFORMC);
	// Add Start by bluemooninc.biz
    if ( array_intersect( $xoopsModuleConfig['uploadgroups'], $xoopsUser->getGroups() ) ){
		$xoopsTpl->assign('lang_upload', _MD_UPLOAD . $mustmark);
		$xoopsTpl->assign('maxbyte',$xoopsModuleConfig['maxuploadsize']);
		if ($xoopsModuleConfig['maxuploadsize']>=1000000){
			$maxbyte_str=sprintf("%d M",$xoopsModuleConfig['maxuploadsize']/1000000);
		} elseif ($xoopsModuleConfig['maxuploadsize']>=1000){
			$maxbyte_str=sprintf("%d K",$xoopsModuleConfig['maxuploadsize']/1000);
		} else {
			$maxbyte_str=sprintf("%d ",$xoopsModuleConfig['maxuploadsize']);
		}
		$xoopsTpl->assign('maxbyte_str',$maxbyte_str);
		$xoopsTpl->assign('accept_files',preg_replace("/\|/"," ",$xoopsModuleConfig['uploadtypes']));
		$xoopsTpl->assign('lang_siteurl', _MD_DLURL);
	}else{
		$xoopsTpl->assign('lang_siteurl', _MD_DLURL . $mustmark);
	}
	// Add End
	$xoopsTpl->assign('lang_options', _MD_OPTIONS);
	$xoopsTpl->assign('lang_notify', _MD_NOTIFYAPPROVE);
	$xoopsTpl->assign('lang_description', _MD_DESCRIPTION . $mustmark);
	$xoopsTpl->assign('lang_submit', _SUBMIT);
	$xoopsTpl->assign('lang_cancel', _CANCEL);
	ob_start();
	$mytree->makeMySelBox("title", "title",0,1);
	$selbox = ob_get_contents();
	ob_end_clean();
	$xoopsTpl->assign('category_selbox', $selbox);
	include XOOPS_ROOT_PATH.'/modules/mydownloads/footer.php';

}
?>
