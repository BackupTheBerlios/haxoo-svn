<?php
/**
 * Maintenance class manager
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
 * @author      Cointin Maxime (AKA Kraven30)
 * @package     system
 * @version     $Id:$
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * System Maintenance
 *
 * @copyright   copyright (c) 2000 XOOPS.org
 * @package     system
 */
class SystemMaintenance
{
	var $db;
	var $prefix;

	/**
     * Constructor
     */
	function SystemMaintenance() {
		$db =& Database::getInstance();
		$this->db = $db;
		$this->prefix = $this->db->prefix.'_';
	}

	/**
     * Display Tables
	 *
     * @param array
     * @return
     */
	function displayTables($array = true) {
		$tables = array();
		$result = $this->db->queryF('SHOW TABLES');
		while ($myrow = $this->db->fetchArray($result)) {
			$value = array_values($myrow);
			$value = substr($value[0], 5);
			$tables[$value] = $value;
		}
		if( $array = true ) {
			return $tables;
		} else {
			return join(',', $tables);
		}
	}

	/**
     * Clear sessions
     *
     * @return
     */
	function CleanSession() {
		$result = $this->db->queryF('TRUNCATE TABLE '.$this->db->prefix('session'));
		return true;
	}

	/**
     * Clean cache 'xoops_data/caches/smarty_cache'
	 *
	 * @param array cache
     * @return
     */
	function CleanCache($cache) {
		for($i=0; $i<count($cache); $i++) {
			if($cache[$i] == 1) {
				$files = glob(XOOPS_VAR_PATH.'/caches/smarty_cache/*.*');
				foreach ($files as $filename) {
					if(basename(strtolower($filename)) != 'index.html') {
						unlink($filename);
					}
				}
			} else if($cache[$i] == 2) {
				$files = glob(XOOPS_VAR_PATH.'/caches/smarty_compile/*.*');
				foreach ($files as $filename) {
					if(basename(strtolower($filename)) != 'index.html') {
						unlink($filename);
					}
				}
			} else if($cache[$i] == 3) {
				$files = glob(XOOPS_VAR_PATH.'/caches/xoops_cache/*.*');
				foreach ($files as $filename) {
					if(basename(strtolower($filename)) != 'index.html') {
						unlink($filename);
					}
				}
			}
		}
		return true;
	}

	/**
     * Maintenance database
	 *
	 * @param array tables 'list of tables'
	 * @param array maintenance 'optimize, check, repair, analyze'
     * @return array
     */
	function CheckRepairAnalyzeOptimizeQueries($tables, $maintenance) {
		$ret = '<table class="outer"><th>'._AM_SYSTEM_MAINTENANCE_TABLES1.'</th><th>'._AM_SYSTEM_MAINTENANCE_TABLES_OPTIMIZE.'</th><th>'._AM_SYSTEM_MAINTENANCE_TABLES_CHECK.'</th><th>'._AM_SYSTEM_MAINTENANCE_TABLES_REPAIR.'</th><th>'._AM_SYSTEM_MAINTENANCE_TABLES_ANALYZE.'</th>';
		$tab = array();
		for($i=0; $i<4; $i++) {
			$tab[$i] = $i+1;
		}
		$tab1 = array();
		for($i=0; $i<4; $i++) {
			if (in_array ($tab[$i], $maintenance) ) {
				$tab1[$i] = $tab[$i];
			} else {
				$tab1[$i] = '0';
			}
		}
		unset($tab);
		$class = 'odd';
		for($i=0; $i<count($tables); $i++) {
			$ret .= '<tr class="'.$class.'"><td align="center">'.$this->prefix.$tables[$i].'</td>';
			for($j=0; $j<4; $j++) {
				if( $tab1[$j] == 1 ) {
					// Optimize
					$result = $this->db->queryF('OPTIMIZE TABLE '.$this->prefix.$tables[$i]);
					if($result) {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('success.png').'" /></td>';
					} else {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('cancel.png').'" /></td>';
					}
				} else if ( $tab1[$j] == 2 ) {
					// Check tables
					$result = $this->db->queryF('CHECK TABLE '.$this->prefix.$tables[$i]);
					if($result) {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('success.png').'" /></td>';
					} else {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('cancel.png').'" /></td>';
					}
				} else if ( $tab1[$j] == 3 ) {
					// Repair
					$result = $this->db->queryF('REPAIR TABLE '.$this->prefix.$tables[$i]);
					if($result) {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('success.png').'" /></td>';
					} else {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('cancel.png').'" /></td>';
					}
				} else if ( $tab1[$j] == 4 ) {
					// Analyze
					$result = $this->db->queryF('ANALYZE TABLE '.$this->prefix.$tables[$i]);
					if($result) {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('success.png').'" /></td>';
					} else {
						$ret .= '<td class="xo-actions txtcenter"><img src="'.system_AdminIcons('cancel.png').'" /></td>';
					}
				} else {
					$ret .= '<td>&nbsp;</td>';
				}
			}
			$ret .= '</tr>';
			$class = ($class == 'even') ? 'odd' : 'even';
		}
		$ret .= '</table>';
		return $ret;
	}

