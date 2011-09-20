<?php
/**
 * $Id: mimetypes.php,v 1.4 2006/04/07 00:11:36 malanciault Exp $
 * Module: WF-Downloads
 * Version: v2.0.5a
 * Release Date: 26 july 2004
 * Author: WF-Sections
 * Licence: GNU
 */

include 'admin_header.php';

$op = isset($_REQUEST['op']) ? $_REQUEST['op'] : '';

/**
 * edit_mimetype()
 *
 * @param integer $mime_id
 * @return
 */
function edit_mimetype($mime_id = 0)
{
    $mime_id = intval($mime_id);
    $mime_handler = xoops_getmodulehandler('mimetype');

    if ($mime_id != 0)
    {
        $mimetype = $mime_handler->get($mime_id);
    }
    else {
        $mimetype = $mime_handler->create();
    }

    $forminfo = ($mime_id == 0) ? _AM_WFD_MIME_CREATEF : _AM_WFD_MIME_MODIFYF;
    $sform = new XoopsThemeForm($forminfo , "op", $_SERVER['REQUEST_URI']);
    $sform -> addElement(new XoopsFormText(_AM_WFD_MIME_EXTF, 'mime_ext', 5, 60, $mimetype->getVar('mime_ext', 'e')), true);
    $sform -> addElement(new XoopsFormText(_AM_WFD_MIME_NAMEF, 'mime_name', 50, 255, $mimetype->getVar('mime_name', 'e')), true);
    $sform -> addElement(new XoopsFormTextArea(_AM_WFD_MIME_TYPEF, 'mime_type', $mimetype->getVar('mime_types', 'e'), 7, 60));
    $madmin_radio = new XoopsFormRadioYN(_AM_WFD_MIME_ADMINF, 'mime_admin', $mimetype->getVar('mime_admin', 'e'), ' ' . _YES . '', ' ' . _NO . '');
    $sform -> addElement($madmin_radio);
    $muser_radio = new XoopsFormRadioYN(_AM_WFD_MIME_USERF, 'mime_user', $mimetype->getVar('mime_user', 'e'), ' ' . _YES . '', ' ' . _NO . '');
    $sform -> addElement($muser_radio);

    $button_tray = new XoopsFormElementTray('', '');
    $hidden = new XoopsFormHidden('op', 'save');
    $button_tray -> addElement($hidden);

    if (!$mime_id)
    {
        $butt_create = new XoopsFormButton('', '', _AM_WFD_MIME_CREATE, 'submit');
        $butt_create -> setExtra('onclick="this.form.elements.op.value=\'save\'"');
        $button_tray -> addElement($butt_create);

        $butt_clear = new XoopsFormButton('', '', _AM_WFD_MIME_CLEAR, 'reset');
        $button_tray -> addElement($butt_clear);

        $butt_cancel = new XoopsFormButton('', '', _AM_WFD_MIME_CANCEL, 'button');
        $butt_cancel -> setExtra('onclick="history.go(-1)"');
        $button_tray -> addElement($butt_cancel);
    }
    else
    {
        $sform->addElement(new XoopsFormHidden('mime_id', $mime_id));
        $butt_create = new XoopsFormButton('', '', _AM_WFD_MIME_MODIFY, 'submit');
        $butt_create -> setExtra('onclick="this.form.elements.op.value=\'save\'"');
        $button_tray -> addElement($butt_create);

        $butt_cancel = new XoopsFormButton('', '', _AM_WFD_MIME_CANCEL, 'button');
        $butt_cancel -> setExtra('onclick="history.go(-1)"');
        $button_tray -> addElement($butt_cancel);
    }

    $sform -> addElement($button_tray);
    $sform -> display();

    $iform = new XoopsThemeForm(_AM_WFD_MIME_FINDMIMETYPE, "op", $_SERVER['REQUEST_URI']);
    $iform -> addElement(new XoopsFormText(_AM_WFD_MIME_EXTFIND, 'fileext', 5, 60, ""), true);
    $button_tray = new XoopsFormElementTray('', '');
    $hidden = new XoopsFormHidden('op', 'openurl');
    $button_tray -> addElement($hidden);
    $butt_create = new XoopsFormButton('', '', _AM_WFD_MIME_FINDIT, 'submit');
    $butt_create -> setExtra('onclick="this.form.elements.op.value=\'openurl\'"');
    $button_tray -> addElement($butt_create);
    $iform -> addElement($button_tray);
    $iform -> display();
}

