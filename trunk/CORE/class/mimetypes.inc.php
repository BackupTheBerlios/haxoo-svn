<?php
/**
 * Extension to mimetype lookup table
 *
 * This file is provided as an helper for objects who need to perform filename to mimetype translations.
 * Common types have been provided, but feel free to add your own one if you need it.
 * <br /><br />
 * See the enclosed file LICENSE for licensing information.
 * If you did not receive this file, get it at http://www.fsf.org/copyleft/gpl.html
 *
 * @copyright   The XOOPS Project http://sourceforge.net/projects/xoops/
 * @license      GNU GPL 2 (http://www.gnu.org/licenses/old-licenses/gpl-2.0.html)
 * @author       Skalpa Keo <skalpa@xoops.org>
 * @since        2.0.9.3
 * $Id: mimetypes.inc.php 4941 2010-07-22 17:13:36Z beckmi $
 * @deprecated
 */
defined('XOOPS_ROOT_PATH') or die('Restricted access');

$GLOBALS['xoopsLogger']->addDeprecated("'/class/mimetypes.inc.php' is deprecated, use '/include/mimetypes.inc.php' directly.");

return include XOOPS_ROOT_PATH . '/include/mimetypes.inc.php';
?>