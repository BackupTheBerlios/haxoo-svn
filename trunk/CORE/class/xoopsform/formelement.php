<?php
/**
 * XOOPS form element
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
 * @version         $Id: formelement.php 7652 2011-09-20 23:41:34Z trabis $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * Abstract base class for form elements
 */
class XoopsFormElement
{
    /**
     * Javascript performing additional validation of this element data
     *
     * This property contains a list of Javascript snippets that will be sent to
     * XoopsForm::renderValidationJS().
     * NB: All elements are added to the output one after the other, so don't forget
     * to add a ";" after each to ensure no Javascript syntax error is generated.
     *
     * @var array ()
     */
    var $customValidationCode = array();

    /**
     * *#@+
     *
     * @access private
     */
    /**
     * "name" attribute of the element
     *
     * @var string
     */
    var $_name;

    /**
     * caption of the element
     *
     * @var string
     */
    var $_caption;

    /**
     * Accesskey for this element
     *
     * @var string
     */
    var $_accesskey = '';

    /**
     * HTML classes for this element
     *
     * @var array
     */
    var $_class = array();

    /**
     * hidden?
     *
     * @var bool
     */
    var $_hidden = false;

    /**
     * extra attributes to go in the tag
     *
     * @var array
     */
    var $_extra = array();

    /**
     * required field?
     *
     * @var bool
     */
    var $_required = false;

    /**
     * description of the field
     *
     * @var string
     */
    var $_description = '';

    /**
     * *#@-
     */

    /**
     * CAtzwolf: Modified for No Colspan
     *
     * Lets a developer have only one column in a form element rather than two.
     * Example of usgage: Allows text editors to span 2 columns rathe than pushed into one column
     *
     * @deprecated  PLEASE AVOID USING THIS METHOD
     *
     * @var bool
     */
    var $_nocolspan = false;

    /**
     * Get form type
     *
     * @deprecated  PLEASE AVOID USING THIS METHOD
     */
    var $_formtype = '';

    /**
     * constructor
     */
    function XoopsFormElement()
    {
        exit('This class cannot be instantiated!');
    }

    /**
     * Is this element a container of other elements?
     *
     * @return bool false
     */
    function isContainer()
    {
        return false;
    }

    /**
     * set the "name" attribute for the element
     *
     * @param string $name "name" attribute for the element
     */
    function setName($name)
    {
        $this->_name = trim($name);
    }

    /**
     * get the "name" attribute for the element
     *
     * @param bool $ encode?
     * @return string "name" attribute
     */
    function getName($encode = true)
    {
        if (false != $encode) {
            return str_replace('&amp;', '&', htmlspecialchars($this->_name, ENT_QUOTES));
        }
        return $this->_name;
    }

    /**
     * set the "accesskey" attribute for the element
     *
     * @param string $key "accesskey" attribute for the element
     */
    function setAccessKey($key)
    {
        $this->_accesskey = trim($key);
    }
    /**
     * get the "accesskey" attribute for the element
     *
     * @return string "accesskey" attribute value
     */
    function getAccessKey()
    {
        return $this->_accesskey;
    }
    /**
     * If the accesskey is found in the specified string, underlines it
     *
     * @param string $str String where to search the accesskey occurence
     * @return string Enhanced string with the 1st occurence of accesskey underlined
     */
    function getAccessString($str)
    {
        $access = $this->getAccessKey();
        if (! empty($access) && (false !== ($pos = strpos($str, $access)))) {
            return htmlspecialchars(substr($str, 0, $pos), ENT_QUOTES) . '<span style="text-decoration: underline;">' . htmlspecialchars(substr($str, $pos, 1), ENT_QUOTES) . '</span>' . htmlspecialchars(substr($str, $pos + 1), ENT_QUOTES);
        }
        return htmlspecialchars($str, ENT_QUOTES);
    }

    /**
     * set the "class" attribute for the element
     *
     * @param string $key "class" attribute for the element
     */
    function setClass($class)
    {
        $class = trim($class);
        if (! empty($class)) {
            $this->_class[] = $class;
        }
    }
    /**
     * get the "class" attribute for the element
     *
     * @return string "class" attribute value
     */
    function getClass()
    {
        if (empty($this->_class)) {
            return false;
        }
        $classes = array();
        foreach($this->_class as $class) {
            $classes[] = htmlspecialchars($class, ENT_QUOTES);
        }
        return implode(' ', $classes);
    }

    /**
     * set the caption for the element
     *
     * @param string $caption
     */
    function setCaption($caption)
    {
        $this->_caption = trim($caption);
    }

