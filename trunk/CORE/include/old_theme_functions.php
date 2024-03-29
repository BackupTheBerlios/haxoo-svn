<?php
/**
 * XOOPS Deprecated Functions
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
 * @since           2.0.0
 * @author          Kazumi Ono (AKA onokazu) http://www.myweb.ne.jp/, http://jp.xoops.org/
 * @version         $Id: old_theme_functions.php 4941 2010-07-22 17:13:36Z beckmi $
 */

// These are needed when viewing old modules (that don't use Smarty template files) when a theme that use Smarty templates are selected.
// function_exists check is needed for inclusion from the admin side
if (! function_exists('opentable')) {
    function OpenTable($width = '100%')
    {
        $GLOBALS['xoopsLogger']->addDeprecated("Function '" . __FUNCTION__ . "' in '" . __FILE__ . "' is deprecated, should not be used any more");
        echo '<table width="' . $width . '" cellspacing="0" class="outer"><tr><td class="even">';
    }
}

if (! function_exists('closetable')) {
    function CloseTable()
    {
        $GLOBALS['xoopsLogger']->addDeprecated("Function '" . __FUNCTION__ . "' in '" . __FILE__ . "' is deprecated, should not be used any more");
        echo '</td></tr></table>';
    }
}

if (! function_exists('themecenterposts')) {
    function themecenterposts($title, $content)
    {
        $GLOBALS['xoopsLogger']->addDeprecated("Function '" . __FUNCTION__ . "' in '" . __FILE__ . "' is deprecated, should not be used any more");
        echo '<table cellpadding="4" cellspacing="1" width="98%" class="outer"><tr><td class="head">' . $title . '</td></tr><tr><td><br />' . $content . '<br /></td></tr></table>';
    }
}
?>