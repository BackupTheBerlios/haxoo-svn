<?php
// start hack by Trabis
if (!class_exists('ProtectorRegistry')) exit('Registry not found');

$registry   =& ProtectorRegistry::getInstance();
$mydirname  = $registry->getEntry('mydirname');
$mydirpath  = $registry->getEntry('mydirpath');
$language   = $registry->getEntry('language');
// end hack by Trabis

$module_handler =& xoops_gethandler('module');
$xoopsModule =& XoopsModule::getByDirname($mydirname);
$moduleInfo =& $module_handler->get($xoopsModule->getVar('mid'));
$pathImageAdmin = $moduleInfo->getInfo('icons32');

$constpref = '_MI_' . strtoupper( $mydirname ) ;

$adminmenu = array(
	array(
		'title' => constant( $constpref.'_ADMINHOME' ) ,
		'link' => 'admin/index.php' ,
		'icon'  => '../../'.$pathImageAdmin.'/home.png', 		
	) ,
	array(
		'title' => constant( $constpref.'_ADMININDEX' ) ,
		'link' => 'admin/center.php?page=center' ,
		'icon'  => '../../'.$pathImageAdmin.'/firewall.png', 		
	) ,
	array(
		'title' => constant( $constpref.'_ADVISORY' ) ,
		'link' => 'admin/center.php?page=advisory' ,
		'icon'  => '../../'.$pathImageAdmin.'/security.png', 
	) ,
	array(
		'title' => constant( $constpref.'_PREFIXMANAGER' ) ,
		'link' => 'admin/center.php?page=prefix_manager' ,
		'icon'  => '../../'.$pathImageAdmin.'/manage.png', 
	) ,
	array(
		'title' => constant( $constpref.'_ADMINABOUT' ) ,
		'link' => 'admin/about.php' ,
		'icon'  => '../../'.$pathImageAdmin.'/about.png', 
	) ,	
) ;

$adminmenu4altsys = array(
	array(
		'title' => constant( $constpref.'_ADMENU_MYBLOCKSADMIN' ) ,
		'link' => 'admin/main.php?mode=admin&lib=altsys&page=myblocksadmin' ,
	) ,
	array(
		'title' => _PREFERENCES ,
		'link' => 'admin/main.php?mode=admin&lib=altsys&page=mypreferences' ,
	) ,
) ;


?>