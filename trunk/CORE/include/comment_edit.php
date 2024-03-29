<?php
/**
 * XOOPS comment edit
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: comment_edit.php 4941 2010-07-22 17:13:36Z beckmi $
 */

if (!defined('XOOPS_ROOT_PATH') || !is_object($xoopsModule)) {
    die('Restricted access');
}

include_once $GLOBALS['xoops']->path('include/comment_constants.php');

if (('system' != $xoopsModule->getVar('dirname') && XOOPS_COMMENT_APPROVENONE == $xoopsModuleConfig['com_rule']) || (! is_object($xoopsUser) && ! $xoopsModuleConfig['com_anonpost']) || ! is_object($xoopsModule)) {
    redirect_header(XOOPS_URL . '/user.php', 1, _NOPERM);
}

xoops_loadLanguage('comment');

$com_id = isset($_GET['com_id']) ? intval($_GET['com_id']) : 0;
$com_mode = isset($_GET['com_mode']) ? htmlspecialchars(trim($_GET['com_mode']), ENT_QUOTES) : '';

if ($com_mode == '') {
    if (is_object($xoopsUser)) {
        $com_mode = $xoopsUser->getVar('umode');
    } else {
        $com_mode = $xoopsConfig['com_mode'];
    }
}

if (!isset($_GET['com_order'])) {
    if (is_object($xoopsUser)) {
        $com_order = $xoopsUser->getVar('uorder');
    } else {
        $com_order = $xoopsConfig['com_order'];
    }
} else {
    $com_order = intval($_GET['com_order']);
}

/**
 */
$comment_handler =& xoops_gethandler('comment');
$comment = &$comment_handler->get($com_id);
$dohtml = $comment->getVar('dohtml');
$dosmiley = $comment->getVar('dosmiley');
$dobr = $comment->getVar('dobr');
$doxcode = $comment->getVar('doxcode');
$com_icon = $comment->getVar('com_icon');
$com_itemid = $comment->getVar('com_itemid');
$com_title = $comment->getVar('com_title', 'e');
$com_text = $comment->getVar('com_text', 'e');
$com_pid = $comment->getVar('com_pid');
$com_status = $comment->getVar('com_status');
$com_rootid = $comment->getVar('com_rootid');

if ($xoopsModule->getVar('dirname') != 'system') {
    include $GLOBALS['xoops']->path('header.php');
    include $GLOBALS['xoops']->path('include/comment_form.php');
    include $GLOBALS['xoops']->path('footer.php');
} else {
    xoops_cp_header();
    include $GLOBALS['xoops']->path('include/comment_form.php');
    xoops_cp_footer();
}

?>