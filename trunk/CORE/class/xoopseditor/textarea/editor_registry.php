<?php
/**
 * XOOPS editor
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
 * @author          Taiwen Jiang (phppp or D.J.) <php_pp@hotmail.com>
 * @since           2.3.0
 * @version         $Id: editor_registry.php 5451 2010-10-08 08:48:35Z kris_fr $
 * @package         xoopseditor
 */
return $config = array(
        "class"     =>    "FormTextArea",
        "file"      =>    XOOPS_ROOT_PATH . "/class/xoopseditor/textarea/textarea.php",
        "title"     =>    _XOOPS_EDITOR_TEXTAREA, // display to end user
        "order"     =>    1, // 0 will disable the editor
        "nohtml"    =>    1 // For forms that have "dohtml" disabled
    );
?>