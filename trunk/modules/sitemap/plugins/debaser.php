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
 
// Desc     ::  Sitemap Plugin for debaser 0.92
// AUTHOR	::	Proshack <webmaster@proshack.net>
// WEB		::	http://www.proshack.net

function b_sitemap_debaser(){
	$xoopsDB =& Database::getInstance();
	
    $block = sitemap_get_categoires_map($xoopsDB->prefix("debaser_genre"), "genreid", "subgenreid", "genretitle", "genre.php?genreid=", "genretitle");

	return $block;
}

?>