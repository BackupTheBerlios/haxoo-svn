<?php
/**
 * CAPTCHA for Recaptcha mode
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
 * @version         $Id: recaptcha.php 7695 2011-09-22 23:03:33Z trabis $
 */

defined('XOOPS_ROOT_PATH') or die('Restricted access');

class XoopsCaptchaRecaptcha extends XoopsCaptchaMethod
{
    /**
     * XoopsCaptchaRecaptcha::isActive()
     *
     * @return bool
     */
    function isActive()
    {
        return true;
    }

    /**
     * XoopsCaptchaRecaptcha::render()
     *
     * @return string
     */
    function render()
    {
        require_once dirname(__FILE__) . '/recaptcha/recaptchalib.php';
        $form = "<script type=\"text/javascript\">
            var RecaptchaOptions = {
            theme : '" . $this->config['theme']."',
            lang : '" . $this->config['lang']."'
            };
            </script>";
        $form .= recaptcha_get_html($this->config['public_key']);
        return $form;
    }

    /**
     * XoopsCaptchaRecaptcha::verify()
     *
     * @return bool
     */
    function verify($sessionName)
    {
        $is_valid = false;
        require_once dirname(__FILE__) . '/recaptcha/recaptchalib.php';
        if (!empty($_POST['recaptcha_response_field'])) {
            $resp = recaptcha_check_answer(
                $this->config['private_key'],
                $_SERVER['REMOTE_ADDR'],
                $_POST['recaptcha_challenge_field'],
                $_POST['recaptcha_response_field']
            );
            if (!$resp->is_valid) {
              $this->message[] = $resp->error;
            } else {
              $is_valid = true;
            }
        }
        return $is_valid;
    }
}
?>