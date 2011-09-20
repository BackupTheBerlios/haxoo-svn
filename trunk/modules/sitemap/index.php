<?php
/**
 * ****************************************************************************
 *  - SITEMAP module by chanoir, GIJOE, Francesco Mulassano
 *  - Licence GPL Copyright (c)
 *
 * 
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @license     GPL license
 * @author		chanoir, GIJOE, Francesco Mulassano 
 *
 * ****************************************************************************
 */

require '../../mainfile.php' ;


$xoopsOption['template_main'] = 'sitemap_index.html' ;


include XOOPS_ROOT_PATH.'/header.php' ;

$sitemap_configs = $xoopsModuleConfig ;
include_once XOOPS_ROOT_PATH.'/modules/sitemap/include/sitemap.php' ;


// for All-time guest mode (backup uid & set as Guest)
if( is_object( $xoopsUser ) && ! empty( $sitemap_configs['alltime_guest'] ) ) {
	$backup_uid = $xoopsUser->getVar('uid') ;
	$backup_userisadmin = $xoopsUserIsAdmin ;
	$xoopsUser = '' ;
	$xoopsUserIsAdmin = false ;
}

$sitemap = sitemap_show();

// for All-time guest mode (restore $xoopsUser*)
if( ! empty( $backup_uid ) && ! empty( $sitemap_configs['alltime_guest'] ) ) {
	$member_handler =& xoops_gethandler('member');
	$xoopsUser =& $member_handler->getUser( $backup_uid ) ;
	$xoopsUserIsAdmin = $backup_userisadmin ;
}

$myts =& MyTextSanitizer::getInstance();

$xoopsTpl->assign('sitemap', $sitemap);
$xoopsTpl->assign('msgs', $myts->displayTarea( $sitemap_configs['msgs'] , 1 ) ) ;
$xoopsTpl->assign('show_subcategoris', $sitemap_configs["show_subcategoris"]);

if( $sitemap_configs['alltime_guest'] ) {
	$xoopsTpl->assign( 'isuser' , 0 ) ;
	$xoopsTpl->assign( 'isadmin' , 0 ) ;
} else {
	$xoopsTpl->assign( 'isuser' , is_object( $xoopsUser ) ) ;
	$xoopsTpl->assign( 'isadmin' , $xoopsUserIsAdmin ) ;
}

$xoopsTpl->assign('this', array(
	'mods' => $xoopsModule->getVar('dirname'),
	'name' => $xoopsModule->getVar('name')
));

$xoopsTpl->assign( 'num_col' , $sitemap_configs['columns_number'] ) ;
$xoopsTpl->assign( 'visualization_type' , $sitemap_configs['visualization_type'] ) ;
$xoopsTpl->assign( 'show_sublink' , $sitemap_configs['show_sublink'] ) ;


include XOOPS_ROOT_PATH . '/footer.php';
?>