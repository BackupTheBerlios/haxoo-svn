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

include_once(XOOPS_ROOT_PATH . '/class/xoopstree.php');

// 本体
function sitemap_show()
{
	global $xoopsUser, $xoopsConfig, $sitemap_configs ;
	$plugin_dir = XOOPS_ROOT_PATH . "/modules/sitemap/plugins/";

	// invisible weights
	$invisible_weights = array() ;
	if( trim( @$sitemap_configs['invisible_weights'] ) !== '' ) {
		$invisible_weights = explode( ',' , $sitemap_configs['invisible_weights'] ) ;
	}

	// invisible dirnames
	$invisible_dirnames = empty( $sitemap_configs['invisible_dirnames'] ) ? '' : str_replace( ' ' , '' , $sitemap_configs['invisible_dirnames'] ) . ',' ;

	$block = array();

	@$block['lang_home'] = _MD_SITEMAP_HOME ;
	@$block['lang_close'] = _CLOSE ;
	$module_handler =& xoops_gethandler('module');
	$criteria = new CriteriaCompo(new Criteria('hasmain', 1));
	$criteria->add(new Criteria('isactive', 1));
	$modules =& $module_handler->getObjects($criteria, true);
	$moduleperm_handler =& xoops_gethandler('groupperm');
	$groups = is_object($xoopsUser) ? $xoopsUser->getGroups() : XOOPS_GROUP_ANONYMOUS;
	$read_allowed = $moduleperm_handler->getItemIds('module_read', $groups);
	
	if ($sitemap_configs['XMLhomelastmodval'] == "Date/Time") {
	  $block['xmlhomelastmod'] = gmdate( 'Y-m-d\TH:i:s\Z' );
	}elseif ($sitemap_configs['XMLhomelastmodval'] == "Date Only") {
	  $block['xmlhomelastmod'] = gmdate( 'Y-m-d' );
	}else{
	  $block['xmlhomelastmod'] = '';
	}

	if ($sitemap_configs['XMLhomechangefreq'] <> "OFF") {
	  $block['xmlhomecf'] = $sitemap_configs['XMLhomechangefreq'];
	}else{
	  $block['xmlhomecf'] = '';	  
	}

	if ($sitemap_configs['XMLhomepriorityvalue'] <> "OFF") {
      $block['xmlhomepriority'] = $sitemap_configs['XMLhomepriorityvalue'];
	}else{
	  $block['xmlhomepriority'] = '';	  
	}
	
	foreach (array_keys($modules) as $i) {
		if (in_array($i, $read_allowed) && ! in_array($modules[$i]->getVar('weight'),$invisible_weights) && ! stristr( $invisible_dirnames , $modules[$i]->getVar('dirname').',' ) ) {
			if ($modules[$i]->getVar('dirname') == 'sitemap') {
				continue;
			}
			$block['modules'][$i]['id'] = $i;
			$block['modules'][$i]['name'] = $modules[$i]->getVar('name');
			$block['modules'][$i]['directory'] = $modules[$i]->getVar('dirname');

	        if ($sitemap_configs['XMLmodulelastmodval'] == "Date/Time") {
	          $block['modules'][$i]['lastmod'] = gmdate( 'Y-m-d\TH:i:s\Z' );
	        }elseif ($sitemap_configs['XMLmodulelastmodval'] == "Date Only") {
	          $block['modules'][$i]['lastmod'] = gmdate( 'Y-m-d' );
	        }else{
	          $block['modules'][$i]['lastmod'] = '';
	        }

	        if ($sitemap_configs['XMLmodulechangefreq'] <> "OFF") {
	          $block['modules'][$i]['changefreq'] = $sitemap_configs['XMLmodulechangefreq'];
	        }else{
	          $block['modules'][$i]['changefreq'] = '';
	        }

	        if ($sitemap_configs['XMLmodulepriorityvalue'] <> "OFF") {
              $block['modules'][$i]['priority'] = $sitemap_configs['XMLmodulepriorityvalue'];
	        }else{
	          $block['modules'][$i]['priority'] = '';
	        }
			
			$old_error_reporting = error_reporting() ;
			error_reporting( $old_error_reporting & (~E_NOTICE) ) ;
			$sublinks =& $modules[$i]->subLink();
			error_reporting( $old_error_reporting ) ;
			if (count($sublinks) > 0) {
				foreach($sublinks as $sublink){
	              if ($sitemap_configs['XMLsublastmodval'] == "Date/Time") {
	                $lastmodtemp = gmdate( 'Y-m-d\TH:i:s\Z' );
	              }elseif ($sitemap_configs['XMLsublastmodval'] == "Date Only") {
	                $lastmodtemp = gmdate( 'Y-m-d' );
	              }else{
	                $lastmodtemp = '';
	              }
                  if ($sitemap_configs['XMLsubchangefreq'] <> "OFF") {
	                $changefreqtemp = $sitemap_configs['XMLsubchangefreq'];
	              }else{
	                $changefreqtemp = '';
	              }
                  if ($sitemap_configs['XMLsubpriorityvalue'] <> "OFF") {
                    $prioritytemp = $sitemap_configs['XMLsubpriorityvalue'];
	              }else{
	                $prioritytemp = '';
	              }
				  $block['modules'][$i]['sublinks'][] = array('name' => $sublink['name'], 'url' => XOOPS_URL.'/modules/'.$modules[$i]->getVar('dirname').'/'.$sublink['url'], 'lastmod' => $lastmodtemp, 'changefreq' => $changefreqtemp, 'priority' => $prioritytemp);
				
				}
			} else {
				$block['modules'][$i]['sublinks'] = array();
			}
			// こっからプラグイン処理 by Ryuji
			// モジュールのプラグインがあれば、requireして、情報を得る。
			// モジュール側にプラグインが用意されているかチェック
			//  plugin modules/DIRNAME/include/sitemap.plugin.php
			//  lang   modules/DIRNAME/language/LANG/sitemap.php
			$mod = $modules[$i]->getVar("dirname");
			$mydirname = $mod ;

			// get $mytrustdirname for D3 modules
			$mytrustdirname = '' ;
			if( defined( 'XOOPS_TRUST_PATH' ) && file_exists( XOOPS_ROOT_PATH."/modules/".$mydirname."/mytrustdirname.php" ) ) {
				@include XOOPS_ROOT_PATH."/modules/".$mydirname."/mytrustdirname.php" ;
			}

			$mod_plugin_file = XOOPS_ROOT_PATH."/modules/".$mod."/include/sitemap.plugin.php";

			if(file_exists($mod_plugin_file)){
				// module side plugin under xoops_root_path (1st priority)
				$mod_plugin_lng = XOOPS_ROOT_PATH."/modules/".$mod."/language/".$xoopsConfig['language']."/sitemap.php";
				if(file_exists($mod_plugin_lng)){
					include_once($mod_plugin_lng);
				}else{
					$mod_plugin_lng = XOOPS_ROOT_PATH."/modules/".$mod."/language/english/sitemap.php";
					if(file_exists($mod_plugin_lng)){
						include_once($mod_plugin_lng);
					}
				}
				require_once $mod_plugin_file ;
				// call the function
				if (function_exists("b_sitemap_" . $mod)){
					$_tmp = call_user_func("b_sitemap_" . $mod , $mydirname );
					if (isset($_tmp["parent"])) {
						$block['modules'][$i]['parent'] = $_tmp["parent"];
					}
				}
			} else if( ! empty( $mytrustdirname ) && file_exists( XOOPS_TRUST_PATH."/modules/".$mytrustdirname."/include/sitemap.plugin.php" ) ) {
				// D3 module's plugin under xoops_trust_path (2nd priority)
				$mod_plugin_lng = XOOPS_TRUST_PATH."/modules/".$mytrustdirname."/language/".$xoopsConfig['language']."/sitemap.php";
				if(file_exists($mod_plugin_lng)){
					include_once($mod_plugin_lng);
				}else{
					$mod_plugin_lng = XOOPS_TRUST_PATH."/modules/".$mytrustdirname."/language/english/sitemap.php";
					if(file_exists($mod_plugin_lng)){
						include_once($mod_plugin_lng);
					}
				}
				require_once XOOPS_TRUST_PATH."/modules/".$mytrustdirname."/include/sitemap.plugin.php" ;
				// call the function
				if (function_exists("b_sitemap_" . $mytrustdirname)){
					$_tmp = call_user_func("b_sitemap_" . $mytrustdirname , $mydirname );
					if (isset($_tmp["parent"])) {
						$block['modules'][$i]['parent'] = $_tmp["parent"];
					}
				}
			} else {
				// sitemap built-in plugin (last priority)
				$mod_plugin_dir = $plugin_dir ;
				$mod_plugin_file = $mod_plugin_dir . $mod . ".php";
				$mod_plugin_lng = $mod_plugin_dir . $xoopsConfig['language'] . ".lng.php";
				//言語ファイルを読み込む
				if (file_exists($mod_plugin_lng)){
					include_once($mod_plugin_lng);
				}else{
					$mod_plugin_lng = $mod_plugin_dir . "english" . ".lng.php";
					if (file_exists($mod_plugin_lng)){
						include_once($mod_plugin_lng);
					}
				}
				// include the plugin and call the function
				if (file_exists($mod_plugin_file)){
					require_once $mod_plugin_file ;
					// call the function
					if (function_exists("b_sitemap_" . $mod)){
						$_tmp = call_user_func("b_sitemap_" . $mod , $mydirname );
						if (isset($_tmp["parent"])) {
							$block['modules'][$i]['parent'] = $_tmp["parent"];
						}

					}
				}
			}
		}
	}
	return $block;
}

