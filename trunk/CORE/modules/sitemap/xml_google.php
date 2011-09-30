<?php
//
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
//  Original Author: chanoir                        					     //
//  Original Author Website: http://www.petitoops.net       		         //
//  ------------------------------------------------------------------------ //
//  ***                                                                      //

if( ! defined( 'SITEMAP_ROOT_CONTROLLER_LOADED' ) ) {
	if( ! file_exists( dirname(__FILE__).'/modules/sitemap/xml_google.php' ) ) {
		die( "Don't call this file directly" ) ;
	}
	if( ! empty( $_SERVER['REQUEST_URI'] ) ) {
		$_SERVER['REQUEST_URI'] = str_replace( 'xml_google.php' , 'modules/sitemap/xml_google.php' , $_SERVER['REQUEST_URI'] ) ;
	} else {
		$_SERVER['REQUEST_URI'] = '/modules/sitemap/xml_google.php' ;
	}
	$_SERVER['PHP_SELF'] = $_SERVER['REQUEST_URI'] ;
	define( 'SITEMAP_ROOT_CONTROLLER_LOADED' , 1 ) ;
	$real_xml_google_path = dirname(__FILE__).'/modules/sitemap/xml_google.php' ;
	chdir( './modules/sitemap/' ) ;
	require $real_xml_google_path ;
	exit ;
} else {
	require '../../mainfile.php' ;
}

$sitemap_configs = @$xoopsModuleConfig ;
$sitemap_configs['alltime_guest'] = true ;

require_once XOOPS_ROOT_PATH.'/class/template.php' ;

$myts =& MyTextSanitizer::getInstance() ;

$sitemap_configs['with_lastmod'] = true ;


if( function_exists( 'mb_http_output' ) ) {
	mb_http_output('pass');
}
header( 'Content-Type:text/xml; charset=utf-8' ) ;

include_once XOOPS_ROOT_PATH.'/modules/sitemap/include/sitemap.php' ;

$xoopsTpl =& new XoopsTpl() ;

// for All-time guest mode (backup uid & set as Guest)
//if( is_object( $xoopsUser ) && ! empty( $sitemap_configs['alltime_guest'] ) ) {
//	$backup_uid = $xoopsUser->getVar('uid') ;
//	$xoopsUser = '' ;
//	$xoopsUserIsAdmin = false ;
//	$xoopsTpl->assign(array('xoops_isuser' => false, 'xoops_userid' => 0, 'xoops_uname' => '', 'xoops_isadmin' => false));
//}

$sitemap = sitemap_show();

// for All-time guest mode (restore $xoopsUser*)
//if( ! empty( $backup_uid ) && ! empty( $sitemap_configs['alltime_guest'] ) ) {
//	$member_handler =& xoops_gethandler('member');
//	$xoopsUser =& $member_handler->getUser( $backup_uid ) ;
//	$xoopsUserIsAdmin = $xoopsUser->isAdmin();
//}

$xoopsTpl->assign('lastmod', gmdate( 'Y-m-d\TH:i:s\Z' ) ); // TODO
$xoopsTpl->assign('sitemap', $sitemap);
//$xoopsTpl->assign('msgs', $myts->displayTarea($msgs,1));
$xoopsTpl->assign('show_subcategoris', $sitemap_configs["show_subcategoris"]);

$xoopsTpl->assign('this', array(
	'mods' => $xoopsModule->getVar('dirname'),
	'name' => $xoopsModule->getVar('name')
));

if( is_object( @$xoopsLogger ) ) $xoopsLogger->activated = false ;
$xoopsTpl->display( 'db:sitemap_xml_google.html' ) ;


?>