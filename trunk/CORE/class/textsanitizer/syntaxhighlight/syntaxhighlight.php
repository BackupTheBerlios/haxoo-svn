<?php
/**
 * TextSanitizer extension
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
 * @package         class
 * @subpackage      textsanitizer
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: syntaxhighlight.php 7577 2011-09-12 23:10:44Z trabis $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

class MytsSyntaxhighlight extends MyTextSanitizerExtension
{
    function load(&$ts, $source, $language)
    {
        $config = parent::loadConfig(dirname(__FILE__));
        if (empty($config['highlight'])) {
            return "<pre>{$source}</pre>";
        }
        $source = $ts->undoHtmlSpecialChars($source);
        $source = stripslashes($source);
        if ($config['highlight'] == 'geshi') {
            $language = str_replace('=', '', $language);
            $language = ($language) ? $language : $config['language'];
            $language = strtolower($language);
            if ($source2 = MytsSyntaxhighlight::geshi($source, $language)) {
                return $source2;
            }
        }
        $source = MytsSyntaxhighlight::php($source);
        return $source;
    }

    function php($text)
    {
        $text = trim($text);
        $addedtag_open = 0;
        if (!strpos($text, "<?php") and (substr($text, 0, 5) != "<?php")) {
            $text = "<?php " . $text;
            $addedtag_open = 1;
        }
        $addedtag_close = 0;
        if (!strpos($text, "?>")) {
            $text .= "?>";
            $addedtag_close = 1;
        }
        $oldlevel = error_reporting(0);

        //There is a bug in the highlight function(php < 5.3) that it doesn't render
        //backslashes properly like in \s. So here we replace any backslashes
        $text = str_replace("\\", "XxxX", $text);

        $buffer = highlight_string($text, true); // Require PHP 4.20+

        //Placing backspaces back again
        $buffer = str_replace("XxxX", "\\", $buffer);

        error_reporting($oldlevel);
        $pos_open = $pos_close = 0;
        if ($addedtag_open) {
            $pos_open = strpos($buffer, '&lt;?php&nbsp;');
        }
        if ($addedtag_close) {
            $pos_close = strrpos($buffer, '?&gt;');
        }

        $str_open = ($addedtag_open) ? substr($buffer, 0, $pos_open) : "";
        $str_close = ($pos_close) ? substr($buffer, $pos_close + 5) : "";

        $length_open = ($addedtag_open) ? $pos_open + 14 : 0;
        $length_text = ($pos_close) ? $pos_close - $length_open : 0;
        $str_internal = ($length_text) ? substr($buffer, $length_open, $length_text) : substr($buffer, $length_open);

        $buffer = $str_open . $str_internal . $str_close;
        return $buffer;
    }

    function geshi($source, $language)
    {
        if (!@xoops_load("geshi", "framework")) {
            return false;
        }

        // Create the new XoopsGeshi object, passing relevant stuff
        // XoopsGeshi should be extending geSHi in Frameworks/geshi/xoopsgeshi.php
        $geshi = new XoopsGeshi($source, $language);

        // Enclose the code in a <div>
        $geshi->set_header_type(GESHI_HEADER_NONE);

        // Sets the proper encoding charset other than "ISO-8859-1"
        $geshi->set_encoding(_CHARSET);

        $geshi->set_link_target("_blank");

        // Parse the code
        $code = $geshi->parse_code();

        return $code;
    }
}
?>