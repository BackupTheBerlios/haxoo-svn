<?php
include("admin_header.php");

$op = 'Choice';

if ( isset($HTTP_POST_VARS['op']) ) {
	$op = trim($HTTP_POST_VARS['op']);
} elseif ( isset($HTTP_GET_VARS['op']) ) {
	$op = trim($HTTP_GET_VARS['op']);
}

function Choice() {
	global $xoopsModule;
    	xoops_cp_header();

    	OpenTable();
    	echo "<a href='".XOOPS_URL."/modules/system/admin.php?fct=preferences&op=showmod&mod=".$xoopsModule->getVar('mid')."'>"._IMP_Edit."</a><br />";
    	CloseTable();
    	xoops_cp_footer();
}


switch($op){
   	default:
		Choice();
      		break;
}
?>