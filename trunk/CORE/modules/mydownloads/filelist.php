<?php
// $Id: filelist.php,v 1.22 2009/01/21 14:25:03 yoshis Exp $
//  ------------------------------------------------------------------------ //
//                             mydownloads_fileup                            //
//                   Copyright (c) 2005 - 2009 Bluemoon inc.                 //
//                       <http://www.bluemooninc.biz/>                       //
//              Original source by : mydownloads V1.1 Onokazu                //
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
include("../../mainfile.php");
include(XOOPS_ROOT_PATH."/header.php");
include_once XOOPS_ROOT_PATH."/class/xoopslists.php";
include_once "./include/functions.php";
include_once "./class/fileup.class.php";
include_once "./class/download.class.php";
include_once "./fileup.ini.php";

// For GET
$start = isset($_GET['start']) ? intval($_GET['start']) : 0;
$lid = isset($_GET['lid']) ? intval($_GET['lid']) : NULL;
$sortname  = isset($_GET['sortname'])  ? urlencode(strip_tags($_GET['sortname'])) : "date";
$sortorder = isset($_GET['sortorder']) ? intval($_GET['sortorder']) : SORT_DESC;
$filename  = isset($_GET['filename']) ? rawurldecode(strip_tags($_GET['filename'])) : NULL;
$command   = isset($_GET['command'])  ? urlencode(strip_tags($_GET['command'])) : NULL;
// For POST
$lid = isset($_POST['lid']) ? intval($_POST['lid']) : $lid;
$sortname  = isset($_POST['sortname'])  ? urlencode(strip_tags($_POST['sortname'])) : $sortname;
$sortorder = isset($_POST['sortorder']) ? intval($_POST['sortorder']) : $sortorder;
$filename  = isset($_POST['filename']) ? rawurldecode(strip_tags($_POST['filename'])) : $filename;
$command   = isset($_POST['command'])  ? urlencode(strip_tags($_POST['command'])) : $command;

$dir_src = XOOPS_ROOT_PATH . UPLOADS;

fileUp::chk_uploadfolder($dir_src);

//
// For delete controll
//
if($xoopsUser){
	if( $xoopsUser->isAdmin($xoopsModule->mid()) ) {
		$xoopsTpl->assign('isadmin', true ) ;
		$delok = 1;
	}elseif ($lid && ( $command=="deleteok" || $command=="delete")){
		$sql = "SELECT `submitter` FROM ".$xoopsDB->prefix("mydownloads_downloads")." WHERE lid=$lid";
		$result = $xoopsDB->query($sql);
		list($submitter) = $xoopsDB->fetchRow($result);
		$delok = $xoopsUser->uid() == $submitter ?  1 : 0;
	}
}else{
	$delok = 0;
}
if ($delok){
	if ( $command=="delete" ){
		$xoopsTpl->assign('lid', $lid ) ;
		$xoopsTpl->assign('filename', $filename ) ;
		$xoopsTpl->assign('deleteok', sprintf(_MD_DELETEFILE,$filename) ) ;
	}
	if ( $command=="deleteok" ){
		$fullpath = XOOPS_ROOT_PATH . UPLOADS . "/". $filename;
		if (extension_loaded('mbstring')){
            $fullpath = mb_convert_encoding($fullpath, $xoopsModuleConfig['filename_code'], mb_internal_encoding() );
        }
		if (is_file($fullpath)) unlink( $fullpath ) ;
	}
}
$file_array = XoopsLists::getFileListAsArray( $dir_src );
$files = 0;
$per_page = $xoopsModuleConfig['perpage'];
$flist = array();
foreach ( $file_array as $file ){
	$d = new download($file);
	if (!preg_match("/^\./", $d->fnameToDwonload)){
		$info = array();
		$info['name'] = $d->fnameToDwonload;
		$info['size'] = filesize( $dir_src . "/" . $file );
		$info['type'] = $d->contentType;
		$info['date'] = date ("Y/m/d H:i", filemtime( $dir_src . "/" . $file));
		$flist[]=$info;
		$files++;
	}
}
if ($flist){
	// Sorting
	$fileOrder = array();
	foreach($flist as $key => $row){
	    $fileOrder[$key] = $row[$sortname];
	}
	array_multisort($fileOrder,$sortorder,$flist);
}
if ($sortorder == SORT_ASC )
	$altsortorder = SORT_DESC;
else
	$altsortorder = SORT_ASC;
// Page controll
$fdisp = array();
$dispC = 0;
foreach ( $flist as $info ){
	if ($dispC>=$start && $dispC<=$start+$per_page){
		// Get lid
		$dwinfo = array();
		$url = XOOPS_URL . UPLOADS . "/" . $info['name'];
		$sql = 'SELECT cid,lid,`submitter` FROM '.$xoopsDB->prefix("mydownloads_downloads").' WHERE url LIKE "'.$url.'" AND status>0;';
		if ($result = $xoopsDB->query($sql)){
			list($cid,$lid,$submitter) = $xoopsDB->fetchRow($result);
			if ($lid>0){
				$dwinfo['singlefile'] = './singlefile.php?cid=' . $cid . '&amp;lid=' . $lid;
				$dwinfo['lid'] = $lid;
			}
			if ($xoopsUser) $dwinfo['delok'] = $xoopsUser->uid()==$submitter ?  1 : 0;
		}
		$fdisp[]=array_merge($info, $dwinfo);
	}
	$dispC++;
}
if ( $files > $per_page ) {
	include XOOPS_ROOT_PATH.'/class/pagenav.php';
	$nav = new XoopsPageNav($files, $per_page, $start, "start","sortname=".$sortname."&sortorder=".$sortorder."&altsortorder=".$altsortorder );
	$xoopsTpl->assign('page_nav', $nav->renderNav());
} else {
	$xoopsTpl->assign('page_nav', '');
}

$xoopsTpl->assign('sortname', $sortname ) ;
$xoopsTpl->assign('sortorder', $sortorder ) ;
$xoopsTpl->assign('altsortorder', $altsortorder ) ;
$xoopsTpl->assign('flist', $fdisp ) ;
$xoopsOption['template_main'] = 'mydownloads_filelist.html';
include XOOPS_ROOT_PATH.'/modules/mydownloads/footer.php';
?>
