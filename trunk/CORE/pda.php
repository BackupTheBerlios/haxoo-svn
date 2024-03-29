<?php
/**
 * XOOPS PDA for news
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
 * @package         core
 * @since           2.0.0
 * @version         $Id: pda.php 4941 2010-07-22 17:13:36Z beckmi $
 */

include dirname(__FILE__) . DIRECTORY_SEPARATOR . 'mainfile.php';

header("Content-Type: text/html");
echo "<html><head><title>" . htmlspecialchars($xoopsConfig['sitename']) . "</title>
      <meta name='HandheldFriendly' content='True' />
      <meta name='PalmComputingPlatform' content='True' />
      </head>
      <body>";

$sql = "SELECT storyid, title FROM " . $xoopsDB->prefix("stories") . " WHERE published>0 AND published<" . time() . " ORDER BY published DESC";
$result = $xoopsDB->query($sql, 10, 0);
//TODO Remove this hardcoded string
if (!$result) {
    echo "An error occured";
} else {
    echo "<img src='images/logo.gif' alt='" . htmlspecialchars($xoopsConfig['sitename'], ENT_QUOTES) . "' border='0' /><br />";
    echo "<h2>" . htmlspecialchars($xoopsConfig['slogan']) . "</h2>";
    echo "<div>";
    while (list ($storyid, $title) = $xoopsDB->fetchRow($result)) {
        echo "<a href='" . XOOPS_URL . "/modules/news/print.php?storyid=$storyid'>" . htmlspecialchars($title) . "</a><br />";
    }
    echo "</div>";
}
echo "</body></html>";

?>