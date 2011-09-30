<?php
error_reporting(E_ALL);
function spider_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
		$datab=array();
		$datab[] = array("name" => "robot-id", "type" => "string");
		$datab[] = array("name" => "robot-name", "type" => "string");
		$datab[] = array("name" => "robot-cover-url", "type" => "string");
		$datab[] = array("name" => "robot-details-url", "type" => "string");
		$datab[] = array("name" => "robot-owner-name", "type" => "string");
		$datab[] = array("name" => "robot-owner-url", "type" => "string");
		$datab[] = array("name" => "robot-owner-email", "type" => "string");
		$datab[] = array("name" => "robot-status", "type" => "string");
		$datab[] = array("name" => "robot-purpose", "type" => "string");
		$datab[] = array("name" => "robot-type", "type" => "string");
		$datab[] = array("name" => "robot-platform", "type" => "string");
		$datab[] = array("name" => "robot-availability", "type" => "string");
		$datab[] = array("name" => "robot-exclusion", "type" => "string");
		$datab[] = array("name" => "robot-exclusion-useragent", "type" => "string");
		$datab[] = array("name" => "robot-noindex", "type" => "string");
		$datab[] = array("name" => "robot-host", "type" => "string");
		$datab[] = array("name" => "robot-from", "type" => "string");
		$datab[] = array("name" => "robot-useragent", "type" => "string");
		$datab[] = array("name" => "robot-language", "type" => "string");
		$datab[] = array("name" => "robot-description", "type" => "string");
		$datab[] = array("name" => "robot-history", "type" => "string");			
		$datab[] = array("name" => "robot-environment", "type" => "string");
		$datab[] = array("name" => "modified-date", "type" => "string");
		$datab[] = array("name" => "modified-by", "type" => "string");
		$datab[] = array("name" => "robot-safeuseragent", "type" => "string");
		$datab[] = array("name" => "robot-handlesession", "type" => "string");
			$data[] = array("items" => array("data" => $datab, "objname" => "spider"));
			
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'data';	
	
	$xsd['response'][] = array("name" => "mod_made", "type" => "boolean");
	$xsd['response'][] = array("name" => "made", "type" => "integer");
	return $xsd;
}

function spider_wsdl(){

}

function spider_wsdl_service(){

}

// Define the method as a PHP function
function spider($username, $password, $apispider) {
	global $xoopsModuleConfig, $xoopsDB;
	$id=0;
	
	if ($xoopsModuleConfig['site_user_auth']==1){
		if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
		if (!checkright(basename(__FILE__),$username,$password)) {
			mark_for_lock(basename(__FILE__),$username,$password);
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}
	}
	
	$spider_handler = &xoops_getmodulehandler( 'spiders', 'spiders' );	
	$spidermods_handler = &xoops_getmodulehandler( 'modifications', 'spiders' );			
	$suser_handler = &xoops_getmodulehandler( 'spiders_user', 'spiders' );	
	$member_handler = &xoops_gethandler( 'member' );
	
	$modulehandler =& xoops_gethandler('module');
	$confighandler =& xoops_gethandler('config');
	$xoModule = $modulehandler->getByDirname('spiders');
	$xoConfig = $confighandler->getConfigList($xoModule->getVar('mid'),false);
	
	$spiders = $spider_handler->getObjects(NULL);
	
	foreach($spiders as $spider) {
		if (strtolower($spider->getVar('robot-id'))==strtolower($apispider['robot-id'])) {
			$id = $spider->getVar('id');
			$thespider = $spider;
		}
	}
	
	if ($id==0) {
		$part = $spider_handler->safeAgent($apispider['robot-useragent']);
		foreach(array(';','/',',','/','(',')',' ') as $split) {
			$ret= array();
			foreach(explode($split, $part) as $value) {
				$ret[] = $value;
			}
			$part = implode(' ',$ret);
		}
		$criteria = new CriteriaCompo();
		
		foreach($ret as $value) 
			if (!is_numeric((substr($value,0,1)))&&(substr($value,0,1))!='x')
				if (!empty($value)) {
					$criteria->add(new Criteria('`robot-safeuseragent`', '%'.$value.'%', 'LIKE'), 'OR');
					$uagereg[] = strtolower($value);
					$uageregb[] = $value;
				}
	
		$id = 0;
		$spiders = $spider_handler->getObjects($criteria, true);
	
		foreach($spiders as $spider) {
			
			$suser = $suser_handler->get($spider->getVar('id'));
			$robot = $member_handler->getUser( $suser->getVar('uid') );
		
			$part = $spider_handler->safeAgent($spider->getVar('robot-useragent'));
			foreach(array(';','/',',','\\','(',')',' ') as $split) {
				$usersafeagent = array();
				foreach(explode($split, $part) as $value) {
					$usersafeagent[] = $value;
				}
				$part = implode(' ',$usersafeagent);
			}
			$usersafeagent = explode(' ', $part);
			$match=0;
			$dos_crsafe = array();
				
			foreach($uagereg as $uaid => $ireg) {		
				if((in_array($ireg, $usersafeagent)||strpos(strtolower(' '.$part), strtolower($ireg)))&&!is_object($GLOBALS['xoopsUser'])) {
					$match++;			
					$dos_crsafe[] = $uageregb[$uaid];
				}
			}		
	
			if (intval($match/count($uagereg)*100)>intval($xoConfig['match_percentile'])) {
				$id = $spider->getVar('id');
				$thespider = $spider;
			}
		}
	}
	
	$newmod = $spidermods_handler->create();
			
	foreach($apispider as $key => $value){
		if ($id<>0) {
			if (md5($value)!=md5($thespider->getVar($key))&&strlen($value)<>strlen($thespider->getVar($key))) {
				$change++;
				$newmod->setVar($key, $value);							
			} else {
				$newmod->setVar($key, $thespider->getVar($key));							
			}
		} else {
			$change++;
			$newmod->setVar($key, $value);							
		}
	}
	
	$newmod->setVar('id', $id);

	if (strpos(strtolower($_SERVER['HTTP_HOST']), 'xortify.com')) {
		define('XORTIFY_API_LOCAL', 'http://xortify.chronolabs.coop/soap/');
		define('XORTIFY_API_URI', 'http://xortify.chronolabs.coop/soap/');
	} else {
		define('XORTIFY_API_LOCAL', 'http://xortify.com/soap/');
		define('XORTIFY_API_URI', 'http://xortify.com/soap/');
	}
	
	@$soap_client = @new soapclient(NULL, array('location' => XORTIFY_API_LOCAL, 'uri' => XORTIFY_API_URI));
	$soap_client->__soapCall('spider',
				array(      "username"	=> 	$username, 
							"password"	=> 	$password, 
							"spider"	=> 	$apispider	) );

	return array("mod_made" => $spidermods_handler->insert($newmod, true), "made" => time());

}

?>