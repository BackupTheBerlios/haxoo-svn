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
 
// FILE		::	myalbum.php
// AUTHOR	::	suin <tms@s10.xrea.com>
// WEB		::	AmethystBlue <http://www.suin.jp/>
// DATE		::	2005-02-15
function b_sitemap_myalbum(){
	$db =& Database::getInstance();
	$block = sitemap_get_categoires_map($db->prefix("myalbum_cat"), "cid", "pid", "title", "viewcat.php?cid=", "title");
	return $block;
}
?>