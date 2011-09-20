<?php
/**
 * CAPTCHA configurations for Image mode
 *
 * Based on DuGris' SecurityImage
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
 * @since           2.3.0
 * @author          Taiwen Jiang <phppp@users.sourceforge.net>
 * @version         $Id: config.php 4941 2010-07-22 17:13:36Z beckmi $
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

/**
 * This keeping config in files has really got to stop. If we can't actually put these into
 * the actual XOOPS config then we should do this.
 */
return $config = array(
    'disabled' => false,  // Disable CAPTCHA
    'mode' => 'image',  // default mode
    'name' => 'xoopscaptcha',  // captcha name
    'skipmember' => true,  // Skip CAPTCHA check for members
    'maxattempt' => 10,  // Maximum attempts for each session
    'num_chars' => 4,  // Maximum characters
    'rule_text' => _CAPTCHA_RULE_TEXT,
    'maxattempt_text' => _CAPTCHA_MAXATTEMPTS);

?>