switch ($op)
{
    case 'openurl':
        $fileext = trim($_POST['fileext']);
        $url = "http://filext.com/detaillist.php?extdetail=" . $fileext . "";
        if (!headers_sent())
        {
            header("Location: $url");
        }
        else
        {
            echo "<meta http-equiv='refresh' content='0;url=$url target='_blank''>\r\n";
        }
        break;

    case 'update';
        $mime_id = (isset($_REQUEST['mime_id'])) ? intval($_REQUEST['mime_id']) : 0;

        $mime_handler = xoops_getmodulehandler('mimetype');
        $mimetype = $mime_handler->get($mime_id);

        if (isset($_REQUEST['admin']) && $_REQUEST['admin'] == 1)
        {
            if ($mimetype->getVar('mime_admin') == 1) {
                $mimetype->setVar('mime_admin', 0);
            }
            else {
                $mimetype->setVar('mime_admin', 1);
            }
        }
        if (isset($_REQUEST['user']) && $_REQUEST['user'] == 1)
        {
            if ($mimetype->getVar('mime_user') == 1) {
                $mimetype->setVar('mime_user', 0);
            }
            else {
                $mimetype->setVar('mime_user', 1);
            }
        }
        $result = $mime_handler->insert($mimetype, true);
        if (!$result)
        {
            trigger_error($error, E_USER_ERROR);
        }
        redirect_header("mimetypes.php?start=" . intval($_GET['start']) . "", 0, _AM_WFD_MIME_MODIFIED);
        break;

    case 'save':

        $mime_id = (isset($_POST['mime_id']) && $_POST['mime_id'] > 0) ? $_POST['mime_id'] : 0;

        $mime_handler = xoops_getmodulehandler('mimetype');
        $mimetype = $mime_handler->get($mime_id);
        $mimetype->setVar('mime_ext', $_POST['mime_ext']);
        $mimetype->setVar('mime_name', $_POST['mime_name']);
        $mimetype->setVar('mime_types', $_POST['mime_type']);
        $mimetype->setVar('mime_admin', intval($_POST['mime_admin']));
        $mimetype->setVar('mime_user', intval($_POST['mime_user']));

        $result = $mime_handler->insert($mimetype);
        $error = "Could not update mimetype information:";
        if (!$result)
        {
            trigger_error($error, E_USER_ERROR);
        }
        $dbupted = ($mime_id == 0) ? _AM_WFD_MIME_CREATED : _AM_WFD_MIME_MODIFIED;
        redirect_header("mimetypes.php", 1, $dbupted);
        break;

    case 'saveall':

		$mime_admin = (isset($_GET['admin']) && $_GET['admin'] == 1 ) ? $_GET['admin'] : 0;
        $mime_user = (isset($_GET['user']) && $_GET['user'] == 1) ? $_GET['user'] : 0;
		$type_all = intval($_GET['type_all']);

		$mime_handler = xoops_getmodulehandler('mimetype');
        if ($mime_admin == 1) {
            $field = "mime_admin";
		} else {
		    $field = "mime_user";
		}
		$result = $mime_handler->updateAll($field, $type_all);
        if (!$result)
        {
            $error = "Could not update mimetype information:";
            trigger_error($error, E_USER_ERROR);
        }
        redirect_header("mimetypes.php", 1, _AM_WFD_MIME_MODIFIED);
        break;

    case "delete":

        global $xoopsDB;

        $confirm = (isset($_POST['confirm'])) ? 1 : 0;

        $mime_handler = xoops_getmodulehandler('mimetype');
        $mimetype = $mime_handler->get($_REQUEST['mime_id']);
        if ($confirm)
        {
            if ($mime_handler->delete($mimetype))
            {
                redirect_header("mimetypes.php", 1, sprintf(_AM_WFD_MIME_MIMEDELETED, $mimetype->getVar('mime_name')));
            }
            else
            {
                $error = "" . _AM_WFD_EVENNEWS_DBERROR . ": <br /><br />" . $sql;
                trigger_error($error, E_USER_ERROR);
            }
            exit();
        }
        else
        {
            wfdownloads_xoops_cp_header();
            xoops_confirm(array('op' => 'delete', 'mime_id' => $mimetype->getVar('mime_id'), 'confirm' => 1), 'mimetypes.php', _AM_WFD_MIME_DELETETHIS . "<br /><br>" . $mimetype->getVar('mime_name'), _AM_WFD_MIME_DELETE);
            xoops_cp_footer();
        }
        break;

    case 'edit':
        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(7, _AM_WFD_MMIMETYPES);
        edit_mimetype($_GET['mime_id']);
        xoops_cp_footer();
        break;

    case 'main':
    default:

        Global $xoopsUser, $xoopsDB, $xoopsModuleConfig;
        $start = isset($_GET['start']) ? intval($_GET['start']) : 0;

        $mime_handler = xoops_getmodulehandler('mimetype');
        $criteria = new CriteriaCompo();
        $criteria->setSort("mime_name");
        $criteria->setStart($start);
        $criteria->setLimit(20);
        $mimetypes = $mime_handler->getObjects($criteria);
        $mime_num = $mime_handler->getCount();

        wfdownloads_xoops_cp_header();
        wfdownloads_adminMenu(7, _AM_WFD_MMIMETYPES);

	echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_MIME_MODIFYF . "</legend>\n
		<div style='padding: 8px;'>" . _AM_WFD_MIME_INFOTEXT . "</div>\n
		</fieldset>\n
		";

        edit_mimetype();

	echo "
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_MIME_ADMINF . "</legend>\n
		<div style='padding: 8px;'>" . _AM_WFD_MIME_ADMINFINFO . "</div>\n
		";

	    $allowedmimetypes = $usermimetypes = array();
	    foreach (array_keys($mimetypes) as $i) {
	        if ($mimetypes[$i]->getVar('mime_admin') == 1) {
	            $allowedmimetypes[] = $mimetypes[$i]->getVar('mime_ext');
	        }
	        if ($mimetypes[$i]->getVar('mime_user') == 1) {
	            $usermimetypes[] = $mimetypes[$i]->getVar('mime_ext');
	        }
	    }
	    if (count($allowedmimetypes) > 0)
        {
            echo "<div style='padding: 8px;'>" . implode('|', $allowedmimetypes) . "</div>";
        }
        else
        {
            echo "<div style='padding: 8px;'>" . _AM_WFD_MIME_NOMIMEINFO . "</div>";
        }
	echo "
		</fieldset><br />\n
		<fieldset><legend style='font-weight: bold; color: #900;'>" . _AM_WFD_MIME_USERF . "</legend>\n
		<div style='padding: 8px;'>" . _AM_WFD_MIME_USERFINFO . "</div>\n
		";

        if (count($usermimetypes) > 0)
        {
            echo "<div style='padding: 8px;'>" . implode('|', $usermimetypes) . "</div>";
        }
        else
        {
            echo "<div style='padding: 8px;'>" . _AM_WFD_MIME_NOMIMEINFO . "</div>";
        }
	echo "
		</fieldset><br />\n
		<table border='0' width='100%' cellpadding ='2' cellspacing='1' class='outer'>\n
		<tr>\n
		";

        $headingarray = array(_AM_WFD_MIME_ID, _AM_WFD_MIME_NAME, _AM_WFD_MIME_EXT, _AM_WFD_MIME_ADMIN, _AM_WFD_MIME_USER, _AM_WFD_MINDEX_ACTION);
        for($i = 0; $i <= count($headingarray)-1; $i++)
        {
            $align = ($i == 1) ? "left" : "center";
            echo "<td align='$align' class='bg3'><b>" . $headingarray[$i] . "</td>";
        }
        echo "</tr>";
        foreach (array_keys($mimetypes) as $i) {
            echo "<tr>";
            $image_array = array("<a href='mimetypes.php?op=edit&amp;mime_id=" . $mimetypes[$i]->getVar('mime_id') . "'>" . $imagearray['editimg'] . "</a>
				<a href='mimetypes.php?op=delete&amp;mime_id=" . $mimetypes[$i]->getVar('mime_id') . "'>" . $imagearray['deleteimg'] . "</a>");
            echo "<td align='center' class='head'>" . $mimetypes[$i]->getVar('mime_id') . "</td>";
            echo "<td class='even'>" . $mimetypes[$i]->getVar('mime_name') . "</td>";
            echo "<td align='center' class='even'>." . $mimetypes[$i]->getVar('mime_ext') . "</td>";

            $yes_admin_image = ($mimetypes[$i]->getVar('mime_admin')) ? $imagearray['online'] : $imagearray['offline'];
            $image_admin = "<a href='mimetypes.php?op=update&amp;admin=1&amp;mime_id=" . $mimetypes[$i]->getVar('mime_id') . "&amp;start=" . $start . "'>" . $yes_admin_image . "</a>";
            echo "<td align='center' width='10%' class='even'>" . $image_admin . "</td>";

            $yes_user_image = ($mimetypes[$i]->getVar('mime_user')) ? $imagearray['online'] : $imagearray['offline'];
            $image_user = "<a href='mimetypes.php?op=update&amp;user=1&amp;mime_id=" . $mimetypes[$i]->getVar('mime_id') . "&amp;start=" . $start . "'>" . $yes_user_image . "</a>";
            echo "<td align='center' width='10%' class='even'>" . $image_user . "</td>";
            echo "<td align='center' class='even'>";
            foreach ($image_array as $images)
            {
                echo $images;
            }
            echo "</td></tr>";
        }
	echo "
		<tr>\n
		<td align='center' class='head'></td>\n
		<td class='even'></td>\n
		<td align='center' class='even'></td>\n
		";

		$admin_imgon = "<a href='mimetypes.php?op=saveall&amp;admin=1&amp;type_all=1'>" . $imagearray['online'] . "</a>";
		$admin_imgoff = "<a href='mimetypes.php?op=saveall&amp;admin=1&amp;type_all=0'>" . $imagearray['offline'] . "</a>";
            echo "<td align='center' width='10%' class='even'>" . $admin_imgon ." ". $admin_imgoff. "</td>";

			$user_imgon = "<a href='mimetypes.php?op=saveall&amp;user=1&amp;type_all=1'>" . $imagearray['online'] . "</a>";
            $user_imgoff = "<a href='mimetypes.php?op=saveall&amp;user=1&amp;type_all=0'>" . $imagearray['offline'] . "</a>";
	echo "
		<td align='center' width='10%' class='even'>" . $user_imgon ." ". $user_imgoff. "</td>\n
		<td align='center' class='even'>\n
		</td></tr>\n
		</table>\n
		";

        include_once XOOPS_ROOT_PATH . '/class/pagenav.php';
        $page = ($mime_num > 20) ? _AM_WFD_MINDEX_PAGE : '';
        $pagenav = new XoopsPageNav($mime_num, 20, $start, 'start');
        echo "<div align='right' style='padding: 8px;'>" . $page . '' . $pagenav -> renderNav() . '</div>';
        xoops_cp_footer();
}

?>