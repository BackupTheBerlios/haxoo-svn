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

global $adminmenu;

$adminmenu[0]['title'] = _MI_EXTGALLERY_INDEX;
$adminmenu[0]['link']  = "admin/index.php";
$adminmenu[0]['icon']  = "images/icons/index.png";
$adminmenu[1]['title'] = _MI_EXTGALLERY_PUBLIC_CAT;
$adminmenu[1]['link']  = "admin/public-category.php";
$adminmenu[1]['icon']  = "images/icons/category.png";
$adminmenu[2]['title'] = _MI_EXTGALLERY_PHOTO;
$adminmenu[2]['link']  = "admin/photo.php";
$adminmenu[2]['icon']  = "images/icons/photo.png";
$adminmenu[3]['title'] = _MI_EXTGALLERY_PERMISSIONS;
$adminmenu[3]['link']  = "admin/perm-quota.php";
$adminmenu[3]['icon']  = "images/icons/perm.png";
$adminmenu[4]['title'] = _MI_EXTGALLERY_WATERMARK_BORDER;
$adminmenu[4]['link']  = "admin/watermark-border.php";
$adminmenu[4]['icon']  = "images/icons/watermark.png";
$adminmenu[5]['title'] = _MI_EXTGALLERY_SLIDESHOW;
$adminmenu[5]['link']  = "admin/slideshow.php";
$adminmenu[5]['icon']  = "images/icons/slideshow.png";
$adminmenu[6]['title'] = _MI_EXTGALLERY_EXTENTION;
$adminmenu[6]['link']  = "admin/extention.php";
$adminmenu[6]['icon'] = "images/icons/extention.png";
$adminmenu[7]['title'] = _MI_EXTGALLERY_ALBUM;
$adminmenu[7]['link']  = "admin/album.php";
$adminmenu[7]['icon']  = "images/icons/album.png";
$adminmenu[8]['title'] = _MI_EXTGALLERY_ABOUT;
$adminmenu[8]['link']  = "admin/about.php";
$adminmenu[8]['icon']  = "images/icons/about.png";

?>