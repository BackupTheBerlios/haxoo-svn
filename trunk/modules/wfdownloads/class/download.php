<?php
// $Id: download.php,v 1.15 2006/05/22 12:46:12 m0nty_ Exp $
// ------------------------------------------------------------------------ //
// 				 XOOPS - PHP Content Management System                      //
//					 Copyright (c) 2000 XOOPS.org                           //
// 						<http://www.xoops.org/>                             //
// ------------------------------------------------------------------------ //
// This program is free software; you can redistribute it and/or modify     //
// it under the terms of the GNU General Public License as published by     //
// the Free Software Foundation; either version 2 of the License, or        //
// (at your option) any later version.                                      //

// You may not change or alter any portion of this comment or credits       //
// of supporting developers from this source code or any supporting         //
// source code which is considered copyrighted (c) material of the          //
// original comment or credit authors.                                      //
// This program is distributed in the hope that it will be useful,          //
// but WITHOUT ANY WARRANTY; without even the implied warranty of           //
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
// GNU General Public License for more details.                             //

// You should have received a copy of the GNU General Public License        //
// along with this program; if not, write to the Free Software              //
// Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
// ------------------------------------------------------------------------ //
// URL: http://www.xoops.org/												//
// Project: The XOOPS Project                                               //
// -------------------------------------------------------------------------//
if (!class_exists("XoopsPersistableObjectHandler")) {
	include_once XOOPS_ROOT_PATH."/modules/wfdownloads/class/object.php";
}

class WfdownloadsDownload extends XoopsObject {