	/**
     * Dump by tables
	 *
	 * @param array tables 'list of tables'
	 * @param int drop
     * @return array 'ret[0] = dump, ret[1] = display result
     */
	function dump_tables($tables, $drop) {
		$ret = array();
		$ret[0] = "# \n";
		$ret[0] .= "# Dump SQL, Generate by Xoops \n";
		$ret[0] .= "# Date : ".date('d-m-Y � H:i')." \n";
		$ret[1] = '<table class="outer"><tr><th width="30%">'._AM_SYSTEM_MAINTENANCE_DUMP_TABLES.'</th><th width="35%">'._AM_SYSTEM_MAINTENANCE_DUMP_STRUCTURES.'</th><th  width="35%">'._AM_SYSTEM_MAINTENANCE_DUMP_NB_RECORDS.'</th></tr>';
		$class = 'odd';
		for($i=0; $i<count($tables); $i++) {
			//structure
			$ret = $this->dump_table_structure($ret, $this->prefix.$tables[$i], $drop, $class);
			//data
			$ret = $this->dump_table_datas($ret, $this->prefix.$tables[$i]);
			$class = ($class == 'even') ? 'odd' : 'even';
		}
		$ret = $this->dump_write($ret);
		$ret[1] .= '</table>';
		return $ret;
	}

	/**
     * Dump by modules
	 *
	 * @param array modules 'list of modules'
	 * @param int drop
     * @return array 'ret[0] = dump, ret[1] = display result
     */
	function dump_modules($modules, $drop) {
		$ret = array();
		$ret[0] = "# \n";
		$ret[0] .= "# Dump SQL, Generate by Xoops \n";
		$ret[0] .= "# Date : ".date('d-m-Y � H:i')." \n";
		$ret[0] .= "# \n\n";
		$ret[1] = '<table class="outer"><tr><th width="30%">'._AM_SYSTEM_MAINTENANCE_DUMP_TABLES.'</th><th width="35%">'._AM_SYSTEM_MAINTENANCE_DUMP_STRUCTURES.'</th><th  width="35%">'._AM_SYSTEM_MAINTENANCE_DUMP_NB_RECORDS.'</th></tr>';
		$class = 'odd';
		for($i=0; $i<count($modules); $i++) {
			$module_handler =& xoops_gethandler('module');
			$module = $module_handler->getByDirname($modules[$i]);
			$ret[1] .= '<tr><th colspan="3" align="left">'.ucfirst($modules[$i]).'</th></tr>';
			$modtables = $module->getInfo('tables');
			if ($modtables != false && is_array($modtables)) {
				foreach ($modtables as $table) {
					//structure
					$ret = $this->dump_table_structure($ret, $this->prefix.$table, $drop, $class);
					//data
					$ret = $this->dump_table_datas($ret, $this->prefix.$table);
					$class = ($class == 'even') ? 'odd' : 'even';
				}
			} else {
				$ret[1] .= '<tr><td colspan="3" align="center">'._AM_SYSTEM_MAINTENANCE_DUMP_NO_TABLES.'</td></tr>';
			}
		}
		$ret[1] .= '</table>';
		$ret = $this->dump_write($ret);
		return $ret;
	}

