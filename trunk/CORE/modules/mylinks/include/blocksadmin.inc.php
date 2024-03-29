<?php
// $Id: main.php,v 1.12 2004/01/06 09:36:20 okazu Exp $
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
// Author: Kazumi Ono (AKA onokazu)                                          //
// URL: http://www.myweb.ne.jp/, http://www.xoops.org/, http://jp.xoops.org/ //
// Project: The XOOPS Project                                                //
// ------------------------------------------------------------------------- //

if (!is_object($xoopsUser) || !is_object($xoopsModule) || !$xoopsUser->isAdmin($xoopsModule->mid())) {
    exit('Access Denied');
}
include_once XOOPS_ROOT_PATH.'/class/xoopsblock.php';
include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blocksadmin.php';

$op = 'list';

if (!empty($_POST['op'])) {
    $op = $_POST['op'];
}
if (!empty($_POST['bid'])) {
    $bid = intval($_POST['bid']);
}

if (isset($_GET['op'])) {
    if ($_GET['op'] == 'edit' || $_GET['op'] == 'delete' || $_GET['op'] == 'delete_ok' || $_GET['op'] == 'clone' /* || $_GET['op'] == 'previewpopup'*/) {
        $op = $_GET['op'];
        $bid = isset($_GET['bid']) ? intval($_GET['bid']) : 0;
    }
}

if (isset($_POST['previewblock'])) {
/*
    if (!admin_refcheck("/modules/$admin_mydirname/admin/")) {
        exit('Invalid Referer');
    }
*/
    if (!$xoopsGTicket->check(true, 'myblocksadmin')) {
        redirect_header(XOOPS_URL.'/', 3, $xoopsGTicket->getErrors());
    }

    if(empty($bid)) {
        die('Invalid bid.');
    }

    $bside = (!empty($_POST['bside'])) ? intval($_POST['bside']) : 0;
    $bweight = (!empty($_POST['bweight'])) ? intval($_POST['bweight']) : 0;
    $bvisible = (!empty($_POST['bvisible'])) ? intval($_POST['bvisible']) : 0;
    $bmodule = (!empty($_POST['bmodule'])) ? $_POST['bmodule'] : array();
    $btitle = (!empty($_POST['btitle'])) ? $_POST['btitle'] : '';
    $bcontent = (!empty($_POST['bcontent'])) ? $_POST['bcontent'] : '';
    $bctype = (!empty($_POST['bctype'])) ? $_POST['bctype'] : '';
    $bcachetime = (!empty($_POST['bcachetime'])) ? intval($_POST['bcachetime']) : 0;

    xoops_cp_header();
    include_once XOOPS_ROOT_PATH.'/class/template.php';
    $xoopsTpl = new XoopsTpl();
    $xoopsTpl->xoops_setCaching(0);
    $block['bid'] = $bid;

    if ($op == 'clone_ok') {
        $block['form_title'] = _AM_CLONEBLOCK;
        $block['submit_button'] = _CLONE;
        $myblock = new XoopsBlock();
        $myblock->setVar('block_type', 'C');
    } else {
        $op = 'update';
        $block['form_title'] = _AM_EDITBLOCK;
        $block['submit_button'] = _SUBMIT;
        $myblock = new XoopsBlock($bid);
        $block['name'] = $myblock->getVar('name');
    }

    $myts =& MyTextSanitizer::getInstance();
    $myblock->setVar('title', $myts->stripSlashesGPC($btitle));
    $myblock->setVar('content', $myts->stripSlashesGPC($bcontent));
/*
    $dummyhtml = '<html><head><meta http-equiv="content-type" content="text/html; charset='._CHARSET.'" /><meta http-equiv="content-language" content="'._LANGCODE.'" /><title>'.$xoopsConfig['sitename'].'</title><link rel="stylesheet" type="text/css" media="all" href="'.getcss($xoopsConfig['theme_set']).'" /></head><body><table><tr><th>'.$myblock->getVar('title').'</th></tr><tr><td>'.$myblock->getContent('S', $bctype).'</td></tr></table></body></html>';
    $dummyfile = '_dummyfile_'.time().'.html';
    $fp = fopen(XOOPS_CACHE_PATH.'/'.$dummyfile, 'w');
    fwrite($fp, $dummyhtml);
    fclose($fp);
*/
    $block['edit_form'] = false;
    $block['template'] = '';
    $block['op'] = $op;
    $block['side'] = $bside;
    $block['weight'] = $bweight;
    $block['visible'] = $bvisible;
    $block['title'] = $myblock->getVar('title', 'E');
    $block['content'] = $myblock->getVar('content','n');
    $block['modules'] =& $bmodule;
    $block['ctype'] = isset($bctype) ? $bctype : $myblock->getVar('c_type');
    $block['is_custom'] = true;
    $block['cachetime'] = intval($bcachetime);
    echo '<a href="myblocksadmin.php">'. _AM_MYLINKS_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . $block['form_title'] . '<br /><br />';
    include dirname(__FILE__) . '/../admin/myblockform.php'; //GIJ
    //echo '<a href="admin.php?fct=blocksadmin">'. _AM_MYLINKS_BADMIN .'</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'.$block['form_title'].'<br /><br />';
    //include XOOPS_ROOT_PATH.'/modules/system/admin/blocksadmin/blockform.php';
    $xoopsGTicket->addTicketXoopsFormElement($form, __LINE__, 1800, 'myblocksadmin'); //GIJ
    $form->display();

    $original_level = error_reporting(E_ALL);
    echo "<table width='100%' class='outer' cellspacing='1'>\n"
       . "  <tr>\n"
       . "    <th>" . $myblock->getVar('title') . "</th>\n"
       . "  </tr>\n"
       . "  <tr>\n"
       . "    <td class='odd'>" . $myblock->getContent('S', $bctype) . "</td>\n"
       . "  </tr>\n"
       . "</table>\n";
    error_reporting($original_level);

    xoops_cp_footer();
/*
    echo '<script type="text/javascript">
    preview_window = openWithSelfMain("'.XOOPS_URL.'/modules/system/admin.php?fct=blocksadmin&op=previewpopup&file='.$dummyfile.'", "popup", 250, 200);
    </script>';
*/
    exit();
}

