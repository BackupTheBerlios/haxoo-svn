<?php
// $Id: myblockform.php,v 1.8 2003/03/10 13:32:05 okazu Exp $
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 XOOPS.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //

if(!defined('XOOPS_ROOT_PATH')) exit;

$usespaw = empty($_GET['usespaw']) ? false : true;

require_once XOOPS_ROOT_PATH."/class/xoopsformloader.php";
//$form = new XoopsThemeForm($block['form_title'], 'blockform', XOOPS_URL."/modules/blocksadmin/admin/admin.php" );
$form = new XoopsThemeForm($block['form_title'], 'blockform', "admin.php" );
if (isset($block['name'])) {
    $form->addElement(new XoopsFormLabel(_AM_NAME, $block['name']));
}
$side_select = new XoopsFormSelect(_AM_BLKTYPE, "bside", $block['side']);
$side_select->addOptionArray(array(0 => _AM_SBLEFT, 1 => _AM_SBRIGHT, 3 => _AM_CBLEFT, 4 => _AM_CBRIGHT, 5 => _AM_CBCENTER, ));
$form->addElement($side_select);
$form->addElement(new XoopsFormText(_AM_MYLINKS_WEIGHT, "bweight", 2, 5, $block['weight']));
$form->addElement(new XoopsFormRadioYN(_AM_VISIBLE, 'bvisible', $block['visible']));
$mod_select = new XoopsFormSelect(_AM_MYLINKS_VISIBLEIN, "bmodule", $block['modules'], 5, true);
$module_handler =& xoops_gethandler('module');
$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
$criteria->add(new Criteria('isactive', 1));
$module_list =& $module_handler->getList($criteria);
$module_list[-1] = _AM_TOPPAGE;
$module_list[0] = _AM_ALLPAGES;
ksort($module_list);
$mod_select->addOptionArray($module_list);
$form->addElement($mod_select);
$form->addElement(new XoopsFormText(_AM_MYLINKS_TITLE, 'btitle', 50, 255, $block['title']), false);

if ( $block['is_custom'] ) {

    // Custom Block's textarea
    $notice_for_tags = '<span style="font-size:x-small;font-weight:bold;">'._AM_USEFULTAGS.'</span><br /><span style="font-size:x-small;font-weight:normal;">'.sprintf(_AM_BLOCKTAG1, '{X_SITEURL}', XOOPS_URL.'/').'</span>';
    $current_op = @$_GET['op'] == 'clone' ? 'clone' : 'edit';
    $uri_to_myself = XOOPS_URL . "/modules/blocksadmin/admin/admin.php?fct=blocksadmin&amp;op=$current_op&amp;bid={$block['bid']}";

    //TODO:  change this to use XoopsEditor class
    // $can_use_spaw = check_browser_can_use_spaw();
    $can_use_spaw = false;  // v3.11 - set to false to prevent using spaw
    if ($usespaw && $can_use_spaw) {
        // SPAW Config
        include XOOPS_ROOT_PATH.'/common/spaw/spaw_control.class.php';
        ob_start();
        $sw = new SPAW_Wysiwyg('bcontent', $block['content']);
        $sw->show();
        $textarea = new XoopsFormLabel(_AM_CONTENT, ob_get_contents());
        $textarea->setDescription($notice_for_tags . "<br /><br /><a href='$uri_to_myself&amp;usespaw=0'>NORMAL</a>");
        ob_end_clean();
    } else {
        $myts =& MyTextSanitizer::getInstance();
        $textarea = new XoopsFormDhtmlTextArea(_AM_CONTENT, 'bcontent', $myts->htmlSpecialChars( $block['content'] ), 15, 70);
        if ($can_use_spaw) {
            $textarea->setDescription($notice_for_tags . "<br /><br /><a href='$uri_to_myself&amp;usespaw=1'>SPAW</a>");
        } else {
            $textarea->setDescription($notice_for_tags);
        }
    }
    $form->addElement($textarea, true);

    $ctype_select = new XoopsFormSelect(_AM_CTYPE, 'bctype', $block['ctype']);
    $ctype_select->addOptionArray(array('H' => _AM_HTML, 'P' => _AM_PHP, 'S' => _AM_AFWSMILE, 'T' => _AM_AFNOSMILE));
    $form->addElement($ctype_select);
} else {
    if ($block['template'] != '' && ! defined('XOOPS_ORETEKI') ) {
        $tplfile_handler =& xoops_gethandler('tplfile');
        $btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $block['bid']);
        if (count($btemplate) > 0) {
            $form->addElement(new XoopsFormLabel(_AM_CONTENT, '<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&op=edittpl&id='.$btemplate[0]->getVar('tpl_id').'">'._AM_EDITTPL.'</a>'));
        } else {
            $btemplate2 =& $tplfile_handler->find('default', 'block', $block['bid']);
            if (count($btemplate2) > 0) {
                $form->addElement(new XoopsFormLabel(_AM_CONTENT, '<a href="'.XOOPS_URL.'/modules/system/admin.php?fct=tplsets&op=edittpl&id='.$btemplate2[0]->getVar('tpl_id').'" target="_blank">'._AM_EDITTPL.'</a>'));
            }
        }
    }
    if ($block['edit_form'] != false) {
        $form->addElement(new XoopsFormLabel(_AM_OPTIONS, $block['edit_form']));
    }
}
$cache_select = new XoopsFormSelect(_AM_MYLINKS_BCACHETIME, 'bcachetime', $block['cachetime']);
$cache_select->addOptionArray(array('0' => _NOCACHE, '30' => sprintf(_SECONDS, 30), '60' => _MINUTE, '300' => sprintf(_MINUTES, 5), '1800' => sprintf(_MINUTES, 30), '3600' => _HOUR, '18000' => sprintf(_HOURS, 5), '86400' => _DAY, '259200' => sprintf(_DAYS, 3), '604800' => _WEEK, '2592000' => _MONTH));
$form->addElement($cache_select);
if (isset($block['bid'])) {
    $form->addElement(new XoopsFormHidden('bid', $block['bid']));
}
// $form->addElement(new XoopsFormHidden('options', $block['options']));
$form->addElement(new XoopsFormHidden('op', $block['op']));
$form->addElement(new XoopsFormHidden('fct', 'blocksadmin'));
$button_tray = new XoopsFormElementTray('', '&nbsp;');
if ($block['is_custom']) {
    $button_tray->addElement(new XoopsFormButton('', 'previewblock', _PREVIEW, "submit"));
}
$button_tray->addElement(new XoopsFormButton('', 'submitblock', $block['submit_button'], "submit"));
$form->addElement($button_tray);

/**
 *  checks browser compatibility with the control
 *  @return bool	true - can use spaw; false - cannot use spaw
 */
function check_browser_can_use_spaw()
{
    $browser = $_SERVER['HTTP_USER_AGENT'];
    // check if msie
    if (eregi("MSIE[^;]*", $browser, $msie)) {
        // get version
        if (eregi("[0-9]+\.[0-9]+", $msie[0], $version)) {
            // check version
            if ((float)$version[0] >= 5.5) {
                // finally check if it's not opera impersonating ie
                if (!eregi("opera", $browser)) {
                    return true;
                }
            }
        }
    }
    return false;
}