// mylinksやnewsなどよくあるパターンのカテゴリリストを得るためのfunction

function sitemap_get_categoires_map($table, $id_name, $pid_name, $title_name, $url, $order = ""){
	global $sitemap_configs;
	$mytree = new XoopsTree($table, $id_name, $pid_name);
	$xoopsDB =& Database::getInstance();
	
	$sitemap = array();
	$myts =& MyTextSanitizer::getInstance();

	$i = 0;
	$sql = "SELECT `$id_name`,`$title_name` FROM `$table` WHERE `$pid_name`=0" ;
	if ($order != '')
	{
		$sql .= " ORDER BY `$order`" ;
	}
	$result = $xoopsDB->query($sql);
	while (list($catid, $name) = $xoopsDB->fetchRow($result))
	{
		// 親の出力
		$sitemap['parent'][$i]['id'] = $catid;
		$sitemap['parent'][$i]['title'] = $myts->makeTboxData4Show( $name ) ;
		$sitemap['parent'][$i]['url'] = $url.$catid;		
        if ($sitemap_configs['XMLsublastmodval'] == "Date/Time") {
	      $sitemap['parent'][$i]['lastmod'] = gmdate( 'Y-m-d\TH:i:s\Z' );
	    }elseif ($sitemap_configs['XMLsublastmodval'] == "Date Only") {
	      $sitemap['parent'][$i]['lastmod'] = gmdate( 'Y-m-d' );
	    }else{
	      $sitemap['parent'][$i]['lastmod'] = '';
	    }
        if ($sitemap_configs['XMLsubchangefreq'] <> "OFF") {
	      $sitemap['parent'][$i]['changefreq'] = $sitemap_configs['XMLsubchangefreq'];
	    }else{
	      $sitemap['parent'][$i]['changefreq'] = '';
	    }
        if ($sitemap_configs['XMLsubpriorityvalue'] <> "OFF") {
          $sitemap['parent'][$i]['priority'] = $sitemap_configs['XMLsubpriorityvalue'];
	    }else{
	      $sitemap['parent'][$i]['priority'] = '';
	    }
		
		// 子の出力
		if(@$sitemap_configs["show_subcategoris"]){ // サブカテ表示のときのみ実行 by Ryuji
			$j = 0;
			$child_ary = $mytree->getChildTreeArray($catid, $order);
			foreach ($child_ary as $child)
			{
				$count = strlen($child['prefix']) + 1; // MEMO prefixの長さでサブカテの深さを判定してる
				$sitemap['parent'][$i]['child'][$j]['id'] = $child[$id_name];
				$sitemap['parent'][$i]['child'][$j]['title'] = $myts->makeTboxData4Show($child[$title_name] ) ;
				$sitemap['parent'][$i]['child'][$j]['image'] = (($count > 3) ? 4 : $count);
				$sitemap['parent'][$i]['child'][$j]['url'] = $url.$child[$id_name];
	            
				if ($sitemap_configs['XMLsublastmodval'] == "Date/Time") {
	              $sitemap['parent'][$i]['child'][$j]['lastmod'] = gmdate( 'Y-m-d\TH:i:s\Z' );
	            }elseif ($sitemap_configs['XMLsublastmodval'] == "Date Only") {
	              $sitemap['parent'][$i]['child'][$j]['lastmod'] = gmdate( 'Y-m-d' );
	            }else{
	              $sitemap['parent'][$i]['child'][$j]['lastmod'] = '';
	            }
                if ($sitemap_configs['XMLsubchangefreq'] <> "OFF") {
	              $sitemap['parent'][$i]['child'][$j]['changefreq'] = $sitemap_configs['XMLsubchangefreq'];
	            }else{
	              $sitemap['parent'][$i]['child'][$j]['changefreq'] = '';
	            }
                if ($sitemap_configs['XMLsubpriorityvalue'] <> "OFF") {
                  $sitemap['parent'][$i]['child'][$j]['priority'] = $sitemap_configs['XMLsubpriorityvalue'];
	            }else{
	              $sitemap['parent'][$i]['child'][$j]['priority'] = '';
	            }
				$j++;
			}
		}
		$i++;
	}
	return $sitemap;
}

?>