/*
  if ($op == 'previewpopup') {
        if ( !admin_refcheck("/modules/$admin_mydirname/admin/") ) {
            exit('Invalid Referer');
        }
        $file = str_replace('..', '', XOOPS_CACHE_PATH.'/'.trim($_GET['file']));
        if (file_exists($file)) {
            include $file;
            @unlink($file);
        }
        exit();
  }
*/

/*
if ( $op == "list" ) {
    xoops_cp_header();
    list_blocks();
    xoops_cp_footer();
    exit();
}
*/

if ($op == 'order') {
  //if ( !admin_refcheck("/modules/$admin_mydirname/admin/") ) {
  //  exit('Invalid Referer');
  //}
    if (!$xoopsGTicket->check(true, 'myblocksadmin')) {
        redirect_header(XOOPS_URL.'/', 3, $xoopsGTicket->getErrors());
    }
    if (!empty($_POST['side'])) {
        $side = $_POST['side'];
    }
//  if ( !empty($_POST['weight']) ) { $weight = $_POST['weight']; }
    if (!empty($_POST['visible'])) {
        $visible = $_POST['visible'];
    }
/*
    if (!empty($_POST['oldside'])) {
      $oldside = $_POST['oldside'];
  }
    if (!empty($_POST['oldweight'])) {
      $oldweight = $_POST['oldweight'];
  }
    if (!empty($_POST['oldvisible'])) {
      $oldvisible = $_POST['oldvisible'];
  }
*/
    $bid = (!empty($_POST['bid'])) ? $_POST['bid'] : array();
  // GIJ start
    foreach (array_keys($bid) as $i) {
        if ($side[$i] < 0) {
            $visible[$i] = 0;
            $side[$i] = -1;
        } else {
            $visible[$i] = 1;
        }

        $bmodule = (isset($_POST['bmodule'][$i]) && is_array($_POST['bmodule'][$i])) ? $_POST['bmodule'][$i] : array(-1);
        myblocksadmin_update_block($i, $side[$i], $_POST['weight'][$i], $visible[$i], $_POST['title'][$i], null , null , $_POST['bcachetime'][$i], $bmodule, array());

//        if ( $oldweight[$i] != $weight[$i] || $oldvisible[$i] != $visible[$i] || $oldside[$i] != $side[$i] )
//            order_block($bid[$i], $weight[$i], $visible[$i], $side[$i]);
    }
    $query4redirect = '?dirname=' . urlencode( strip_tags( substr( $_POST['query4redirect'], 9 ) ) );
    redirect_header("myblocksadmin.php$query4redirect", 1, _AM_DBUPDATED);
  // GIJ end
    exit();
}