    function WfdownloadsDownload() {
        $this->initVar('lid', XOBJ_DTYPE_INT);
        $this->initVar('cid', XOBJ_DTYPE_INT, 0);
        $this->initVar('title', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('url', XOBJ_DTYPE_URL, 'http://');
        $this->initVar('filename', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('filetype', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('homepage', XOBJ_DTYPE_URL, 'http://');
        $this->initVar('version', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('size', XOBJ_DTYPE_INT, 0);
        $this->initVar('platform', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('screenshot', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('submitter', XOBJ_DTYPE_INT);
        $this->initVar('publisher', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('status', XOBJ_DTYPE_INT, 0);
        $this->initVar('date', XOBJ_DTYPE_INT);
        $this->initVar('hits', XOBJ_DTYPE_INT, 0);
        $this->initVar('rating', XOBJ_DTYPE_OTHER, 0.0);
        $this->initVar('votes', XOBJ_DTYPE_INT, 0);
        $this->initVar('comments', XOBJ_DTYPE_INT, 0);
        $this->initVar('license', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('mirror', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('price', XOBJ_DTYPE_TXTBOX, 0);
        $this->initVar('paypalemail', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('features', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('requirements', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('homepagetitle', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('forumid', XOBJ_DTYPE_INT, 0);
        $this->initVar('limitations', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('dhistory', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('published', XOBJ_DTYPE_INT, 0);
        $this->initVar('expired', XOBJ_DTYPE_INT, 0);
        $this->initVar('updated', XOBJ_DTYPE_INT, 0);
        $this->initVar('offline', XOBJ_DTYPE_INT, 0);
        $this->initVar('summary', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('description', XOBJ_DTYPE_TXTAREA, '');
        $this->initVar('ipaddress', XOBJ_DTYPE_TXTBOX, '');
        $this->initVar('notifypub', XOBJ_DTYPE_INT, 0);
    }

    function getDownloadInfo() {
        global $xoopsModuleConfig, $xoopsConfig;
        global $xoopsUser, $xoopsModule, $myts;
        $down['id'] = $this->getVar('lid');
        $down['cid'] = $this->getVar('cid');

		$use_mirrors = $xoopsModuleConfig['enable_mirrors'];
		$add_mirror = 0;
		if (!is_object($xoopsUser) && ($xoopsModuleConfig['anonpost'] == 3 || $xoopsModuleConfig['anonpost'] == 4) && ($xoopsModuleConfig['submissions'] == 3 || $xoopsModuleConfig['submissions'] == 4) && $use_mirrors == 1)
		{
			$add_mirror = 1;
		}
		elseif (is_object($xoopsUser) && ($xoopsModuleConfig['submissions'] == 3 || $xoopsModuleConfig['submissions'] == 4 || $xoopsUser->isAdmin()) && $use_mirrors == 1)
		{
			$add_mirror = 1;
		}
		$down['add_mirror'] = $add_mirror;
		$down['use_mirrors'] = $use_mirrors;

        $rating = round(number_format($this->getVar('rating'), 0) / 2);
        $rateimg = "rate$rating.gif";
        $down['rateimg'] = $rateimg;
        $down['votes'] = ($this->getVar('votes') == 1) ? _MD_WFD_ONEVOTE : sprintf(_MD_WFD_NUMVOTES, $this->getVar('votes'));
        $down['hits'] = $this->getVar('hits');

        $category_handler = xoops_getmodulehandler('category');
        $down['path'] = $category_handler->getNicePath($this->getVar('cid'));

        $down['imageheader'] = wfd_imageheader();

        $down['title'] = $this->getVar('title');
        $down['url'] = $this->getVar('url');
		$down['filename'] = $this->getVar('filename');
		$down['filetype'] = $this->getVar('filetype');

        if ($this->getVar('screenshot'))
        {
            $down['screenshot_full'] = $this->getVar('screenshot');
            if ($this->getVar('screenshot') && file_exists(XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['screenshots'] . "/" . xoops_trim($this->getVar('screenshot'))))
            {
                if (isset($xoopsModuleConfig['usethumbs']) && $xoopsModuleConfig['usethumbs'] == 1)
                {
                    $down['screenshot_thumb'] = down_createthumb($down['screenshot_full'], $xoopsModuleConfig['screenshots'], "thumbs", $xoopsModuleConfig['shotwidth'], $xoopsModuleConfig['shotheight'],
                    $xoopsModuleConfig['imagequality'], $xoopsModuleConfig['updatethumbs'], $xoopsModuleConfig['keepaspect']);
                } else {
                    $down['screenshot_thumb'] = XOOPS_URL . "/" . $xoopsModuleConfig['screenshots'] . "/" . xoops_trim($this->getVar('screenshot'));
                }
            }
        }

        $down['homepage'] = (!$this->getVar('homepage') || $this->getVar('homepage') == "http://") ? '' : $myts->htmlSpecialChars(trim($this->getVar('homepage')));
		$homepagetitle = $this->getVar('homepagetitle');
        if ($down['homepage'] && !empty($down['homepage']))
        {
            $down['homepagetitle'] = ($homepagetitle != "") ? trim($down['homepage']) : trim($homepagetitle);
            $down['homepage'] = "<a href='" . $down['homepage'] . "' target='_blank'>" . $homepagetitle . "</a>";
        }
        else
        {
            $down['homepage'] = '';
        }
		if ($use_mirrors !== 1)
		{
        	$down['mirror'] = ($this->getVar('mirror') == "http://") ? '' : trim($this->getVar('mirror'));
			if ($down['mirror'] && !empty($down['mirror']))
			{
        		$down['mirror'] = "<a href='" . $down['mirror'] . "' target='_blank'>" . _MD_WFD_MIRRORSITE . "</a>";
			}
			else
			{
				$down['mirror'] = '';
			}
		}
        $down['comments'] = $this->getVar('comments');
		$version = $this->getVar('version');
		if ($version != 0)
		{
        	$down['version'] = $this->getVar('version');
		}
		else
		{
			$down['version'] = 0;
		}
        $down['downtime'] = wfd_GetDownloadTime($this->getVar('size'), 1, 1, 1, 1, 0);
        $down['downtime'] = str_replace("|", "<br />", $down['downtime']);
        $down['size'] = wfd_PrettySize($this->getVar('size'));

        $time = ($this->getVar('updated') != 0) ? $this->getVar('updated') : $this->getVar('published');
        $down['updated'] = formatTimestamp($time, $xoopsModuleConfig['dateformat']);
        $is_updated = ($this->getVar('updated') != 0) ? _MD_WFD_UPDATEDON : _MD_WFD_SUBMITDATE;
        $down['lang_subdate'] = $is_updated;

		$summary = $this->getVar('summary');
		if ($xoopsModuleConfig['autosummary'] == 1 || empty($summary))
		{
			$sumlength = intval($xoopsModuleConfig['autosumlength']);
			$sumdesc = stripslashes($this->getVar('description'));
			if (strlen($sumdesc) > $sumlength) {
					$sumdesc = substr($sumdesc, 0, $sumlength);
				$sumdesc = trim(substr($sumdesc, 0, strrpos($sumdesc, ' '))).' ...';
				$down['summary'] = $myts->displayTarea($sumdesc, 1);
			 }
				else {
						$down['summary'] = $myts->displayTarea($sumdesc, 1);
					 }
		}
		 else {
		 		$down['summary'] = $summary;
		}

        $down['description'] = $this->getVar('description'); //no html
        $down['price'] = ($this->getVar('price') != 0) ? $this->getVar('price') : _MD_WFD_PRICEFREE;
        $down['limitations'] = ($this->getVar('limitations') == "") ? _MD_WFD_NOTSPECIFIED : $myts->htmlSpecialChars(trim($xoopsModuleConfig['limitations'][$this->getVar('limitations')]));
        $down['license'] = ($this->getVar('license')=="") ? _MD_WFD_NOTSPECIFIED : $myts->htmlSpecialChars(trim($xoopsModuleConfig['license'][$this->getVar('license')]));
        $down['submitter'] = xoops_getLinkedUnameFromId($this->getVar('submitter'));
		$publisher = $this->getVar('publisher');
		if (!empty($publisher))
		{
        	$down['publisher'] = $publisher;
		}
		else
		{
			$down['publisher'] = '';
		}
        $down['platform'] = $myts->htmlSpecialChars($xoopsModuleConfig['platform'][$this->getVar('platform')]);
        $history = $this->getVar('dhistory', 'n');
        $down['history'] = $myts->displayTarea($history, 1);
        $down['features'] = '';
        if ($this->getVar('features'))
        {
            $downfeatures = explode('|', trim($this->getVar('features')));
            foreach ($downfeatures as $bi)
            {
                $down['features'][] = $bi;
            }
        }

        $down['requirements'] = '';
        if ($this->getVar('requirements'))
        {
            $downrequirements = explode('|', trim($this->getVar('requirements')));
            foreach ($downrequirements as $bi)
            {
                $down['requirements'][] = $bi;
            }
        }
        $down['mail_subject'] = rawurlencode(sprintf(_MD_WFD_INTFILEFOUND, $xoopsConfig['sitename']));
        $down['mail_body'] = rawurlencode(sprintf(_MD_WFD_INTFILEFOUND, $xoopsConfig['sitename']) . ':  ' . XOOPS_URL . '/modules/wfdownloads/singlefile.php?cid=' . $this->getVar('cid') . '&amp;lid=' . $this->getVar('lid'));

        $down['isadmin'] = (!empty($xoopsUser) && $xoopsUser->isAdmin($xoopsModule->mid())) ? true : false;

        $down['adminlink'] = '';
        if ($down['isadmin'] == true)
        {
            $down['adminlink'] = '[ <a href="' . XOOPS_URL . '/modules/wfdownloads/admin/index.php?op=Download&amp;lid=' . $this->getVar('lid') . '">' . _MD_WFD_EDIT . '</a> | ';
            $down['adminlink'] .= '<a href="' . XOOPS_URL . '/modules/wfdownloads/admin/index.php?op=delDownload&amp;lid=' . $this->getVar('lid') . '">' . _MD_WFD_DELETE . '</a> ]';
        }
        $votestring = ($this->getVar('votes') == 1) ? _MD_WFD_ONEVOTE : sprintf(_MD_WFD_NUMVOTES, $this->getVar('votes'));
        $down['is_updated'] = ($this->getVar('updated') > 0) ? _MD_WFD_UPDATEDON : _MD_WFD_SUBMITDATE;

        if (is_object($xoopsUser) && $down['isadmin'] != true)
        {
            $down['useradminlink'] = ($xoopsUser->getvar('uid') == $this->getVar('submitter')) ? true : false;
        }

        global $xoopsDB;
        $sql2 = "SELECT rated FROM " . $xoopsDB->prefix('wfdownloads_reviews') . " WHERE lid = " . $this->getVar('lid') . " AND submit = 1";
        $results = $xoopsDB->query($sql2);
        $numrows = $xoopsDB->getRowsNum($results);

        $down['reviews_num'] = ($numrows) ? $numrows : 0;

        $finalrating = 0;
        $totalrating = 0;

        while ($review_text = $xoopsDB->fetchArray($results))
        {
            $totalrating += $review_text['rated'];
        }

        if ($down['reviews_num'] > 0)
        {
            $finalrating = $totalrating / $down['reviews_num'];
            $finalrating = round(number_format($finalrating, 0) / 2);
        }
        $down['review_rateimg'] = "rate$finalrating.gif";;

        $down['icons'] = wfd_displayicons($this->getVar('published'), $this->getVar('status'), $this->getVar('hits'));

		global $xoopsDB;
        $sql3 = "SELECT downurl FROM " . $xoopsDB->prefix('wfdownloads_mirrors') . " WHERE lid = " . $this->getVar('lid') . " AND submit = 1";
        $results3 = $xoopsDB->query($sql3);
        $numrows2 = $xoopsDB->getRowsNum($results3);

        $down['mirrors_num'] = ($numrows2) ? $numrows2 : 0;
        return $down;

    }

    function getForm() {
        global $xoopsModuleConfig, $xoopsUser;
        include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
        include_once(XOOPS_ROOT_PATH."/class/tree.php");

		$use_mirrors = $xoopsModuleConfig['enable_mirrors'];

        $sform = new XoopsThemeForm(_MD_WFD_SUBMITCATHEAD, "storyform", $_SERVER['REQUEST_URI']);
        $sform->setExtra('enctype="multipart/form-data"');

        $sform->addElement(new XoopsFormText(_MD_WFD_FILETITLE, 'title', 50, 255, $this->getVar('title', 'e')), true);
        $sform->addElement(new XoopsFormText(_MD_WFD_DLURL, 'url', 50, 255, $this->getVar('url', 'e')), false);
        if (!$this->isNew()) {
		$sform->addElement(new XoopsFormHidden('filename', $this->getVar('filename', 'e')));
		$sform->addElement(new XoopsFormHidden('filetype', $this->getVar('filetype', 'e')));
		}
        if ($xoopsModuleConfig['useruploads'] || (is_object($xoopsUser) && $xoopsUser->isAdmin()) )
        {
            $sform->addElement(new XoopsFormFile(_MD_WFD_UPLOAD_FILEC, 'userfile', 0), false);
        }
		if ($use_mirrors !== 1)
		{
	        $sform->addElement(new XoopsFormText(_MD_WFD_MIRROR, 'mirror', 50, 255, $this->getVar('mirror', 'e')), false);
		}
        $category_handler = xoops_getmodulehandler('category');
        $categories = $category_handler->getUserCategories();
        $mytree = new XoopsObjectTree($categories, "cid", "pid");
        $sform->addElement(new XoopsFormLabel(_MD_WFD_CATEGORYC, $mytree->makeSelBox('cid', 'title', "-", $this->getVar('cid', 'e'))));

        $sform->addElement(new XoopsFormText(_MD_WFD_HOMEPAGETITLEC, 'homepagetitle', 50, 255, $this->getVar('homepagetitle', 'e')), false);
        $sform->addElement(new XoopsFormText(_MD_WFD_HOMEPAGEC, 'homepage', 50, 255, $this->getVar('homepage', 'e')), false);
        $sform->addElement(new XoopsFormText(_MD_WFD_VERSIONC, 'version', 10, 20, $this->getVar('version', 'e')), false);
        $sform->addElement(new XoopsFormText(_MD_WFD_PUBLISHERC, 'publisher', 50, 255, $this->getVar('publisher', 'e')), false);
        $sform->addElement(new XoopsFormText(_MD_WFD_FILESIZEC, 'size', 10, 20, $this->getVar('size', 'e')), false);

        $platform_array = $xoopsModuleConfig['platform'];
        $platform_select = new XoopsFormSelect(_MD_WFD_PLATFORMC, 'platform', $this->getVar('platform', 'e'));
        $platform_select->addOptionArray($platform_array);
        $sform->addElement($platform_select);

        $license_array = $xoopsModuleConfig['license'];
        $license_select = new XoopsFormSelect(_MD_WFD_LICENCEC, 'license', $this->getVar('license', 'e'));
        $license_select->addOptionArray($license_array);
        $sform->addElement($license_select);

        $limitations_array = $xoopsModuleConfig['limitations'];
        $limitations_select = new XoopsFormSelect(_MD_WFD_LIMITATIONS, 'limitations', $this->getVar('limitations', 'e'));
        $limitations_select->addOptionArray($limitations_array);
        $sform->addElement($limitations_select);

        $sform->addElement(new XoopsFormText(_MD_WFD_PRICEC, 'price', 10, 20, $this->getVar('price', 'e')), false);
        $sform->addElement(new XoopsFormDhtmlTextArea(_MD_WFD_SUMMARY, 'summary', $this->getVar('summary', 'e'), 10, 60, "smartHiddenSummary"), false);
        $sform->addElement(new XoopsFormDhtmlTextArea(_MD_WFD_DESCRIPTION, 'description', $this->getVar('description', 'e'), 15, 60, "smartHiddenDescription"), true);
        $sform->addElement(new XoopsFormTextArea(_MD_WFD_KEYFEATURESC, 'features', $this->getVar('features', 'e'), 7, 60), false);
        $sform->addElement(new XoopsFormTextArea(_MD_WFD_REQUIREMENTSC, 'requirements', $this->getVar('requirements', 'e'), 7, 60), false);
        $sform->addElement(new XoopsFormTextArea(_MD_WFD_HISTORYC, 'dhistory', $this->getVar('dhistory', 'e'), 7, 60), false);
        if (!$this->isNew() && $this->getVar('dhistory', 'n') != "")
        {
            $sform->addElement(new XoopsFormTextArea(_MD_WFD_HISTORYD, 'dhistoryaddedd', "", 7, 60), false);
        }
        $sform->addElement(new XoopsFormFile(_MD_WFD_DUPLOADSCRSHOT, 'screenshot', 0), false);

        $option_tray = new XoopsFormElementTray(_MD_WFD_OPTIONS, '<br />');
        $notify_checkbox = new XoopsFormCheckBox('', 'notifypub');
        $notify_checkbox->addOption(1, _MD_WFD_NOTIFYAPPROVE);
        $option_tray->addElement($notify_checkbox);
        $sform->addElement($option_tray);
        $button_tray = new XoopsFormElementTray('', '');

        $button_tray->addElement(new XoopsFormButton('', 'submit', _SUBMIT, 'submit'));
        if (!$this->isNew()) {
            $button_tray->addElement(new XoopsFormHidden('lid', $this->getVar('lid', 'e')));
        }
        $sform->addElement($button_tray);
        return $sform;
    }

    function getAdminForm($title) {
        global $xoopsModuleConfig, $xoopsUser;

		$use_mirrors = $xoopsModuleConfig['enable_mirrors'];

        $sform = new XoopsThemeForm($title, "storyform", $_SERVER['REQUEST_URI']);
        $sform -> setExtra('enctype="multipart/form-data"');
        if (!$this->isNew()) {
            $sform -> addElement(new XoopsFormLabel(_AM_WFD_FILE_ID, $this->getVar('lid')));
        }
        if ($this->getVar('ipaddress') != "")  {
            $sform -> addElement(new XoopsFormLabel(_AM_WFD_FILE_IP, $this->getVar('ipaddress')));
        }

        $titles_tray = new XoopsFormElementTray(_AM_WFD_FILE_TITLE, '<br />');
        $titles = new XoopsFormText('', 'title', 50, 255, $this->getVar('title', 'e'));
        $titles_tray -> addElement($titles);
        $titles_checkbox = new XoopsFormCheckBox('', "title_checkbox", 0);
        $titles_checkbox -> addOption(1, _AM_WFD_FILE_USE_UPLOAD_TITLE);
        $titles_tray -> addElement($titles_checkbox);
        $sform -> addElement($titles_tray);

        if (!$this->isNew()) {
	        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_SUBMITTERID, 'submitter', 10, 10, $this->getVar('submitter', 'e')), true);
		} else {
			$sform -> addElement(new XoopsFormHidden('submitter', $xoopsUser->getVar('uid', 'e')));
		}

        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_DLURL, 'url', 50, 255, $this->getVar('url', 'e')), false);
		$sform -> addElement(new XoopsFormText(_AM_WFD_FILE_FILENAME, 'filename', 50, 255, $this->getVar('filename', 'e')), false);
/*
        $filename_tray = new XoopsFormElementTray(_AM_WFD_FILE_FILENAME);
        $filename_tray->addElement(new XoopsFormLabel($this->getVar('filename')));
        $filename_tray->addElement(new XoopsFormHidden("filename", $this->getVar('filename', 'e')));
		$sform -> addElement($filename_tray, false);
*/
		$sform -> addElement(new XoopsFormText(_AM_WFD_FILE_FILETYPE, 'filetype', 50, 100, $this->getVar('filetype', 'e')), false);
		if ($use_mirrors !== 1)
		{
	        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_MIRRORURL, 'mirror', 50, 255, $this->getVar('mirror', 'e')), false);
		}
        $sform -> addElement(new XoopsFormFile(_AM_WFD_FILE_DUPLOAD, 'userfile', 0), false);

        $category_handler = xoops_getmodulehandler('category');
        $categories = $category_handler->getObjects();
        $mytree = new XoopsObjectTree($categories, "cid", "pid");
        $sform->addElement(new XoopsFormLabel(_AM_WFD_FILE_CATEGORY, $mytree->makeSelBox('cid', 'title', "-", $this->getVar('cid', 'e'))));

        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_HOMEPAGETITLE, 'homepagetitle', 50, 255, $this->getVar('homepagetitle', 'e')), false);
        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_HOMEPAGE, 'homepage', 50, 255, $this->getVar('homepage', 'e')), false);
        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_VERSION, 'version', 10, 20, $this->getVar('version', 'e')), false);
        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_PUBLISHER, 'publisher', 50, 255, $this->getVar('publisher', 'e')), false);
        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_SIZE, 'size', 10, 20, $this->getVar('size', 'e')), false);

        $platform_array = $xoopsModuleConfig['platform'];
        $platform_select = new XoopsFormSelect('', 'platform', $this->getVar('platform', 'e'), '', '', 0);
        $platform_select -> addOptionArray($platform_array);
        $platform_tray = new XoopsFormElementTray(_AM_WFD_FILE_PLATFORM, '&nbsp;');
        $platform_tray -> addElement($platform_select);
        $sform -> addElement($platform_tray);

        $license_array = $xoopsModuleConfig['license'];
        $license_select = new XoopsFormSelect('', 'license', $this->getVar('license', 'e'), '', '', 0);
        $license_select -> addOptionArray($license_array);
        $license_tray = new XoopsFormElementTray(_AM_WFD_FILE_LICENCE, '&nbsp;');
        $license_tray -> addElement($license_select);
        $sform -> addElement($license_tray);

        $limitations_array = $xoopsModuleConfig['limitations'];
        $limitations_select = new XoopsFormSelect('', 'limitations', $this->getVar('limitations', 'e'), '', '', 0);
        $limitations_select -> addOptionArray($limitations_array);
        $limitations_tray = new XoopsFormElementTray(_AM_WFD_FILE_LIMITATIONS, '&nbsp;');
        $limitations_tray -> addElement($limitations_select);
        $sform -> addElement($limitations_tray);

        $sform -> addElement(new XoopsFormText(_AM_WFD_FILE_PRICE, 'price', 10, 20, $this->getVar('price', 'e')), false);

        $sform -> addElement(new XoopsFormDhtmlTextArea(_AM_WFD_FILE_SUMMARY, 'summary', $this->getVar('summary', 'e'), 10, 60, "smartHiddenSummary"), false);
        $sform -> addElement(new XoopsFormDhtmlTextArea(_AM_WFD_FILE_DESCRIPTION, 'description', $this->getVar('description', 'e'), 15, 60, "smartHiddenDescription"), true);
        $sform -> addElement(new XoopsFormTextArea(_AM_WFD_FILE_KEYFEATURES, 'features', $this->getVar('features', 'e'), 7, 60), false);
        $sform -> addElement(new XoopsFormTextArea(_AM_WFD_FILE_REQUIREMENTS, 'requirements', $this->getVar('requirements', 'e'), 7, 60), false);
        $sform -> addElement(new XoopsFormTextArea(_AM_WFD_FILE_HISTORY, 'dhistory', $this->getVar('dhistory', 'e'), 7, 60), false);
        if (!$this->isNew() && $this->getVar('dhistory') != "")
        {
            $sform -> addElement(new XoopsFormTextArea(_AM_WFD_FILE_HISTORYD, 'dhistoryaddedd', "", 7, 60), false);
        }
        $graph_array = & WfsLists :: getListTypeAsArray(XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['screenshots'], $type = "images");
        $indeximage_select = new XoopsFormSelect('', 'screenshot', $this->getVar('screenshot', 'e'));
        $indeximage_select -> addOptionArray($graph_array);
        $indeximage_select -> setExtra("onchange='showImgSelected(\"image\", \"screenshot\", \"" . $xoopsModuleConfig['screenshots'] . "\", \"\", \"" . XOOPS_URL . "\")'");
        $indeximage_tray = new XoopsFormElementTray(_AM_WFD_FILE_SHOTIMAGE, '&nbsp;');
        $indeximage_tray -> addElement($indeximage_select);
        if ($this->getVar('screenshot') != "")
        {
            $indeximage_tray -> addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/" . $xoopsModuleConfig['screenshots'] . "/" . $this->getVar('screenshot', 'e') . "' name='image' id='image' alt='' />"));
        }
        else
        {
            $indeximage_tray -> addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
        }
        $sform -> addElement($indeximage_tray);

        $sform -> insertBreak(sprintf(_AM_WFD_FILE_MUSTBEVALID, "<b>" .  $xoopsModuleConfig['screenshots'] . "</b>"), "even");

        $publishtext = ($this->isNew() || $this->getVar('published') == 0) ? _AM_WFD_FILE_SETPUBLISHDATE : _AM_WFD_FILE_SETNEWPUBLISHDATE;
        if ($this->getVar('published') > time())
        {
            $publishtext = _AM_WFD_FILE_SETPUBDATESETS;
        }
        $ispublished = ($this->getVar('published') > time()) ? 1 : 0 ;
        $publishdates = ($this->getVar('published') > time()) ? _AM_WFD_FILE_PUBLISHDATESET . formatTimestamp($this->getVar('published', 'e'), "Y-m-d H:s") : _AM_WFD_FILE_SETDATETIMEPUBLISH;
        $publishdate_checkbox = new XoopsFormCheckBox('', 'publishdateactivate', $ispublished);
        $publishdate_checkbox -> addOption(1, $publishdates . "<br /><br />");

        if (!$this->isNew())
        {
            $sform -> addElement(new XoopsFormHidden('was_published', $this->getVar('published', 'e')));
            $sform -> addElement(new XoopsFormHidden('was_expired', $this->getVar('expired', 'e')));
        }

        $publishdate_tray = new XoopsFormElementTray(_AM_WFD_FILE_PUBLISHDATE, '');
        $publishdate_tray -> addElement($publishdate_checkbox);
        $publishdate_tray -> addElement(new XoopsFormDateTime($publishtext, 'published', 15, $this->getVar('published', 'e')));
        $publishdate_tray -> addElement(new XoopsFormRadioYN(_AM_WFD_FILE_CLEARPUBLISHDATE, 'clearpublish', 0, ' ' . _YES . '', ' ' . _NO . ''));
        $sform -> addElement($publishdate_tray);

        $isexpired = ($this->getVar('expired', 'e') > time()) ? 1: 0 ;
        $expiredates = ($this->getVar('expired', 'e') > time()) ? _AM_WFD_FILE_EXPIREDATESET . formatTimestamp($this->getVar('expired'), 'Y-m-d H:s') : _AM_WFD_FILE_SETDATETIMEEXPIRE;
        $warning = ($this->getVar('published') > $this->getVar('expired') && $this->getVar('expired') > time()) ? _AM_WFD_FILE_EXPIREWARNING : '';
        $expiredate_checkbox = new XoopsFormCheckBox('', 'expiredateactivate', $isexpired);
        $expiredate_checkbox -> addOption(1, $expiredates . "<br /><br />");

        $expiredate_tray = new XoopsFormElementTray(_AM_WFD_FILE_EXPIREDATE . $warning, '');
        $expiredate_tray -> addElement($expiredate_checkbox);
        $expiredate_tray -> addElement(new XoopsFormDateTime(_AM_WFD_FILE_SETEXPIREDATE . "<br />", 'expired', 15, $this->getVar('expired')));
        $expiredate_tray -> addElement(new XoopsFormRadioYN(_AM_WFD_FILE_CLEAREXPIREDATE, 'clearexpire', 0, ' ' . _YES . '', ' ' . _NO . ''));
        $sform -> addElement($expiredate_tray);

        $filestatus_radio = new XoopsFormRadioYN(_AM_WFD_FILE_FILESSTATUS, 'offline', $this->getVar('offline', 'e'), ' ' . _YES . '', ' ' . _NO . '');
        $sform -> addElement($filestatus_radio);

        $up_dated = ($this->getVar('updated', 'e') == 0) ? 0 : 1;
        $file_updated_radio = new XoopsFormRadioYN(_AM_WFD_FILE_SETASUPDATED, 'up_dated', $up_dated, ' ' . _YES . '', ' ' . _NO . '');
        $sform -> addElement($file_updated_radio);

        if (!$this->isNew() && $this->getVar('published') == 0)
        {
            $approved = ($this->getVar('published') == 0) ? 0 : 1;
            $approve_checkbox = new XoopsFormCheckBox(_AM_WFD_FILE_EDITAPPROVE, "approved", 1);
            $approve_checkbox -> addOption(1, " ");
            $sform -> addElement($approve_checkbox);
        }

        if ($this->isNew())
        {
            $button_tray = new XoopsFormElementTray('', '');
            $button_tray -> addElement(new XoopsFormHidden('status', 1));
            $button_tray -> addElement(new XoopsFormHidden('notifypub', $this->getVar('notifypub', 'e')));
            $button_tray -> addElement(new XoopsFormHidden('op', 'addDownload'));
            $button_tray -> addElement(new XoopsFormButton('', '', _AM_WFD_BSAVE, 'submit'));
            $sform -> addElement($button_tray);
        }
        else
        {
            $button_tray = new XoopsFormElementTray('', '');
            $button_tray -> addElement(new XoopsFormHidden('lid', $this->getVar('lid')));
            $button_tray -> addElement(new XoopsFormHidden('status', 2));
            $hidden = new XoopsFormHidden('op', 'addDownload');
            $button_tray -> addElement($hidden);

            $butt_dup = new XoopsFormButton('', '', _AM_WFD_BMODIFY, 'submit');
            $butt_dup -> setExtra('onclick="this.form.elements.op.value=\'addDownload\'"');
            $button_tray -> addElement($butt_dup);

            $butt_dupct = new XoopsFormButton('', '', _AM_WFD_BDELETE, 'submit');
            $butt_dupct -> setExtra('onclick="this.form.elements.op.value=\'delDownload\'"');
            $button_tray -> addElement($butt_dupct);

            $butt_dupct2 = new XoopsFormButton('', '', _AM_WFD_BCANCEL, 'submit');
            $butt_dupct2 -> setExtra('onclick="this.form.elements.op.value=\'downloadsConfigMenu\'"');
            $button_tray -> addElement($butt_dupct2);
            $sform -> addElement($button_tray);
        }
        return $sform;
    }

