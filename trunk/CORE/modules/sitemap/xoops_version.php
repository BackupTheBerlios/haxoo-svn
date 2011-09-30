<?php
//
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
//  Original Author: chanoir                        					     //
//  Original Author Website: http://www.petitoops.net       		         //
//  ------------------------------------------------------------------------ //
//  ***                                                                      //

$modversion['name'] = _MI_SITEMAP_NAME;
$modversion['version'] = '1.30';
$modversion['description'] = "Sitemap";
$modversion['author'] = 'chanoir(original)';
$modversion['credits'] = "refer to modules.txt";
$modversion['help'] = "";
$modversion['license'] = "GPL see LICENSE";
$modversion['official'] = 0;
$modversion['image'] = 'images/sitemap_slogo.png';
$modversion['dirname'] = 'sitemap';

$modversion['hasAdmin'] = 1;
$modversion['adminindex'] = 'admin/index.php';
$modversion['adminmenu'] = 'admin/menu.php';

$modversion['hasMain'] = 1;

$modversion['templates'][1]['file'] = 'sitemap_inc_eachmodule.html';
$modversion['templates'][1]['description'] = 'Sitemap display';

$modversion['templates'][2]['file'] = 'sitemap_index.html';
$modversion['templates'][2]['description'] = 'Sitemap index page';

$modversion['templates'][3]['file'] = 'sitemap_xml_google.html';
$modversion['templates'][3]['description'] = 'XML file template for search engines';

$modversion['blocks'][1]['file'] = 'sitemap_blocks.php';
$modversion['blocks'][1]['name'] = _MI_SITEMAP_BLOCKNAME;
$modversion['blocks'][1]['description'] = _MI_SITEMAP_BLOCKDESC;
$modversion['blocks'][1]['show_func'] = 'b_sitemap_show';
$modversion['blocks'][1]['edit_func'] = 'b_sitemap_edit';
$modversion['blocks'][1]['template'] = 'sitemap_block_show.html';
$modversion['blocks'][1]['options'] = '1';

// Configuration

$modversion['config'][1]['name'] = 'msgs';
$modversion['config'][1]['title'] = '_MI_MESSAGE';
$modversion['config'][1]['description'] = '_MI_MESSAGEDSC';
$modversion['config'][1]['formtype'] = 'textarea';
$modversion['config'][1]['valuetype'] = 'text';
$modversion['config'][1]['default'] = '_MI_SITEMAP_MESSAGE';

$modversion['config'][2]['name'] = 'show_subcategoris';
$modversion['config'][2]['title'] = '_MI_SHOW_SUBCATEGORIES';
$modversion['config'][2]['description'] = '_MI_SHOW_SUBCATEGORIESDSC';
$modversion['config'][2]['formtype'] = 'yesno';
$modversion['config'][2]['valuetype'] = 'int';
$modversion['config'][2]['default'] = 1;

$modversion['config'][3]['name'] = 'alltime_guest';
$modversion['config'][3]['title'] = '_MI_ALLTIME_GUEST';
$modversion['config'][3]['description'] = '_MI_ALLTIME_GUESTDSC';
$modversion['config'][3]['formtype'] = 'yesno';
$modversion['config'][3]['valuetype'] = 'int';
$modversion['config'][3]['default'] = 0;

$modversion['config'][4]['name'] = 'invisible_weights';
$modversion['config'][4]['title'] = '_MI_INVISIBLE_WEIGHTS';
$modversion['config'][4]['description'] = '_MI_INVISIBLE_WEIGHTSDSC';
$modversion['config'][4]['formtype'] = 'text';
$modversion['config'][4]['valuetype'] = 'text';
$modversion['config'][4]['default'] = '0';

$modversion['config'][5]['name'] = 'invisible_dirnames';
$modversion['config'][5]['title'] = '_MI_INVISIBLE_DIRNAMES';
$modversion['config'][5]['description'] = '_MI_INVISIBLE_DIRNAMESDSC';
$modversion['config'][5]['formtype'] = 'text';
$modversion['config'][5]['valuetype'] = 'text';
$modversion['config'][5]['default'] = '';

$modversion['config'][6]['name'] = 'XMLhomelastmodval';
$modversion['config'][6]['title'] = '_MI_XML_HOMELASTMODVAL';
$modversion['config'][6]['description'] = '_MI_XML_HOMELASTMODVALDSC';
$modversion['config'][6]['formtype'] = 'select';
$modversion['config'][6]['valuetype'] = 'text';
$modversion['config'][6]['default'] = 'OFF';
$modversion['config'][6]['options'] = array(_MI_XML_OFF => 'OFF',
                                            _MI_XML_LM_DATEONLY => 'Date Only', 
                                            _MI_XML_LM_DATETIME => 'Date/Time');

$modversion['config'][7]['name'] = 'XMLhomechangefreq';
$modversion['config'][7]['title'] = '_MI_XML_HOMECF';
$modversion['config'][7]['description'] = '_MI_XML_HOMECFDSC';
$modversion['config'][7]['formtype'] = 'select';
$modversion['config'][7]['valuetype'] = 'text';
$modversion['config'][7]['default'] = 'daily';
$modversion['config'][7]['options'] = array(_MI_XML_OFF => 'OFF',
                                            _MI_XML_CF_ALWAYS => 'always', 
                                            _MI_XML_CF_HOURLY => 'hourly', 
											_MI_XML_CF_DAILY => 'daily', 
											_MI_XML_CF_WEEKLY => 'weekly', 
											_MI_XML_CF_MONTHLY => 'monthly', 
											_MI_XML_CF_YEARLY => 'yearly', 
											_MI_XML_CF_NEVER => 'never');