if ( $op == 'order2' ) {
    if (!$xoopsGTicket->check( true , 'myblocksadmin')) {
        redirect_header(XOOPS_URL.'/', 3, $xoopsGTicket->getErrors());
    }

    if (isset($_POST['addblock']) && is_array($_POST['addblock'])) {
        // addblock
        foreach ($_POST['addblock'] as $bid => $val) {
            myblocksadmin_update_blockinstance(0, 0, 0, 0, '', null, null, 0, array(), array(), intval( $bid ));
        }
    } else {

        // else change order
        if (!empty($_POST['side'])) {
            $side = $_POST['side'];
        }
        if (!empty($_POST['visible'])) {
            $visible = $_POST['visible'];
        }
        $id = (!empty($_POST['id'])) ? $_POST['id'] : array();

        foreach (array_keys($id) as $i) {
            // separate side and visible
            if ($side[$i] < 0) {
                $visible[$i] = 0;
                $side[$i] = -1;  // for not to destroy the original position
            } else {
                $visible[$i] = 1;
            }

            $bmodule = (isset($_POST['bmodule'][$i]) && is_array($_POST['bmodule'][$i])) ? $_POST['bmodule'][$i] : array(-1);
            myblocksadmin_update_blockinstance($i, $side[$i], $_POST['weight'][$i], $visible[$i], $_POST['title'][$i], null, null, $_POST['bcachetime'][$i], $bmodule, array());
        }
    }

    $query4redirect = '?dirname=' . urlencode(strip_tags(substr($_POST['query4redirect'], 9)));
    redirect_header("myblocksadmin.php$query4redirect", 1, _MD_MYLINKS_AM_DBUPDATED);
    exit;
}

/* if ( $op == 'save' ) {
  if ( !admin_refcheck("/modules/$admin_mydirname/admin/") ) {
    exit('Invalid Referer');
  }
  if ( ! $xoopsGTicket->check( true , 'myblocksadmin' ) ) {
    redirect_header(XOOPS_URL.'/',3,$xoopsGTicket->getErrors());
  }
  if ( !empty($_POST['bside']) ) { $bside = intval($_POST['bside']); } else { $bside = 0; }
  if ( !empty($_POST['bweight']) ) { $bweight = intval($_POST['bweight']); } else { $bweight = 0; }
  if ( !empty($_POST['bvisible']) ) { $bvisible = intval($_POST['bvisible']); } else { $bvisible = 0; }
  if ( !empty($_POST['bmodule']) ) { $bmodule = $_POST['bmodule']; } else { $bmodule = array(); }
  if ( !empty($_POST['btitle']) ) { $btitle = $_POST['btitle']; } else { $btitle = ""; }
  if ( !empty($_POST['bcontent']) ) { $bcontent = $_POST['bcontent']; } else { $bcontent = ""; }
  if ( !empty($_POST['bctype']) ) { $bctype = $_POST['bctype']; } else { $bctype = ""; }
  if ( !empty($_POST['bcachetime']) ) { $bcachetime = intval($_POST['bcachetime']); } else { $bcachetime = 0; }
  save_block($bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bmodule, $bcachetime);
  exit();
} */