    /**
    * Returns an array representation of the object
    *
    * @return array
    */
    function toArray() {
        $ret = array();
        $vars = $this->getVars();
        foreach (array_keys($vars) as $i) {
            $ret[$i] = $this->getVar($i);
        }
        return $ret;
    }
}

class WfdownloadsDownloadHandler extends XoopsPersistableObjectHandler {
    function WfdownloadsDownloadHandler($db) {
        $this->XoopsPersistableObjectHandler($db, 'wfdownloads_downloads', 'WfdownloadsDownload', 'lid', 'title');
    }

    /**
	 * Get maximum published date from a criteria
	 *
	 * @param CriteriaElement $criteria
	 * @return mixed
	 */
	function getMaxPublishdate($criteria = null) {
		if (isset($criteria) && is_subclass_of($criteria, 'criteriaelement')) {
            if ($criteria->groupby != "") {
                $groupby = true;
                $field = $criteria->groupby.", "; //Not entirely secure unless you KNOW that no criteria's groupby clause is going to be mis-used
            }
        }
		$sql = "SELECT ".$field."MAX(published) FROM ".$this->table;
		if (is_object($criteria)) {
			$sql .= " ".$criteria->renderWhere();
			if ($criteria->groupby != "") {
				$sql .= $criteria->getGroupby();
			}
		}
		$result = $this->db->query($sql);
		if (!$result) {
			return 0;
		}
		if ($groupby == false) {
			list($count) = $this->db->fetchRow($result);
			return $count;
		}
		else {
			$ret = array();
			while (list($id, $count) = $this->db->fetchRow($result)) {
				$ret[$id] = $count;
			}
			return $ret;
		}
	}

