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
 

function b_sitemap_xoopsheadline(){

	$db =& Database::getInstance();
	$myts =& MyTextSanitizer::getInstance();

	$result = $db->query( "SELECT headline_id,headline_name FROM ".$db->prefix("xoopsheadline")." WHERE headline_display=1 ORDER BY headline_weight" ) ;

	$ret = array() ;
	while( list( $id , $name ) = $db->fetchRow( $result ) ) {

		$ret["parent"][] = array(
			"id" => $id ,
			"title" => $myts->makeTboxData4Show( $name ) ,
			"url" => "index.php?id=$id"
		) ;

	}

	return $ret ;
}
?>