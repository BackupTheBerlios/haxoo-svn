<?php
function seolinks_xsd(){
	$xsd = array();
	$i=0;
	$data = array();
			$data[] = array("name" => "username", "type" => "string");
			$data[] = array("name" => "password", "type" => "string");	
			$data[] = array("name" => "records", "type" => "integer");	
											 
	$xsd['request'][$i]['items']['data'] = $data;
	$xsd['request'][$i]['items']['objname'] = 'var';	
	$i=0;
	$xsd['response'][$i] = array("name" => "links", "type" => "integer");
	$i++;
	$xsd['response'][$i] = array("name" => "made", "type" => "integer");
	$datab=array();
	$datab[] = array("name" => "uri", "type" => "string");
	$datab[] = array("name" => "host", "type" => "string");
	$datab[] = array("name" => "sitename", "type" => "string");
		$data[] = array("items" => array("data" => $datab, "objname" => "data"));
	$i++;
	$xsd['response'][$i]['items']['data'] = $data;
	$xsd['response'][$i]['items']['objname'] = 'data';
	return $xsd;
}

function seolinks_wsdl(){

}

function seolinks_wsdl_service(){

}

// Define the method as a PHP function
function seolinks($username, $password, $records) {
	global $xoopsModuleConfig, $xoopsDB;

	if ($xoopsModuleConfig['site_user_auth']==1){
		if ($ret = check_for_lock(basename(__FILE__),$username,$password)) { return $ret; }
		if (!checkright(basename(__FILE__),$username,$password)) {
			mark_for_lock(basename(__FILE__),$username,$password);
			return array('ErrNum'=> 9, "ErrDesc" => 'No Permission for plug-in');
		}
	}


	$records = ($records!=0)?intval($records):12;

	$sql = "SELECT DISTINCT `uri`, `sitename` FROM ".$xoopsDB->prefix('spiders_statistics'). ' order by `when` DESC limit '.intval($records);
	$result = $xoopsDB->query($sql);
	$ret = array();
	while($robot = $xoopsDB->fetchArray($result) ){
		$id++;
		foreach(array( 'uri','sitename' ) as $field)
			$ret[$id][$field] = urldecode($robot[$field]);
		
		$url = parse_url(urldecode($robot['uri']));
		$ret[$id]['host'] = $url['host'];
	}
	return array("links" => count($ret), "made" => time(), "seolinks" => $ret);

}

?>