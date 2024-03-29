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
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: formhiddentoken.php 4941 2010-07-22 17:13:36Z beckmi $
 */
 
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * A hidden token field
 */
class XoopsFormHiddenToken extends XoopsFormHidden
{
    /**
     * Constructor
     *
     * @param string $name "name" attribute
     */
    function XoopsFormHiddenToken($name = 'XOOPS_TOKEN', $timeout = 0)
    {
        $this->XoopsFormHidden($name . '_REQUEST', $GLOBALS['xoopsSecurity']->createToken($timeout, $name));
    }
}

?>