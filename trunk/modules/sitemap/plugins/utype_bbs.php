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
 
function b_sitemap_utype_bbs(){
	global $xoopsConfig;
	$db =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	include_once(XOOPS_ROOT_PATH.'/modules/utype_bbs/language/'.$xoopsConfig['language'].'/main.php');
	$result = $db->query("
		SELECT cid,top
		FROM ".$db->prefix('u_type_category')."
		ORDER BY cid DESC
		",
		5, 0);

	$ret = array();
	while(list($cid, $top) = $db->fetchRow($result)) {
		$ret['parent'][] = array(
			'id' => $cid,
			'title' => _U_TYPE_CATEGORY.'&raquo;&raquo;'.$myts->makeTboxData4Show($top),
			'url' => 'index.php?mode=viewcat&amp;cat='.$cid
		);
	}
	
	$result = $db->query("
		SELECT nid,a_title,hn
		FROM ".$db->prefix('u_typebbs')." as b
		WHERE sakujyo='f'
		ORDER BY v_day DESC
		",
		5, 0);
	if ($result) {
		while(list($nid, $title, $hn) = $db->fetchRow($result)) {
			$ret['parent'][] = array(
				'id' => $nid,
				'title' => _U_TYPE_NEWPOST.'&raquo;&raquo;'.$myts->makeTboxData4Show($title),
				'url' => 'index.php?mode=single&amp;article='.$nid
			);
		}
	}
	return $ret;
}

?>