$modversion['config'][8]['name'] = 'XMLhomepriorityvalue';
$modversion['config'][8]['title'] = '_MI_XML_HOMEPRIORITYVAL';
$modversion['config'][8]['description'] = '_MI_XML_HOMEPRIORITYVALDSC';
$modversion['config'][8]['formtype'] = 'select';
$modversion['config'][8]['valuetype'] = 'text';
$modversion['config'][8]['default'] = '1.0';
$modversion['config'][8]['options'] = array(_MI_XML_OFF => 'OFF', '0.0' => '0.0', '0.1' => '0.1', '0.2' => '0.2', '0.3' => '0.3', '0.4' => '0.4', '0.5' => '0.5',
                                            '0.6' => '0.6', '0.7' => '0.7', '0.8' => '0.8', '0.9' => '0.9', '1.0' => '1.0');

$modversion['config'][9]['name'] = 'XMLmodulelastmodval';
$modversion['config'][9]['title'] = '_MI_XML_MODULELASTMODVAL';
$modversion['config'][9]['description'] = '_MI_XML_MODULELASTMODVALDSC';
$modversion['config'][9]['formtype'] = 'select';
$modversion['config'][9]['valuetype'] = 'text';
$modversion['config'][9]['default'] = 'OFF';
$modversion['config'][9]['options'] = array(_MI_XML_OFF => 'OFF',
                                            _MI_XML_LM_DATEONLY => 'Date Only', 
                                            _MI_XML_LM_DATETIME => 'Date & Time');

$modversion['config'][10]['name'] = 'XMLmodulechangefreq';
$modversion['config'][10]['title'] = '_MI_XML_MODULECF';
$modversion['config'][10]['description'] = '_MI_XML_MODULECFDSC';
$modversion['config'][10]['formtype'] = 'select';
$modversion['config'][10]['valuetype'] = 'text';
$modversion['config'][10]['default'] = 'monthly';
$modversion['config'][10]['options'] = array(_MI_XML_OFF => 'OFF',
                                            _MI_XML_CF_ALWAYS => 'always', 
                                            _MI_XML_CF_HOURLY => 'hourly', 
											_MI_XML_CF_DAILY => 'daily', 
											_MI_XML_CF_WEEKLY => 'weekly', 
											_MI_XML_CF_MONTHLY => 'monthly', 
											_MI_XML_CF_YEARLY => 'yearly', 
											_MI_XML_CF_NEVER => 'never');

$modversion['config'][11]['name'] = 'XMLmodulepriorityvalue';
$modversion['config'][11]['title'] = '_MI_XML_MODULEPRIORITYVAL';
$modversion['config'][11]['description'] = '_MI_XML_MODULEPRIORITYVALDSC';
$modversion['config'][11]['formtype'] = 'select';
$modversion['config'][11]['valuetype'] = 'text';
$modversion['config'][11]['default'] = '0.9';
$modversion['config'][11]['options'] = array(_MI_XML_OFF => 'OFF', '0.0' => '0.0', '0.1' => '0.1', '0.2' => '0.2', '0.3' => '0.3', '0.4' => '0.4', '0.5' => '0.5',
                                            '0.6' => '0.6', '0.7' => '0.7', '0.8' => '0.8', '0.9' => '0.9', '1.0' => '1.0');

$modversion['config'][12]['name'] = 'XMLsublastmodval';
$modversion['config'][12]['title'] = '_MI_XML_SUBLASTMODVAL';
$modversion['config'][12]['description'] = '_MI_XML_SUBLASTMODVALDSC';
$modversion['config'][12]['formtype'] = 'select';
$modversion['config'][12]['valuetype'] = 'text';
$modversion['config'][12]['default'] = 'OFF';
$modversion['config'][12]['options'] = array(_MI_XML_OFF => 'OFF',
                                            _MI_XML_LM_DATEONLY => 'Date Only', 
                                            _MI_XML_LM_DATETIME => 'Date & Time');

$modversion['config'][13]['name'] = 'XMLsubchangefreq';
$modversion['config'][13]['title'] = '_MI_XML_SUBCF';
$modversion['config'][13]['description'] = '_MI_XML_SUBCFDSC';
$modversion['config'][13]['formtype'] = 'select';
$modversion['config'][13]['valuetype'] = 'text';
$modversion['config'][13]['default'] = 'monthly';
$modversion['config'][13]['options'] = array(_MI_XML_OFF => 'OFF',
                                            _MI_XML_CF_ALWAYS => 'always', 
                                            _MI_XML_CF_HOURLY => 'hourly', 
											_MI_XML_CF_DAILY => 'daily', 
											_MI_XML_CF_WEEKLY => 'weekly', 
											_MI_XML_CF_MONTHLY => 'monthly', 
											_MI_XML_CF_YEARLY => 'yearly', 
											_MI_XML_CF_NEVER => 'never');

$modversion['config'][14]['name'] = 'XMLsubpriorityvalue';
$modversion['config'][14]['title'] = '_MI_XML_SUBPRIORITYVAL';
$modversion['config'][14]['description'] = '_MI_XML_SUBPRIORITYVALDSC';
$modversion['config'][14]['formtype'] = 'select';
$modversion['config'][14]['valuetype'] = 'text';
$modversion['config'][14]['default'] = '0.8';
$modversion['config'][14]['options'] = array(_MI_XML_OFF => 'OFF', '0.0' => '0.0', '0.1' => '0.1', '0.2' => '0.2', '0.3' => '0.3', '0.4' => '0.4', '0.5' => '0.5',
                                            '0.6' => '0.6', '0.7' => '0.7', '0.8' => '0.8', '0.9' => '0.9', '1.0' => '1.0');

?>
