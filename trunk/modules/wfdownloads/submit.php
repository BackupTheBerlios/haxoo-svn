<?php
/**
 * $Id: submit.php,v 1.18 2006/05/25 14:15:12 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'header.php';
include_once XOOPS_ROOT_PATH . '/class/xoopstree.php';

$myts = &MyTextSanitizer::getInstance(); // MyTextSanitizer object
$mytree = new XoopsTree($xoopsDB->prefix('wfdownloads_cat'), "cid", "pid");

global $xoopsModuleConfig, $xoopsUser;

if (!is_object($xoopsUser) && ($xoopsModuleConfig['anonpost'] == 1 || $xoopsModuleConfig['anonpost'] == 3))
{
    redirect_header(XOOPS_URL . '/user.php', 1, _MD_WFD_MUSTREGFIRST);
    exit();
}
elseif (is_object($xoopsUser) && ($xoopsModuleConfig['submissions'] == 1 || $xoopsModuleConfig['submissions'] == 3) && !$xoopsUser->isAdmin())
{
    redirect_header("index.php", 1, _MD_WFD_NOTALLOWESTOSUBMIT);
    exit();
}

if (is_object($xoopsUser) && !$xoopsUser->isAdmin()) {
    if ($xoopsUser->getVar('posts') < $xoopsModuleConfig['upload_minposts']) {
        redirect_header("index.php",5,_MD_WFD_UPLOADMINPOSTS);
        exit();
    }
}

if (isset($_POST['submit']) && !empty($_POST['submit']))
{
    $notify = !empty($_POST['notify']) ? 1 : 0;

    $lid = (!empty($_POST['lid'])) ? intval($_POST['lid']) : 0 ;
    $cid = (!empty($_POST['cid'])) ? intval($_POST['cid']) : 0 ;

    if (empty($_FILES['userfile']['name']))
    {
		if ($_POST["url"] && $_POST["url"] != "" && $_POST["url"] != "http://")
		{
	        $url = ($_POST["url"] != "http://") ? $_POST["url"] : '';
			$filename = '';
			$filetype = '';
		} else {
	        $url = ($_POST["url"] != "http://") ? $_POST["url"] : '';
			$filename = $_POST['filename'];
			$filetype = $_POST['filetype'];
		}
        $size = ((empty($_POST["size"]) || !is_numeric($_POST["size"]))) ? 0 : intval($_POST["size"]);
        $title = trim($_POST["title"]);
    }
    else
    {
        global $_FILES;

        $down = wfd_uploading($_FILES, $xoopsModuleConfig['uploaddir'], "", "index.php", 0, 0, 0);
        $url = ($_POST["url"] != "http://") ? $_POST["url"] : '';
        $size = $down['size'];
        $filename = $down['filename'];
        $filetype = $_FILES['userfile']['type'];
        $title = $_FILES['userfile']['name'];
        $title = rtrim(wfd_strrrchr($title, "."), ".");
        $title = (isset($_POST["title_checkbox"]) && $_POST["title_checkbox"] == 1) ? $title : trim($_POST["title"]);
    }

    if ((isset($_FILES['screenshot']['name']) && !empty($_FILES['screenshot']['name'])))
    {
        $allowed_mimetypes = $allowed_mimetypes = array('image/gif', 'image/jpeg', 'image/pjpeg', 'image/x-png');
        $maxfilesize = $xoopsModuleConfig['maxfilesize'];
        $maxfilewidth = $xoopsModuleConfig['maximgwidth'];
        $maxfileheight = $xoopsModuleConfig['maximgheight'];
        $uploaddir = XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['screenshots'] . "/";
        $screenshot = strtolower($_FILES['screenshot']['name']);

        include_once XOOPS_ROOT_PATH . '/modules/wfdownloads/class/img_uploader.php';
        $uploader = new XoopsMediaImgUploader($uploaddir, $allowed_mimetypes, $maxfilesize, $maxfilewidth, $maxfileheight);

        if ($uploader->fetchMedia($_POST['xoops_upload_file'][1]))
        {
            if (!$uploader->upload())
            {
                $errors = $uploader->getErrors();
				@unlink($uploaddir.$screenshot);
                redirect_header("index.php?op=downloadsConfigMenu", 1, $errors);
            }
            else
            {
            }
        }
        else
        {
            $errors = $uploader->getErrors();
			@unlink($uploaddir.$screenshot);
            redirect_header("index.php?op=downloadsConfigMenu", 1, $errors);
        }
    }
	else
	{
		$screenshot = '';
	}
    $download_handler = xoops_getmodulehandler('download');
    if ($lid > 0) {
        if ($xoopsModuleConfig['autoapprove'] == 2 || $xoopsModuleConfig['autoapprove'] == 4)
        {
            $download = $download_handler->get($lid);
        }
        else {
            $modification_handler = xoops_getmodulehandler('modification');
            $download = $modification_handler->create();
            $download->setVar('lid', $lid);
        }
    }
    else {
        $download = $download_handler->create();
        if ($xoopsModuleConfig['autoapprove'] == 2 || $xoopsModuleConfig['autoapprove'] == 4)
        {
            $download->setVar("published", time());
            $download->setVar("status", 1);
        }
        else {
            $download->setVar('published', 0);
            $download->setVar('status', 0);
        }
    }
    if (!empty($_POST["homepage"]) || $_POST["homepage"] != "http://")
    {
        $download->setVar('homepage', formatURL(trim($_POST["homepage"])));
        $download->setVar('homepagetitle', trim($_POST["homepagetitle"]));
    }
    $download->setVar('title', $title);
    $download->setVar('url', $url);
    $download->setVar('cid', $cid);
    $download->setVar('filename', $filename);
    $download->setVar('filetype', $filetype);
    $download->setVar('version', $_POST["version"]);
    $download->setVar('size', $size);
    $download->setVar('platform', $_POST["platform"]);
	$download->setVar('screenshot', $screenshot);
    $download->setVar('summary', $_POST["summary"]);
    $download->setVar('description', $_POST["description"]);
    $submitter = !empty($xoopsUser) ? $xoopsUser->getVar('uid') : 0;
    $download->setVar('submitter', $submitter);
    $download->setVar('publisher', trim($_POST["publisher"]));
    $download->setVar('price', trim($_POST["price"]));
    $download->setVar('mirror', isset($_POST["mirror"]) ? formatURL(trim($_POST["mirror"])) : '');
    $download->setVar('license', trim($_POST["license"]));
    $paypalemail = '';
    $download->setVar('features', trim($_POST["features"]));
    $download->setVar('requirements', trim($_POST["requirements"]));
    $forumid = (isset($_POST["forumid"]) && $_POST["forumid"] > 0) ? intval($_POST["forumid"]) : 0;
    $download->setVar('forumid', $forumid);
    $limitations = (isset($_POST["limitations"])) ? $myts->addslashes($_POST["limitations"]) : '';
    $download->setVar('limitations', $limitations);
    $dhistory = (isset($_POST["dhistory"])) ? $myts->addslashes($_POST["dhistory"]) : '';
    $dhistoryhistory = (isset($_POST["dhistoryaddedd"])) ? $myts->addslashes($_POST["dhistoryaddedd"]) : '';
    if ($lid > 0 && !empty($dhistoryhistory))
    {
        $dhistory = $dhistory . "\n\n";
        $time = time();
        $dhistory .= "<b>" . formatTimestamp($time, $xoopsModuleConfig['dateformat']) . "</b>\n\n";
        $dhistory .= $dhistoryhistory;
    }
    $download->setVar('dhistory', $dhistory);
    $offline = (isset($_POST['offline']) && $_POST['offline'] == 1) ? 1 : 0;
    $download->setVar('offline', $offline);
    $download->setVar('date', time());

    $screenshot = '';

    if ($lid == 0)
    {
        $notifypub = (isset($_POST['notifypub']) && $_POST['notifypub'] == 1) ? 1 : 0;
        $download->setVar('notifypub', $notifypub);
        $download->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);

        if (!$download_handler->insert($download))
        {
            $error = _MD_WFD_INFONOSAVEDB;
            trigger_error($error, E_USER_ERROR);
        }
        $newid = $download->getVar('lid');
        $groups = array(1, 2);
        /*
        *  Notify of new link (anywhere) and new link in category
        */
        $notification_handler = &xoops_gethandler('notification');
        $tags = array();
        $tags['FILE_NAME'] = $title;
        $tags['FILE_URL'] = XOOPS_URL . '/modules/wfdownloads/singlefile.php?cid=' . $cid . '&amp;lid=' . $newid;
        $category_handler = xoops_getmodulehandler('category');
        $category = $category_handler->get($cid);

        $tags['CATEGORY_NAME'] = $category->getVar('title');
        $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/wfdownloads/viewcat.php?cid=' . $cid;

        if ($xoopsModuleConfig['autoapprove'] == 2 || $xoopsModuleConfig['autoapprove'] == 4)
        {
            $notification_handler->triggerEvent('global', 0, 'new_file', $tags);
            $notification_handler->triggerEvent('category', $cid, 'new_file', $tags);
            redirect_header('index.php', 2, _MD_WFD_ISAPPROVED . "");
        }
        else
        {
            $tags['WAITINGFILES_URL'] = XOOPS_URL . '/modules/wfdownloads/admin/newdownloads.php';
            $notification_handler->triggerEvent('global', 0, 'file_submit', $tags);
            $notification_handler->triggerEvent('category', $cid, 'file_submit', $tags);
            if ($notify)
            {
                include_once XOOPS_ROOT_PATH . '/include/notification_constants.php';
                $notification_handler->subscribe('file', $newid, 'approve', XOOPS_NOTIFICATION_MODE_SENDONCETHENDELETE);
            }
            redirect_header('index.php', 2, _MD_WFD_THANKSFORINFO);
        }
        exit();
    }
    else
    {
        if ($xoopsModuleConfig['autoapprove'] == 2 || $xoopsModuleConfig['autoapprove'] == 4)
        {
            $notifypub = (isset($_POST['notifypub']) && $_POST['notifypub'] == 1) ? 1 : 0;
            $download->setVar('notifypub', $notifypub);
            $download->setVar('ipaddress', $_SERVER['REMOTE_ADDR']);

            $updated = time();
            $download->setVar('updated', $updated);
            $download_handler->insert($download);

            $notification_handler = &xoops_gethandler('notification');
            $tags = array();
            $tags['FILE_NAME'] = $title;
            $tags['FILE_URL'] = XOOPS_URL . '/modules/wfdownloads/singlefile.php?cid=' . $cid . '&amp;lid=' . $lid;

            $category_handler = xoops_getmodulehandler('category');
            $category = $category_handler->get($cid);
            $tags['CATEGORY_NAME'] = $category->getVar('title');
            $tags['CATEGORY_URL'] = XOOPS_URL . '/modules/wfdownloads/viewcat.php?cid=' . $cid;
        }
        else
        {
            $updated = (isset($_POST['up_dated']) && $_POST['up_dated'] == 0) ? 0 : time();
            $download->setVar('updated', $updated);
            $download->setVar('modifysubmitter', $xoopsUser->uid());
            $download->setVar('requestdate', time());
            if (!$modification_handler->insert($download))
            {
				$error = _MD_WFD_INFONOSAVEDB;
                trigger_error($error, E_USER_ERROR);
            }
            $tags = array();
            $tags['MODIFYREPORTS_URL'] = XOOPS_URL . '/modules/wfdownloads/admin/index.php?op=listModReq';
            $notification_handler = &xoops_gethandler('notification');
            $notification_handler->triggerEvent('global', 0, 'file_modify', $tags);
        }
        redirect_header('index.php', 2, _MD_WFD_THANKSFORINFO);
        exit();
    }
}
else
{
    include XOOPS_ROOT_PATH . '/header.php';

    global $_FILES, $xoopsModuleConfig, $xoopsConfig;

    if ($xoopsModuleConfig['showdisclaimer'] && !isset($_GET['agree']) && !isset($_REQUEST['lid']))
    {
        echo "
		<p><div align = 'center'>" . wfd_imageheader() . "</div></p>\n
		<h4>" . _MD_WFD_DISCLAIMERAGREEMENT . "</h4>\n
		<p><div>" . $myts->displayTarea($xoopsModuleConfig['disclaimer'], 1, 1, 1, 1, 1) . "</div></p>\n
		<form action='submit.php' method='post'>\n
		<div align='center'><b>" . _MD_WFD_DOYOUAGREE . "</b><br /><br />\n
		<input type = 'button' onclick = 'location=\"submit.php?agree=1\"' class='formButton' value='" . _MD_WFD_AGREE . "' alt='" . _MD_WFD_AGREE . "' />\n
		&nbsp;\n
		<input type='button' onclick = 'location=\"index.php\"' class='formButton' value='" . _CANCEL . "' alt='" . _CANCEL . "' />\n
		</div></form>\n<br /><br />";
        include XOOPS_ROOT_PATH . '/footer.php';
        exit();
    }
	elseif ($xoopsModuleConfig['showdisclaimer'] && !isset($_GET['agree']) && isset($_REQUEST['lid']))
	{
        $lid = intval($_REQUEST['lid']);
        echo "
		<p><div align = 'center'>" . wfd_imageheader() . "</div></p>\n
		<h4>" . _MD_WFD_DISCLAIMERAGREEMENT . "</h4>\n
		<p><div>" . $myts->displayTarea($xoopsModuleConfig['disclaimer'], 1, 1, 1, 1, 1) . "</div></p>\n
		<form action='submit.php' method='post'>\n
		<div align='center'><b>" . _MD_WFD_DOYOUAGREE . "</b><br /><br />\n
		<input type = 'button' onclick = 'location=\"submit.php?agree=1&amp;lid=$lid\"' class='formButton' value='" . _MD_WFD_AGREE . "' alt='" . _MD_WFD_AGREE . "' />\n
		&nbsp;\n
		<input type='button' onclick = 'location=\"index.php\"' class='formButton' value='" . _CANCEL . "' alt='" . _CANCEL . "' />\n
		</div></form>\n<br /><br />";
        include XOOPS_ROOT_PATH . '/footer.php';
        exit();
	}

    $download_handler = xoops_getmodulehandler('download');
    if (isset($_REQUEST['lid']) && is_object($xoopsUser))
    {
        $user_id = $xoopsUser->uid();
		$lid = intval($_REQUEST['lid']);
		$download = $download_handler->get($lid);
		if ($user_id !== $download->getVar('submitter')) {
			redirect_header("index.php",5, _MD_WFD_NOTALLOWEDTOMOD);
	        exit();
		}
    }
    else
    {
        $lid = 0;
        $download = $download_handler->create();
    }

    echo "
		<p><div align = 'center'>" . wfd_imageheader() . "</div></p>\n
		<div>" . _MD_WFD_SUB_SNEWMNAMEDESC . "</div>\n";
    $sform = $download->getForm();
    $sform->display();
    include XOOPS_ROOT_PATH . '/footer.php';
}

?>