<?php
function spiders_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
			$data[] = array("name" => "records", "type" => "integer");	
											 
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
	$i=0;
	$xsd['response'][$i] = array("name" => "spiders", "type" => "integer");
	$i++;
	$xsd['response'][$i] = array("name" => "made", "type" => "integer");
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
		$data[] = array("items" => array("data" => $datab, "objname" => "data"));
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'data';
	return $xsd;
}

function spiders_wsdl(){

}

function spiders_wsdl_service(){

}

// Define the method as a PHP function
function spiders($username, $password, $records) {
	global $xoopsModuleConfig, $xoopsDB;

	if ($xoopsModuleConfig['site_user_auth']==1){
		if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
		if (!checkright(basename(__FILE__),$username,$password)) {
			mark_for_lock(basename(__FILE__),$username,$password);
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}
	}


	$records = ($records!=0)?intval($records):600;

	$sql = "SELECT * FROM ".$xoopsDB->prefix('spiders'). ' limit '.intval($records);
	$result = $xoopsDB->query($sql);
	$ret = array();
	while($robot = $xoopsDB->fetchArray($result) ){
		$id++;
		foreach(array(	'robot-id','robot-name','robot-cover-url','robot-details-url','robot-owner-name','robot-owner-url','robot-owner-email',
						'robot-status','robot-purpose','robot-type','robot-platform','robot-availability','robot-exclusion','robot-exclusion-useragent',
						'robot-noindex','robot-host','robot-from','robot-useragent','robot-language','robot-description','robot-history',
						'robot-environment','modified-date','modified-by','robot-safeuseragent','robot-handlesession' ) as $field)
			$ret[$id][$field] = $robot[$field];
		
	}
	return array("spiders" => count($ret), "made" => time(), "robots" => $ret);

}

?>