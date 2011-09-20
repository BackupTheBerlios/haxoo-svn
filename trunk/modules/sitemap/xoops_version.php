<?php
/**
 * ****************************************************************************
 *  - SITEMAP module by chanoir, GIJOE, Francesco Mulassano
 *  - Licence GPL Copyright (c)
 *
 * 
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @license     GPL license
 * @author		chanoir, GIJOE, Francesco Mulassano 
 *
 * ****************************************************************************
 */

$modversion['name'] = "Sitemap";
$modversion['version'] = 1.02;
$modversion['description'] = _MI_SITEMAP_DESC;
$modversion['author'] = 'chanoir, GIJOE, Francesco Mulassano';
$modversion['author_website_url'] = "";
$modversion['author_website_name'] = "";
$modversion['credits'] = "Slickmap CSS by ASTUTEO (http://astuteo.com/slickmap/) ";
$modversion['license'] = "GPL";
$modversion["release_info"] = "release_note.txt";
$modversion["release_file"] = "";
$modversion["manual"] = "none";
$modversion["manual_file"] = "";
$modversion['image'] = "images/logo.png";
$modversion['dirname'] = "sitemap"; 
 


$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['hasMain'] = 1;

$modversion['templates'][1]['file'] = 'sitemap_inc_eachmodule.html';
$modversion['templates'][1]['description'] = '';

$modversion['templates'][2]['file'] = 'sitemap_index.html';
$modversion['templates'][2]['description'] = '';

$modversion['templates'][3]['file'] = 'sitemap_xml_google.html';
$modversion['templates'][3]['description'] = '';

$modversion['templates'][4]['file'] = 'sitemap_inc_eachmodule_slickmap.html';
$modversion['templates'][4]['description'] = '';



$modversion['blocks'][1]['file'] = 'sitemap_blocks.php';
$modversion['blocks'][1]['name'] = _MI_SITEMAP_BLOCKNAME ;
$modversion['blocks'][1]['description'] = _MI_SITEMAP_BLOCKDESC ;
$modversion['blocks'][1]['show_func'] = 'b_sitemap_show';
$modversion['blocks'][1]['edit_func'] = 'b_sitemap_edit';
$modversion['blocks'][1]['template'] = 'sitemap_block_show.html';
$modversion['blocks'][1]['options'] = '1';

$i = 1;

$modversion['config'][$i]['name'] = 'msgs';
$modversion['config'][$i]['title'] = '_MI_MESSAGE';
$modversion['config'][$i]['description'] = '_MI_MESSAGEEDSC';
$modversion['config'][$i]['formtype'] = 'textarea';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = _MI_SITEMAP_MESSAGE;

$i++;
$modversion['config'][$i]['name'] = 'show_subcategoris';
$modversion['config'][$i]['title'] = '_MI_SHOW_SUBCATEGORIES';
$modversion['config'][$i]['description'] = '_MI_SHOW_SUBCATEGORIESDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'show_sublink';
$modversion['config'][$i]['title'] = '_MI_SHOW_SUBLINK';
$modversion['config'][$i]['description'] = '_MI_SHOW_SUBLINKDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;

$i++;
$modversion['config'][$i]['name'] = 'alltime_guest';
$modversion['config'][$i]['title'] = '_MI_ALLTIME_GUEST';
$modversion['config'][$i]['description'] = '_MI_ALLTIME_GUESTDSC';
$modversion['config'][$i]['formtype'] = 'yesno';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 0;

$i++;
$modversion['config'][$i]['name'] = 'invisible_weights';
$modversion['config'][$i]['title'] = '_MI_INVISIBLE_WEIGHTS';
$modversion['config'][$i]['description'] = '_MI_INVISIBLE_WEIGHTSDSC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '0';

$i++;
$modversion['config'][$i]['name'] = 'invisible_dirnames';
$modversion['config'][$i]['title'] = '_MI_INVISIBLE_DIRNAMES';
$modversion['config'][$i]['description'] = '_MI_INVISIBLE_DIRNAMESDSC';
$modversion['config'][$i]['formtype'] = 'text';
$modversion['config'][$i]['valuetype'] = 'text';
$modversion['config'][$i]['default'] = '';

// Add by Francesco Mulassano (Urbanspaceman - www.takeaweb.it)
$i++;
$modversion['config'][$i]['name'] = 'visualization_type';
$modversion['config'][$i]['title'] = '_MI_VISUALIZATION_TYPENAMES';
$modversion['config'][$i]['description'] = '_MI_VISUALIZATION_TYPENAMESDSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 1;
$modversion['config'][$i]['options'] = array('Classic' => 1, 'Slickmap' => 2);

$i++;
$modversion['config'][$i]['name'] = 'columns_number';
$modversion['config'][$i]['title'] = '_MI_NUMBER_COLNAMES';
$modversion['config'][$i]['description'] = '_MI_NUMBER_COLNAMESDSC';
$modversion['config'][$i]['formtype'] = 'select';
$modversion['config'][$i]['valuetype'] = 'int';
$modversion['config'][$i]['default'] = 4;
$modversion['config'][$i]['options'] = array('1' => 1, '2' => 2, '3' => 3, '4' => 4, '5' => 5, '6' => 6, '7' => 7, '8' => 8, '9' => 9, '10' => 10);
?>