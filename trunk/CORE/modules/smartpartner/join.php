<?php

/**
* $Id: join.php,v 1.15 2005/05/26 15:26:22 fx2024 Exp $
* Module: SmartPartner
* Author: The SmartFactory <www.smartfactory.ca>
* Licence: GNU
*/

include "header.php";
$xoopsOption['template_main'] = 'smartpartner_join.html';
include XOOPS_ROOT_PATH."/header.php";
include "footer.php";

xoops_load('XoopsCaptcha');

$myts =& MyTextSanitizer::getInstance();

$op = isset($_POST['op']) ? $_POST['op'] : 'form';

switch ($op) {
	
	case "submitPartner" :
	
    $xoopsCaptcha = XoopsCaptcha::getInstance();
    if (!$xoopsCaptcha->verify()) {
        redirect_header('join.php',10,$xoopsCaptcha->getMessage());
		exit(0);
    }
	
	include XOOPS_ROOT_PATH."/class/xoopsmailer.php";
	
	$partnerObj = $partner_handler->create();
	// Uploading the logo, if any
	// Retreive the filename to be uploaded
	
	if ( $_FILES['logo_file']['name'] != "" ) {
		$filename = $_POST["xoops_upload_file"][0] ;
		if( !empty( $filename ) || $filename != "" ) {
			global $xoopsModuleConfig;
			
			$max_size = 10000000;
			$max_imgwidth = $xoopsModuleConfig['img_max_width'];
			$max_imgheight = $xoopsModuleConfig['img_max_height'];
			$allowed_mimetypes = smartpartner_getAllowedMimeTypes();
			
			include_once(XOOPS_ROOT_PATH."/class/uploader.php");
			
			if( $_FILES[$filename]['tmp_name'] == "" || ! is_readable( $_FILES[$filename]['tmp_name'] ) ) {
				redirect_header( 'javascript:history.go(-1)' , 2, _CO_SPARTNER_FILE_UPLOAD_ERROR ) ;
				exit ;
			}
			
			$uploader = new XoopsMediaUploader(smartpartner_getImageDir(), $allowed_mimetypes, $max_size, $max_imgwidth, $max_imgheight);
			
			if( $uploader->fetchMedia( $filename ) && $uploader->upload() ) {
				
				$partnerObj->setVar('image', $uploader->getSavedFileName());
				
			} else {
				redirect_header( 'javascript:history.go(-1)' , 2, _CO_SPARTNER_FILE_UPLOAD_ERROR . $uploader->getErrors() ) ;
				exit ;
			}
		}
	}
	
	// Putting the values in the partner object
	$partnerObj->setVar('id', (isset($_POST['id'])) ? intval($_POST['id']) : 0);
	$partnerObj->setVar('title', $_POST['title']);
	$partnerObj->setVar('summary', $_POST['summary']);
	$partnerObj->setVar('description', $_POST['description']);
	$partnerObj->setVar('contact_name', $_POST['contact_name']);
	$partnerObj->setVar('contact_email', $_POST['contact_email']);
	$partnerObj->setVar('contact_phone', $_POST['contact_phone']);
	$partnerObj->setVar('adress', $_POST['adress']);
	$partnerObj->setVar('url', $_POST['url']);
	$partnerObj->setVar('weight', (isset($_POST['weight'])) ? intval($_POST['weight']) : 0);
	$partnerObj->setVar('status', _SPARTNER_STATUS_SUBMITTED);
	$partnerObj->setVar('domains', $_POST['domains']);
	$partnerObj->setVar('tags', $_POST['tags']);

	if ($xoopsModuleConfig['autoapprove_submitted']) {
		$partnerObj->setVar('status', _SPARTNER_STATUS_ACTIVE);
	} else {
		$partnerObj->setVar('status', _SPARTNER_STATUS_SUBMITTED);	
	}	
	
	// Storing the partner
	If ( !$partnerObj->store() ) {
		redirect_header("javascript:history.go(-1)", 3, _MD_SPARTNER_SUBMIT_ERROR . smartpartner_formatErrors($partnerObj->getErrors()));
		exit;
	}
	
	if (file_exists($GLOBALS['xoops']->path('/modules/tag/class/tag.php'))) {
		$tag_handler = xoops_getmodulehandler('tag', 'tag');
		$tag_handler->updateByItem($_POST["tags"], $partnerObj->id(), $xoopsModule->getVar("dirname"), $catid = 0);
	}
	
	if (isset($_POST['notifypub']) && $_POST['notifypub'] == 1) {
		include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
		$notification_handler = &xoops_gethandler('notification');
		$notification_handler->subscribe('partner', $partnerObj->id(), 'approved', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
	}
	
	$partnerObj->sendNotifications(array(_SPARTNER_NOT_PARTNER_SUBMITTED));
	redirect_header("index.php", 3, _MD_SPARTNER_SUBMIT_SUCCESS);
	exit;
	break;
	
	case "form" :
	
	If (($xoopsModuleConfig['allowsubmit'] != 1) || (!$xoopsUser) && $xoopsModuleConfig['anonpost'] != 1) {
		redirect_header("index.php",2,_NOPERM);
	}
	
	include XOOPS_ROOT_PATH."/class/xoopsformloader.php";
	$form = new XoopsThemeForm(_MD_SPARTNER_JOIN, "joinform", "join.php");
	$form->setExtra('enctype="multipart/form-data"');
	$title_text = new XoopsFormText(_MD_SPARTNER_TITLE, "title", 30, 50);
	$title_text->setDescription(_MD_SPARTNER_TITLE_DSC);
	
	// TITLE
	$title_text = new XoopsFormText(_CO_SPARTNER_TITLE_REQ, 'title', 50, 255, '');
	$title_text->setDescription(_CO_SPARTNER_TITLE_DSC);
	$form->addElement($title_text, true);
	
	// LOGO UPLOAD
	$max_size = 5000000;
	$file_box = new XoopsFormFile(_CO_SPARTNER_LOGO_UPLOAD, "logo_file", $max_size);
	$file_box->setExtra( "size ='45'") ;
	$file_box->setDescription(sprintf(_CO_SPARTNER_LOGO_UPLOAD_DSC,$xoopsModuleConfig['img_max_width'],$xoopsModuleConfig['img_max_height']));
	$form->addElement($file_box);
	
	// IMAGE_URL
	$image_url_text = new XoopsFormText(_CO_SPARTNER_IMAGE_URL, 'image_url', 50, 255, '');
	$image_url_text->setDescription(_CO_SPARTNER_IMAGE_URL_DSC);
	$form->addElement($image_url_text, false);
	
	// URL
	$url_text = new XoopsFormText(_CO_SPARTNER_URL, 'url', 50, 255, '');
	$url_text->setDescription(_CO_SPARTNER_URL_DSC);
	$form->addElement($url_text, false);
	
	// SUMMARY
	$summary_text = new XoopsFormTextArea(_CO_SPARTNER_SUMMARY_REQ, 'summary', '', 7, 60);
	$summary_text->setDescription(_CO_SPARTNER_SUMMARY_DSC);
	$form->addElement($summary_text, true);
	
	// DESCRIPTION
	$description_text = new XoopsFormDhtmlTextArea(_CO_SPARTNER_DESCRIPTION, 'description', '', 15, 60);
	$description_text->setDescription(_CO_SPARTNER_DESCRIPTION_DSC);
	$form->addElement($description_text, false);
	
	// CONTACT_NAME
	$contact_name_text = new XoopsFormText(_CO_SPARTNER_CONTACT_NAME, 'contact_name', 50, 255, '');
	$contact_name_text->setDescription(_CO_SPARTNER_CONTACT_NAME_DSC);
	$form->addElement($contact_name_text, false);	
	
	// CONTACT_EMAIL
	$contact_email_text = new XoopsFormText(_CO_SPARTNER_CONTACT_EMAIL, 'contact_email', 50, 255, '');
	$contact_email_text->setDescription(_CO_SPARTNER_CONTACT_EMAIL_DSC);
	$form->addElement($contact_email_text, false);	
	
	// CONTACT_PHONE
	$contact_phone_text = new XoopsFormText(_CO_SPARTNER_CONTACT_PHONE, 'contact_phone', 50, 255, '');
	$contact_phone_text->setDescription(_CO_SPARTNER_CONTACT_PHONE_DSC);
	$form->addElement($contact_phone_text, false);	
	
	// ADRESS
	$adress_text = new XoopsFormTextArea(_CO_SPARTNER_ADRESS, 'adress', '', 4, 60);
	$adress_text->setDescription(_CO_SPARTNER_ADRESS_DSC);
	$form->addElement($adress_text, false);	

	// TAGS
	if (file_exists($GLOBALS['xoops']->path('/modules/tag/include/formtag.php'))) {
		include_once $GLOBALS['xoops']->path('/modules/tag/include/formtag.php');
		$tags_text = new XoopsFormTag("tags", 60, 255, $itemid, $catid = 0);
		$form->addElement($tags_text, false);	
	}

	// DOMAINS
	if (file_exists($GLOBALS['xoops']->path('/modules/multisite/class/formselectdomains.php'))) {
		include_once $GLOBALS['xoops']->path('/modules/multisite/class/formselectdomains.php');
		$domains_text = new XoopsFormSelectDomains(_CO_SPARTNER_DOMAINS, "domains", array(urlencode(XOOPS_URL)), 10, true);
		$domains_text->setDescription(_CO_SPARTNER_DOMAINS_DSC);
		$form->addElement($domains_text, false);	
	}
	
	// NOTIFY ON PUBLISH
	if (is_object($xoopsUser)&& ($xoopsModuleConfig['autoapprove_submitted'] != 1)) {
		$notify_checkbox = new XoopsFormCheckBox('', 'notifypub', 1);
		$notify_checkbox->addOption(1, _MD_SPARTNER_NOTIFY);
		$form->addElement($notify_checkbox);
	}
	
	$form->addElement(new XoopsFormCaptcha(), true);
	
	// BUTTONS
	$button_tray = new XoopsFormElementTray('', '');
	$hidden = new XoopsFormHidden('op', 'submitPartner');
	$button_tray->addElement($hidden);
	
	$butt_create = new XoopsFormButton('', '', _CO_SPARTNER_SUBMIT, 'submit');
	$butt_create->setExtra('onclick="this.form.elements.op.value=\'submitPartner\'"');
	$button_tray->addElement($butt_create);
	
	$butt_clear = new XoopsFormButton('', '', _CO_SPARTNER_CLEAR, 'reset');
	$button_tray->addElement($butt_clear);
	
	$butt_cancel = new XoopsFormButton('', '', _CO_SPARTNER_CANCEL, 'button');
	$butt_cancel->setExtra('onclick="history.go(-1)"');
	$button_tray->addElement($butt_cancel);
	
	$form->addElement($button_tray, true);
	
	$form->assign($xoopsTpl);
	$xoopsTpl->assign(array("lang_main_partner" => _MD_SPARTNER_PARTNERS, "lang_join" => _MD_SPARTNER_JOIN));
	$xoopsTpl->assign('lang_intro_title', _MD_SPARTNER_JOIN);
	$xoopsTpl->assign('lang_intro_text', sprintf(_MD_SPARTNER_INTRO_JOIN, $xoopsConfig['sitename']));
	$xoopsTpl->assign('xoops_pagetitle', $myts->makeTboxData4Show($xoopsModule->name()) . ' : ' . _MD_SPARTNER_JOIN);
	break;
}


include_once XOOPS_ROOT_PATH.'/footer.php';
?>