if ('update' ==$op) {
/*
    if (!admin_refcheck("/modules/$admin_mydirname/admin/")) {
      exit('Invalid Referer');
    }
*/
    if (!$xoopsGTicket->check(true, 'myblocksadmin')) {
        redirect_header(XOOPS_URL.'/', 3, $xoopsGTicket->getErrors());
    }
/*
    if ( !empty($_POST['bside']) ) { $bside = intval($_POST['bside']); } else { $bside = 0; }
    if ( !empty($_POST['bweight']) ) { $bweight = intval($_POST['bweight']); } else { $bweight = 0; }
    if ( !empty($_POST['bvisible']) ) { $bvisible = intval($_POST['bvisible']); } else { $bvisible = 0; }
    if ( !empty($_POST['btitle']) ) { $btitle = $_POST['btitle']; } else { $btitle = ""; }
    if ( !empty($_POST['bcontent']) ) { $bcontent = $_POST['bcontent']; } else { $bcontent = ""; }
    if ( !empty($_POST['bctype']) ) { $bctype = $_POST['bctype']; } else { $bctype = ""; }
    if ( !empty($_POST['bcachetime']) ) { $bcachetime = intval($_POST['bcachetime']); } else { $bcachetime = 0; }
    if ( !empty($_POST['bmodule']) ) { $bmodule = $_POST['bmodule']; } else { $bmodule = array(); }
    if ( !empty($_POST['options']) ) { $options = $_POST['options']; } else { $options = array(); }
    update_block($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodule, $options);
*/

    $bcachetime = isset($_POST['bcachetime']) ? intval($_POST['bcachetime']) : 0;
    $options = isset($_POST['options']) ? $_POST['options'] : array();
    $bcontent = isset($_POST['bcontent']) ? $_POST['bcontent'] : '';
    $bctype = isset($_POST['bctype']) ? $_POST['bctype'] : '';
    $bmodule = (isset($_POST['bmodule']) && is_array($_POST['bmodule'])) ? $_POST['bmodule'] : array(-1); // GIJ +
    $msg = myblocksadmin_update_block($_POST['bid'], $_POST['bside'], $_POST['bweight'], $_POST['bvisible'], $_POST['btitle'], $bcontent, $bctype, $bcachetime, $bmodule, $options); // GIJ !
    redirect_header('myblocksadmin.php', 1, $msg);
}


if ('delete_ok' == $op) {
/*
    if ( !admin_refcheck("/modules/$admin_mydirname/admin/") ) {
      exit('Invalid Referer');
    }
*/
    if (!$xoopsGTicket->check(true, 'myblocksadmin')) {
        redirect_header(XOOPS_URL.'/', 3, $xoopsGTicket->getErrors());
    }
    // delete_block_ok($bid); GIJ imported from blocksadmin.php
    $myblock = new XoopsBlock($bid);
    if ('D' != $myblock->getVar('block_type') && 'C' != $myblock->getVar('block_type')) {
        redirect_header('myblocksadmin.php', 4, 'Invalid block');
        exit();
    }
    $myblock->delete();
    if ('' != $myblock->getVar('template') && !defined('XOOPS_ORETEKI')) {
        $tplfile_handler =& xoops_gethandler('tplfile');
        $btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $bid);
        if (count($btemplate) > 0) {
            $tplman->delete($btemplate[0]);
        }
    }
    redirect_header('myblocksadmin.php', 1, _AM_DBUPDATED);
    // end of delete_block_ok() GIJ
    exit();
}

if ('delete' == $op) {
    xoops_cp_header();
    // delete_block($bid); GIJ imported from blocksadmin.php
    $myblock = new XoopsBlock($bid);
    if ('S' == $myblock->getVar('block_type')) {
        $message = _AM_SYSTEMCANT;
        redirect_header('admin.php?fct=blocksadmin', 4, $message);
        exit();
    } elseif ('M' == $myblock->getVar('block_type')) {
        $message = _AM_MODULECANT;
        redirect_header('admin.php?fct=blocksadmin', 4, $message);
        exit();
    } else {
        xoops_confirm(array('fct' => 'blocksadmin', 'op' => 'delete_ok', 'bid' => $myblock->getVar('bid')) + $xoopsGTicket->getTicketArray( __LINE__ , 1800 , 'myblocksadmin' ), 'admin.php', sprintf(_AM_RUSUREDEL, $myblock->getVar('title')));
    }
    // end of delete_block() GIJ
    xoops_cp_footer();
    exit();
}

if ('edit' == $op) {

    xoops_cp_header();
    // edit_block($bid); GIJ imported from blocksadmin.php
    $myblock = new XoopsBlock($bid);

    $db =& Database::getInstance();
    $sql = 'SELECT module_id FROM '.$db->prefix('block_module_link').' WHERE block_id='.intval($bid);
    $result = $db->query($sql);
    $modules = array();
    while ($row = $db->fetchArray($result)) {
        $modules[] = intval($row['module_id']);
    }
    $is_custom = ('C' == $myblock->getVar('block_type') || 'E' == $myblock->getVar('block_type')) ? true : false;
    $block = array('form_title' => _AM_EDITBLOCK, 'name' => $myblock->getVar('name'), 'side' => $myblock->getVar('side'), 'weight' => $myblock->getVar('weight'), 'visible' => $myblock->getVar('visible'), 'title' => $myblock->getVar('title','E'), 'content' => $myblock->getVar('content','n'), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar('c_type'), 'cachetime' => $myblock->getVar('bcachetime'), 'op' => 'update', 'bid' => $myblock->getVar('bid'), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar('template'), 'options' => $myblock->getVar('options'), 'submit_button' => _SUBMIT);

    echo '<a href="myblocksadmin.php">' . _AM_MYLINKS_BADMIN . '</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;' . _AM_EDITBLOCK . '<br /><br />';
    include dirname(__FILE__) . '/../admin/myblockform.php'; //GIJ
    $xoopsGTicket->addTicketXoopsFormElement($form, __LINE__, 1800, 'myblocksadmin'); //GIJ
    $form->display();
    // end of edit_block() GIJ
    xoops_cp_footer();
    exit();
}


