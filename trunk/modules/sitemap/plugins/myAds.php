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
// $Id: myAds.php,v 1.1 2005/04/07 09:23:42 gij Exp $
// FILE		::	myAds.php
// AUTHOR	::	Tom_G3X
// WEB		::http://malaika.s31.xrea.com/

function b_sitemap_myAds(){
	$xoopsDB =& Database::getInstance();
	$block = sitemap_get_categoires_map($xoopsDB->prefix("ann_categories"), "cid", "pid", "title", "index.php?pa=view&amp;cid=", "title");
	return $block;
}


?>