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
 
// $Id: wordpress.php,v 1.1 2005/04/07 09:23:42 gij Exp $
// FILE		::	wordpress.php
// AUTHOR	::	Ryuji AMANO <info@ryus.co.jp>
// WEB		::	Ryu's Planning <http://ryus.co.jp/>
//

function b_sitemap_wordpress(){
	$xoopsDB =& Database::getInstance();

    $block = sitemap_get_categoires_map($xoopsDB->prefix("wp_categories"), "cat_ID", "category_parent", "cat_name", "index.php?cat=", "cat_name");

	return $block;
}


?>