if ('clone' == $op) {
    xoops_cp_header();
    $myblock = new XoopsBlock($bid);

    $db =& Database::getInstance();
    $sql = 'SELECT module_id FROM ' . $db->prefix('block_module_link') . ' WHERE block_id=' . intval($bid);
    $result = $db->query($sql);
    $modules = array();
    while ($row = $db->fetchArray($result)) {
        $modules[] = intval($row['module_id']);
    }
    $is_custom = ('C' == $myblock->getVar('block_type') || 'E' == $myblock->getVar('block_type')) ? true : false;
    $block = array('form_title' => _AM_CLONEBLOCK, 'name' => $myblock->getVar('name'), 'side' => $myblock->getVar('side'), 'weight' => $myblock->getVar('weight'), 'visible' => $myblock->getVar('visible'), 'content' => $myblock->getVar('content', 'N'), 'title' => $myblock->getVar('title','E'), 'modules' => $modules, 'is_custom' => $is_custom, 'ctype' => $myblock->getVar('c_type'), 'cachetime' => $myblock->getVar('bcachetime'), 'op' => 'clone_ok', 'bid' => $myblock->getVar('bid'), 'edit_form' => $myblock->getOptions(), 'template' => $myblock->getVar('template'), 'options' => $myblock->getVar('options'), 'submit_button' => _CLONE);
    echo '<a href="myblocksadmin.php">' . _AM_MYLINKS_BADMIN . '</a>&nbsp;<span style="font-weight:bold;">&raquo;&raquo;</span>&nbsp;'. _AM_CLONEBLOCK . '<br /><br />';
    include dirname(__FILE__) . '/../admin/myblockform.php';
    $xoopsGTicket->addTicketXoopsFormElement($form, __LINE__, 1800, 'myblocksadmin'); //GIJ
    $form->display();
    xoops_cp_footer();
    exit();
}


