<?php
$config_handler =& xoops_gethandler('config');
$module_handler =& xoops_gethandler('module');
$xoMod = $module_handler->getByDirname('spiders');
$xoConfig = $config_handler->getConfigList($xoMod->getVar('mid'));

define('XORTIFY_JSON_API', $xoConfig['xortify_urijson']);

include_once($GLOBALS['xoops']->path('/modules/spiders/include/JSON.php'));	

ini_set('allow_furl_open', true);

if (ini_get('allow_furl_open')==true)
	define('XOOPS_JSON_LIB', 'PHPJSON');

class JSONSpidersExchange {

	var $json_client;
	var $json_xoops_username = '';
	var $json_xoops_password = '';
	var $refresh = 600;
	
	function JSONSpidersExchange () {
	
		$module_handler =& xoops_gethandler('module');
		$config_handler =& xoops_gethandler('config');
		$xoModule = $module_handler->getByDirname('spiders');
		$configs = $config_handler->getConfigList($xoModule->mid());
		
		$this->json_xoops_username = $configs['xortify_username'];
		$this->json_xoops_password = $configs['xortify_password'];
		$this->refresh = $configs['xortify_records'];
			
	}
	
	function sendSpider($spider) {
		@$this->JSONSpidersExchange();
		$sJSON = new Services_JSON();
		switch (XOOPS_JSON_LIB){
		case "PHPJSON":
			$data = file_get_contents(XORTIFY_JSON_API.'?spider='.urlencode($sJSON->encode(array("username" => $this->json_xoops_username, "password"	=> $this->json_xoops_password, "spider" => $spider ))));
			$result = $this->obj2array($sJSON->decode($data));
			break;
		}
			
		return $result;	
	}
	
	function sendStatistic($statistic) {
		@$this->JSONSpidersExchange();
		$sJSON = new Services_JSON();
		switch (XOOPS_JSON_LIB){
		case "PHPJSON":
			$data = file_get_contents(XORTIFY_JSON_API.'?spiderstat='.urlencode($sJSON->encode(array("username" => $this->json_xoops_username, "password"	=> $this->json_xoops_password, "statistic" => $statistic ))));
			$result = $this->obj2array($sJSON->decode($data));
			break;
		}
			
		return $result;	
	}
	
	function getSpiders() {
		@$this->JSONSpidersExchange();
		$sJSON = new Services_JSON();		
		switch (XOOPS_JSON_LIB){
		case "PHPJSON":
			$data = file_get_contents(XORTIFY_JSON_API.'?spiders='.urlencode($sJSON->encode(array( "username" => $this->json_xoops_username, "password"	=> 	$this->json_xoops_password ))));
			$result = $this->obj2array($sJSON->decode($data));
			break;
		}
		return $result['robots'];
	}
	
	function getSEOLinks() {
		@$this->JSONSpidersExchange();	
		$sJSON = new Services_JSON();	
		switch (XOOPS_JSON_LIB){
		case "PHPJSON":
			$data = file_get_contents(XORTIFY_JSON_API.'?seolinks='.urlencode($sJSON->encode(array( "username" => $this->json_xoops_username, "password"	=> 	$this->json_xoops_password ))));
			$result = $this->obj2array($sJSON->decode($data));;
			break;
		}
		return $result['seolinks'];	
	}
	
	function obj2array($objects) {
		$ret = array();
		foreach($objects as $key => $value) {
			if (is_a($value, 'stdClass')) {
				$ret[$key] = (array)$value;
			} elseif (is_array($value)) {
				$ret[$key] = $this->obj2array($value);
			} else {
				$ret[$key] = $value;
			}
		}
		return $ret;
	}
}


?>