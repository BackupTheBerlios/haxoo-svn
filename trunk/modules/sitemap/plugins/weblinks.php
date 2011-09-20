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
 
// $Id: weblinks.php,v 1.1 2005/04/07 09:23:42 gij Exp $
// FILE		::	weblinks.php
// AUTHOR	::	Ryuji AMANO <info@ryus.co.jp>
// WEB		::	Ryu's Planning <http://ryus.co.jp/>
//

function b_sitemap_weblinks(){
	$xoopsDB =& Database::getInstance();

	// news
//     $maptree = new SitemapTree($xoopsDB->prefix("topics"), "topic_id", "topic_pid");
//     $block = $maptree->getCategoriesMap("topic_title", "topic_title");
    $block = sitemap_get_categoires_map($xoopsDB->prefix("weblinks_category"), "cid", "pid", "title", "viewcat.php?cid=", "title");
    //$block["path"] = "viewcat.php?cid=";

	return $block;
}


?>