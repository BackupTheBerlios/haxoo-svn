<?php
/**
 * ExtGallery Admin settings
 * Manage admin pages
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
 * @author      Zoullou (http://www.zoullou.net)
 * @package     ExtGallery
 * @version     $Id$
 */

function extgalleryAdminMenu($currentoption = 0, $breadcrumb = '')
{
	global $xoopsConfig;
	global $xoopsModule;
	if(file_exists(XOOPS_ROOT_PATH.'/modules/extgallery/language/'.$xoopsConfig['language'].'/modinfo.php')) {
		include_once XOOPS_ROOT_PATH.'/modules/extgallery/language/'.$xoopsConfig['language'].'/modinfo.php';
	} else {
		include_once XOOPS_ROOT_PATH.'/modules/extgallery/language/english/modinfo.php';
	}
}

?>