if ('clone_ok' == $op) {
    // Ticket Check
    if (!$xoopsGTicket->check(true, 'myblocksadmin')) {
        redirect_header(XOOPS_URL . '/', 3, $xoopsGTicket->getErrors());
    }

    $block = new XoopsBlock($bid);

    // block type check
    $block_type = $block->getVar('block_type');
    if( 'C' != $block_type && 'M' != $block_type && 'D' != $block_type) {
        redirect_header('myblocksadmin.php', 4, 'Invalid block');
    }

    if(empty($_POST['options'])) {
        $options = array();
    } elseif (is_array($_POST['options'])) {
        $options = $_POST['options'];
    } else {
        $options = explode('|', $_POST['options']);
    }

    // for backward compatibility
    // $cblock =& $block->clone(); or $cblock =& $block->xoopsClone();
    $cblock = new XoopsBlock();
    foreach ($block->vars as $k=>$v) {
        $cblock->assignVar($k, $v['value']);
    }
    $cblock->setNew();

    $myts =& MyTextSanitizer::getInstance();
    $cblock->setVar('side', $_POST['bside']);
    $cblock->setVar('weight', $_POST['bweight']);
    $cblock->setVar('visible', $_POST['bvisible']);
    $cblock->setVar('title', $_POST['btitle']);
    $cblock->setVar('content', @$_POST['bcontent']);
    $cblock->setVar('c_type', @$_POST['bctype']);
    $cblock->setVar('bcachetime', $_POST['bcachetime']);
    if (isset($options) && (count($options) > 0)) {
        $options = implode('|', $options);
        $cblock->setVar('options', $options);
    }
    $cblock->setVar('bid', 0);
    $cblock->setVar('block_type', $block_type == 'C' ? 'C' : 'D');
    $cblock->setVar('func_num', 255);
    $newid = $cblock->store();
    if (!$newid) {
        xoops_cp_header();
        $cblock->getHtmlErrors();
        xoops_cp_footer();
        exit();
    }
/*
  if ($cblock->getVar('template') != '') {
        $tplfile_handler =& xoops_gethandler('tplfile');
        $btemplate =& $tplfile_handler->find($GLOBALS['xoopsConfig']['template_set'], 'block', $bid);
        if (count($btemplate) > 0) {
            $tplclone =& $btemplate[0]->clone();
            $tplclone->setVar('tpl_id', 0);
            $tplclone->setVar('tpl_refid', $newid);
            $tplman->insert($tplclone);
        }
  }
*/
    $db =& Database::getInstance();
    $bmodule = (isset($_POST['bmodule']) && is_array($_POST['bmodule'])) ? $_POST['bmodule'] : array(-1); // GIJ +
    foreach( $bmodule as $bmid ) {
        $sql = "INSERT INTO " . $db->prefix('block_module_link') . " (block_id, module_id) VALUES ('{$newid}', '{$bmid}')";
        $db->query($sql);
    }

/*	global $xoopsUser;
  $groups =& $xoopsUser->getGroups();
  $count = count($groups);
  for ($i = 0; $i < $count; $i++) {
    $sql = "INSERT INTO ".$db->prefix('group_permission')." (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES (".$groups[$i].", ".$newid.", 1, 'block_read')";
    $db->query($sql);
  }
*/

    $sql = "SELECT gperm_groupid FROM " . $db->prefix('group_permission') . " WHERE gperm_name='block_read' AND gperm_modid='1' AND gperm_itemid='{$bid}'";
    $result = $db->query($sql);
    while (list($gid) = $db->fetchRow($result)) {
        $sql = "INSERT INTO " . $db->prefix('group_permission') . " (gperm_groupid, gperm_itemid, gperm_modid, gperm_name) VALUES ({$gid}, {$newid}, 1, 'block_read')";
        $db->query($sql);
    }

  redirect_header('myblocksadmin.php', 1, _AM_DBUPDATED);
}

// import from modules/system/admin/blocksadmin/blocksadmin.php
function myblocksadmin_update_block($bid, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodule, $options=array())
{
    global $xoopsConfig;
/*
    if (empty($bmodule)) {
        xoops_cp_header();
        xoops_error(sprintf(_AM_NOTSELNG, _AM_MYLINKS_VISIBLEIN));
        xoops_cp_footer();
        exit();
  }
*/
    $myblock = new XoopsBlock($bid);
    //$myblock->setVar('side', $bside); GIJ -
    if ($bside >= 0) {
        $myblock->setVar('side', $bside); // GIJ +
    }
    $myblock->setVar('weight', $bweight);
    $myblock->setVar('visible', $bvisible);
    $myblock->setVar('title', $btitle);
    if (isset($bcontent)) {
        $myblock->setVar('content', $bcontent);
    }
    if (isset($bctype)) {
        $myblock->setVar('c_type', $bctype);
    }
    $myblock->setVar('bcachetime', $bcachetime);
    if (isset($options) && (count($options) > 0)) {
        $options = implode('|', $options);
        $myblock->setVar('options', $options);
    }
    if ('C' == $myblock->getVar('block_type')) {
      switch ($myblock->getVar('c_type')) {
      case 'H':
          $name = _AM_CUSTOMHTML;
          break;
      case 'P':
          $name = _AM_CUSTOMPHP;
          break;
      case 'S':
          $name = _AM_CUSTOMSMILE;
          break;
      default:
          $name = _AM_CUSTOMNOSMILE;
          break;
      }
      $myblock->setVar('name', $name);
    }
    $msg = _AM_DBUPDATED;
    if (false != $myblock->store()) {
        $db =& Database::getInstance();
        $sql = sprintf("DELETE FROM %s WHERE block_id = %u", $db->prefix('block_module_link'), $bid);
        $db->query($sql);
        foreach ($bmodule as $bmid) {
            $sql = sprintf("INSERT INTO %s (block_id, module_id) VALUES (%u, %d)", $db->prefix('block_module_link'), $bid, intval($bmid));
            $db->query($sql);
        }
        include_once XOOPS_ROOT_PATH.'/class/template.php';
        $xoopsTpl = new XoopsTpl();
        $xoopsTpl->xoops_setCaching(2);
        if ('' != $myblock->getVar('template')) {
            if ($xoopsTpl->is_cached('db:'.$myblock->getVar('template'))) {
                if (!$xoopsTpl->clear_cache('db:'.$myblock->getVar('template'))) {
                    $msg = "Unable to clear cache for block ID: {$bid}";
                }
            }
        } else {
            if ($xoopsTpl->is_cached('db:system_dummy.html', 'block'.$bid)) {
                if (!$xoopsTpl->clear_cache('db:system_dummy.html', 'block'.$bid)) {
                    $msg = 'Unable to clear cache for block ID'.$bid;
                }
            }
        }
    } else {
        $msg = "Failed update of block. ID: {$bid}";
    }
    // redirect_header('admin.php?fct=blocksadmin&amp;t='.time(),1,$msg);
    // exit(); GIJ -
    return $msg; // GIJ +
}

