<?php
/**
 * CAPTCHA configurations for Recaptcha mode
 *
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *
 * @copyright       The XOOPS project http://sourceforge.net/projects/xoops/
 * @license         GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @package         class
 * @subpackage      CAPTCHA
 * @since           2.5.2
 * @author          trabis <lusopoemas@gmail.com>
 * @version         $Id: config.recaptcha.php 7695 2011-09-22 23:03:33Z trabis $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

return $config = array(
    'private_key' => 'YourPrivateApiKey',
    'public_key'  => 'YourPublicApiKey',
    'theme'       => 'white', // 'red' | 'white' | 'blackglass' | 'clean' | 'custom'
    'lang'        => _LANGCODE
    );
?>