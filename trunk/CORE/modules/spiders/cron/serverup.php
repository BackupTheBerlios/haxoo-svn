<?php
/**
 * @package     Spider
 * @subpackage  module
 * @description	Sector Network Security Drone for Robots
 * @author	    Simon Roberts WISHCRAFT <simon@chronolabs.coop>
 * @copyright	copyright (c) 2010-2013 XOOPS.org
 * @licence		GPL 2.0 - see docs/LICENCE.txt
 * @cron		Run at Least Once an Hour to five minutes!
 */

	define("SERVER1", 'http://xortify.chronolabs.coop/unban/');
	define("SERVER2", 'http://xortify.com/unban/');
	define("REPLACE", 'unban');
	define("SOAP", 'soap');
	define("CURL", 'curl');
	define("JSON", 'json');

	include('../../../mainfile.php');
	include ('../class/auth/authfactory.php');
	
	$module_handler =& xoops_gethandler('module');
	$config_handler =& xoops_gethandler('config');
	$xoMod = $module_handler->getByDirname('spiders');
	$GLOBALS['xoopsModuleConfig'] = $config_handler->getConfigList($xoMod->getVar('mid'));
	if ($GLOBALS['xoopsModuleConfig']['xortify_shareme']==true){
		
		$source = file_get_contents(SERVER1);
		if (strlen($source)>0) {		
			$xoMod->setVar('isactive', true);
			
			$criteria = new CriteriaCompo(new Criteria('conf_modid', $xoMod->getVar('mid')));
			$criteria->add(new Criteria('conf_name', 'xortify_urisoap'));
			$xoConfig = $config_handler->getConfigs($criteria, false);
			$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SOAP, SERVER1));
			$config_handler->insertConfig($xoConfig[0]);
			
			$criteria = new CriteriaCompo(new Criteria('conf_modid', $xoMod->getVar('mid')));
			$criteria->add(new Criteria('conf_name', 'xortify_uricurl'));
			$xoConfig = $config_handler->getConfigs($criteria, false);
			$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, CURL, SERVER1));
			$config_handler->insertConfig($xoConfig[0]);

			$criteria = new CriteriaCompo(new Criteria('conf_modid', $xoMod->getVar('mid')));
			$criteria->add(new Criteria('conf_name', 'xortify_urijson'));
			$xoConfig = $config_handler->getConfigs($criteria, false);
			$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, JSON, SERVER1));
			$config_handler->insertConfig($xoConfig[0]);
			
		} else {
			$source = file_get_contents(SERVER2);
			if (strlen($source)>0) {
				$xoMod->setVar('isactive', true);
				
				$criteria = new CriteriaCompo(new Criteria('conf_modid', $xoMod->getVar('mid')));
				$criteria->add(new Criteria('conf_name', 'xortify_urisoap'));
				$xoConfig = $config_handler->getConfigs($criteria, false);
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, SOAP, SERVER2));
				$config_handler->insertConfig($xoConfig[0]);
				
				$criteria = new CriteriaCompo(new Criteria('conf_modid', $xoMod->getVar('mid')));
				$criteria->add(new Criteria('conf_name', 'xortify_uricurl'));
				$xoConfig = $config_handler->getConfigs($criteria, false);
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, CURL, SERVER2));
				$config_handler->insertConfig($xoConfig[0]);
	
				$criteria = new CriteriaCompo(new Criteria('conf_modid', $xoMod->getVar('mid')));
				$criteria->add(new Criteria('conf_name', 'xortify_urijson'));
				$xoConfig = $config_handler->getConfigs($criteria, false);
				$xoConfig[0]->setVar('conf_value', str_replace(REPLACE, JSON, SERVER2));
				$config_handler->insertConfig($xoConfig[0]);
					
			} else {
				$xoMod->setVar('isactive', false);
			}
		}	
		$module_handler->insert($xoMod);
	}

?>