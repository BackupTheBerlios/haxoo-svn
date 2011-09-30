<?php
/**
 * Name: xoops_version.php
 * Description:
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright::  The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license::    http://www.fsf.org/copyleft/gpl.html GNU public license
 * @package::    XOOPS
 * @module::     xoopsfaq
 * @subpackage::
 * @since::      2.3.0
 * @author::     John Neill
 * @version::    $Id: xoops_version.php 0000 10/04/2009 09:30:53 John Neill $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

$xfDirName = basename(dirname(__FILE__)) ;

/**
 * Module configs
 */
$modversion = array('name' => _MI_XOOPSFAQ_NAME,
                    'description' => _MI_XOOPSFAQ_DESC,
                    'author' => 'John Neill, Kazumi Ono',
                    'credits' => 'The Xoops Module Development Team',
                    'license' => 'GNU GPL 2.0',
                    'license_url' => "www.gnu.org/licenses/gpl-2.0.html/",
                    'version' => 1.23,
                    'module_status' => "Beta",
                    'official' => 1,
                    'help' => 'page=help',
                    'image' => 'images/slogo.png',
                    'dirname' => $xfDirName,
                    'dirmoduleadmin' => 'Frameworks/moduleclasses',
                    'icons16' => 'Frameworks/moduleclasses/icons/16',
                    'icons32' => 'Frameworks/moduleclasses/icons/32',    					

                    //about
                    'author_website_url' => "http://xoops.org",
                    'author_website_name' => "XOOPS",
                    'module_website_url' => "http://xoops.org",
                    'module_website_name' => "XOOPS",
                    'release_date' => '2011/05/11',
                    'min_php' => "5.2.0",
                    'min_xoops' => "2.5.0"				
					
           );

/**
 * Module Sql
 */
$modversion['sqlfile']['mysql'] = 'sql/mysql.sql';

/**
 * Module SQL Tables
 */
$modversion['tables'] = array('xoopsfaq_contents', 'xoopsfaq_categories') ;

/**
 * Module Admin
 */
$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

/** *
 * Admin Menu
 * Set to:
 *    0 if you do not want to display menu generated by system module
 *    1 if you want to display menu generated by system module
 */
$modversion['system_menu'] = 1;

/**
 * Module Main
 */
$modversion['hasMain'] = 1;

/**
 * Blocks
 */
$modversion['blocks'][] = array('file'        => 'xoopsfaq_rand.php',
                                'name'        => _MI_XOOPSFAQ_BNAME1,
                                'description' => _MI_XOOPSFAQ_BNAME1DESC,
                                'show_func'   => 'b_xoopsfaq_random_show',
                                'edit_func'   => 'b_xoopsfaq_rand_edit',
                                'options'     => '100',
                                'template'    => 'xoopfaq_block_rand.html'
                      );



/**
 * Module Search
 */
$modversion['hasSearch'] = 1;
$modversion['search']['file'] = 'include/search.inc.php';
$modversion['search']['func'] = 'xoopsfaq_search';

/**
 * Module Templates
 */
// $modversion['templates'][] = array('file' => 'xoopsfaq_index.html', 'description' => '');
// $modversion['templates'][] = array('file' => 'xoopsfaq_category.html', 'description' => '');

$i = 1;

$modversion["templates"][$i]["file"] = $xfDirName . "_index.html";
$modversion["templates"][$i]["description"]	= _MI_XOOPSFAQ_TPLDESC_INDEX;
$i++;
$modversion["templates"][$i]["file"] = $xfDirName . "_category.html";
$modversion["templates"][$i]["description"] = _MI_XOOPSFAQ_TPLDESC_CATEGORY;

/**
 * Module Comments
 */
// $modversion['hasComments'] = 1;
// $modversion['comments'][] = array('pageName' => 'index.php', 'itemName' => 'cat_id');

// Comments
$modversion['hasComments'] = 1;
$modversion['comments']['itemName'] = 'cat_id';
$modversion['comments']['pageName'] = 'index.php';

/**
 * Module configs
 */
xoops_load('XoopsEditorHandler');
$editor_handler = XoopsEditorHandler::getInstance();
$editorList = array_flip($editor_handler->getList());

$modversion['config'][] = array('name'        => 'use_wysiwyg',
                                'title'       => '_MI_XOOPSFAQ_EDITORS',
                                'description' => '_MI_XOOPSFAQ_EDITORS_DSC',
                                'formtype'    => 'select',
                                'valuetype'   => 'text',
                                'options'     => $editorList,
                                'default'     => 'dhtmltextarea');