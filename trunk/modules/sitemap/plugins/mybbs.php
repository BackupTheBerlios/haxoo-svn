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
 
// FILE		::	mybbs.php
// AUTHOR	::	suin <tms@s10.xrea.com>
// WEB		::	AmethystBlue <http://www.suin.jp/>
// DATE		::	2005-02-15
function b_sitemap_mybbs(){
	$db =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	$sitemap = array();
	$i = 0;
	$url = "index.php?bbs_id=";
	
	$sql = 'SELECT bbs_id, bbs_name FROM '.$db->prefix('mybbs_master').' WHERE status=1 ORDER BY sort_order';
	$result = $db->query($sql);
	while ( list($catid, $name) = $db->fetchRow($result) ) {
		$sitemap['parent'][$i]['id'] = $catid;
		$sitemap['parent'][$i]['title'] = $myts->makeTboxData4Show($name);
		$sitemap['parent'][$i]['url'] = $url.$catid;
		$i++;
	}
	return $sitemap;
}
?>