<?php
/**
 * XOOPS tree class
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
 * @package     kernel
 * @since       2.0.0
 * @author      Kazumi Ono (http://www.myweb.ne.jp/, http://jp.xoops.org/)
 * @version     $Id: tree.php 4941 2010-07-22 17:13:36Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A tree structures with {@link XoopsObject}s as nodes
 *
 * @package kernel
 * @subpackage core
 * @author Kazumi Ono <onokazu@xoops.org>
 */
class XoopsObjectTree {
	/**
	 * *#@+
	 *
	 * @access private
	 */
	var $_parentId;
	var $_myId;
	var $_rootId = null;
	var $_tree = array();
	var $_objects;

	/**
	 * Constructor
	 *
	 * @param array $objectArr Array of {@link XoopsObject}s
	 * @param string $myId field name of object ID
	 * @param string $parentId field name of parent object ID
	 * @param string $rootId field name of root object ID
	 */
	function XoopsObjectTree( &$objectArr, $myId, $parentId, $rootId = null ) {
		$this->_objects = &$objectArr;
		$this->_myId = $myId;
		$this->_parentId = $parentId;
		if ( isset( $rootId ) ) {
			$this->_rootId = $rootId;
		}
		$this->_initialize();
	}

	/**
	 * Initialize the object
	 *
	 * @access private
	 */
	function _initialize() {
		foreach ( array_keys( $this->_objects ) as $i ) {
			$key1 = $this->_objects[$i]->getVar( $this->_myId );
			$this->_tree[$key1]['obj'] = &$this->_objects[$i];
			$key2 = $this->_objects[$i]->getVar( $this->_parentId );
			$this->_tree[$key1]['parent'] = $key2;
			$this->_tree[$key2]['child'][] = $key1;
			if ( isset( $this->_rootId ) ) {
				$this->_tree[$key1]['root'] = $this->_objects[$i]->getVar( $this->_rootId );
			}
		}
	}

	/**
	 * Get the tree
	 *
	 * @return array Associative array comprising the tree
	 */
	function &getTree() {
		return $this->_tree;
	}

	/**
	 * returns an object from the tree specified by its id
	 *
	 * @param string $key ID of the object to retrieve
	 * @return object Object within the tree
	 */
	function &getByKey( $key ) {
		return $this->_tree[$key]['obj'];
	}

	/**
	 * returns an array of all the first child object of an object specified by its id
	 *
	 * @param string $key ID of the parent object
	 * @return array Array of children of the parent
	 */
	function getFirstChild( $key ) {
		$ret = array();
		if ( isset( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
				$ret[$childkey] = &$this->_tree[$childkey]['obj'];
			}
		}
		return $ret;
	}

	/**
	 * returns an array of all child objects of an object specified by its id
	 *
	 * @param string $key ID of the parent
	 * @param array $ret (Empty when called from client) Array of children from previous recursions.
	 * @return array Array of child nodes.
	 */
	function getAllChild( $key, $ret = array() ) {
		if ( isset( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
				$ret[$childkey] = &$this->_tree[$childkey]['obj'];
				$children = &$this->getAllChild( $childkey, $ret );
				foreach ( array_keys( $children ) as $newkey ) {
					$ret[$newkey] = &$children[$newkey];
				}
			}
		}
		return $ret;
	}

	/**
	 * returns an array of all parent objects.
	 * the key of returned array represents how many levels up from the specified object
	 *
	 * @param string $key ID of the child object
	 * @param array $ret (empty when called from outside) Result from previous recursions
	 * @param int $uplevel (empty when called from outside) level of recursion
	 * @return array Array of parent nodes.
	 */
	function getAllParent( $key, $ret = array(), $uplevel = 1 ) {
		if ( isset( $this->_tree[$key]['parent'] ) && isset( $this->_tree[$this->_tree[$key]['parent']]['obj'] ) ) {
			$ret[$uplevel] = &$this->_tree[$this->_tree[$key]['parent']]['obj'];
			$parents = &$this->getAllParent( $this->_tree[$key]['parent'], $ret, $uplevel + 1 );
			foreach ( array_keys( $parents ) as $newkey ) {
				$ret[$newkey] = &$parents[$newkey];
			}
		}
		return $ret;
	}

	/**
	 * Make options for a select box from
	 *
	 * @param string $fieldName Name of the member variable from the
	 *       node objects that should be used as the title for the options.
	 * @param string $selected Value to display as selected
	 * @param int $key ID of the object to display as the root of select options
	 * @param string $ret (reference to a string when called from outside) Result from previous recursions
	 * @param string $prefix_orig String to indent items at deeper levels
	 * @param string $prefix_curr String to indent the current item
	 * @return
	 * @access private
	 */
	function _makeSelBoxOptions( $fieldName, $selected, $key, &$ret, $prefix_orig, $prefix_curr = '' ) {
		if ( $key > 0 ) {
			$value = $this->_tree[$key]['obj']->getVar( $this->_myId );
			$ret .= '<option value="' . $value . '"';
			if ( $value == $selected ) {
				$ret .= ' selected="selected"';
			}
			$ret .= '>' . $prefix_curr . $this->_tree[$key]['obj']->getVar( $fieldName ) . '</option>';
			$prefix_curr .= $prefix_orig;
		}
		if ( isset( $this->_tree[$key]['child'] ) && !empty( $this->_tree[$key]['child'] ) ) {
			foreach ( $this->_tree[$key]['child'] as $childkey ) {
				$this->_makeSelBoxOptions( $fieldName, $selected, $childkey, $ret, $prefix_orig, $prefix_curr );
			}
		}
	}

	/**
	 * Make a select box with options from the tree
	 *
	 * @param string $name Name of the select box
	 * @param string $fieldName Name of the member variable from the
	 *       node objects that should be used as the title for the options.
	 * @param string $prefix String to indent deeper levels
	 * @param string $selected Value to display as selected
	 * @param bool $addEmptyOption Set TRUE to add an empty option with value "0" at the top of the hierarchy
	 * @param integer $key ID of the object to display as the root of select options
	 * @return string HTML select box
	 */
	function makeSelBox( $name, $fieldName, $prefix = '-', $selected = '', $addEmptyOption = false, $key = 0, $extra = '' ) {
		$ret = '<select name="' . $name . '" id="' . $name . '" ' . $extra . '>';
		if ( false != $addEmptyOption ) {
			$ret .= '<option value="0"></option>';
		}
		$this->_makeSelBoxOptions( $fieldName, $selected, $key, $ret, $prefix );
		return $ret . '</select>';
	}
}

?>
