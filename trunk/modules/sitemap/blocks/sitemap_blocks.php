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

function b_sitemap_show( $options )
{
	global $xoopsConfig, $xoopsDB, $xoopsUser, $xoopsUserIsAdmin;
	global $sitemap_configs ;

	$cols = empty( $options[0] ) ? 1 : intval( $options[0] ) ;

	$module_handler =& xoops_gethandler('module');
	$module =& $module_handler->getByDirname('sitemap');
	$config_handler =& xoops_gethandler('config');
	$sitemap_configs = $config_handler->getConfigsByCat(0, $module->getVar('mid'));

	$block = array();

	include_once(XOOPS_ROOT_PATH . '/modules/sitemap/include/sitemap.php');

	// for All-time guest mode (backup uid & set as Guest)
	if( is_object( $xoopsUser ) && ! empty( $sitemap_configs['alltime_guest'] ) ) {
		$backup_uid = $xoopsUser->getVar('uid') ;
		$backup_userisadmin = $xoopsUserIsAdmin ;
		$member_handler =& xoops_gethandler('member');
		$xoopsUser =& $member_handler->getUser( 0 ) ;
		$xoopsUserIsAdmin = false ;
	}

	$sitemap = sitemap_show();

	// for All-time guest mode (restore $xoopsUser*)
	if( ! empty( $backup_uid ) ) {
		$xoopsUser =& $member_handler->getUser( $backup_uid ) ;
		$xoopsUserIsAdmin = $backup_userisadmin ;
	}

	$myts =& MyTextSanitizer::getInstance();

	$block['this']['mods'] = 'sitemap';
	$block['cols'] = $cols ;
	$block['div_width'] = 90.0 / $cols ;
	$block['sitemap'] = $sitemap;
	$block['msgs'] = $myts->displayTarea( $sitemap_configs['msgs'] , 1 ) ;
	$block['show_subcategoris'] = $sitemap_configs['show_subcategoris'];

	if( $sitemap_configs['alltime_guest'] ) {
		$block['isuser'] = 0 ;
		$block['isadmin'] = 0 ;
	} else {
		$block['isuser'] = is_object( $xoopsUser ) ;
		$block['isadmin'] = $xoopsUserIsAdmin ;
	}

	$sitemap_configs = @$sitemap_configsBackup ;

	return $block;
}




function b_sitemap_edit( $options )
{
	return '
		'._MB_SITEMAP_COLS.': <input type="text" size="2" maxlength="2" name="options[0]" value="'.intval($options[0]).'" />
	' ;
}


?>