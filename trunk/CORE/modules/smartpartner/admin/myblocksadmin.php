<?php

/**
* $Id: myblocksadmin.php,v 1.4 2005/03/15 20:09:34 malanciault Exp $
* Module: SmartPartner
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

// ------------------------------------------------------------------------- //
//                            myblocksadmin.php                              //
//                - XOOPS block admin for each modules -                     //
//                          GIJOE <http://www.peak.ne.jp/>                   //
// ------------------------------------------------------------------------- //

include_once("../../../include/cp_header.php");
include_once("mygrouppermform.php");
include_once(XOOPS_ROOT_PATH."/class/xoopsblock.php");
include_once XOOPS_ROOT_PATH."/modules/".$xoopsModule->dirname()."/include/functions.php";

$xoops_system_path = XOOPS_ROOT_PATH."/modules/system";

// language files
$language = $xoopsConfig['language'] ;
if(!file_exists("$xoops_system_path/language/$language/admin/blocksadmin.php")) $language = 'english';

// to prevent from notice that constants already defined
$error_reporting_level = error_reporting(0);
include_once( "$xoops_system_path/constants.php" ) ;
include_once( "$xoops_system_path/language/$language/admin.php" ) ;
include_once( "$xoops_system_path/language/$language/admin/blocksadmin.php" ) ;
error_reporting( $error_reporting_level ) ;

$group_defs = file( "$xoops_system_path/language/$language/admin/groups.php" ) ;
foreach( $group_defs as $def ) {
	if( strstr( $def , '_AM_ACCESSRIGHTS' ) || strstr( $def , '_AM_ACTIVERIGHTS' ) ) eval( $def ) ;
}

// check $xoopsModule
if( ! is_object( $xoopsModule ) ) redirect_header( XOOPS_URL.'/user.php' , 1 , _NOPERM ) ;

// check access right (needs system_admin of BLOCK)
$sysperm_handler =& xoops_gethandler('groupperm');
if (!$sysperm_handler->checkRight('system_admin', XOOPS_SYSTEM_BLOCK, $xoopsUser->getGroups())) redirect_header( XOOPS_URL.'/user.php' , 1 , _NOPERM ) ;

// get blocks owned by the module
$block_arr =& XoopsBlock::getByModule( $xoopsModule->mid() ) ;


function list_blocks()
{
	global $block_arr, $xoopsModule ;
	
	// cachetime options
	$cachetimes = array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH);
	
	// displaying TH
    smartpartner_collapsableBar('toptable', 'toptableicon');
    echo "<img id='toptableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _AM_SPARTNER_BLOCKS . "</h3>";
    echo "<div id='toptable'>";
    echo "<span style=\"color: #567; margin: 3px 0 12px 0; font-size: small; display: block; \">" . _AM_SPARTNER_BLOCKSTXT . "</span>";

	
	echo "
	<form action='admin.php' name='blockadmin' method='post'>
		<table width='100%' class='outer' cellpadding='4' cellspacing='1'>
		<tr valign='middle'>
			<th>"._AM_TITLE."</th>
			<th align='center' nowrap='nowrap'>"._AM_SPARTNER_POSITION."</th>
			<th align='center'>"._AM_WEIGHT."</th>
			<th align='center'>"._AM_VISIBLEIN."</th>
			<th align='center'>"._AM_BCACHETIME."</th>
			<th align='center'>"._AM_ACTION."</th>
		</tr>\n" ;
	
	// blocks displaying loop
	$class = 'even' ;
	foreach( array_keys( $block_arr ) as $i ) {
		$sseln = $ssel0 = $ssel1 = $ssel2 = $ssel3 = $ssel4 = "";
		
		$weight = $block_arr[$i]->getVar("weight") ;
		$title = $block_arr[$i]->getVar("title") ;
		$name = $block_arr[$i]->getVar("name") ;
		$bcachetime = $block_arr[$i]->getVar("bcachetime") ;
		$bid = $block_arr[$i]->getVar("bid") ;
		
		// visible and side
		if ( $block_arr[$i]->getVar("visible") != 1 ) {
			$sseln = " checked='checked' style='background-color:#FF0000;'";
		} else switch( $block_arr[$i]->getVar("side") ) {
			default :
			case XOOPS_SIDEBLOCK_LEFT :
			$ssel0 = " checked='checked' style='background-color:#00FF00;'";
			break ;
			case XOOPS_SIDEBLOCK_RIGHT :
			$ssel1 = " checked='checked' style='background-color:#00FF00;'";
			break ;
			case XOOPS_CENTERBLOCK_LEFT :
			$ssel2 = " checked='checked' style='background-color:#00FF00;'";
			break ;
			case XOOPS_CENTERBLOCK_RIGHT :
			$ssel4 = " checked='checked' style='background-color:#00FF00;'";
			break ;
			case XOOPS_CENTERBLOCK_CENTER :
			$ssel3 = " checked='checked' style='background-color:#00FF00;'";
			break ;
		}
		
		// bcachetime
		$cachetime_options = '' ;
		foreach( $cachetimes as $cachetime => $cachetime_name ) {
			if( $bcachetime == $cachetime ) {
				$cachetime_options .= "<option value='$cachetime' selected='selected'>$cachetime_name</option>\n" ;
			} else {
				$cachetime_options .= "<option value='$cachetime'>$cachetime_name</option>\n" ;
			}
		}
		
		// target modules
		$db =& Database::getInstance();
		$result = $db->query( "SELECT module_id FROM ".$db->prefix('block_module_link')." WHERE block_id='$bid'" ) ;
		$selected_mids = array();
		while ( list( $selected_mid ) = $db->fetchRow( $result ) ) {
			$selected_mids[] = intval( $selected_mid ) ;
		}
		$module_handler =& xoops_gethandler('module');
		$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
		$criteria->add(new Criteria('isactive', 1));
		$module_list =& $module_handler->getList($criteria);
		$module_list[-1] = _AM_TOPPAGE;
		$module_list[0] = _AM_ALLPAGES;
		ksort($module_list);
		$module_options = '' ;
		$myts = &MyTextSanitizer::getInstance();
		foreach( $module_list as $mid => $mname ) {
			if( in_array( $mid , $selected_mids ) ) {
				$module_options .= "<option value='$mid' selected='selected'>" . $myts->displayTarea($mname) . "</option>\n" ;
			} else {
				$module_options .= "<option value='$mid'>" . $myts->displayTarea($mname) . "</option>\n" ;
			}
		}
		
		// displaying part
		echo "
		<tr valign='middle'>
			<td class='$class'>
				$name
				<br />
				<input type='text' name='title[$bid]' value='$title' size='20' />
			</td>
			<td class='$class' align='center' nowrap='nowrap'>
				<input type='radio' name='side[$bid]' value='".XOOPS_SIDEBLOCK_LEFT."'$ssel0 />-<input type='radio' name='side[$bid]' value='".XOOPS_CENTERBLOCK_LEFT."'$ssel2 /><input type='radio' name='side[$bid]' value='".XOOPS_CENTERBLOCK_CENTER."'$ssel3 /><input type='radio' name='side[$bid]' value='".XOOPS_CENTERBLOCK_RIGHT."'$ssel4 />-<input type='radio' name='side[$bid]' value='".XOOPS_SIDEBLOCK_RIGHT."'$ssel1 />
				<br />
				<br />
				<input type='radio' name='side[$bid]' value='-1'$sseln />
				"._NONE."
			</td>
			<td class='$class' align='center'>
				<input type='text' name=weight[$bid] value='$weight' size='5' maxlength='5' style='text-align:right;' />
			</td>
			<td class='$class' align='center'>
				<select name='bmodule[$bid][]' size='5' multiple='multiple'>
					$module_options
				</select>
			</td>
			<td class='$class' align='center'>
				<select name='bcachetime[$bid]' size='1'>
					$cachetime_options
				</select>
			</td>
			<td class='$class' align='center'>
				<a href='admin.php?fct=blocksadmin&amp;op=edit&amp;bid=$bid'>"._EDIT."</a>
				<input type='hidden' name='bid[$bid]' value='$bid' />
			</td>
		</tr>\n" ;
		
		$class = ( $class == 'even' ) ? 'odd' : 'even' ;
	}
	
	echo "
		<tr>
			<td class='foot' align='center' colspan='6'>
				<input type='hidden' name='fct' value='blocksadmin' />
				<input type='hidden' name='op' value='order' />
				<input type='submit' name='submit' value='"._SUBMIT."' />
			</td>
		</tr>
		</table>
	</form>\n" ;
	echo "</div>";
}


function list_groups()
{
	global $xoopsModule , $block_arr ;
	$myts = &MyTextSanitizer::getInstance();
	
    smartpartner_collapsableBar('bottomtable', 'bottomtableicon');
    
	foreach( array_keys( $block_arr ) as $i ) {
		$item_list[ $block_arr[$i]->getVar("bid") ] = $block_arr[$i]->getVar("title") ;
	}
	
	$form = new MyXoopsGroupPermForm('' , 1 , 'block_read' , "<img id='bottomtableicon' src=" . XOOPS_URL . "/modules/" . $xoopsModule->dirname() . "/images/icon/close12.gif alt='' /></a>&nbsp;" . _AM_SPARTNER_GROUPS . "</h3><div id='bottomtable'><span style=\"color: #567; margin: 3px 0 0 0; font-size: small; display: block; \">" . _AM_SPARTNER_GROUPSINFO . "</span>") ;
	$form->addAppendix('module_admin',$xoopsModule->mid(),$myts->displayTarea($xoopsModule->name()).' '._AM_ACTIVERIGHTS);
	$form->addAppendix('module_read',$xoopsModule->mid(),$myts->displayTarea($xoopsModule->name()).' '._AM_ACCESSRIGHTS);
	foreach( $item_list as $item_id => $item_name) {
		$form->addItem( $item_id , $myts->displayTarea($item_name) ) ;
	}
	echo $form->render() ;
    echo "</div>";	
}

if(!empty($_POST['submit'])) {
	include( "mygroupperm.php" ) ;
	include_once( "$xoops_system_path/language/$language/admin.php" ) ;
	redirect_header( XOOPS_URL."/modules/".$xoopsModule->dirname()."/admin/myblocksadmin.php" , 1 , _AM_DBUPDATED );
}

xoops_cp_header() ;
if(file_exists('./mymenu.php')) include('./mymenu.php');
smartpartner_adminMenu(2, _AM_SPARTNER_BLOCKSANDGROUPS);

list_blocks() ;
list_groups() ;
xoops_cp_footer() ;
?>