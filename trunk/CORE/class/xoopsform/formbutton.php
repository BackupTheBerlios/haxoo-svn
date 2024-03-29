<?php
/**
 * XOOPS form element of button
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS Project http://sourceforge.net/projects/xoops/ 
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         kernel
 * @subpackage      form
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: formbutton.php 4941 2010-07-22 17:13:36Z beckmi $
 */
defined('XOOPS_ROOT_PATH') or die("XOOPS root path not defined");

xoops_load('XoopsFormElement');

/**
 *
 *
 * @package     kernel
 * @subpackage  form
 *
 * @author	    Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 */
/**
 * A button
 *
 * @author	Kazumi Ono	<onokazu@xoops.org>
 * @copyright	copyright (c) 2000-2003 XOOPS.org
 *
 * @package     kernel
 * @subpackage  form
 */
class XoopsFormButton extends XoopsFormElement
{

	/**
     * Value
	 * @var	string
	 * @access	private
	 */
	var $_value;

	/**
     * Type of the button. This could be either "button", "submit", or "reset"
	 * @var	string
	 * @access	private
	 */
	var $_type;

	/**
	 * Constructor
     *
	 * @param	string  $caption    Caption
     * @param	string  $name
     * @param	string  $value
     * @param	string  $type       Type of the button. Potential values: "button", "submit", or "reset"
	 */
	function XoopsFormButton($caption, $name, $value = "", $type = "button")
	{
		$this->setCaption($caption);
		$this->setName($name);
		$this->_type = $type;
		$this->setValue($value);
	}

	/**
	 * Get the initial value
	 *
	 * @param	bool    $encode To sanitizer the text?
     * @return	string
	 */
	function getValue($encode = false)
	{
		return $encode ? htmlspecialchars($this->_value, ENT_QUOTES) : $this->_value;
	}

	/**
	 * Set the initial value
	 *
     * @return	string
	 */
	function setValue($value)
	{
		$this->_value = $value;
	}

	/**
	 * Get the type
	 *
     * @return	string
	 */
	function getType()
	{
		return in_array( strtolower($this->_type), array("button", "submit", "reset") ) ? $this->_type : "button";
	}

	/**
	 * prepare HTML for output
	 *
     * @return	string
	 */
	function render()
	{
		return "<input type='" . $this->getType() . "' class='formButton' name='" . $this->getName() . "'  id='" . $this->getName() . "' value='" . $this->getValue() . "' title='" . $this->getValue() . "'" . $this->getExtra() . " />";
	}
}
?>