<?php
function spiderstat_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
		$datab=array();
		$datab[] = array("name" => "uri", "type" => "string");
		$datab[] = array("name" => "useragent", "type" => "string");
		$datab[] = array("name" => "netaddy", "type" => "string");
		$datab[] = array("name" => "ip", "type" => "string");
		$datab[] = array("name" => "server-ip", "type" => "string");
		$datab[] = array("name" => "when", "type" => "string");
		$datab[] = array("name" => "sitename", "type" => "string");
		$datab[] = array("name" => "robot-id", "type" => "string");
		$datab[] = array("name" => "robot-name", "type" => "string");
			$data[] = array("items" => array("statistic" => $datab, "objname" => "statistic"));
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'data';	
	
	$xsd['response'][] = array("name" => "ban_made", "type" => "boolean");
	$xsd['response'][] = array("name" => "made", "type" => "integer");
	return $xsd;
}

function spiderstat_wsdl(){

}

function spiderstat_wsdl_service(){

}

// Define the method as a PHP function
function spiderstat($username, $password, $statistic) {
	global $xoopsModuleConfig, $xoopsDB;
	
	if ($xoopsModuleConfig['site_user_auth']==1){
		if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
		if (!checkright(basename(__FILE__),$username,$password)) {
			mark_for_lock(basename(__FILE__),$username,$password);
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}
	}
	
	$spider_handler = &xoops_getmodulehandler( 'spiders', 'spiders' );	
	$member_handler = &xoops_gethandler( 'member' );
	
	$modulehandler =& xoops_gethandler('module');
	$confighandler =& xoops_gethandler('config');
	$xoModule = $modulehandler->getByDirname('spiders');
	$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);

	$statistics_handler =& xoops_getmodulehandler('statistics', 'spiders');

	$ban = $spider_handler->banDetails($statistic['netaddy']);
	
	if ($ban!=false) {
		return array("ban_made" => $ban, "made" => time());
	}
	
	$spiders = $spider_handler->getObjects(NULL);
	
	foreach($spiders as $spider) {
		if (strtolower($spider->getVar('robot-id'))==strtolower($statistic['robot-id'])) {
			$id = $spider->getVar('id');
			$thespider = $spider;
		}
	}

	$stat = $statistics_handler->create();
	$stat->setVar('id', $id);
	$stat->setVar('useragent', $statistic['useragent']);
	$stat->setVar('uri', $statistic['uri']);
	$stat->setVar('netaddy', $statistic['netaddy']);
	$stat->setVar('ip', $statistic['ip']);
	$stat->setVar('server-ip', $statistic['server-ip']);	
	$stat->setVar('when', $statistic['when']);		
	$stat->setVar('sitename', $statistic['sitename']);	
	$sql = "DELETE FROM " . $GLOBALS['xoopsDB']->prefix('spiders_statistics') . " WHERE `when` < '".(time()-(24*60*60*7*2))."'";
	@$GLOBALS['xoopsDB']->queryF($sql);
	$status = ($statistics_handler->insert($stat))?true:false;

	if (strpos(strtolower($_SERVER['HTTP_HOST']), 'xortify.com')) {
		define('XORTIFY_API_LOCAL', 'http://xortify.chronolabs.coop/soap/');
		define('XORTIFY_API_URI', 'http://xortify.chronolabs.coop/soap/');
	} else {
		define('XORTIFY_API_LOCAL', 'http://xortify.com/soap/');
		define('XORTIFY_API_URI', 'http://xortify.com/soap/');
	}
	
	@$soap_client = @new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
	@$soap_client->__soapCall('spiderstat',
				array(      "username"	=> 	$username, 
							"password"	=> 	$password, 
							"statistic"	=> 	$statistic	) );
				
				
	return array("stat_made" => $status, "made" => time());

}

?>