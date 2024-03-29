<?php
/**
 * Xoops Cpanel class
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
 * @package     system
 * @subpackage  class
 * @author      Taiwen Jiang <phppp@users.sourceforge.net>
 * @version     $Id: cpanel.php 4978 2010-08-20 11:12:54Z forxoops $
 */

class XoopsSystemCpanel
{
    /**
     * Reference to GUI object
     */
    var $gui;

    /**
     * Constructer
     *
     */
    function __construct()
    {
        $cpanel = xoops_getConfigOption('cpanel');
        $this->loadGui($cpanel);
    }

    /**
     * Get an instance of the class
     *
     * @return unknown
     */
    function &getInstance()
    {
        static $instance;
        if (!isset($instance)) {
            $class = __CLASS__;
            $instance = new $class();
        }
        return $instance;
    }

    /**
     * Load the Xoops Admin Gui by preference
     *
     * @param string $gui
     */
    function loadGui($gui)
    {
        if (!empty($gui)) {
            $class = 'XoopsGui' . ucfirst($gui);
            if (!class_exists($class)) {
                include_once XOOPS_ADMINTHEME_PATH . '/' . $gui . '/' . $gui . '.php';
            }
            if (class_exists($class)) {
                if (call_user_func(array($class , 'validate'))) {
                    $this->gui = new $class();
                    $this->gui->foldername = $gui;
                }
            }
        }
        if (!isset($this->gui)) {
            if (file_exists($file = XOOPS_ADMINTHEME_PATH . '/default/default.php')) {
                include_once $file;
                $this->gui = new XoopsGuiDefault();
                $this->gui->foldername = 'default';
            }
        }
    }

    /**
     * Get a list of Xoops Admin Gui
     *
     * @return unknown
     */
    function getGuis()
    {
        $guis = array();
        xoops_load('XoopsLists');
        $lists = XoopsLists::getDirListAsArray(XOOPS_ADMINTHEME_PATH);
        foreach (array_keys($lists) as $gui) {
            if (file_exists($file = XOOPS_ADMINTHEME_PATH . '/' . $gui . '/' . $gui . '.php')) {
                include_once $file;
                if (class_exists($class = 'XoopsGui' . ucfirst($gui))) {
                    if (call_user_func(array($class , 'validate'))) {
                        $guis[$gui] = $gui;
                    }
                }
            }
        }
        return $guis;
    }

    /**
     * Flush the Xoops Admin Gui
     *
     */
    function flush()
    {
        $guis = XoopsSystemCpanel::getGuis();
        foreach ($guis as $gui) {
            if ($file = XOOPS_ADMINTHEME_PATH . '/' . $gui . '/' . $gui . '.php') {
                include_once $file;
                if (class_exists($class = 'XoopsGui' . ucfirst($gui))) {
                    call_user_func(array($class , 'flush'));
                }
            }
        }
    }
}

?>