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
 
function b_sitemap_liaise() {
	$xoopsDB = & Database :: getInstance();

	$sitemap = array ();
	$myts = & MyTextSanitizer :: getInstance();

	$table = $xoopsDB->prefix("liaise_forms");

	$i = 0;
	$sql = "SELECT `form_id`,`form_title` FROM `$table` WHERE `form_order`!=0 ORDER BY `form_order`";
	$result = $xoopsDB->query($sql);
	while (list ($id, $title) = $xoopsDB->fetchRow($result)) {
		$sitemap['parent'][$i]['id'] = $id;
		$sitemap['parent'][$i]['title'] = $myts->makeTboxData4Show($title);
		$sitemap['parent'][$i]['url'] = 'index.php?form_id=' . $id;

		$i++;
	}
	return $sitemap;
}
?>