<?php
/**
 * Xoops Localization function
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license     GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package     core
 * @since       2.3.0
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id: xoopslocal.php 7364 2011-08-26 12:39:25Z trabis $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * XoopsLocalWrapper
 *
 */
class XoopsLocalWrapper
{
    function load($language = null)
    {
        if (class_exists('Xoopslocal')) {
            return true;
        }
        require_once $GLOBALS['xoops']->path('class/xoopslocal.php');
        //XoopsLocal is inside language file, let us load it
        xoops_loadLanguage('locale');

        return true;
    }
}

/**
 * Enter description here...
 *
 * @return unknown
 */
function xoops_local()
{
    // get parameters
    $func_args = func_get_args();
    $func = array_shift($func_args);
    // local method defined
    return call_user_func_array(array(
        'XoopsLocal' ,
        $func), $func_args);
}

XoopsLocalWrapper::load();
?>