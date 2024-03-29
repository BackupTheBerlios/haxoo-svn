<?php
/**
 * Extended Language
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package         xLanguage
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: core.php 3333 2009-08-27 10:46:15Z trabis $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Xlanguage core preloads
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license         http://www.fsf.org/copyleft/gpl.html GNU public license
 * @author          trabis <lusopoemas@gmail.com>
 */
class XlanguageCorePreload extends XoopsPreloadItem
{
	function eventCoreIncludeCommonLanguage($args)
    {
         if (XlanguageCorePreload::isActive()) {
            global $xoopsConfig;
            include_once dirname(dirname(__FILE__)) . '/api.php';
         }
    }


	function isActive()
    {
        $module_handler =& xoops_getHandler('module');
        $module = $module_handler->getByDirname('xlanguage');
        return ($module && $module->getVar('isactive')) ? true : false;
    }
}
?>
