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

include "header.php";
include_once('./../../../include/cp_header.php');
define( '_MYMENU_CONSTANT_IN_MODINFO' , '_MI_SITEMAP_NAME' ) ;

// branch for altsys
if( defined( 'XOOPS_TRUST_PATH' ) && ! empty( $_GET['lib'] ) ) {
	$mydirname = basename( dirname( dirname( __FILE__ ) ) ) ;
	$mydirpath = dirname( dirname( __FILE__ ) ) ;

	// common libs (eg. altsys)
	$lib = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , $_GET['lib'] ) ;
	$page = preg_replace( '/[^a-zA-Z0-9_-]/' , '' , @$_GET['page'] ) ;
	
	if( file_exists( XOOPS_TRUST_PATH.'/libs/'.$lib.'/'.$page.'.php' ) ) {
		include XOOPS_TRUST_PATH.'/libs/'.$lib.'/'.$page.'.php' ;
	} else if( file_exists( XOOPS_TRUST_PATH.'/libs/'.$lib.'/index.php' ) ) {
		include XOOPS_TRUST_PATH.'/libs/'.$lib.'/index.php' ;
	} else {
		die( 'wrong request' ) ;
	}
	exit ;
}

xoops_cp_header();

/*global $xoopsModule;

//costruisco il menu
if ( !is_readable(XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php'))	{
podcast_adminmenu(0, _MI_SITEMAP_ADMENU_TOP);
} else {
include_once XOOPS_ROOT_PATH.'/Frameworks/art/functions.admin.php';
loadModuleAdminMenu (0, _MI_SITEMAP_ADMENU_TOP);
}

echo "<div><h1>"._MI_SITEMAP_NAME." "._MI_SITEMAP_VERSION."</h1>";
echo "<h2>"._MI_SITEMAP_ABOUT."</h2>";
echo "<p>"._MI_SITEMAP_ABOUTDSC."</p>";

echo "<h2>"._MI_SITEMAP_CREDITS."</h2>";
echo "<p>"._MI_SITEMAP_CREDITSDSC."</p>";
echo "<p>"._MI_SITEMAP_CONTACT."</p>"; */

loadModuleAdminMenu(0);

$form = "
    <style type=\"text/css\">
    label,text {
        display: block;
        float: left;
        margin-bottom: 2px;
    }
    label {
        text-align: right;
        width: 150px;
        padding-right: 20px;
    }
    br {
        clear: left;
    }
    </style>
";

$form .= "<fieldset><legend style='font-weight: bold; color: #900;'>" . _SITEMAP_AM_CONFIGSERVER . "</legend>";
$form .= "<div style='padding: 8px;'>";
$form .= "<label>" . "<strong>PHP Version:</strong>" . ":</label><text>" . phpversion() . "</text><br />";
$form .= "<label>" . "<strong>MySQL Version:</strong>" . ":</label><text>" . mysql_get_server_info() . "</text><br />";
$form .= "<label>" . "<strong>XOOPS Version:</strong>" . ":</label><text>" . XOOPS_VERSION . "</text><br />";
$form .= "<label>" . "<strong>Module Version:</strong>" . ":</label><text>" . $xoopsModule->getInfo('version') . "</text><br />";
$form .= "</div>";
$form .= "<div style='padding: 8px;'>";
$form .= "<label>" . _AM_SITEMAP_SAFEMODE . ":</label><text>";
$form .= ( ini_get( 'safe_mode' ) ) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_REGISTERGLOBALS . ":</label><text>";
$form .= ( ini_get( 'register_globals' )) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_MAGICQUOTESGPC . ":</label><text>";
$form .= ( ini_get( 'magic_quotes_gpc' )) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_MAXPOSTSIZE .":</label><text>". ini_get( 'post_max_size' );
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_MAXINPUTTIME .":</label><text>". ini_get( 'max_input_time' );
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_OUTPUTBUFFERING .":</label><text>". ini_get( 'output_buffering' );
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_XML_EXTENSION .":</label><text>";
$form .= ( extension_loaded( 'xml' )) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_MB_EXTENSION .":</label><text>";
$form .= ( extension_loaded( 'mbstring' )) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_CURL .":</label><text>";
$form .= ( function_exists('curl_init')) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_FSOCKOPEN .":</label><text>";
$form .= ( function_exists('fsockopen')) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "<label>" . _AM_SITEMAP_URLFOPEN .":</label><text>";
$form .= ( ini_get('allow_url_fopen')) ? _AM_SITEMAP_ON : _AM_SITEMAP_OFF;
$form .= "</text><br />";
$form .= "</div>";

echo $form;


xoops_cp_footer();

?>