	/**
     * Dump table structure
	 *
	 * @param array
	 * @param string table
	 * @param int drop
	 * @param string class
     * @return array 'ret[0] = dump, ret[1] = display result
     */
	function dump_table_structure($ret, $table, $drop, $class)
	{
		$verif = false;
		$result = $this->db->queryF('SHOW create table `'.$table.'`;');
		if( $result ) {
			if( $row = $this->db->fetchArray($result) ) {
				$ret[0] .= "# Table structure for table `".$table."` \n\n";
				if ( $drop == 1 ) {
					$ret[0] .= "DROP TABLE IF EXISTS `".$table."`;\n\n";
				}
				$verif = true;
				$ret[0] .= $row['Create Table'].";\n\n";
			}
		}
		$ret[1] .= '<tr class="'.$class.'"><td align="center">'.$table.'</td><td class="xo-actions txtcenter">';
		$ret[1] .= ( $verif == true ) ? '<img src="'.system_AdminIcons('success.png').'" />' : '<img src="'.system_AdminIcons('cancel.png').'" />';
		$ret[1] .= '</td>';
		$this->db->freeRecordSet($result);
		return $ret;
	}

	/**
     * Dump table data
	 *
	 * @param array
	 * @param string table
     * @return array 'ret[0] = dump, ret[1] = display result
     */
	function dump_table_datas($ret, $table)
	{
		$count = 0;
		$result = $this->db->queryF('SELECT * FROM '.$table.';');
		if( $result) {
			$num_rows= $this->db->getRowsNum($result);
			$num_fields= $this->db->getFieldsNum($result);

			if( $num_rows > 0) {
				$field_type = array();
				$i = 0;
				while( $i < $num_fields ) {
					$meta = mysql_fetch_field($result, $i);
					array_push($field_type, $meta->type);
					$i++;
				}

				$ret[0] .= "INSERT INTO `".$table."` values\n";
				$index = 0;
				while( $row = $this->db->fetchRow($result) ) {
					$count++;
					$ret[0] .= "(";
					for( $i=0; $i < $num_fields; $i++ ) {
						if( is_null( $row[$i] ) ) {
							$ret[0] .= "null";
						} else {
							switch( $field_type[$i]) {
								case 'int':
									$ret[0] .= $row[$i];
									break;
								default:
									$ret[0] .= "'".mysql_real_escape_string($row[$i])."'";
							}
						}
						if( $i < $num_fields-1) {
							$ret[0] .= ",";
						}
					}
					$ret[0] .= ")";

					if( $index < $num_rows-1 ) {
						$ret[0] .= ",";
					} else {
						$ret[0] .= ";";
					}
					$ret[0] .= "\n";
					$index++;
				}
			}
		}
		$ret[1] .= '<td align="center">';
		$ret[1] .= $count.'&nbsp;'._AM_SYSTEM_MAINTENANCE_DUMP_RECORDS.'</td></tr>';
		$ret[0] .= "\n";
		$this->db->freeRecordSet($result);
		$ret[0] .= "\n";
		return $ret;
	}

	/**
     * Dump Write
	 *
	 * @param array
     * @return array 'ret[0] = dump, ret[1] = display result
     */
	function dump_write($ret) {
		$file_name = "dump_".date("Y.m.d")."_".date("H.i.s").".sql";
		$path_file = "./admin/maintenance/dump/".$file_name;
		if( file_put_contents($path_file, $ret[0]) ) {
			$ret[1] .= '<table class="outer"><tr><th colspan="2" align="center">'._AM_SYSTEM_MAINTENANCE_DUMP_FILE_CREATED.'</th><th>'._AM_SYSTEM_MAINTENANCE_DUMP_RESULT.'</th></tr><tr><td colspan="2" align="center"><a href="'.XOOPS_URL.'/modules/system/admin/maintenance/dump/'.$file_name.'">'.$file_name.'</a></td><td  class="xo-actions txtcenter"><img src="'.system_AdminIcons('success.png').'" /></td><tr></table>';
		} else {
			$ret[1] .= '<table class="outer"><tr><th colspan="2" align="center">'._AM_SYSTEM_MAINTENANCE_DUMP_FILE_CREATED.'</th><th>'._AM_SYSTEM_MAINTENANCE_DUMP_RESULT.'</th></tr><tr><td colspan="2" class="xo-actions txtcenter">'.$file_name.'</td><td  class="xo-actions txtcenter"><img src="'.system_AdminIcons('cancel.png').'" /></td><tr></table>';
		}
		return $ret;
	}
}

?>