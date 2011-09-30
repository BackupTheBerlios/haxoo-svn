<?php

if (!defined('XOOPS_ROOT_PATH')) {
	exit();
}
/**
 * Class for Spiders
 * @author Simon Roberts (simon@xoops.org)
 * @copyright copyright (c) 2000-2009 XOOPS.org
 * @package kernel
 */
class SpidersStatistics extends XoopsObject
{

    function SpidersStatistics($fid = null)
    {
        $this->initVar('id', XOBJ_DTYPE_INT, null, false);
        $this->initVar('uri', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 65535);
        $this->initVar('useragent', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 255);
        $this->initVar('netaddy', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 255);
        $this->initVar('ip', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 65535);
        $this->initVar('server-ip', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 65535);
        $this->initVar('when', XOBJ_DTYPE_INT, time(), false);
        $this->initVar('sitename', XOBJ_DTYPE_UNICODE_TXTBOX, null, false, 255);		
    }


}


/**
* XOOPS Spider handler class.
* This class is responsible for providing data access mechanisms to the data source
* of XOOPS user class objects.
*
* @author  Simon Roberts <simon@xoops.org>
* @package kernel
*/
class SpidersStatisticsHandler extends XoopsPersistableObjectHandler
{
    function __construct(&$db) 
    {
        parent::__construct($db, "spiders_statistics", 'SpidersStatistics', "id", "uri");
    }
	
	function apimethod() {
		$config_handler =& xoops_gethandler('config');
		$module_handler =& xoops_gethandler('module');
		$xoMod = $module_handler->getByDirname('spiders');
		$xoConfig = $config_handler->getConfigList($xoMod->getVar('mid'));
		return $xoConfig['protocol'];
	}

	function insert($obj, $force=true) {
		error_reporting(E_ALL);
		xoops_load('cache');
		$read = XoopsCache::read('spider_id%%'.$obj->getVar('id'));
		if (!is_array($read)) {
			$value = '0A';
		} else {
			$value = $read['value'];
		}
		$value++;
		$read = XoopsCache::delete('spider_id%%'.$obj->getVar('id'));
		$read = XoopsCache::write('spider_id%%'.$obj->getVar('id'), array('value' => $value));	
		
		$modulehandler =& xoops_gethandler('module');
		$confighandler =& xoops_gethandler('config');
		$xoModule = $modulehandler->getByDirname('spiders');
		$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);

		if ($xoConfig['xortify_shareme']==true) {
			
			// Connect to API
			$api = $this->apimethod();
			include_once($GLOBALS['xoops']->path('/modules/spiders/class/'.$api.'.php'));
			$func = strtoupper($api).'SpidersExchange';
			$exchange = new $func;
			
			//Form Associated Array
			$spiders_handler =& xoops_getmodulehandler('spiders', 'spiders');
			$spider = $spiders_handler->get($obj->getVar('id'));
			$ret = array();
			$ret['useragent'] = $obj->getVar('useragent');
			$ret['netaddy'] = $obj->getVar('netaddy');
			$ret['ip'] = $obj->getVar('ip');
			$ret['server-ip'] = isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR'];
			$obj->setVar('server-ip', isset($_SERVER['SERVER_ADDR']) ? $_SERVER['SERVER_ADDR'] : $_SERVER['LOCAL_ADDR']);
			$ret['when'] = $obj->getVar('when');
			$ret['uri'] = $obj->getVar('uri');
			$ret['sitename'] = $GLOBALS['xoopsConfig']['sitename'];
			$ret['robot-name'] = $spider->getVar('robot-name');
			$ret['robot-id'] = $spider->getVar('robot-id');			
			
			//Send to API
			$exchange->sendStatistic($ret);
		}
		
		// Clear Statistics - Save on database size
		$modulehandler = xoops_gethandler('module');
		$confighandler = xoops_gethandler('config');
		$xoMod = $modulehandler->getByDirname('spiders');
		$xoConfig = $confighandler->getConfigList($xoMod->getVar('mid'));	
		$criteria = new Criteria('when', time() - ($xoConfig['weeks_stats'] *(60*60*24*7)), '<');
		$this->deleteAll($criteria, true);
								
		return parent::insert($obj, $force);
	}
}
?>