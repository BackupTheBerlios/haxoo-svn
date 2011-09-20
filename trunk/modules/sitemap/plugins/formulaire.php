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
 
function b_sitemap_formulaire(){
	
	$db =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();
	
	$result = $db->query("SELECT f.id_form, f.desc_form, f.help FROM " . $db->prefix("form_id") . " f LEFT JOIN " . $db->prefix("form_menu") . " m on f.id_form = m.menuid WHERE m.status =1 ORDER BY m.position");
		
	$ret = array() ;
	while(list($id, $name) = $db->fetchRow($result)){
		$ret["parent"][] = array(
			"id" => $id,
			"title" => $myts->makeTboxData4Show($name),
			"url" => "index.php?id=$id"
		);
	}
	
	return $ret;
}
?>