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
 * @version         $Id: formselectmatchoption.php 4941 2010-07-22 17:13:36Z beckmi $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

xoops_load('XoopsFormSelect');

/**
 * A selection box with options for matching search terms.
 */
class XoopsFormSelectMatchOption extends XoopsFormSelect
{
    /**
     * Constructor
     *
     * @param string $caption
     * @param string $name
     * @param mixed $value Pre-selected value (or array of them).
     * 							Legal values are {@link XOOPS_MATCH_START}, {@link XOOPS_MATCH_END},
     * 							{@link XOOPS_MATCH_EQUAL}, and {@link XOOPS_MATCH_CONTAIN}
     * @param int $size Number of rows. "1" makes a drop-down-list
     */
    function XoopsFormSelectMatchOption($caption, $name, $value = null, $size = 1)
    {
        $this->XoopsFormSelect($caption, $name, $value, $size, false);
        $this->addOption(XOOPS_MATCH_START, _STARTSWITH);
        $this->addOption(XOOPS_MATCH_END, _ENDSWITH);
        $this->addOption(XOOPS_MATCH_EQUAL, _MATCHES);
        $this->addOption(XOOPS_MATCH_CONTAIN, _CONTAINS);
    }
}

?>