    /**
     * get the caption for the element
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    function getCaption($encode = false)
    {
        return $encode ? htmlspecialchars($this->_caption, ENT_QUOTES) : $this->_caption;
    }

	 /**
     * get the caption for the element
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    function getTitle($encode = true)
    {
        if (strlen($this->_description) > 0) {
            return $encode
                    ? htmlspecialchars(strip_tags($this->_caption . ' - ' . $this->_description), ENT_QUOTES)
                    : strip_tags($this->_caption . ' - ' . $this->_description);
        } else {
            return $encode ? htmlspecialchars(strip_tags($this->_caption), ENT_QUOTES)
                    : strip_tags($this->_caption);
        }
    }

    /**
     * set the element's description
     *
     * @param string $description
     */
    function setDescription($description)
    {
        $this->_description = trim($description);
    }

    /**
     * get the element's description
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    function getDescription($encode = false)
    {
        return $encode ? htmlspecialchars($this->_description, ENT_QUOTES) : $this->_description;
    }

    /**
     * flag the element as "hidden"
     */
    function setHidden()
    {
        $this->_hidden = true;
    }

    /**
     * Find out if an element is "hidden".
     *
     * @return bool
     */
    function isHidden()
    {
        return $this->_hidden;
    }

    /**
     * Find out if an element is required.
     *
     * @return bool
     */
    function isRequired()
    {
        return $this->_required;
    }

    /**
     * Add extra attributes to the element.
     *
     * This string will be inserted verbatim and unvalidated in the
     * element's tag. Know what you are doing!
     *
     * @param string $extra
     * @param string $replace If true, passed string will replace current content otherwise it will be appended to it
     * @return array New content of the extra string
     */
    function setExtra($extra, $replace = false)
    {
        if ($replace) {
            $this->_extra = array(trim($extra));
        } else {
            $this->_extra[] = trim($extra);
        }
        return $this->_extra;
    }

    /**
     * Get the extra attributes for the element
     *
     * @param bool $encode To sanitizer the text?
     * @return string
     */
    function getExtra($encode = false)
    {
        if (!$encode) {
            return ' ' . implode(' ', $this->_extra);
        }
        $value = array();
        foreach ($this->_extra as $val) {
            $value[] = str_replace('>', '&gt;', str_replace('<', '&lt;', $val));
        }
        return empty($value) ? '' : ' ' . implode(' ', $value);
    }

    /**
     * Set the element's nocolspan
     * Modified by Catzwolf
     *
     * @param string $description
     *
     * @deprecated  PLEASE AVOID USING THIS METHOD
     */
    function setNocolspan($nocolspan = true)
    {
        $this->_nocolspan = $nocolspan;
    }

    /**
     * Get the element's nocolspan
     * Modified by Catzwolf
     *
     * @return string
     *
     * @deprecated  PLEASE AVOID USING THIS METHOD
     */
    function getNocolspan()
    {
        return $this->_nocolspan;
    }

    /**
     * get the element's nocolspan
     * Modified by Catzwolf
     *
     * @return string
     *
     * @deprecated  PLEASE AVOID USING THIS METHOD
     */
    function getFormType()
    {
        return $this->_formtype;
    }

    /**
     * set the element's nocolspan
     * Modified by Catzwolf
     *
     * @param string $description
     *
     * @deprecated  PLEASE AVOID USING THIS METHOD
     */
    function setFormType($value = '')
    {
        $this->_formtype = $value;
    }

    /**
     * Render custom javascript validation code
     *
     * @seealso XoopsForm::renderValidationJS
     */
    function renderValidationJS()
    {
        // render custom validation code if any
        if (!empty($this->customValidationCode)) {
            return implode(NWLINE, $this->customValidationCode);
            // generate validation code if required
        } else if ($this->isRequired() && $eltname = $this->getName()) {
            // $eltname    = $this->getName();
            $eltcaption = $this->getCaption();
            $eltmsg = empty($eltcaption) ? sprintf(_FORM_ENTER, $eltname) : sprintf(_FORM_ENTER, $eltcaption);
            $eltmsg = str_replace(array(':' , '?' , '%'), '', $eltmsg);
            $eltmsg = str_replace('"', '\"', stripslashes($eltmsg));
            $eltmsg = strip_tags($eltmsg);
            echo $this->getFormType();
            switch ($this->getFormType()) {
                case 'checkbox':
                    return NWLINE . "if ( !myform.{$eltname}.checked ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }\n";
                    break;
                default:
                    return NWLINE . "if ( myform.{$eltname}.value == \"\" ) { window.alert(\"{$eltmsg}\"); myform.{$eltname}.focus(); return false; }\n";
                    break;
            } // switch
        }
        return false;
    }

    /**
     * Generates output for the element.
     *
     * This method is abstract and must be overwritten by the child classes.
     *
     * @abstract
     */
    function render()
    {
    }
}

?>