// update block instance for 2.2
function myblocksadmin_update_blockinstance($id, $bside, $bweight, $bvisible, $btitle, $bcontent, $bctype, $bcachetime, $bmodule, $options=array(), $bid=null)
{
    global $xoopsDB;

    $instance_handler =& xoops_gethandler('blockinstance');
    $block_handler =& xoops_gethandler('block');
    if ($id > 0) {
        // update
        $instance =& $instance_handler->get($id);
        if ($bside >= 0) {
            $instance->setVar('side', $bside);
        }
        if (!empty($options)) {
            $instance->setVar('options', $options);
        }
    } else {
        // insert
        $instance =& $instance_handler->create();
        $instance->setVar('bid', $bid);
        $instance->setVar('side', $bside);
        $block = $block_handler->get($bid);
        $instance->setVar('options', $block->getVar("options"));
        if(empty($btitle)) {
            $btitle = $block->getVar("name");
        }
    }
    $instance->setVar('weight', $bweight);
    $instance->setVar('visible', $bvisible);
    $instance->setVar('title', $btitle);
    //if (isset($bcontent)) $instance->setVar('content', $bcontent);
    //if (isset($bctype)) $instance->setVar('c_type', $bctype);
    $instance->setVar('bcachetime', $bcachetime);

    if ($instance_handler->insert($instance)) {
        $GLOBALS['xoopsDB']->query("DELETE FROM " . $GLOBALS['xoopsDB']->prefix('block_module_link') . " WHERE block_id=" . $instance->getVar('instanceid'));
        foreach ($bmodule as $mid) {
            $page = explode('-', $mid);
            $mid = $page[0];
            $pageid = $page[1];
            $GLOBALS['xoopsDB']->query("INSERT INTO " . $GLOBALS['xoopsDB']->prefix('block_module_link')." VALUES (" . $instance->getVar('instanceid').", " . intval($mid) . ", " . intval($pageid) . ")");
        }
        return _MD_MYLINKS_AM_DBUPDATED;
    }
    return "Failed update of block instance. ID: {$id}";

/*		// NAME for CUSTOM BLOCK
    if ( $instance->getVar('block_type') == 'C') {
      switch ( $instance->getVar('c_type') ) {
      case 'H':
        $name = _AM_CUSTOMHTML;
        break;
      case 'P':
        $name = _AM_CUSTOMPHP;
        break;
      case 'S':
        $name = _AM_CUSTOMSMILE;
        break;
      default:
        $name = _AM_CUSTOMNOSMILE;
        break;
      }
      $instance->setVar('name', $name);
    }
*/
/*			// CLEAR TEMPLATE CACHE
      include_once XOOPS_ROOT_PATH.'/class/template.php';
      $xoopsTpl = new XoopsTpl();
      $xoopsTpl->xoops_setCaching(2);
      if ($instance->getVar('template') != '') {
        if ($xoopsTpl->is_cached('db:'.$instance->getVar('template'))) {
          if (!$xoopsTpl->clear_cache('db:'.$instance->getVar('template'))) {
            $msg = 'Unable to clear cache for block ID'.$bid;
          }
        }
      } else {
        if ($xoopsTpl->is_cached('db:system_dummy.html', 'block'.$bid)) {
          if (!$xoopsTpl->clear_cache('db:system_dummy.html', 'block'.$bid)) {
            $msg = 'Unable to clear cache for block ID'.$bid;
          }
        }
      }
*/
  }

  // TODO  edit2, delete2, customblocks
