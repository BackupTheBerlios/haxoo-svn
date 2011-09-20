<?php
/**
 * $Id: about.php,v 1.5 2006/04/06 17:55:23 mithyt2 Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';
$myts = & MyTextSanitizer :: getInstance();

global $xoopsModule;

wfdownloads_xoops_cp_header();

$versioninfo =& $xoopsModule;

wfdownloads_adminMenu(-1, _AM_WFD_ABOUT);
// Left headings...
	echo "
		<img src='" . XOOPS_URL . "/modules/wfdownloads/" . $versioninfo -> getInfo('image') . "' alt='' hspace='10' vspace='0' /></a>\n
		<div style='margin-top: 10px; color: #33538e; margin-bottom: 4px; font-size: 18px; line-height: 18px; font-weight: bold; display: block;'>" . $versioninfo -> getInfo('name') . " version " . $versioninfo -> getInfo('version') . "</div>\n
		<div>\n";
if ($versioninfo -> getInfo('author_realname') != '')
{
    $author_name = $versioninfo -> getInfo('author');
}
else
{
    $author_name = $versioninfo -> getInfo('author');
}
	echo "
		</div>\n
		<div>" . _MI_WFD_RELEASE . " " . $versioninfo -> getInfo('releasedate') . "</div>\n
		<div>" . _AM_WFD_BY . " " . $author_name . "</div>\n
		<div>" . $versioninfo -> getInfo('license') . "</div><br />\n";
// Author Information
$sform = new XoopsThemeForm(_MI_WFD_AUTHOR_INFO, "", "");

$sform -> addElement(new XoopsFormLabel(_MI_WFD_AUTHOR_NAME, $author_name));

$sform -> addElement(new XoopsFormLabel(_MI_WFD_AUTHOR_WEBSITE, "<a href='" . $versioninfo -> getInfo('author_website_url') . "' target='_blank'>" . $versioninfo -> getInfo('author_website_name') . "</a>"));

$sform -> addElement(new XoopsFormLabel(_MI_WFD_AUTHOR_EMAIL, "<a href='mailto:" . $versioninfo -> getInfo('author_email') . "'>" . $versioninfo -> getInfo('author_email') . "</a>"));

$sform -> addElement(new XoopsFormLabel(_MI_WFD_AUTHOR_DEVTEAM, $versioninfo -> getInfo('teammembers')));
$sform -> display();
// Author Information
$sform = new XoopsThemeForm(_MI_WFD_MODULE_INFO, "", "");

$sform -> addElement(new XoopsFormLabel(_MI_WFD_MODULE_STATUS, $versioninfo -> getInfo('status')));

//$sform -> addElement(new XoopsFormLabel(_MI_WFD_MODULE_DEMO, "<a href='" . $versioninfo -> getInfo('support_site_url') . "' target='_blank'>" . $versioninfo -> getInfo('support_site_name') . "</a>"));

$sform -> addElement(new XoopsFormLabel(_MI_WFD_MODULE_SUPPORT, "<a href='" . $versioninfo -> getInfo('support_site_url') . "' target='_blank'>" . $versioninfo -> getInfo('support_site_name') . "</a>"));

$sform -> addElement(new XoopsFormLabel(_MI_WFD_MODULE_BUG, "<a href='" . $versioninfo -> getInfo('submit_bug') . "' target='_blank'>" . "Submit a Bug" . "</a>"));

$sform -> addElement(new XoopsFormLabel(_MI_WFD_MODULE_FEATURE, "<a href='" . $versioninfo -> getInfo('submit_feature') . "' target='_blank'>" . "Request a new feature" . "</a>"));
$sform -> display();
// Author Information

$sform = new XoopsThemeForm(_MI_WFD_MODULE_DISCLAIMER, "", "");
ob_start();
echo "<div class='even'>" . $versioninfo -> getInfo('warning') . "</div>";
$sform -> addElement(new XoopsFormLabel('', ob_get_contents(), 0));
ob_end_clean();
$sform -> display();

$sform = new XoopsThemeForm(_MI_WFD_AUTHOR_CREDITS, "", "");
ob_start();
echo "<div class='even'>" . $versioninfo -> getInfo('author_credits') . "</div>";
$sform -> addElement(new XoopsFormLabel('', ob_get_contents(), 0));
ob_end_clean();
$sform -> display();

global $myts;

$file = '../changelog.txt';
if (@file_exists($file))
{
    $fp = @fopen($file, "r");
    $bugtext = @fread($fp, filesize($file));
    @fclose($file);
}

$sform = new XoopsThemeForm(_MI_WFD_AUTHOR_BUGFIXES, "", "");
ob_start();
echo "<div class='even'>".$myts->displayTarea($bugtext, true)."</div>";
$sform -> addElement(new XoopsFormLabel('', ob_get_contents(), 0));
ob_end_clean();
$sform -> display();
unset($file);

	echo "
		<div align = 'center'>" . _MI_WFD_COPYRIGHTIMAGE . "</div>\n
	";
xoops_cp_footer();

?>