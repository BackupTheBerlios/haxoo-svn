<?php
/**
 * $Id: indexpage.php,v 1.3 2005/12/30 17:46:36 m0nty_ Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';

$op = isset($_REQUEST['op']) ?$_REQUEST['op'] : "default";

switch ($op)
{
    case "save":

        global $xoopsDB;

        $indexheading = $myts->addslashes($_POST['indexheading']);
        $indexheader = $myts->addslashes($_POST['indexheader']);
        $indexfooter = $myts->addslashes($_POST['indexfooter']);
		$indeximage = $myts->addslashes($_POST['indeximage']);
        $nohtml = isset($_POST['nohtml']);
        $nosmiley = isset($_POST['nosmiley']);
        $noxcodes = isset($_POST['noxcodes']);
        $noimages = isset($_POST['noimages']);
        $nobreak = isset($_POST['nobreak']);
        $indexheaderalign = $_POST['indexheaderalign'];
        $indexfooteralign = $_POST['indexfooteralign'];

        $xoopsDB->query("update " . $xoopsDB->prefix("wfdownloads_indexpage") . " set indexheading='$indexheading', indexheader='$indexheader', indexfooter='$indexfooter', indeximage='$indeximage', indexheaderalign='$indexheaderalign ', indexfooteralign='$indexfooteralign', nohtml='$nohtml', nosmiley='$nosmiley', noxcodes='$noxcodes', noimages='$noimages', nobreak='$nobreak' ");
        redirect_header("indexpage.php", 1, _AM_WFD_IPAGE_UPDATED);
        exit();

        break;

    default:

        include_once '../class/wfd_lists.php';
        include XOOPS_ROOT_PATH . '/class/xoopsformloader.php';

        global $xoopsModuleConfig, $xoopsDB;

        $result = $xoopsDB->query("SELECT indeximage, indexheading, indexheader, indexfooter, nohtml, nosmiley, noxcodes, noimages, nobreak, indexheaderalign, indexfooteralign FROM " . $xoopsDB->prefix('wfdownloads_indexpage') . " ");
        list($indeximage, $indexheading, $indexheader, $indexfooter, $nohtml, $nosmiley, $noxcodes, $noimages, $nobreak, $indexheaderalign, $indexfooteralign) = $xoopsDB->fetchrow($result);

        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(1, _AM_WFD_INDEXPAGE);

	echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_IPAGE_INFORMATION . "</legend>\n
		<div style='padding: 8px;'>" . _AM_WFD_MINDEX_PAGEINFOTXT . "</div>\n
		</fieldset>\n
		";

        $sform = new XoopsThemeForm(_AM_WFD_IPAGE_MODIFY, "op", xoops_getenv('PHP_SELF'));
        $sform->addElement(new XoopsFormText(_AM_WFD_IPAGE_CTITLE, 'indexheading', 60, 60, $indexheading), false);
        $graph_array = &WfsLists::getListTypeAsArray(XOOPS_ROOT_PATH . "/" . $xoopsModuleConfig['mainimagedir'], $type = "images");
        $indeximage_select = new XoopsFormSelect('', 'indeximage', $indeximage);
        $indeximage_select->addOptionArray($graph_array);
        $indeximage_select->setExtra("onchange='showImgSelected(\"image\", \"indeximage\", \"" . $xoopsModuleConfig['mainimagedir'] . "\", \"\", \"" . XOOPS_URL . "\")'");
        $indeximage_tray = new XoopsFormElementTray(_AM_WFD_IPAGE_CIMAGE, '&nbsp;');
        $indeximage_tray->addElement($indeximage_select);
        if (!empty($indeximage))
        {
            $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/".$xoopsModuleConfig['mainimagedir']."/" . $indeximage . "' name='image' id='image' alt='' />"));
        }
        else
        {
            $indeximage_tray->addElement(new XoopsFormLabel('', "<br /><br /><img src='" . XOOPS_URL . "/uploads/blank.gif' name='image' id='image' alt='' />"));
        }
        $sform->addElement($indeximage_tray);

        $sform->addElement(new XoopsFormDhtmlTextArea(_AM_WFD_IPAGE_CHEADING, 'indexheader', $indexheader, 15, 60));
        $headeralign_select = new XoopsFormSelect(_AM_WFD_IPAGE_CHEADINGA, "indexheaderalign", $indexheaderalign);
        $headeralign_select->addOptionArray(array("left" => _AM_WFD_IPAGE_CLEFT, "right" => _AM_WFD_IPAGE_CRIGHT, "center" => _AM_WFD_IPAGE_CCENTER));
        $sform->addElement($headeralign_select);
        $sform->addElement(new XoopsFormTextArea(_AM_WFD_IPAGE_CFOOTER, 'indexfooter', $indexfooter, 10, 60));
        $footeralign_select = new XoopsFormSelect(_AM_WFD_IPAGE_CFOOTERA, "indexfooteralign", $indexfooteralign);
        $footeralign_select->addOptionArray(array("left" => _AM_WFD_IPAGE_CLEFT, "right" => _AM_WFD_IPAGE_CRIGHT, "center" => _AM_WFD_IPAGE_CCENTER));
        $sform->addElement($footeralign_select);

        $options_tray = new XoopsFormElementTray(_AM_WFD_TEXTOPTIONS, '<br />');

        $html_checkbox = new XoopsFormCheckBox('', 'nohtml', $nohtml);
        $html_checkbox->addOption(1, _AM_WFD_ALLOWHTML);
        $options_tray->addElement($html_checkbox);

        $smiley_checkbox = new XoopsFormCheckBox('', 'nosmiley', $nosmiley);
        $smiley_checkbox->addOption(1, _AM_WFD_ALLOWSMILEY);
        $options_tray->addElement($smiley_checkbox);

        $xcodes_checkbox = new XoopsFormCheckBox('', 'noxcodes', $noxcodes);
        $xcodes_checkbox->addOption(1, _AM_WFD_ALLOWXCODE);
        $options_tray->addElement($xcodes_checkbox);

        $noimages_checkbox = new XoopsFormCheckBox('', 'noimages', $noimages);
        $noimages_checkbox->addOption(1, _AM_WFD_ALLOWIMAGES);
        $options_tray->addElement($noimages_checkbox);

        $breaks_checkbox = new XoopsFormCheckBox('', 'nobreak', $nobreak);
        $breaks_checkbox->addOption(1, _AM_WFD_ALLOWBREAK);
        $options_tray->addElement($breaks_checkbox);
        $sform->addElement($options_tray);

        $button_tray = new XoopsFormElementTray('', '');
        $hidden = new XoopsFormHidden('op', 'save');
        $button_tray->addElement($hidden);
        $button_tray->addElement(new XoopsFormButton('', 'post', _AM_WFD_BSAVE, 'submit'));
        $sform->addElement($button_tray);
        $sform->display();
        break;
}
xoops_cp_footer();

?>