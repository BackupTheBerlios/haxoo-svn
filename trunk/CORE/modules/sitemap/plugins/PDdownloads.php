<?php
// $Id: mydownloads.php,v 1.1 2005/04/07 09:23:42 gij Exp $
// FILE		::	weblinks.php
// AUTHOR	::	Ryuji AMANO <info@ryus.biz>
// WEB		::	Ryu's Planning <http://ryus.biz/>
//

function b_sitemap_PDdownloads(){
    $xoopsDB =& Database::getInstance();

    $block = sitemap_get_categoires_map($xoopsDB->prefix("PDdownloads_cat"), "cid", "pid", "title", "viewcat.php?cid=", "title");

	return $block;
}


?>