    /**
	 * Get criteria for active downloads
	 *
	 * @return CriteriaElement
	 */
    function getActiveCriteria() {
        $criteria = new CriteriaCompo(new Criteria('offline', 0));
        $criteria->add(new Criteria('published', 0, '>'));
        $criteria->add(new Criteria('published', time(), "<="));
        $expired_criteria = new CriteriaCompo(new Criteria('expired', 0));
        $expired_criteria->add(new Criteria("expired", time(), ">="), "OR");
        $criteria->add($expired_criteria);

        // add criteria for categories that the user has permissions for
        global $xoopsUser;
        $wfModule = wfdownloads_getModuleInfo();
        $gperm_handler = xoops_gethandler('groupperm');
        $groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
        $mid = $wfModule->getVar('mid');
        $categoryids = $gperm_handler->getItemIds('WFDownCatPerm', $groups, $mid);
        $criteria->add(new Criteria('cid', "(".implode(',', $categoryids).")", "IN"));
        return $criteria;
    }

    /**
	 * Get array of active downloads with optional additional criteria
	 *
	 * @param CriteriaCompo $crit Additional criteria
	 * @return array
	 */
    function getActiveDownloads($crit = null) {
        if (is_object($crit)) {
            $criteria = $crit;
        }
        else {
            $criteria = new CriteriaCompo();
        }
        $active_crit = $this->getActiveCriteria();
        $criteria->add($active_crit);
        return $this->getObjects($criteria);
    }

