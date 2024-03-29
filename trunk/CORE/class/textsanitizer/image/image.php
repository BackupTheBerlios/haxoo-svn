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
 * @version         $Id: image.php 7600 2011-09-14 08:33:05Z beckmi $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');
global $xoopsConfig;
include_once $GLOBALS['xoops']->path('language/' . $xoopsConfig['language'] . '/misc.php');

class MytsImage extends MyTextSanitizerExtension
{
    function load(&$ts)
    {
    static $jsLoaded;

        $config = $this->loadConfig(dirname(__FILE__));
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 width=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img width=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

        $ts->patterns[] = "/\[img align=(['\"]?)(left|center|right)\\1 id=(['\"]?)([0-9]*)\\3]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";
        $ts->patterns[] = "/\[img id=(['\"]?)([0-9]*)\\1]([^\"\(\)\?\&'<>]*)\[\/img\]/sU";

        if (empty($ts->config['allowimage'])) {
            $ts->replacements[] = '<a href="\\5" rel="external">\\5</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\3" rel="external">\\3</a>';
            $ts->replacements[] = '<a href="\\1" rel="external">\\1</a>';

            $ts->replacements[] = '<a href="' . XOOPS_URL . '/image.php?id=\\4" rel="external" title="\\5">\\5</a>';
            $ts->replacements[] = '<a href="' . XOOPS_URL . '/image.php?id=\\2" rel="external" title="\\3">\\3</a>';

        } else {            
               if (!empty($config['resize']) && empty($config['clickable']) && !empty($config['max_width']) && !empty($GLOBALS['xoTheme'])) {
                if (!$jsLoaded) {
                    $jsLoaded = true;
                    $GLOBALS['xoTheme']->addScript('/class/textsanitizer/image/image.js', array(
                        'type' => 'text/javascript'));
                }
                $ts->replacements[] = "<img src='\\5' class='\\2' alt='"._MSC_RESIZED_IMAGE . "' border='0' onload=\"JavaScript:if(this.width>\\4)this.width=\\4\" />";
                $ts->replacements[] = "<img src='\\3' class='\\2' alt='"._MSC_RESIZED_IMAGE . "' border='0'" . ($config['resize'] ? "onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";
                $ts->replacements[] = "<img src='\\3' alt='"._MSC_RESIZED_IMAGE . "' border='0' onload=\"JavaScript:if(this.width>\\2)this.width=\\2\" /><br />";
                $ts->replacements[] = "<img src='\\1' alt='"._MSC_RESIZED_IMAGE . "' border='0'" . ($config['resize'] ? " onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";
                
                } elseif 

                (!empty($config['clickable']) && !empty($config['max_width']) && !empty($GLOBALS['xoTheme'])) {
                if (!$jsLoaded) {
                    $jsLoaded = true;
                    $GLOBALS['xoTheme']->addScript('/class/textsanitizer/image/image.js', array(
                        'type' => 'text/javascript'));
                }
                $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\5\");'><img src='\\5' class='\\2' alt='"._MSC_CLICK_TO_OPEN_IMAGE . "' border='0' onload=\"JavaScript:if(this.width>\\4)this.width=\\4\" /></a>";
                $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\3\");'><img src='\\3' class='\\2' alt='"._MSC_CLICK_TO_OPEN_IMAGE . "' border='0' " . ($config['resize'] ? "onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/></a>";
                $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\3\");'><img src='\\3' alt='"._MSC_CLICK_TO_OPEN_IMAGE . "' border='0' onload=\"JavaScript:if(this.width>\\2)this.width=\\2\" /></a><br />";
                $ts->replacements[] = "<a href='javascript:CaricaFoto(\"\\1\");'><img src='\\1' alt='"._MSC_CLICK_TO_OPEN_IMAGE . "' border='0' title='"._MSC_CLICK_TO_OPEN_IMAGE . "'" . ($config['resize'] ? " onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/></a>";}
                
             else {
                $ts->replacements[] = "<img src='\\5' class='\\2' border='0' alt='"._MSC_ORIGINAL_IMAGE ."' onload=\"JavaScript:if(this.width>\\4) this.width=\\4\" />";
                $ts->replacements[] = "<img src='\\3' class='\\2' border='0' alt='"._MSC_ORIGINAL_IMAGE ."' " . ($config['resize'] ? "onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";
                $ts->replacements[] = "<img src='\\3' border='0' alt='"._MSC_ORIGINAL_IMAGE ."' onload=\"JavaScript:if(this.width>\\2) this.width=\\2\" />";
                $ts->replacements[] = "<img src='\\1' border='0' alt='"._MSC_ORIGINAL_IMAGE ."' " . ($config['resize'] ? " onload=\"javascript:imageResize(this, " . $config['max_width'] . ")\"" : "") . "/>";
            }            
            $ts->replacements[] = '<img src="' . XOOPS_URL . '/image.php?id=\\4" class="\\2" title="\\5" />';
            $ts->replacements[] = '<img src="' . XOOPS_URL . '/image.php?id=\\2" title="\\3" />';
        }
        return true;
    }
}

?>