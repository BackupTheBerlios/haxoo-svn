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
 
function b_sitemap_weblog()
{
	$xoopsDB =& Database::getInstance();
	$block = sitemap_get_categoires_map($xoopsDB->prefix("weblog_category"), "cat_id", "cat_pid", "cat_title", "index.php?cat_id=", "cat_title");
	return $block;
}
?>