    /**
	 * Get count of active downloads
	 *
	 * @param CriteriaElement $crit Additional criteria
	 * @return array/int
	 */
    function getActiveCount($crit = null) {
        $criteria = $this->getActiveCriteria();
        if (is_object($crit)) {
            $criteria->add($crit);
        }
        return $this->getCount($criteria);
    }

    /**
	 * Increment hit counter for a download
	 *
	 * @param int $lid
	 * @return bool
	 */
    function incrementHits($lid) {
    	$sql = "UPDATE ".$this->table." SET hits=hits+1 WHERE lid=".intval($lid);
    	return $this->db->queryF($sql);
    }

    function delete($download, $force = false) {
        if (parent::delete($download, $force)) {
            global $xoopsModule;
            $criteria = new Criteria("lid", $download->getVar('lid'));
            $rating_handler = xoops_getmodulehandler('rating', 'wfdownloads');
            $rating_handler->deleteAll($criteria);
			$mirror_handler = xoops_getmodulehandler('mirror', 'wfdownloads');
            $mirror_handler->deleteAll($criteria);
			$review_handler = xoops_getmodulehandler('review', 'wfdownloads');
			$review_handler->deleteAll($criteria);
            $report_handler = xoops_getmodulehandler('report', 'wfdownloads');
            $report_handler->deleteAll($criteria);
            // delete comments
            xoops_comment_delete($xoopsModule -> getVar('mid'), $download->getVar('lid'));
            return true;
        }
        return false;
    }
}
?>